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
        foreach ($firebaseUsers as $id => $user) {
            if (is_array($user) && isset($user['email']) && $user['email'] === $request->email) {
                $firebaseUser = $user;
                $firebaseUserId = $id; // Capture the Firebase user ID
                break;
            }
        }

        if (!$firebaseUser) {
            return back()->withErrors(['email' => 'Your email or password is incorrect. Please try again!']);
        }

        // Convert the bcrypt hash from $2a$ to $2y$
        $firebasePasswordHash = str_replace('$2a$', '$2y$', $firebaseUser['password']);

        // Verify password using Laravel's Hash::check()
        if (!Hash::check($request->password, $firebasePasswordHash)) {
            return back()->withErrors(['password' => 'Your email or password is incorrect. Please try again!']);
        }

        // Retrieve usertype from Firebase
        $usertype = $firebaseUser['usertype'] ?? 'user'; // Default to 'user' if no usertype is set

        // Para survey
        $surveyTaken = $firebaseUser['survey_taken'] ?? 'test ni siya';

        // Check if the survey is taken, if not, redirect to the survey










        // if ($surveyTaken == 0) {
        //     // Ensure 'survey_taken' is set in Firebase if not set
        //     if (!isset($firebaseUser['survey_taken'])) {
        //         $database->getReference('users/' . $firebaseUserId . '/survey_taken')->set(0);
        //     }

        //     // Log in the user locally but redirect to survey immediately
        //     // Create or update local user in the database
        //     $localUser = \App\Models\User::firstOrCreate(
        //         ['email' => $firebaseUser['email']],
        //         [
        //             'name' => $firebaseUser['name'],
        //             'password' => $firebasePasswordHash, // Using the converted hashed password from Firebase
        //             'usertype' => $usertype, // Storing the usertype from Firebase
        //             'firebase_id' => $firebaseUserId, // Save Firebase ID
        //             'survey_taken' => 0, // Set the initial value of survey_taken
        //         ]
        //     );

        //     // Authenticate the user
        //     Auth::login($localUser);
        //     session(['firebase_id' => $localUser->firebase_id]);

        //     // Regenerate the session to prevent fixation attacks
        //     $request->session()->regenerate();

        //     // Redirect to survey
        //     return redirect()->route('survey.show');
        // }



















        // If the survey is already taken, proceed with login logic
        // Check if the user exists locally in the Laravel database
        $localUser = \App\Models\User::where('email', $firebaseUser['email'])->first();

        // If the user does not exist in the local database, create them
        if (!$localUser) {
            $localUser = \App\Models\User::create([
                'name' => $firebaseUser['name'],
                'email' => $firebaseUser['email'],
                'password' => $firebasePasswordHash, // Using the converted hashed password from Firebase
                'usertype' => $usertype, // Storing the usertype from Firebase
                'firebase_id' => $firebaseUserId, // Save Firebase ID
                'survey_taken' => $firebaseUser['survey_taken'],

            ]);
        } else {
            // If the user exists, check if the usertype has changed or if the firebase_id is missing
            if ($localUser->usertype !== $usertype || !$localUser->firebase_id) {
                $localUser->usertype = $usertype;
                $localUser->firebase_id = $firebaseUserId; // Update firebase_id if it was not set
                $localUser->save();
            }
        }

        // Authenticate the user locally
        Auth::login($localUser);
        session(['firebase_id' => $localUser->firebase_id]);

        // Regenerate the session to prevent fixation attacks
        $request->session()->regenerate();

        // Redirect based on user type or to the default dashboard
        if ($localUser->usertype === 'admin') {
            return redirect('admin/dashboard');
        } elseif ($localUser->usertype === 'expert') {
            return redirect('expert/dashboard');
        }

        // Default redirect to user dashboard
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
