<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Factory;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     // Authenticate the user
    //     $request->authenticate();
    
    //     // Regenerate the session to prevent fixation attacks
    //     $request->session()->regenerate();
    
    //     // Redirect based on user type
    //     if ($request->user()->usertype === 'admin') {
    //         return redirect('admin/dashboard');
    //     } elseif ($request->user()->usertype === 'expert') {
    //         return redirect('expert/dashboard');
    //     }
    
    //     // Default redirect for other users
    //     return redirect()->intended(route('dashboard'));
    // }
    public function store(LoginRequest $request): RedirectResponse
    {
        // Set up Firebase connection
        $factory = (new Factory)->withServiceAccount('C:\laravel\Dialecto--Backend-Views\config\dialecto-c14c1-firebase-adminsdk-q80as-e6ee6b1b18.json')
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
    
        $database = $factory->createDatabase();
    
        // Retrieve the users from Firebase
        $firebaseUsers = $database->getReference('users')->getValue();
    
        // Find user by email
        $firebaseUser = null;
        foreach ($firebaseUsers as $user) {
            if ($user['email'] === $request->email) {
                $firebaseUser = $user;
                break;
            }
        }
    
        if (!$firebaseUser) {
            return back()->withErrors(['email' => 'These credentials do not match our records.']);
        }
    
        // Verify password
        if (!Hash::check($request->password, $firebaseUser['password'])) {
            return back()->withErrors(['password' => 'The provided password is incorrect.']);
        }
    
        // Check if the user exists locally in the Laravel database
        $localUser = \App\Models\User::where('email', $firebaseUser['email'])->first();
    
        // If the user does not exist in the local database, create them
        if (!$localUser) {
            $localUser = \App\Models\User::create([
                'name' => $firebaseUser['name'],
                'email' => $firebaseUser['email'],
                'password' => $firebaseUser['password'], // Using the same hashed password from Firebase
            ]);
        }
    
        // Authenticate the user locally
        Auth::login($localUser);
    
        // Regenerate the session to prevent fixation attacks
        $request->session()->regenerate();
    
        // Redirect based on user type or to the default dashboard
        if ($localUser->usertype === 'admin') {
            return redirect('admin/dashboard');
        } elseif ($localUser->usertype === 'expert') {
            return redirect('expert/dashboard');
        }
    
        return redirect()->intended(route('dashboard'));
    }
    
    

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
