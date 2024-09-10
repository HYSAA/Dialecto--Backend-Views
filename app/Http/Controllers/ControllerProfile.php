<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Credential;
use App\Models\User;

use App\Models\Course;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;






class ControllerProfile extends Controller
{
    public function show($id)

    {

        // dd($id);

        // Get the authenticated user's ID
        $currentUserId = Auth::user();

        // Retrieve all users except the current one
        $users = User::where('id', '!=', $currentUserId)->get();

        // Pass filtered users and the current user's ID to the view
        return view('userUser.profile.show', compact('users', 'currentUserId'));
    }

    public function edit(User $user)
    {

        return view('userUser.profile.edit', compact('user'));
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

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }



    public function applyExpert($currentUserId)
    {

        $courses = Course::all();

        return view('userUser.profile.applyVerifier', compact('currentUserId', 'courses'));
    }








    public function postCredentials(Request $request)
    {
        // Validate the request
        $request->validate([
            'langExperties' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Handle the image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Create a new credential or update the existing one
        Credential::updateOrCreate(
            ['user_id' => $user->id], // Use this to find the existing record
            [
                'language_experty' => $request->input('langExperties'),
                'credentials' => $imagePath,
            ]
        );

        // Update the user's boolean field to true
        $user->update(['credentials' => true]);

        // Redirect with success message
        return redirect()->route('user.profile.show', ['id' => $user->id])
            ->with('success', 'Credentials uploaded successfully.');
    }




    public function submittedCredentials($name)

    {

        return view('userUser.profile.submitCreds', compact('name'));
    }
}
