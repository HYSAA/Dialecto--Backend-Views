<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class LessonController extends Controller
{
    // public function index(Course $course)
    // {
    //     $lessons = $course->lessons;
    //     return view('lessons.index', compact('course', 'lessons'));
    // }
    public function index($courseId = null)
    {
        if ($courseId) {
            // Fetch lessons for the specific course   //BAG O NI 
            $course = Course::findOrFail($courseId);
            $lessons = $course->lessons;
        } else {
            // Fetch all lessons, possibly with course information  /BAG O NI
            $lessons = Lesson::with('course')->get();
        }

        return view('lessons.index', compact('lessons'));
    }


    public function create(Course $course)
    {
        return view('lessons.create', compact('course'));
    }





    public function store(Request $request, Course $course)
    {
        // Validate the incoming request
        $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);
    
        $imagePath = null; // Initialize the imagePath variable
    
        // Check if the image file is present and store it
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }
    
        // Create the lesson in the local database
        $lesson = Lesson::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'image' => $imagePath,
        ]);
    
        // Initialize Firebase
        $factory = (new Factory)->withServiceAccount('C:\laravel\Dialecto--Backend-Views\config\dialecto-c14c1-firebase-adminsdk-q80as-e6ee6b1b18.json')
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
    
        $database = $factory->createDatabase();
    
        // Prepare the data for Firebase
        $lessonData = [
            'course_id' => $course->id,
            'title' => $request->title,
            'image' => $imagePath, // Firebase will store the path as a string
        ];
    
        // Store the data in Firebase Realtime Database
        $database->getReference('lessons')->push($lessonData);
    
        // Redirect with a success message
        return redirect()->route('admin.courses.show', $course->id)->with('success', 'Lesson created successfully.');
    }

    public function show(Course $course, Lesson $lesson)
    {
        $contents = $lesson->contents; // Fetch contents associated with the lesson
        return view('lessons.show', compact('course', 'lesson', 'contents'));
    }





    public function edit(Course $course, Lesson $lesson)
    {
        return view('lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);
        $imagePath = $lesson->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Update the course with the new or existing image path
        $lesson->update([
            'title' => $request->title,

            'image' => $imagePath,
        ]);

        return redirect()->route('admin.courses.show', $course->id)->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->route('admin.courses.show', $course->id)->with('success', 'Lesson deleted successfully.');
    }
}
