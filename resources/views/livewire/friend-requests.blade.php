<div class="card w-100 shadow-xss rounded-xxl border-0 mb-3">
    <div class="card-body d-flex align-items-center p-4">
        <h4 class="fw-700 mb-0 font-xssss text-grey-900">Friend Request</h4>
        <a href="#" class="fw-600 ms-auto font-xssss text-primary">See all</a>
    </div>

    @forelse($requests as $req)
        @php
            $user = $req->requester;
            // avatar path fallback
            $avatarPath = $user->alumniProfile?->profile_picture;
            $avatar = $avatarPath ? Storage::url($avatarPath) : asset('default.png');

            // optional mutual friends placeholder (you can replace with real data)
            $mutual = $user->mutual_friends_count ?? '12 mutual friends';
        @endphp

        <div class="card-body d-flex pt-4 ps-4 pe-4 pb-0">
            <figure class="avatar me-3">
                <img src="{{ $avatar }}" alt="image" class="shadow-sm rounded-circle w45">
            </figure>

            <h4 class="fw-700 text-grey-900 font-xssss mt-1">
                {{ $user->name }}
                <span class="d-block font-xssss fw-500 mt-1 lh-3 text-grey-500">{{ $mutual }}</span>
            </h4>
        </div>
        <div class="card-body d-flex align-items-center pt-0 ps-4 pe-4 pb-4">
            <a href="#" wire:click.prevent="confirm({{ $user->id }})"
               class="p-2 lh-20 w100 bg-primary-gradiant me-2 text-white text-center font-xssss fw-600 ls-1 rounded-xl">Confirm</a>

            <a href="#" wire:click.prevent="deleteRequest({{ $user->id }})"
               class="p-2 lh-20 w100 bg-grey text-grey-800 text-center font-xssss fw-600 ls-1 rounded-xl">Delete</a>

            <a href="#" wire:click.prevent="block({{ $user->id }})"
               class="p-2 lh-20 ms-2 text-danger font-xssss fw-600">Block</a>
        </div>
    @empty
        <div class="card-body p-4">
            
 
  <center>
            <img src="https://fonts.gstatic.com/s/e/notoemoji/latest/1f423/512.gif" alt="🐣" width="50" height="50"> </center>
            <p class="text-center text-muted mb-0 font-xssss">No pending friend requests</p>
        </div>

        
    @endforelse
</div>
