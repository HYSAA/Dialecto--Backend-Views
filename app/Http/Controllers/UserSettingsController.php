<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Database;

class UserSettingsController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function edit()
{
    $userId = Auth::user()->firebase_id; // Current user ID
    $user = $this->database->getReference("users/{$userId}")->getValue();

    // Update session with Firebase data
    if ($user) {
        session()->put('user', $user);
    }

    return view('userUser.settings.edit', compact('user'));
}

    public function update(Request $request)
    {
        $userId = Auth::user()->firebase_id;
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
    
        // Update Firebase user data
        $this->database->getReference("users/{$userId}")->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
    
        // Update session
        $updatedUser = $this->database->getReference("users/{$userId}")->getValue();
    
        if ($updatedUser) {
            session()->put('user', $updatedUser);
        }
        return redirect()->route('user.profile.show')->with('success', 'Profile updated successfully.');
    }
    
}
