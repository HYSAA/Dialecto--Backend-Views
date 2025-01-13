<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Kreait\Firebase\Factory;

use Illuminate\Support\Facades\Log;


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
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    //without the local db
    public function store(Request $request): RedirectResponse
    {
        $usertype = $request->usertype ?? 'user'; // Default usertype if not provided in the request

        // Validate the request data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[^\s@]+@gmail\.com$/'
            ], // Validate uniqueness in Firebase if needed
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Hash the password
        $hashedPassword = Hash::make($request->password);

        // Set up Firebase connection
        $factory = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $factory->createDatabase();

        // Prepare user data for Firebase including the usertype
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $hashedPassword, // Storing hashed password in Firebase
            'usertype' => $usertype, // Make sure the usertype is explicitly set here
            'survey_taken' => 0,
        ];

        // Log the user data for debugging
        Log::info('User Data for Firebase:', $userData);

        // Store user data in Firebase Realtime Database
        $userReference = $database->getReference('users')->push($userData);

        // Log in the user after registration
        $userId = $userReference->getKey(); // Get the Firebase generated ID
        $firebaseUser = [
            'id' => $userId,
            'name' => $request->name,
            'email' => $request->email,
        ];

        Auth::loginUsingId($firebaseUser['id']); // Log in the user with Firebase user ID

        // Redirect to the dashboard after successful registration
        return redirect(route('login'))->with('success', 'User registered successfully.');
    }
}
