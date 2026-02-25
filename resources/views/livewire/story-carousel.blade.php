{{-- Modal that hosts the viewer --}}
<div class="modal bottom side fade" id="Modalstory" tabindex="-1" aria-hidden="true" style="overflow-y:auto;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 bg-transparent">
      <button type="button" class="btn-close mt-0 position-absolute top--30 right--10" data-bs-dismiss="modal" aria-label="Close"></button>

      <div class="modal-body p-0">
        <div class="card w-100 border-0 rounded-3 overflow-hidden bg-gradiant-bottom bg-gradiant-top">
          <div class="owl-carousel owl-theme dot-style3 story-slider owl-dot-nav nav-none" id="storySlides">
            @forelse($slides as $s)
              <div class="item" style="max-height: 80vh;">
                @if($s['type']==='video')
                  <video autoplay loop muted playsinline class="w-100" style="height:80vh;object-fit:cover;">
                    <source src="{{ $s['url'] }}" type="{{ $s['mime'] ?? 'video/mp4' }}">
                  </video>
                @elseif($s['type']==='image')
                  <img src="{{ $s['url'] }}" alt="" class="w-100" style="height:80vh;object-fit:cover;">
                @else
                  <div class="p-5 text-center text-white">Create a new story…</div>
                @endif
              </div>
            @empty
              <div class="item p-5 text-center text-white">No media.</div>
            @endforelse
          </div>
        </div>

        <div class="form-group mt-3 mb-0 p-3 position-absolute bottom-0 z-index-1 w-100">
          <input type="text" class="style2-input w-100 bg-transparent border-light-md p-3 pe-5 font-xssss fw-500 text-white" placeholder="Write a comment">
          <span class="feather-send text-white font-md position-absolute" style="bottom:35px;right:30px;"></span>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
  // When StoryCarousel finishes loading slides, (re)init Owl for the inner viewer
  Livewire.on('initStoryOwl', () => {
    const $el = $('#storySlides');
    try { $el.trigger('destroy.owl.carousel'); } catch(e) {}
    $el.owlCarousel({
      items: 1,
      dots: true,
      nav: false,
      loop: true,
      autoplay: false
    });
  });
});
</script>
@endpush
