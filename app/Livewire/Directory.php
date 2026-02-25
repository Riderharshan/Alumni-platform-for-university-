<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Connection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
            use Livewire\Attributes\Layout;


class Directory extends Component
{
    public string $search = '';

    // map of other_user_id => Connection model
    public array $connectionsMap = [];
            #[Layout('components.layouts.master-alumni-profile')]

    public function mount()
    {
        $this->refreshConnectionsMap();
    }

    protected function refreshConnectionsMap(): void
    {
        $authId = Auth::id();

        $userQuery = User::with('alumniProfile')->whereNotIn('role_id', ['1', '3'])
            ->where('id', '!=', $authId);

        if ($this->search !== '') {
            $userQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $userIds = $userQuery->pluck('id')->toArray();

        if (empty($userIds)) {
            $this->connectionsMap = [];
            return;
        }

        $connections = Connection::where(function($q) use ($authId, $userIds) {
            $q->whereIn('requester_id', array_merge([$authId], $userIds))
              ->whereIn('requestee_id', array_merge([$authId], $userIds));
        })->where(function($q) use ($authId) {
            $q->where('requester_id', $authId)
              ->orWhere('requestee_id', $authId);
        })->get();

        $map = [];
        foreach ($connections as $conn) {
            $otherId = $conn->requester_id === $authId ? $conn->requestee_id : $conn->requester_id;
            $map[$otherId] = $conn;
        }

        $this->connectionsMap = $map;
    }

    // <-- REPLACED: use a "getUsersProperty" accessor (works with $this->users in blade) -->
    public function getUsersProperty()
    {
        $authId = Auth::id();

        return User::with('alumniProfile')->whereNotIn('role_id', ['1', '3'])
            ->where('id', '!=', $authId)
            ->when($this->search !== '', function($q) {
                $q->where(function($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->get();
    }

    // actions (same behaviour as before) — note: removed forgetComputed() calls
    public function sendRequest(int $userId): void
    {
        $authId = Auth::id();

        $exists = Connection::where(function($q) use ($authId, $userId) {
            $q->where('requester_id', $authId)->where('requestee_id', $userId);
        })->orWhere(function($q) use ($authId, $userId) {
            $q->where('requester_id', $userId)->where('requestee_id', $authId);
        })->first();

        if ($exists) {
            $this->refreshConnectionsMap();
            return;
        }

        Connection::create([
            'requester_id' => $authId,
            'requestee_id' => $userId,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        // refresh users & connections map
        $this->refreshConnectionsMap();
    }

    public function cancelRequest(int $userId): void
    {
        $authId = Auth::id();

        $conn = Connection::where('requester_id', $authId)
            ->where('requestee_id', $userId)
            ->where('status', 'pending')
            ->first();

        if ($conn) $conn->delete();

        $this->refreshConnectionsMap();
    }

    public function acceptRequest(int $userId): void
    {
        $authId = Auth::id();

        $conn = Connection::where('requester_id', $userId)
            ->where('requestee_id', $authId)
            ->where('status', 'pending')
            ->first();

        if ($conn) {
            $conn->update([
                'status' => 'accepted',
                'responded_at' => now(),
            ]);
        }

        $this->refreshConnectionsMap();
    }

    public function rejectRequest(int $userId): void
    {
        $authId = Auth::id();

        $conn = Connection::where('requester_id', $userId)
            ->where('requestee_id', $authId)
            ->where('status', 'pending')
            ->first();

        if ($conn) {
            $conn->update([
                'status' => 'rejected',
                'responded_at' => now(),
            ]);
        }

        $this->refreshConnectionsMap();
    }

    public function removeConnection(int $userId): void
    {
        $authId = Auth::id();

        $conn = Connection::where(function($q) use ($authId, $userId) {
            $q->where('requester_id', $authId)->where('requestee_id', $userId);
        })->orWhere(function($q) use ($authId, $userId) {
            $q->where('requester_id', $userId)->where('requestee_id', $authId);
        })->where('status', 'accepted')->first();

        if ($conn) $conn->delete();

        $this->refreshConnectionsMap();
    }

    public function blockUser(int $userId): void
    {
        $authId = Auth::id();

        $conn = Connection::where(function($q) use ($authId, $userId) {
            $q->where('requester_id', $authId)->where('requestee_id', $userId);
        })->orWhere(function($q) use ($authId, $userId) {
            $q->where('requester_id', $userId)->where('requestee_id', $authId);
        })->first();

        if ($conn) {
            $conn->update([
                'status' => 'blocked',
                'responded_at' => now(),
            ]);
        } else {
            Connection::create([
                'requester_id' => $authId,
                'requestee_id' => $userId,
                'status' => 'blocked',
                'requested_at' => now(),
                'responded_at' => now(),
            ]);
        }

        $this->refreshConnectionsMap();
    }

    // helper to compute the label/key for a user from map
    public function getStatusForUser($otherUser): array
    {
        $authId = Auth::id();
        $otherId = $otherUser->id;

        $conn = $this->connectionsMap[$otherId] ?? null;

        if (! $conn) {
            return ['label' => 'Not connected', 'key' => 'none', 'conn' => null];
        }

        $iAmRequester = $conn->requester_id === $authId;
        $status = $conn->status;

        if ($status === 'pending') {
            return $iAmRequester
                ? ['label' => 'Request sent', 'key' => 'pending_sent', 'conn' => $conn]
                : ['label' => 'Requested you', 'key' => 'pending_incoming', 'conn' => $conn];
        }

        if ($status === 'accepted') {
            return ['label' => 'Friend', 'key' => 'accepted', 'conn' => $conn];
        }

        if ($status === 'rejected') {
            return $iAmRequester
                ? ['label' => 'Request rejected', 'key' => 'rejected_sent', 'conn' => $conn]
                : ['label' => 'You rejected', 'key' => 'rejected_incoming', 'conn' => $conn];
        }

        if ($status === 'blocked') {
            return $iAmRequester
                ? ['label' => 'You blocked', 'key' => 'blocked_by_you', 'conn' => $conn]
                : ['label' => 'Blocked you', 'key' => 'blocked_by_other', 'conn' => $conn];
        }

        return ['label' => ucfirst($status), 'key' => $status, 'conn' => $conn];
    }

    // refresh connections when search updates
    public function updatedSearch(): void
    {
        $this->refreshConnectionsMap();
    }

    public function render()
    {
        return view('livewire.directory');
    }
}
