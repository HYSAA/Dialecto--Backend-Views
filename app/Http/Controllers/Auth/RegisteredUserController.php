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
    // public function store(Request $request): RedirectResponse
    // {
    //     $usertype = $request->usertype ?? 'user';
    //     // Validate the request data
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //         'usertype' => $usertype,
    //     ]);

    //     // Hash the password
    //     $hashedPassword = Hash::make($request->password);

    //     // Create the user in the local Laravel database
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => $hashedPassword,
    //     ]);

    //     // Fire the Registered event
    //     event(new Registered($user));

    //     // Log in the user
    //     Auth::login($user);

    //     // Set up Firebase connection
    //     $factory = (new Factory)
    //         ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
    //         ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

    //     $database = $factory->createDatabase();
    //     // Prepare user data for Firebase including hashed password
    //     $userData = [
    //         'id' => $user->id,
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => $hashedPassword, // Storing hashed password in Firebase
    //         'usertype' => $user->usertype,
    //     ];

    //     // Store user data in Firebase Realtime Database
    //     $database->getReference('users')->push($userData);

    //     // Redirect to the dashboard after successful registration
    //     return redirect(route('dashboard'))->with('success', 'User registered successfully.');
    // }

    
//ADDED USER TYPE with local db
    // public function store(Request $request): RedirectResponse
    // {
    //     $usertype = $request->usertype ?? 'user'; // Default usertype if not provided in the request

    //     // Validate the request data
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     // Hash the password
    //     $hashedPassword = Hash::make($request->password);

    //     // Create the user in the local Laravel database with usertype
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => $hashedPassword,
    //         'usertype' => $usertype, // Save the usertype in the local database
    //     ]);

    //     // Fire the Registered event
    //     event(new Registered($user));

    //     // Log in the user
    //     Auth::login($user);

    //     // Set up Firebase connection
    //     $factory = (new Factory)
    //         ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
    //         ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

    //     $database = $factory->createDatabase();

    //     // Prepare user data for Firebase including the usertype
    //     $userData = [
    //         'id' => $user->id,
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => $hashedPassword, // Storing hashed password in Firebase
    //         'usertype' => $usertype, // Make sure the usertype is explicitly set here
    //     ];

    //     // Check the content of the userData array before pushing to Firebase
    //     // This will help ensure the usertype is present
    //     Log::info('User Data for Firebase:', $userData);

    //     // Store user data in Firebase Realtime Database
    //     $database->getReference('users')->push($userData);

    //     // Redirect to the dashboard after successful registration
    //     return redirect(route('login'))->with('success', 'User registered successfully.');
    // }
//without the local db
public function store(Request $request): RedirectResponse
{
    $usertype = $request->usertype ?? 'user'; // Default usertype if not provided in the request

    // Validate the request data
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'], // Validate uniqueness in Firebase if needed
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
