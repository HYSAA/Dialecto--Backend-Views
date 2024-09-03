<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUserId = Auth::id(); // Get the currently authenticated user's ID
        $users = User::where('id', '!=', $currentUserId)->get(); // Retrieve all users except the current one
        return view('users.index', compact('users')); // Pass filtered users to the view
    }








    public function show(string $id)
    {
        $courses = Course::all();
        
        return view('users.show',compact('courses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {

        return view('users.edit', compact('user'));
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






















    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {

    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Course deleted successfully.');
    }
}
