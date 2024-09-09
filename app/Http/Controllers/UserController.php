<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

use App\Models\Lesson;
use App\Models\suggestedWord;

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
        $courses = Course::all();

        $lessons = Lesson::withCount('contents')->get();

        $userProgress = UserProgress::where('user_id', $userId)
            ->select('lesson_id', DB::raw('count(*) as count'))
            ->groupBy('lesson_id')
            ->get();

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


    public function wordSuggested()
    {
        return view('suggestions.userwords');
    }

    public function selectUserCourseLesson(Course $course, Lesson $lesson)
    {
        $courses = Course::with('lessons')->get();
        $lessons = Lesson::with('course')->get();


        return view('suggestions.selectUserCourseLesson', compact('courses', 'lessons'));
    }

    public function addUserSuggestedWord($courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);

        return view('suggestions.addUserSuggestedWord', compact('course', 'lesson'));
    }

    public function submitWordSuggested(Request $request, $courseId, $lessonId)
    {
        // Validate the form data
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'lesson_id' => 'required|exists:lessons,id',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480', // assuming you allow video upload
            'text' => 'required|string',
            'english' => 'required|string',
        ]);

        // Handle the video file upload if it exists
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('videos', 'public');  // store video in 'videos' directory
        }

        // Store the data into the database
        SuggestedWord::firstOrCreate([
            'user_id' => auth()->id(),
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'video' => $videoPath,
            'text' => $request->text,
            'english' => $request->english,
        ]);

        // Redirect or send a response after successful submission
        return redirect()->back()->with('success', 'Word suggestion submitted successfully!');
    }

}

