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
    public function store(Request $request): RedirectResponse
{
    // Validate the request data
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // Hash the password
    $hashedPassword = Hash::make($request->password);

    // Create the user in the local Laravel database
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $hashedPassword,
    ]);

    // Fire the Registered event
    event(new Registered($user));

    // Log in the user
    Auth::login($user);

    // Set up Firebase connection
    $factory = (new Factory)->withServiceAccount('C:\laravel\Dialecto--Backend-Views\config\dialecto-c14c1-firebase-adminsdk-q80as-e6ee6b1b18.json')
        ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

    $database = $factory->createDatabase();

    // Prepare user data for Firebase including hashed password
    $userData = [
        'id' => $user->id,
        'name' => $request->name,
        'email' => $request->email,
        'password' => $hashedPassword, // Storing hashed password in Firebase
    ];

    // Store user data in Firebase Realtime Database
    $database->getReference('users')->push($userData);

    // Redirect to the dashboard after successful registration
    return redirect(route('dashboard'))->with('success', 'User registered successfully.');
}
}
