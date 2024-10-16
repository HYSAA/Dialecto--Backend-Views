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

     public function store(LoginRequest $request): RedirectResponse
     {
         // Set up Firebase connection
         $factory = (new Factory)
             ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
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
     
         // **Convert the bcrypt hash from $2a$ to $2y$**
         $firebasePasswordHash = str_replace('$2a$', '$2y$', $firebaseUser['password']);
     
         // Verify password using Laravel's Hash::check()
         if (!Hash::check($request->password, $firebasePasswordHash)) {
             return back()->withErrors(['password' => 'The provided password is incorrect.']);
         }
     
         // Retrieve usertype from Firebase
         $usertype = $firebaseUser['usertype'] ?? 'user'; // Default to 'user' if no usertype is set
     
         // Check if the user exists locally in the Laravel database
         $localUser = \App\Models\User::where('email', $firebaseUser['email'])->first();
     
         // If the user does not exist in the local database, create them
         if (!$localUser) {
             $localUser = \App\Models\User::create([
                 'name' => $firebaseUser['name'],
                 'email' => $firebaseUser['email'],
                 'password' => $firebasePasswordHash, // Using the converted hashed password from Firebase
                 'usertype' => $usertype, // Storing the usertype from Firebase
             ]);
         } else {
             // If the user exists, check if the usertype has changed
             if ($localUser->usertype !== $usertype) {
                 // Update the local usertype to match Firebase
                 $localUser->usertype = $usertype;
                 $localUser->save();
             }
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
