<?php

namespace App\Http\Controllers\Expert;


use App\Http\Controllers\Controller;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;



class ControllerProfile extends Controller
{

    protected $database;
    protected $storage;

    public function __construct(Database $database, Storage $storage)
    {
        $this->database = $database;
        $this->storage = $storage;
    }


    public function show()
    {
        $user = Auth::user(); // Get the currently authenticated user's ID

        $userId = $user->firebase_id;

        $credentials = $this->database->getReference("credentials/$userId")->getValue();

        $courseId = $credentials['langExperties'];
        $courses  = $this->database->getReference("courses")->getValue();
        $quizResults  = $this->database->getReference("quiz_results/$userId")->getValue();

        $languageExperty = $this->database->getReference("courses/$courseId")->getValue();
        $languageExperty = $languageExperty['name'];

        $user = $this->database
        ->getReference("users/{$userId}")
        ->getValue();

        $firebaseUser = $this->database->getReference("users/{$userId}")->getValue();

        // Update session data with the latest name
        if ($firebaseUser && isset($firebaseUser['name'])) {
            session(['user.name' => $firebaseUser['name']]);
        }

        if ($firebaseUser) {
            // ✅ Always find the user by firebase_id
            $localUser = \App\Models\User::where('firebase_id', $userId)->first();
        
            if ($localUser) {
                $needsUpdate = false;
        
                // ✅ Update name if it changed
                if ($localUser->name !== $firebaseUser['name']) {
                    $localUser->name = $firebaseUser['name'];
                    $needsUpdate = true;
                }
        
                // ✅ Update email if it changed
                if (isset($firebaseUser['email']) && $localUser->email !== $firebaseUser['email']) {
                    // Check if another user has this email
                    $existingUser = \App\Models\User::where('email', $firebaseUser['email'])
                        ->where('firebase_id', '!=', $userId) // ✅ Ensure it's not the same user
                        ->first();
        
                    if (!$existingUser) {
                        // ✅ Safe to update the email
                        $localUser->email = $firebaseUser['email'];
                        $needsUpdate = true;
                    } else {
                        // ❌ Prevent duplicate emails
                        return back()->withErrors(['email' => 'This email is already taken by another user.']);
                    }
                }
        
                // ✅ Save only if something changed
                if ($needsUpdate) {
                    $localUser->save();
                }
            }
        }

        // dd($languageExperty);

        return view('userExpert.profile.show', compact('firebaseUser','user', 'userId', 'credentials', 'courses', 'quizResults', 'languageExperty')); // Pass filtered users to the view
    }


    public function edit(User $user)
    {

        return view('userExpert.profile.edit', compact('user'));
    }




    public function update(Request $request, User $user)
    {

        // dd($request->all());

        $request->validate([
            'name' => 'required',
            'usertype' => 'nullable',

        ]);



        $user->update([
            'name' => $request->name,
            'usertype' => $request->usertype,

        ]);

        return redirect()->route('expert.users.index')->with('success', 'User updated successfully.');
    }
}
