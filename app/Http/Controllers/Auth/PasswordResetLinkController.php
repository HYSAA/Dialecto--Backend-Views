<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $email = $request->email;

        // Firebase URL for all users
        $firebaseUrl = 'https://dialecto-c14c1-default-rtdb.asia-southeast1.firebasedatabase.app/users.json';
        $response = Http::get($firebaseUrl);

        if ($response->failed()) {
            return back()->withErrors(['email' => 'Failed to connect to the system. Please try again.']);
        }

        $users = $response->json();

        // Search for the user with the matching email
        $userKey = null;
        foreach ($users as $key => $user) {
            if (isset($user['email']) && $user['email'] === $email) {
                $userKey = $key;
                break;
            }
        }

        if (!$userKey) {
            return back()->withErrors(['email' => 'Email not found in the system.']);
        }

        // Generate a reset token
        $resetToken = Str::random(60);

        // Save the token in the user's record
        $updateUrl = "https://dialecto-c14c1-default-rtdb.asia-southeast1.firebasedatabase.app/users/{$userKey}.json";
        $updateResponse = Http::patch($updateUrl, [
            'reset_token' => $resetToken,
        ]);

        if ($updateResponse->failed()) {
            return back()->withErrors(['email' => 'Failed to generate reset token. Please try again.']);
        }

        // Generate the reset link
        $resetLink = route('password.reset', ['email' => $email, 'token' => $resetToken]);

        // Send the reset link to the user's email
        Mail::to($email)->send(new PasswordResetMail($resetLink));

        return back()->with('status', 'Password reset link has been sent to your email.');
    }
}
