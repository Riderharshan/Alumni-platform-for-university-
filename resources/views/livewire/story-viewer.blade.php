<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}

    <div wire:ignore.self class="modal bottom side fade" id="Modalstory" tabindex="-1" role="dialog" style="overflow-y: auto;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 bg-transparent">
            <button type="button"
                    class="close mt-0 position-absolute top--30 right--10"
                    data-bs-dismiss="modal" aria-label="Close">
                <i class="ti-close text-grey-900 font-xssss"></i>
            </button>
            <div class="modal-body p-0">
                @if($story && count($slides))
                    <div class="card w-100 border-0 rounded-3 overflow-hidden bg-gradiant-bottom bg-gradiant-top">
                        <div class="owl-carousel owl-theme dot-style3 story-slider owl-dot-nav nav-none">
                            @foreach($slides as $slide)
                                <div class="item">
                                    <img src="{{ $slide['url'] }}" alt="story image" class="w-100">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group mt-3 mb-0 p-3 position-absolute bottom-0 z-index-1 w-100">
                        <input type="text"
                               class="style2-input w-100 bg-transparent border-light-md p-3 pe-5 font-xssss fw-500 text-white"
                               placeholder="Write comments">
                        <span class="feather-send text-white font-md position-absolute"
                              style="bottom: 35px; right: 30px;"></span>
                    </div>
                @else
                    <div class="card w-100 border-0 rounded-3 overflow-hidden bg-dark">
                        <div class="card-body text-center text-white p-5">
                            No story to display.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

</div>
