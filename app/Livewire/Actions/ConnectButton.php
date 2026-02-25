<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use App\Models\Connection;
use Illuminate\Support\Facades\Auth;

class ConnectButton extends Component
{
   
   public $user; // The target user
    public $statusKey;
    public $statusLabel;

    public function mount($user)
    {
        $this->user = $user;
        $this->loadStatus();
    }

    protected function loadStatus()
    {
        $authId = Auth::id();
        $otherId = $this->user->id;

        $connection = Connection::where(function ($q) use ($authId, $otherId) {
            $q->where('requester_id', $authId)
              ->where('requestee_id', $otherId);
        })->orWhere(function ($q) use ($authId, $otherId) {
            $q->where('requester_id', $otherId)
              ->where('requestee_id', $authId);
        })->first();

        if (!$connection) {
            $this->statusKey = 'none';
            $this->statusLabel = 'Connect';
            return;
        }

        $status = $connection->status;
        $iAmRequester = $connection->requester_id === $authId;

        $map = [
            'pending' => $iAmRequester ? ['pending_sent', 'Request Sent'] : ['pending_incoming', 'Accept Request'],
            'accepted' => ['accepted', 'Connected'],
            'rejected' => ['rejected', 'Connect Again'],
            'blocked' => $iAmRequester ? ['blocked_by_you', 'Blocked'] : ['blocked_by_other', 'Blocked'],
        ];

        [$this->statusKey, $this->statusLabel] = $map[$status] ?? ['none', 'Connect'];
    }

    public function sendRequest()
    {
        $authId = Auth::id();
        $userId = $this->user->id;

        if ($authId === $userId) return;

        $exists = Connection::where(function ($q) use ($authId, $userId) {
            $q->where('requester_id', $authId)->where('requestee_id', $userId);
        })->orWhere(function ($q) use ($authId, $userId) {
            $q->where('requester_id', $userId)->where('requestee_id', $authId);
        })->first();

        if ($exists) {
            if ($exists->status === 'accepted') return;
            if ($exists->status === 'pending') return;
            if ($exists->status === 'rejected') {
                $exists->update([
                    'status' => 'pending',
                    'requested_at' => now(),
                    'responded_at' => null,
                    'requester_id' => $authId,
                    'requestee_id' => $userId,
                ]);
                $this->loadStatus();
                return;
            }
        }

        Connection::create([
            'requester_id' => $authId,
            'requestee_id' => $userId,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        $this->loadStatus();
    }

    public function acceptRequest()
    {
        $authId = Auth::id();
        $userId = $this->user->id;

        $conn = Connection::where('requester_id', $userId)
            ->where('requestee_id', $authId)
            ->where('status', 'pending')
            ->first();

        if ($conn) {
            $conn->update(['status' => 'accepted', 'responded_at' => now()]);
        }

        $this->loadStatus();
    }

   
    public function render()
    {
        return view('livewire.actions.connect-button');
    }
}
