<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     */

public function store(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
        'token' => 'required',
    ]);

    $email = $request->email;
    $token = $request->token;

    // Firebase URL for all users
    $firebaseUrl = "https://dialecto-c14c1-default-rtdb.asia-southeast1.firebasedatabase.app/users.json";
    $response = Http::get($firebaseUrl);

    if ($response->failed()) {
        return back()->withErrors(['email' => 'Failed to connect to the system. Please try again.']);
    }

    $users = $response->json();
    $userKey = null;
    $userData = null;

    // Match the email and retrieve the user
    foreach ($users as $key => $user) {
        if (isset($user['email']) && $user['email'] === $email) {
            $userKey = $key;
            $userData = $user;
            break;
        }
    }

    // Validate token and email
    if (!$userKey || !isset($userData['reset_token']) || $userData['reset_token'] !== $token) {
        return back()->withErrors(['email' => 'Invalid token or email.']);
    }

    // Hash the new password
    $hashedPassword = Hash::make($request->password);

    // Update the user's password and clear the token
    $updateUrl = "https://dialecto-c14c1-default-rtdb.asia-southeast1.firebasedatabase.app/users/{$userKey}.json";
    $updateResponse = Http::patch($updateUrl, [
        'password' => $hashedPassword,
        'reset_token' => null, // Clear the token
    ]);

    if ($updateResponse->failed()) {
        return back()->withErrors(['email' => 'Failed to update the password. Please try again.']);
    }

    return redirect()->route('login')->with('status', 'Password successfully reset!');
}
  
    
}
