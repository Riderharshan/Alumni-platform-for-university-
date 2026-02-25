<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Post;
use App\Models\PostMedia;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;


class PostForm extends Component
{
    use WithFileUploads;
    #[Layout('components.layouts.master-alumni')]
    public ?int $postId = null;
    public $title = null;
    public $body = '';
    public $post_type = 'update'; // 'story'|'job'|'update'
    public $visibility = 'alumni_only'; // 'public'|'alumni_only'|'connections'
    public array $existingMedia = []; // DB media rows for edit mode
    public $images = []; // Livewire uploaded files (temporary)
    public $maxImages = 6;
    public bool $lockType = false;

    protected function rules()
    {
        return [
        'title' => 'nullable|string|max:191',
        'body' => 'required|string',
        'post_type' => ['required', Rule::in(['story', 'job', 'update'])],
        'visibility' => ['required', Rule::in(['public', 'alumni_only', 'connections'])],
        'images.*' => 'image|max:5120', // max 5MB per image',
        ];
    }

   

    protected $messages = [
        'images.*.image' => 'Each uploaded file must be an image.',
        'images.*.max' => 'Each image must be at most 5 MB.',
    ];

    /* public function mount($postId = null)
    {
        $this->postId = $postId ? (int) $postId : null;

        if ($this->postId) {
            $post = Post::findOrFail($this->postId);

            // optional: authorization gate/policy check
            // $this->authorize('update', $post);

            $this->title = $post->title;
            $this->body = $post->body;
            $this->post_type = $post->post_type;
            $this->visibility = $post->visibility;
            $this->existingMedia = $post->media()->orderBy('position')->get()->toArray();
        }
    } */

        public function mount($postId = null, $postType = null, $lockType = false)
{
    $this->postId   = $postId ? (int) $postId : null;
    $this->lockType = (bool) $lockType;

    if ($postType) {
        $this->post_type = $postType; // e.g. 'story'
    }

    if ($this->postId) {
        $post = Post::findOrFail($this->postId);
        $this->title       = $post->title;
        $this->body        = $post->body;
        $this->post_type   = $post->post_type;
        $this->visibility  = $post->visibility;
        $this->existingMedia = $post->media()->orderBy('position')->get()->toArray();
    }
}

    public function updatedImages()
    {
        // Prevent uploading more than allowed (existing + new)
        $existingCount = count($this->existingMedia);
        if (($existingCount + count($this->images)) > $this->maxImages) {
            $this->addError('images', "You may upload up to {$this->maxImages} images per post (including existing).");
        } else {
            $this->resetValidation('images');
        }
    }

    /* public function debugSave()
{
    session()->flash('message', 'debugSave triggered');
} */

    public function save()
    {
        $this->validate();

        // Basic auth check
        $user = Auth::user();
        if (! $user) {
            $this->addError('auth', 'You must be logged in to create or edit posts.');
            return;
        }

        // Create or update the post
        if ($this->postId) {
            $post = Post::findOrFail($this->postId);

            // authorization check (optional)
            // $this->authorize('update', $post);

            $post->update([
                'title' => $this->title,
                'body' => $this->body,
                'post_type' => $this->post_type,
                'visibility' => $this->visibility,
            ]);
        } else {
            $post = Post::create([
                'user_id' => $user->id,
                'title' => $this->title,
                'body' => $this->body,
                'post_type' => $this->post_type,
                'visibility' => $this->visibility,
                'metadata' => null,
                'is_published' => true,
                'is_approved' => false,
            ]);
            $this->postId = $post->id;
        }

        // Handle uploaded images
        if (!empty($this->images)) {
            foreach ($this->images as $file) {
                $this->storeImageForPost($file, $post);
            }
            // clear temporary uploads
            $this->images = [];
            // reload existingMedia
            $this->existingMedia = $post->media()->orderBy('position')->get()->toArray();
        }

        session()->flash('message', $this->postId ? 'Post saved.' : 'Post created.');
        $this->dispatch('postSaved', $post->id);
    }

    protected function storeImageForPost($uploadedFile, Post $post)
    {
        // Disk configuration: change as needed, e.g., 's3' or 'public'
        $disk = config('filesystems.default', 'public');
        $dir = 'posts/' . $post->id;

        // Build unique filename
        $ext = $uploadedFile->getClientOriginalExtension();
        $originalName = $uploadedFile->getClientOriginalName();
        $filename = Str::random(12) . '_' . time() . '.' . $ext;
        $path = $uploadedFile->storeAs($dir, $filename, $disk);

        // Derive public URL (if disk supports url())
        $url = Storage::disk($disk)->url($path);

        // Attempt to create a medium thumbnail (optional)
        $thumbnailPath = null;
        if (class_exists(\Intervention\Image\ImageManagerStatic::class)) {
            try {
                $image = \Intervention\Image\ImageManagerStatic::make($uploadedFile->getRealPath());

                // strip EXIF to remove GPS (if any)
                if (method_exists($image, 'orientate')) {
                    $image->orientate();
                }

                $thumb = $image->resize(640, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $thumbFilename = 'thumb_' . $filename;
                $thumbnailFullPath = sys_get_temp_dir() . '/' . $thumbFilename;
                $thumb->save($thumbnailFullPath);

                $thumbnailPath = $dir . '/' . $thumbFilename;
                Storage::disk($disk)->put($thumbnailPath, file_get_contents($thumbnailFullPath));
                @unlink($thumbnailFullPath);
            } catch (\Throwable $e) {
                // if Intervention fails, skip thumbnail silently (you can log here)
            }
        }

        $thumbnailUrl = $thumbnailPath ? Storage::disk($disk)->url($thumbnailPath) : null;

        // collect meta
        $meta = [
            'original_name' => $originalName,
            'size' => $uploadedFile->getSize(),
            'mime' => $uploadedFile->getClientMimeType(),
        ];

        // Insert into post_media table
        $position = ($post->media()->max('position') ?? 0) + 1;

        $media = PostMedia::create([
            'post_id' => $post->id,
            'url' => $url,
            'type' => 'image',
            'meta' => $meta,
            'disk' => $disk,
            'file_name' => $path,
            'original_name' => $originalName,
            'mime_type' => $uploadedFile->getClientMimeType(),
            'thumbnail_url' => $thumbnailUrl,
            'is_processed' => true,
            'position' => $position,
            'created_at' => now(),
        ]);

        return $media;
    }

    


    public function removeExistingMedia(int $mediaId)
    {
        $media = PostMedia::find($mediaId);
        if (! $media) {
            $this->addError('media', 'Media not found.');
            return;
        }

        // optional: authorize deletion
        // $this->authorize('delete', $media);

        // delete files from disk if present
        try {
            if ($media->file_name && Storage::disk($media->disk ?? config('filesystems.default'))->exists($media->file_name)) {
                Storage::disk($media->disk ?? config('filesystems.default'))->delete($media->file_name);
            }
            // delete thumbnail (if stored)
            if (!empty($media->thumbnail_url)) {
                // derive thumbnail path if using same disk file paths - we stored thumbnail path in file_name based fields
                // best-effort: if we stored thumbnail path in thumbnail_url as a Storage::url(), we cannot derive key reliably.
                // Skip or implement provider-specific deletion (Cloudinary, S3 key mapping).
            }
        } catch (\Throwable $e) {
            // ignore filesystem deletion failures; you may log this.
        }

        $media->delete();

        // refresh existing media array
        if ($this->postId) {
            $this->existingMedia = Post::find($this->postId)->media()->orderBy('position')->get()->toArray();
        }

        $this->dispatch('mediaDeleted', $mediaId);
        session()->flash('message', 'Image deleted.');
    }

    public function deletePost()
    {
        if (! $this->postId) {
            $this->addError('post', 'No post selected for deletion.');
            return;
        }

        $post = Post::findOrFail($this->postId);

        // authorization check (optional)
        // $this->authorize('delete', $post);

        // Remove media files (best-effort)
        foreach ($post->media as $m) {
            try {
                if ($m->file_name && Storage::disk($m->disk ?? config('filesystems.default'))->exists($m->file_name)) {
                    Storage::disk($m->disk ?? config('filesystems.default'))->delete($m->file_name);
                }
            } catch (\Throwable $e) {
                // ignore; optionally log
            }
        }

        $post->delete();

        session()->flash('message', 'Post deleted.');
        $this->resetForm();
        $this->dispatch('postDeleted', $this->postId);
        $this->postId = null;
    }

    protected function resetForm()
    {
        $this->title = null;
        $this->body = '';
        $this->post_type = 'story';
        $this->visibility = 'alumni_only';
        $this->existingMedia = [];
        $this->images = [];
    }


    
    public function render()
    {
        return view('livewire.post-form');
    }
}
