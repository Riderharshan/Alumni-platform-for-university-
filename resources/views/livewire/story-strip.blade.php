@php use Illuminate\Support\Facades\Storage; @endphp

<div class="card w-100 shadow-none bg-transparent bg-transparent-card border-0 p-0 mb-0">
    <div class="owl-carousel category-card owl-theme overflow-hidden nav-none">
        {{-- Add Story bubble (current user) --}}
        @auth
            <div class="item">
                <div data-bs-toggle="modal" data-bs-target="#ModalCreateStory"
                     class="card w125 h200 d-block border-0 shadow-none rounded-xxxl bg-dark overflow-hidden mb-3 mt-3 cursor-pointer">
                    <div class="card-body d-block p-3 w-100 position-absolute bottom-0 text-center">
                        <a href="javascript:void(0);">
                            <span class="btn-round-lg bg-white">
                                <i class="feather-plus font-lg"></i>
                            </span>
                            <div class="clearfix"></div>
                            <h4 class="fw-700 position-relative z-index-1 ls-1 font-xssss text-white mt-2 mb-1">
                                Add Story
                            </h4>
                        </a>
                    </div>
                </div>
            </div>
        @endauth

        {{-- Other users' stories --}}
        @forelse ($stories as $story)
            @php
                $author  = $story->user;
                $profile = optional($author)->alumniProfile;
                $firstMedia = $story->media->first();
                $bg = $firstMedia?->thumbnail_url ?? $firstMedia?->url ?? null;
                $avatar = $profile?->profile_picture
                    ? Storage::url($profile->profile_picture)
                    : asset('/default.png');
            @endphp

            <div class="item">
                <div wire:click="openStory({{ $story->id }})"
                     class="card w125 h200 d-block border-0 shadow-xss rounded-xxxl bg-gradiant-bottom overflow-hidden cursor-pointer mb-3 mt-3"
                     @if($bg) style="background-image: url('{{ $bg }}'); background-size: cover; background-position: center;" @endif>
                    <div class="card-body d-block p-3 w-100 position-absolute bottom-0 text-center">
                        <a href="javascript:void(0);">
                            <figure class="avatar ms-auto me-auto mb-0 position-relative w50 z-index-1">
                                <img src="{{ $avatar }}" alt="image"
                                     class="float-right p-0 bg-white rounded-circle w-100 shadow-xss">
                            </figure>
                            <div class="clearfix"></div>
                            <h4 class="fw-600 position-relative z-index-1 ls-1 font-xssss text-white mt-2 mb-1">
                                {{ $author?->name ?? 'Unknown' }}
                            </h4>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            {{-- No stories yet – you can show nothing or a message --}}
        @endforelse
    </div>
</div>
