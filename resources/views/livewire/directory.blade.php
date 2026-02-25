<div class="middle-sidebar-left pe-0">
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-xss w-100 d-block d-flex border-0 p-4 mb-3">
                <div class="card-body d-flex align-items-center p-0">
                    <h2 class="fw-700 mb-0 mt-0 font-md text-grey-900">Friends</h2>

                    <div class="search-form-2 ms-auto">
                        <i class="ti-search font-xss"></i>

                        {{-- Livewire 3 recommended live binding with debounce --}}
                        <input wire:model.live.debounce.250ms="search" type="text"
                               class="form-control text-grey-500 mb-0 bg-greylight theme-dark-bg border-0"
                               placeholder="Search here.">
                    </div>

                    <a href="#" class="btn-round-md ms-2 bg-greylight theme-dark-bg rounded-3">
                        <i class="feather-filter font-xss text-grey-500"></i>
                    </a>
                </div>
            </div>

            <div class="row ps-2 pe-2">
                @foreach($this->users as $user)
                   @php
    $st = $this->getStatusForUser($user);
    $label = $st['label'];
    $key = $st['key'];

    // avatar: use Storage::url only when profile_picture exists, otherwise default
    $avatarPath = $user->alumniProfile?->profile_picture;
    $avatar = $avatarPath ? Storage::url($avatarPath) : asset('default.png');

    // display name fallback
    $handle = $user->alumniProfile?->display_name ?? Str::slug($user->name, '_');
@endphp


                    <div class="col-md-3 col-sm-4 pe-2 ps-2">
                        <div class="card d-block border-0 shadow-xss rounded-3 overflow-hidden mb-3">
                            <div class="card-body d-block w-100 ps-3 pe-3 pb-4 text-center">
                                <a href="/alumni/{{ $user->id }}">
                                <figure class="avatar ms-auto me-auto mb-0 position-relative w65 z-index-1">
                                    <img src="{{  $avatar  }}" alt="image"
                                         class="float-right p-0 bg-white rounded-circle w-100 shadow-xss">
                                </figure></a>
                                <div class="clearfix"></div>
                                <a href="/alumni/{{ $user->id }}"><h4 class="fw-700 font-xsss mt-3 mb-1">{{ $user->name }}</h4></a>
                                <p class="fw-500 font-xsssss text-grey-500 mt-0 mb-1">{{ '@'.$handle }}</p>

                                <p class="font-xsssss mb-2"><small class="text-muted">{{ $label }}</small></p>

                                <div>
                                    @if($key === 'none')
                                        <a href="#" wire:click.prevent="sendRequest({{ $user->id }})"
                                           class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 d-inline-block rounded-xl bg-success font-xsssss fw-700 ls-lg text-white">
                                           ADD FRIEND
                                        </a>

                                    @elseif($key === 'pending_sent')
                                        <a href="#" wire:click.prevent="cancelRequest({{ $user->id }})"
                                           class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 d-inline-block rounded-xl bg-warning font-xsssss fw-700 ls-lg text-white">
                                           CANCEL
                                        </a>

                                    @elseif($key === 'pending_incoming')
                                        <a href="#" wire:click.prevent="acceptRequest({{ $user->id }})"
                                           class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 me-1 d-inline-block rounded-xl bg-success font-xsssss fw-700 ls-lg text-white">
                                           ACCEPT
                                        </a>
                                        <a href="#" wire:click.prevent="rejectRequest({{ $user->id }})"
                                           class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 d-inline-block rounded-xl bg-danger font-xsssss fw-700 ls-lg text-white">
                                           REJECT
                                        </a>

                                    @elseif($key === 'accepted')
                                        <a href="#" wire:click.prevent="removeConnection({{ $user->id }})"
                                           class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 d-inline-block rounded-xl bg-danger font-xsssss fw-700 ls-lg text-white">
                                           FRIEND
                                        </a>

                                    @elseif($key === 'rejected_sent' || $key === 'rejected_incoming')
                                        <a href="#" wire:click.prevent="sendRequest({{ $user->id }})"
                                           class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 d-inline-block rounded-xl bg-success font-xsssss fw-700 ls-lg text-white">
                                           SEND AGAIN
                                        </a>

                                    @elseif($key === 'blocked_by_you' || $key === 'blocked_by_other')
                                        <a href="#" class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 d-inline-block rounded-xl bg-secondary font-xsssss fw-700 ls-lg text-white">BLOCKED</a>

                                    @else
                                        <a href="#" wire:click.prevent="sendRequest({{ $user->id }})"
                                           class="mt-0 btn pt-2 pb-2 ps-3 pe-3 lh-24 ms-1 ls-3 d-inline-block rounded-xl bg-success font-xsssss fw-700 ls-lg text-white">
                                           ADD FRIEND
                                        </a>
                                    @endif

                                    @if(! in_array($key, ['blocked_by_you', 'blocked_by_other']))
                                        <a href="#" wire:click.prevent="blockUser({{ $user->id }})" class="ms-1 text-danger font-xsss">Block</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
        </div>
    </div>
</div>
