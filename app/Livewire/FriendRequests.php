<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Connection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FriendRequests extends Component
{
    // no public props required for this basic component
    protected $listeners = [
        'refreshFriendRequests' => 'refreshIncoming',
    ];

    public function mount()
    {
        $this->refreshIncoming();
    }

    // load incoming pending requests (requester -> auth)
    public function getIncomingRequestsProperty()
    {
        $authId = Auth::id();

        return Connection::with(['requester.alumniProfile'])
            ->where('requestee_id', $authId)
            ->where('status', 'pending')
            ->orderBy('requested_at', 'desc')
            ->get();
    }

    // helper to refresh any cached state (not strictly required w/ computed prop)
    public function refreshIncoming(): void
    {
        // nothing to do because computed accessor runs on render,
        // but we provide this method so actions can call it explicitly or emit
        $this->dispatch('render');
    }

    // Accept: set status to accepted
    public function confirm(int $requesterId): void
    {
        $authId = Auth::id();

        $conn = Connection::where('requester_id', $requesterId)
            ->where('requestee_id', $authId)
            ->where('status', 'pending')
            ->first();

        if ($conn) {
            $conn->update([
                'status' => 'accepted',
                'responded_at' => Carbon::now(),
            ]);
        }

        $this->refreshIncoming();
    }

    // Delete (reject) the request — mark rejected (keeps history)
    public function deleteRequest(int $requesterId): void
    {
        $authId = Auth::id();

        $conn = Connection::where('requester_id', $requesterId)
            ->where('requestee_id', $authId)
            ->where('status', 'pending')
            ->first();

        if ($conn) {
            $conn->update([
                'status' => 'rejected',
                'responded_at' => Carbon::now(),
            ]);
        }

        $this->refreshIncoming();
    }

    // Block the user (create or update to blocked)
    public function block(int $requesterId): void
    {
        $authId = Auth::id();

        $conn = Connection::where(function($q) use ($authId, $requesterId) {
            $q->where('requester_id', $authId)->where('requestee_id', $requesterId);
        })->orWhere(function($q) use ($authId, $requesterId) {
            $q->where('requester_id', $requesterId)->where('requestee_id', $authId);
        })->first();

        if ($conn) {
            $conn->update([
                'status' => 'blocked',
                'responded_at' => Carbon::now(),
            ]);
        } else {
            // create a blocking record where auth is requester for bookkeeping
            Connection::create([
                'requester_id' => $authId,
                'requestee_id' => $requesterId,
                'status' => 'blocked',
                'requested_at' => Carbon::now(),
                'responded_at' => Carbon::now(),
            ]);
        }

        $this->refreshIncoming();
    }

    public function render()
    {
        return view('livewire.friend-requests', [
            'requests' => $this->incomingRequests, // computed accessor
        ]);
    }
}
