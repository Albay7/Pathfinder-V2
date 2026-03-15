<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated or guest user's email address as verified and log them in.
     */
    public function __invoke(\Illuminate\Http\Request $request): RedirectResponse
    {
        $user = \App\Models\User::findOrFail($request->route('id'));

        // Verify the hash manually as we are not using the standard EmailVerificationRequest
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('pathfinder.home', absolute: false).'?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
            
            // Log the user in only if they are not already logged in
            if (! \Illuminate\Support\Facades\Auth::check()) {
                \Illuminate\Support\Facades\Auth::login($user);
            }

            // Signal to the registration polling endpoint (for cross-device login)
            // if this verification happened via a standard link but a laptop is polling.
            $token = \Illuminate\Support\Facades\Cache::get("pending_email_{$user->email}");
            if ($token) {
                \Illuminate\Support\Facades\Cache::put("registration_verified_{$token}", $user->id, now()->addMinutes(10));
            }
        }

        return redirect()->intended(route('pathfinder.home', absolute: false).'?verified=1');
    }
}
