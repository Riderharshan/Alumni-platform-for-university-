<?php

use App\Models\Connection;
use Illuminate\Support\Facades\Auth;

if (! function_exists('can_view_profile')) {
    function can_view_profile($profile, $viewer = null): bool
    {
        $viewer = $viewer ?? Auth::user();
        $visibility = $profile->visibility ?? 'public';
        $ownerId = $profile->user_id;
        $viewerId = $viewer?->id;

        if ($ownerId === $viewerId) {
            return true;
        }

        if ($visibility === 'public') {
            return true;
        }

        if ($visibility === 'alumni_only') {
            return Connection::where(function ($q) use ($ownerId, $viewerId) {
                $q->where('requester_id', $viewerId)
                  ->where('requestee_id', $ownerId)
                  ->where('status', 'accepted');
            })->orWhere(function ($q) use ($ownerId, $viewerId) {
                $q->where('requester_id', $ownerId)
                  ->where('requestee_id', $viewerId)
                  ->where('status', 'accepted');
            })->exists();
        }

        return false; // private or unknown
    }
}
