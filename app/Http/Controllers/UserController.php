<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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





    // public function show($userId)
    // {
    //     $courses = Course::all();
    //     $lessons = Lesson::withCount('contents')->get();

    //     $userProgress = UserProgress::where('user_id', $userId)
    //         ->select('lesson_id')
    //         ->distinct()
    //         ->get();
    //     foreach($userProgress as $progress) {
    //         $lessonid = $progress->lesson_id;
    //     }

    //     $contents = Content::all();

    //     $user = User::findOrFail($userId);

    //     foreach ($courses as $course) {
    //         foreach ($course->lessons as $lesson) {
    //             $lesson->contents_count = $lesson->contents->count();
    //         }
    //     }

    //     return view('users.show', compact('courses', 'lessons', 'user', 'userProgress'));
    // }
    
    public function show($userId)
{
    // Retrieve all courses
    $courses = Course::all();

    // Retrieve lessons with the count of contents
    $lessons = Lesson::withCount('contents')->get();

    // Query to get distinct lesson IDs and their counts for the given user
    $userProgress = UserProgress::where('user_id', $userId)
        ->select('lesson_id', DB::raw('count(*) as count'))
        ->groupBy('lesson_id')
        ->get();

    // Find the user by ID
    $user = User::findOrFail($userId);

    // Calculate content counts for each lesson under each course
    foreach ($courses as $course) {
        foreach ($course->lessons as $lesson) {
            $lesson->contents_count = $lesson->contents->count();
        }
    }

    // Pass all the necessary data to the view
    return view('users.show', compact('courses', 'lessons', 'user', 'userProgress'));
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
