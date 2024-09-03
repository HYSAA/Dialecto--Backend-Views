<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;






class ControllerProfile extends Controller
{
    public function show()
    {
        $currentUserId = Auth::user(); // Get the currently authenticated user's ID
        $users = User::where('id', '!=', $currentUserId)->get(); // Retrieve all users except the current one
        return view('userUser.profile.show', compact('users','currentUserId')); // Pass filtered users to the view
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



}