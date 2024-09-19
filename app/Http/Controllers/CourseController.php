<?php


namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }



    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $imagePath = null; // Initialize the imagePath variable

        // Check if the image file is present and store it
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Create the course in the local database
        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath, // Store the image path in the database
        ]);
        $courseId = $course->id;

        // Initialize Firebase
        $factory = (new Factory)->withServiceAccount('C:\laravel\Dialecto--Backend-Views\config\dialecto-c14c1-firebase-adminsdk-q80as-e6ee6b1b18.json')
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $factory->createDatabase();

        // Prepare the data for Firebase
        $courseData = [
            'id' => $courseId,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath, // Firebase will store the path as a string
        ];

        // Store the data in Firebase Realtime Database
        $database->getReference('courses')->push($courseData);

        // Redirect with a success message
        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }




    public function show(Course $course)
    {


        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }







    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        // Retain the existing image path unless a new image is uploaded
        $imagePath = $course->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        // Update the course with the new or existing image path
        $course->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }












    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
