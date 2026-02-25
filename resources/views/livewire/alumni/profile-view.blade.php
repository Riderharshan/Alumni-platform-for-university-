<div class="middle-sidebar-bottom">
    <div class="middle-sidebar-left">
        <div class="row">
            {{-- Profile header --}}
            <div class="col-lg-12">
                <div class="card w-100 border-0 p-0 bg-white shadow-xss rounded-xxl">
                    <div class="card-body h250 p-0 rounded-xxl overflow-hidden m-3">
                        <img src="{{ $user->alumniProfile->cover_image ?? asset('bg.png') }}" alt="cover" class="w-100">
                    </div>
                    <div class="card-body p-0 position-relative">
                        <figure class="avatar position-absolute w100 z-index-1" style="top:-40px; left: 30px;">
                            <img src="{{ Storage::url($user->alumniProfile->profile_picture) ?? asset('default.png') }}" alt="avatar" class="float-right p-1 bg-white rounded-circle w-100">
                        </figure>
                        <h4 class="fw-700 font-sm mt-2 mb-lg-5 mb-4 pl-15">
                            {{ $user->name }}
                            <span class="fw-500 font-xssss text-grey-500 mt-1 mb-3 d-block">{{ $user->alumniProfile->display_name ?? ''  }}</span>
                        </h4>
                    </div>
                </div>
            </div>

             @if(can_view_profile($user->alumniProfile))
                                            
           
            {{-- Left sidebar --}}
            <div class="col-xl-4 col-xxl-3 col-lg-4 pe-0">
                <div class="card w-100 shadow-xss rounded-xxl border-0 mb-3 mt-3">
                    <div class="card-body d-block p-4">
                        <h4 class="fw-700 mb-3 font-xsss text-grey-900">About</h4>
                        <p class="fw-500 text-grey-500 lh-24 font-xssss mb-0">
                            {{ $user->alumniProfile->about ?? 'No bio yet.' }}
                        </p>
                    </div>
                     <div class="card-body d-flex pt-0">
                        <i class="feather-mail text-grey-500 me-3 font-lg"></i>
                        <h4 class="fw-700 text-grey-900 font-xssss mt-1">
                            {{ $user->email }}
                            
                        </h4>
                       
                    </div>
                    
                   
                    <div class="card-body d-flex pt-0">
                        <i class="feather-phone text-grey-500 me-3 font-lg"></i>
                        <h4 class="fw-700 text-grey-900 font-xssss mt-1">
                        {{ $user->alumniProfile->extra['phone']  }}                      
                        </h4>
                    </div>
                    <div class="card-body d-flex pt-0">
                        <i class="feather-phone text-grey-500 me-3 font-lg"></i>
                        <h4 class="fw-700 text-grey-900 font-xssss mt-1">
                        {{ $user->alumniProfile->extra['phone']  }}                      
                        </h4>
                    </div>
                    <div class="card-body d-flex pt-0">
                        <i class="feather-map-pin text-grey-500 me-3 font-lg"></i>
                        <h4 class="fw-700 text-grey-900 font-xssss mt-1">
                            {{ $user->alumniProfile->location_city ?? ' — ' }},{{ $user->alumniProfile->location_state ?? ' — ' }}, {{ $user->alumniProfile->location_country ?? ' — ' }}
                            
                        </h4>
                    </div>
                </div>
            </div>

            {{-- Main feed --}}
            <div class="col-xl-8 col-xxl-9 col-lg-8">
                {{-- Posts loop --}}


             @if (Auth::id() == $userId)
                <livewire:post-form />
            @endif
 

            @foreach($posts as $post)
                <livewire:post-card :post="$post" :wire:key="'post-'.$post->id" />
                
            @endforeach

            <div class="text-center mt-3">
                @if($hasMore)
                <button wire:click="loadMore" class="btn btn-outline-secondary">Load more</button>
                @endif
            </div>
                <div class="mt-3">
                    {{ $posts->links() }}
                </div>
            </div>

            @else
                <div class="">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12 text-center default-page vh-10 align-items-center d-flex">
                        <div class="border-0 text-center d-block p-0">
                            <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f512/512.webp" alt="icon" class="w200 mb-4 ms-auto me-auto pt-md-5">
                            
                            <h6 class="fw-700 text-grey-900 display1-size display4-sm-size">Please send the connection request to view the profile</h6>

                            <livewire:actions.connect-button :user="$user" />

                        </div>
                    </div>
                </div>
                </div>

            @endif


        </div>
    </div>
</div>
