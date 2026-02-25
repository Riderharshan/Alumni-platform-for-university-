<div class="card w-100 shadow-xss rounded-xxl border-0 p-4 mb-3" id="post-{{ $post->id }}" style="margin-top:16px;">
  <div class="card-body p-0 d-flex">
    <figure class="avatar me-3">
      <img src="{{ Storage::url($post->user->alumniProfile->profile_picture)  ?? asset('default.png') }}" alt="image" class="shadow-sm rounded-circle w45">
    </figure>
    <h4 class="fw-700 text-grey-900 font-xssss mt-1">
     <a href="{{ route('alumni.view', $post->user->id) }}">{{ $post->user->name }} </a> 

      <span class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">{{ $post->created_at->diffForHumans() }}</span>
    </h4>

    <a href="#" class="ms-auto" id="dropdownMenu2-{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="ti-more-alt text-grey-900 btn-round-md bg-greylight font-xss"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-end p-4 rounded-xxl border-0 shadow-lg" aria-labelledby="dropdownMenu2-{{ $post->id }}">
      <div class="card-body p-0 d-flex">
        <a type="button" wire:click="$toggle('showReportForm')" class="d-flex align-items-center text-decoration-none">
  <i class="feather-flag text-grey-500 me-3 font-lg"></i>
  <h4 class="fw-600 text-grey-900 font-xssss mt-0 me-4">
    Report 
    <span class="d-block font-xsssss fw-500 mt-1 lh-3 text-grey-500">Report this post</span>
  </h4>
</a>

      </div>
      
      
      
    </div>
  </div>

  {{-- Post body --}}
  <div class="card-body p-0 me-lg-5">
    <p class="fw-500 text-grey-500 lh-26 font-xssss w-100">
      {!! nl2br(e(Str::limit($post->body, 400))) !!}
      @if(Str::length($post->body) > 400)
        <a href="{{ route('posts.show', $post) }}" class="fw-600 text-primary ms-2">See more</a>
      @endif
    </p>
  </div> 

  {{-- Media grid (if any) --}}
  @if($post->media && $post->media->count())
    <div class="card-body d-block p-0">
      <div class="row ps-2 pe-2">
       {{--  @foreach($post->media->take(3) as $idx => $media)
          <div class="col-xs-4 col-sm-4 p-1">
            <a href="{{ $media->url }}" data-lightbox="post-{{ $post->id }}">
              <img src="{{ $media->thumbnail_url ?? $media->url }}" class="rounded-3 w-100" alt="image">
              @if($idx === 2 && $post->media->count() > 3)
                <span class="img-count font-sm text-white ls-3 fw-600"><b>+{{ $post->media->count() - 3 }}</b></span>
              @endif
            </a>
          </div>
        @endforeach --}}
        @foreach($post->media->take(3) as $idx => $media)
  <div class="col-xs-4 col-sm-4 p-1">
    <a href="{{ $media->url }}" data-lightbox="post-{{ $post->id }}" class="position-relative d-block">
      <img src="{{ $media->thumbnail_url ?? $media->url }}" class="rounded-3 w-100" alt="image">
      
      @if($idx === 2 && $post->media->count() > 3)
        <span class="img-count font-sm text-white ls-3 fw-600">
          <b>+{{ $post->media->count() - 3 }}</b>
        </span>
      @endif

    </a>
  </div>
@endforeach
      </div>
    </div>
  @endif
  {{-- <div class="card-body d-block p-0">
      <div class="row ps-2 pe-2">
          <div class="col-xs-4 col-sm-4 p-1"><a href="images/t-10.jpg" data-lightbox="roadtrip"><img src="images/t-10.jpg" class="rounded-3 w-100" alt="image"></a></div>
          <div class="col-xs-4 col-sm-4 p-1"><a href="images/t-11.jpg" data-lightbox="roadtrip"><img src="images/t-11.jpg" class="rounded-3 w-100" alt="image"></a></div>
          <div class="col-xs-4 col-sm-4 p-1"><a href="images/t-12.jpg" data-lightbox="roadtrip" class="position-relative d-block">
            <img src="images/t-12.jpg" class="rounded-3 w-100" alt="image">
            <span class="img-count font-sm text-white ls-3 fw-600"><b>+2</b>
            </span></a></div>
      </div>
  </div> --}}

  {{-- Reaction / comment / share bar --}}
  <div class="card-body d-flex p-0 mt-3">
   {{--  <a href="#" wire:click.prevent="toggleReaction('like')" class="emoji-bttn d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-2">
      <i class="feather-thumbs-up text-white {{ $userReaction === 'like' ? 'bg-primary-gradiant' : 'bg-greylight' }} me-1 btn-round-xs font-xss"></i>
      <i class="feather-heart text-white {{ $userReaction === 'love' ? 'bg-red-gradiant' : 'bg-greylight' }} me-2 btn-round-xs font-xss"></i>
      <span>{{ array_sum($reactionsCount) ?: 0 }} Like</span>
    </a> --}}

    <div class="d-flex align-items-center">

  {{-- Like --}}
  <button type="button"
        wire:click.prevent="toggleReaction('like')"
        class="emoji-bttn d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-2 btn btn-link p-0 emoji-reaction">

    <img src="{{ $userReaction === 'like'
                ? 'https://fonts.gstatic.com/s/e/notoemoji/latest/1f44d/512.webp'
                : 'https://fonts.gstatic.com/s/e/notoemoji/latest/1f44d/emoji.svg' }}"
         style="width:26px; height:26px; {{ $userReaction !== 'like' ? 'opacity:0.2;' : '' }}"
         class="me-1">

    <span class="me-2">{{ $reactionsCount['like'] ?? 0 }}</span>
    <span class="d-none d-sm-inline">Like</span>

</button>


  {{-- Love --}}
  {{-- <button type="button"
          wire:click.prevent="toggleReaction('love')"
          class="emoji-bttn d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-2 btn btn-link p-0 emoji-reaction">
    <i class="feather-heart text-white {{ $userReaction === 'love' ? 'bg-red-gradiant' : 'bg-greylight' }} me-1 btn-round-xs font-xss"></i>
    <span class="me-2">{{ $reactionsCount['love'] ?? 0 }}</span>
    <span class="d-none d-sm-inline">Love</span>
  </button> --}}
  <button type="button"
        wire:click.prevent="toggleReaction('love')"
        class="emoji-bttn d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-2 btn btn-link p-0 emoji-reaction">

    <img src="{{ $userReaction === 'love'
                ? 'https://fonts.gstatic.com/s/e/notoemoji/latest/2764_fe0f/512.webp'
                : 'https://fonts.gstatic.com/s/e/notoemoji/latest/2764_fe0f/emoji.svg' }}"
         style="width:26px; height:26px; {{ $userReaction === 'love' ? '' : 'opacity:0.2;' }}"
         class="me-1">

    <span class="me-2">{{ $reactionsCount['love'] ?? 0 }}</span>
    <span class="d-none d-sm-inline">Love</span>

</button>




  {{-- Clap --}}
  <button type="button"
        wire:click.prevent="toggleReaction('clap')"
        class="emoji-bttn d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss me-2 btn btn-link p-0 emoji-reaction">

    <img src="{{ $userReaction === 'clap'
                ? 'https://fonts.gstatic.com/s/e/notoemoji/latest/1f44f/512.webp'
                : 'https://fonts.gstatic.com/s/e/notoemoji/latest/1f44f/emoji.svg' }}"
         style="width:26px; height:26px; {{ $userReaction !== 'clap' ? 'opacity:0.2;' : '' }}"
         class="me-1">

    <span class="me-2">{{ $reactionsCount['clap'] ?? 0 }}</span>
    <span class="d-none d-sm-inline">Clap</span>

</button>


  {{-- aggregated text like "2.8K Like" (optional) --}}
  <span class="ms-3 text-muted small">{{ number_format(array_sum($reactionsCount ?? [])) ?: 0 }} reactions</span>

</div>



    {{-- small emoji list (static) --}}
   {{--  <div class="emoji-wrap">
      <ul class="emojis list-inline mb-0">
        <li class="emoji list-inline-item"><i class="em em---1"></i></li>
        <li class="emoji list-inline-item"><i class="em em-angry"></i></li>
        <li class="emoji list-inline-item"><i class="em em-anguished"></i></li>
        <li class="emoji list-inline-item"><i class="em em-astonished"></i></li>
        <li class="emoji list-inline-item"><i class="em em-blush"></i></li>
        <li class="emoji list-inline-item"><i class="em em-clap"></i></li>
        <li class="emoji list-inline-item"><i class="em em-cry"></i></li>
        <li class="emoji list-inline-item"><i class="em em-full_moon_with_face"></i></li>
      </ul>
    </div> --}}

    <a href="#" class="d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss" wire:click.prevent="$dispatch('focusComments', {{ $post->id }})">
      <i class="feather-message-circle text-dark text-grey-900 btn-round-sm font-lg"></i>
      <span class="d-none-xss ms-1">{{ $post->comments_count ?? $post->comments()->count() }} Comment</span>
    </a>

    <a href="#" id="dropdownMenu21-{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false" class="ms-auto d-flex align-items-center fw-600 text-grey-900 text-dark lh-26 font-xssss">
      <i class="feather-share-2 text-grey-900 text-dark btn-round-sm font-lg"></i><span class="d-none-xs ms-1">Share</span>
    </a>

    <div class="dropdown-menu dropdown-menu-end p-4 rounded-xxl border-0 shadow-lg" aria-labelledby="dropdownMenu21-{{ $post->id }}">
      <h4 class="fw-700 font-xss text-grey-900 d-flex align-items-center">Share <i class="feather-x ms-auto font-xssss btn-round-xs bg-greylight text-grey-900 me-2"></i></h4>
      <div class="card-body p-0 d-flex">
        <ul class="d-flex align-items-center justify-content-between mt-2">
          
          <li><a href="https://wa.me/?text={{ urlencode(url()->current() . '#post-' . $post->id) }}" 
      target="_blank" class="btn-round-lg bg-pinterest"> <i class="fa-brands fa-whatsapp"></i></a></li>
        </ul>
      </div>

      <h4 class="fw-700 font-xssss mt-4 text-grey-500 d-flex align-items-center mb-3">Copy Link</h4>
      <i class="feather-copy position-absolute right-35 mt-3 font-xs text-grey-500"></i>
      <input type="text" value="{{ url()->current() . '#post-' . $post->id }}" class="bg-grey text-grey-500 font-xssss border-0 lh-32 p-2 font-xssss fw-600 rounded-3 w-100 theme-dark-bg" readonly>
    </div>
  </div>

  {{-- Comments preview --}}
  <div class="card-body p-0 mt-3">
    @foreach($commentsPreview as $comment)
      <div class="d-flex align-items-start mb-2">
        <figure class="avatar me-2"><img src="{{ Storage::url($comment->user->alumniProfile->profile_picture) ?? asset('default.png') }}" alt="image" class="shadow-sm rounded-circle w30"></figure>
        <div>
          <div class="small"><strong class="fw-600">{{ $comment->user->name }}</strong> <span class="text-grey-500"> {{ $comment->created_at->diffForHumans() }}</span></div>
          <div class="small text-grey-500">{{ $comment->body }}</div>
        </div>
      </div>
    @endforeach

    @if(($post->comments_count ?? $post->comments()->count()) > count($commentsPreview))
      <a href="{{ route('posts.show', $post) }}" class="small">View all ({{ $post->comments_count ?? $post->comments()->count() }})</a>
    @endif
  </div>

  {{-- Comment input / Report (auth only) --}}
  <div class="card-body p-0 mt-3">
    @auth
      <form wire:submit.prevent="addComment">
        <div class="input-group">
          <input type="text" wire:model.defer="commentBody" class="form-control font-xssss" placeholder="Write a comment...">
          <button class="btn btn-primary" type="submit">Send</button>
        </div>
        @error('commentBody') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
      </form>

      <div class="mt-2">

        <div class="mt-2" wire:ignore.self>
          @if($showReportForm ?? false)
            <div class="p-3 border rounded">
              <label class="form-label small">Reason</label>
              <input type="text" wire:model.defer="reportReason" class="form-control form-control-sm" />
              <label class="form-label small mt-2">Details</label>
              <textarea wire:model.defer="reportDetails" class="form-control form-control-sm"></textarea>
              <div class="mt-2 d-flex gap-2">
                <button wire:click="fileReport" class="btn btn-danger btn-sm">Submit report</button>
                <button type="button" class="btn btn-light btn-sm" wire:click="$set('showReportForm', false)">Cancel</button>
              </div>
              @error('reportReason') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>
          @endif
        </div>
      </div>
    @else
      <div class="mt-2"><a href="#" wire:click="$dispatch('showLogin')">Log in to react or comment</a></div>
    @endauth
  </div>
</div>
