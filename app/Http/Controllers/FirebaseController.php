<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class FirebaseController extends Controller
{
    protected FirebaseAuth $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Verify the Firebase ID token for phone authentication.
     * Expects: { idToken: string }
     * Returns: JSON { success: true, phone: ..., uid: ... }
     */
    public function verifyIdToken(Request $request)
    {
        $request->validate(['idToken' => 'required|string']);

        try {
            $verified = $this->auth->verifyIdToken($request->input('idToken'));
            $claims = $verified->claims()->all();

            // Firebase uid and phone
            $uid = $verified->claims()->get('sub'); // or $verified->getClaim('sub')
            // Use Firebase SDK user fetch to get phone number reliably
            $firebaseUser = $this->auth->getUser($uid);
            $phone = $firebaseUser->phoneNumber ?? null;

            if (! $phone) {
                return response()->json(['success' => false, 'message' => 'Phone not present in token'], 400);
            }

            return response()->json([
                'success' => true,
                'uid' => $uid,
                'phone' => $phone,
            ]);
        } catch (\Kreait\Firebase\Exception\Auth\FailedToVerifyToken $e) {
            return response()->json(['success' => false, 'message' => 'Invalid token'], 401);
        } catch (\Throwable $e) {
            \Log::error('Firebase verify error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }
}
