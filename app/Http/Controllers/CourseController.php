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
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        Course::create($request->all());

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
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
        ]);

        $course->update($request->all());

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }
    

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}