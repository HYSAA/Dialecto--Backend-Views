<?php

// namespace App\Http\Controllers;

// use App\Models\Course;
// use Illuminate\Http\Request;

// class CourseController extends Controller
// {
//     public function index()
//     {
//         $courses = Course::all();
//         return view('courses.index', compact('courses'));
//     }
//     public function userIndex()
// {
//     $courses = Course::all(); // Adjust as needed to filter or format courses for users
//     return view('courses.index', compact('courses'));
// }

//     public function create()
//     {
//         return view('courses.create');
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'name' => 'required',
//             'description' => 'nullable',
//         ]);

//         Course::create($request->all());

//         return redirect()->route('courses.index')->with('success', 'Course created successfully.');
//     }

//     public function show(Course $course)
//     {
//         return view('courses.show', compact('course'));
//     }

//     public function edit(Course $course)
//     {
//         return view('courses.edit', compact('course'));
//     }

//     public function update(Request $request, Course $course)
//     {
//         $request->validate([
//             'name' => 'required',
//             'description' => 'nullable',
//         ]);

//         $course->update($request->all());

//         return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
//     }


//     public function destroy(Course $course)
//     {
//         $course->delete();

//         return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
//     }
// }


namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

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

        // Create the course with the image path
        Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath, // Store the image path in the database
        ]);

        // Redirect with a success message
        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }




    public function show(Course $course)
    {

        dd($course);
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
