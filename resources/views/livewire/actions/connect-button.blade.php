<div>
    @if($statusKey === 'none')
        <button wire:click="sendRequest"
                class="p-3 w175 bg-success text-white d-inline-block text-center fw-600 font-xssss rounded-3 text-uppercase ls-3">
            Connect
        </button>
 
    @elseif($statusKey === 'pending_sent')
        <button class="p-3 w175 bg-secondary text-white d-inline-block text-center fw-600 font-xssss rounded-3 text-uppercase ls-3" disabled>
            Request Sent
        </button>

    @elseif($statusKey === 'pending_incoming')
        <button wire:click="acceptRequest"
                class="p-3 w175 bg-warning text-white d-inline-block text-center fw-600 font-xssss rounded-3 text-uppercase ls-3">
            Accept Request
        </button>

        
        
        
        

    @elseif($statusKey === 'accepted')
        <button class="btn btn-sm bg-secondary text-white rounded-pill px-3 py-1" disabled>
            Connected
        </button>

    @elseif($statusKey === 'rejected')
        <button wire:click="sendRequest"
                class="p-3 w175 bg-success text-white d-inline-block text-center fw-600 font-xssss rounded-3 text-uppercase ls-3">
            Connect Again
        </button>

    @elseif(str_contains($statusKey, 'blocked'))
        <button class="p-3 w175 bg-danger text-white d-inline-block text-center fw-600 font-xssss rounded-3 text-uppercase ls-3" disabled>
            Blocked
        </button>
    @endif
</div>
