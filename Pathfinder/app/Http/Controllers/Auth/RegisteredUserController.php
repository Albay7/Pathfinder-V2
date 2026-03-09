<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationLinkMail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Stores data in cache and sends verification email.
     * User is only created in DB after clicking the verification link.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $token = Str::random(64);

        // Invalidate any previous pending registration for this email
        $oldToken = Cache::get("pending_email_{$request->email}");
        if ($oldToken) {
            Cache::forget("pending_registration_{$oldToken}");
        }

        // Store registration data in cache (expires in 60 minutes)
        $data = [
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
        ];

        Cache::put("pending_registration_{$token}", $data, now()->addMinutes(60));
        Cache::put("pending_email_{$request->email}", $token, now()->addMinutes(60));

        // Generate signed verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.complete',
            now()->addMinutes(60),
            ['token' => $token]
        );

        // Send verification email
        try {
            Mail::to($request->email)->send(new VerificationLinkMail($verificationUrl, $data['name']));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send verification email: ' . $e->getMessage());
            // Clean up cache since user can't verify without the email
            Cache::forget("pending_registration_{$token}");
            Cache::forget("pending_email_{$request->email}");
            return response()->json([
                'message' => 'Unable to send verification email. Please try again later.',
            ], 503);
        }

        return response()->json([
            'message' => 'Please check your email for the verification link.',
            'email' => $request->email,
            'token' => $token,
        ]);
    }

    /**
     * Complete the registration after the user clicks the verification link.
     * Creates the user in DB, marks email as verified, logs them in.
     */
    public function completeVerification(Request $request, string $token): RedirectResponse
    {
        $data = Cache::get("pending_registration_{$token}");

        if (!$data) {
            return redirect()->route('register')->with('error', 'Registration data expired. Please register again.');
        }

        // Check if email was already registered (e.g. race condition)
        if (User::where('email', $data['email'])->exists()) {
            Cache::forget("pending_registration_{$token}");
            Cache::forget("pending_email_{$data['email']}");
            return redirect()->route('login')->with('message', 'This email is already registered. Please log in.');
        }

        // Create the user in DB
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        // Mark email as verified immediately
        $user->markEmailAsVerified();

        // Clean up cache
        Cache::forget("pending_registration_{$token}");
        Cache::forget("pending_email_{$data['email']}");

        // Signal to the polling endpoint that verification is complete
        Cache::put("registration_verified_{$token}", true, now()->addMinutes(10));

        // Fire the Registered event (email won't be sent again since already verified)
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        return redirect()->route('pathfinder.home');
    }

    /**
     * Check if a pending registration has been verified.
     * Used by the registration modal polling (token-based, no auth needed).
     */
    public function checkVerification(Request $request): JsonResponse
    {
        $token = $request->query('token');

        if (!$token) {
            return response()->json(['verified' => false]);
        }

        $verified = Cache::get("registration_verified_{$token}", false);

        return response()->json(['verified' => $verified]);
    }

    /**
     * Resend the verification email for a pending registration.
     */
    public function resendVerification(Request $request): JsonResponse
    {
        $token = $request->input('token');

        if (!$token) {
            return response()->json(['message' => 'Invalid request.'], 400);
        }

        $data = Cache::get("pending_registration_{$token}");

        if (!$data) {
            return response()->json(['message' => 'Registration data expired. Please register again.'], 422);
        }

        // Generate a new signed verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.complete',
            now()->addMinutes(60),
            ['token' => $token]
        );

        try {
            Mail::mailer('resend')->to($data['email'])->send(new VerificationLinkMail($verificationUrl, $data['name']));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to resend verification email: ' . $e->getMessage());
            return response()->json(['message' => 'Unable to send verification email. Please try again later.'], 503);
        }

        return response()->json(['message' => 'A new verification link has been sent to your email.']);
    }
}
