<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class StoryViewer extends Component
{
    public ?Post $story = null;
    public array $slides = [];
    public int $currentIndex = 0;

    protected $listeners = [
        'openStoryModal' => 'loadStory', // from StoryStrip
    ];

    public function loadStory(int $storyId)
    {
        $post = Post::activeStories()
            ->with(['media', 'user.alumniProfile'])
            ->findOrFail($storyId);

        $profile = optional($post->user)->alumniProfile;
        if (! $profile || ! can_view_profile($profile)) {
            $this->story = null;
            $this->slides = [];
            return;
        }

        $this->story = $post;

        $this->slides = $post->media->map(function ($media) {
            return [
                'url'   => $media->url,
                'thumb' => $media->thumbnail_url,
            ];
        })->values()->toArray();

        $this->currentIndex = 0;

        // Tell browser to open the Bootstrap modal
        $this->dispatch('showStoryModal');
    }

    public function render()
    {
        return view('livewire.story-viewer');
    }
}
