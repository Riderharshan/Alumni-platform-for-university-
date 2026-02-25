<div class="card p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm" style="margin-top:16px;">
    @if (session()->has('message'))
        <div class="mb-3 text-sm text-green-700 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif

   <div class="card w-100 shadow-xss rounded-xxl border-0 ps-4 pt-4 pe-4 pb-3 mb-3">
    <div class="card-body p-0">
        <a href="#" class="font-xssss fw-600 text-grey-500 card-body p-0 d-flex align-items-center">
            <i class="btn-round-sm font-xs text-primary feather-edit-3 me-2 bg-greylight"></i>Create Post
        </a>
    </div>

    <form wire:submit.prevent="save" class="card-body p-0 mt-3 position-relative">
        {{-- avatar --}}
        <figure class="avatar position-absolute ms-2 mt-1 top-5">
            <img src="{{ optional(Auth::user()->alumniProfile)->profile_picture
             ? Storage::url(optional(Auth::user()->alumniProfile)->profile_picture)
             : asset('/default.png') }}"
     alt="avatar" class="shadow-sm rounded-circle w30">
        </figure>

        {{-- body textarea (styled to match your UI) --}}
        <textarea
            name="message"
            wire:model.defer="body"
            class="h100 bor-0 w-100 rounded-xxl p-2 ps-5 font-xssss text-grey-500 fw-500 border-light-md theme-dark-bg"
            cols="30"
            rows="4"
            placeholder="What's on your mind?"
        ></textarea>
        @error('body') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror

        {{-- Optional title and type (hidden by default, toggleable if you want) --}}
      {{--  <div class="mt-3 d-none"> 
            <label class="font-xss fw-600">Type</label>
            <select wire:model="post_type" class="form-select w-50">
                <option value="story">Story</option>
                <option value="job">Job</option>
                <option value="update">Update</option>
            </select>
            @error('post_type') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div> --}}

        {{-- Type (hidden when lockType=true) --}}
@if(!$lockType)
  <div class="mt-3">
    <label class="font-xss fw-600">Type</label>
    <select wire:model="post_type" class="form-select w-50">
      <option value="story">Story</option>
      <option value="job">Job</option>
      <option value="update">Update</option>
    </select>
    @error('post_type') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
  </div>
@else
  <input type="hidden" value="story" />
@endif


        {{-- Image upload + actions row --}}
        <div class="card-body d-flex p-0 mt-3 align-items-center gap-3">
            <label class="d-flex align-items-center font-xssss fw-600 ls-1 text-grey-700 text-dark pe-4 mb-0" style="cursor:pointer">
                <i class="font-md text-success feather-image me-2"></i>
                <span class="d-none-xs">Photo</span>
                <input wire:model="images" type="file" multiple accept="image/*" class="d-none" />
            </label>

            <a class="d-flex align-items-center font-xssss fw-600 ls-1 text-grey-700 text-dark pe-4 mb-0">
                <i class="font-md text-warning feather-camera me-2"></i>
                <span class="d-none-xs">Feeling/Activity</span>
            </a>

            <a class="ms-auto" id="dropdownMenu4" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ti-more-alt text-grey-900 btn-round-md bg-greylight font-xss"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-start p-4 rounded-xxl border-0 shadow-lg" aria-labelledby="dropdownMenu4">
                {{-- dropdown items (unchanged) --}}
                <div class="card-body p-0 d-flex">
                    <i class="feather-bookmark text-grey-500 me-3 font-lg"></i>
                    <h4 class="fw-600 text-grey-900 font-xssss mt-0 me-4">Save Link <span class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">Add this to your saved items</span></h4>
                </div>
                {{-- ... other dropdown items ... --}}
            </div>
        </div>

        {{-- Validation for images --}}
        @error('images') <div class="text-red-600 text-sm mt-2">{{ $message }}</div> @enderror
        @if ($errors->has('images.*'))
            <div class="text-red-600 text-sm mt-2">{{ $errors->first('images.*') }}</div>
        @endif

        {{-- Preview grid for newly uploaded images --}}
        @if (!empty($images))
            <div class="mt-3 grid" style="display:flex; gap:8px; flex-wrap:wrap;">
                @foreach ($images as $img)
                    <div class="position-relative" style="width:96px; height:96px; border-radius:10px; overflow:hidden; border:1px solid #eee;">
                        <img src="{{ $img->temporaryUrl() }}" alt="preview" style="width:100%; height:100%; object-fit:cover;">
                        <button type="button" wire:click="$emit('removeTemp', {{ $loop->index }})" class="position-absolute top-0 end-0 bg-white rounded-circle p-1" style="transform:translate(25%,-25%);">&times;</button>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Existing images in edit mode --}}
        @if (!empty($existingMedia))
            <div class="mt-3 grid" style="display:flex; gap:8px; flex-wrap:wrap;">
                @foreach ($existingMedia as $m)
                    <div class="position-relative" style="width:96px; height:96px; border-radius:10px; overflow:hidden; border:1px solid #eee;">
                        <img src="{{ $m['thumbnail_url'] ?? $m['url'] }}" alt="{{ $m['meta']['original_name'] ?? 'image' }}" style="width:100%; height:100%; object-fit:cover;">
                        <button type="button" wire:click="removeExistingMedia({{ $m['id'] }})" class="position-absolute top-0 end-0 bg-white rounded-circle p-1" style="transform:translate(25%,-25%);">&times;</button>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- action buttons --}}
        <div class="d-flex align-items-center gap-2 mt-3">
            <button type="submit" class="bg-primary text-white rounded-xxl px-4 py-2 fw-600" wire:loading.attr="disabled" wire:target="save,images">
                {{-- Show spinner while saving --}}
                <span wire:loading.remove wire:target="save">Post</span>
                <span wire:loading wire:target="save">Posting...</span>
            </button>

            @if ($postId)
                <button type="button" wire:click="deletePost" class="bg-danger text-white rounded-xxl px-4 py-2 fw-600" wire:loading.attr="disabled">Delete</button>
            @endif

            {{-- small hint --}}
            <div class="font-xssss text-grey-500 ms-3">Max {{ $maxImages }} images • JPG/PNG/WebP • 5MB each</div>
        </div>
    </form>
</div>

<div wire:ignore.self class="modal fade" id="ModalCreateStory" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 600px;">
        <div class="modal-content border-0 rounded-3">
            <div class="modal-header border-0">
                <h5 class="modal-title">Create Story</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                {{-- Force post_type = story --}}
                <livewire:post-form :post-type-initial="'story'" />
            </div>
        </div>
    </div>
</div>


{{-- Optional: handle removing a single temp image remotely (you can keep or remove) --}}
@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        // removeTemp does not exist server-side by default — you can implement if you want to remove a temp file before submit
        Livewire.on('removeTemp', (index) => {
            // If you want to handle removing a temp file client-side you need additional JS.
            // Simpler: you can let users select files again or implement a small JS array to manage temp files.
            console.warn('removeTemp requested for index', index);
        });
    });
</script>
@endpush

</div>
