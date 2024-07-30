<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController; // Correct import
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends BaseController
{
    public function index()
    {
        $courses = Course::all();
        return response()->json($courses);
    }

    // public function create()
    // {
    //     return view('courses.create');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $course = Course::create($request->all());
        return response()->json(['message' => 'Course created successfully.', 'data' => $course], 201);
    }

    public function show(Course $course)
    {
        return response()->json($course);
    }

    // public function edit(Course $course)
    // {
    //     return view('courses.edit', compact('course'));
    // }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

       
        $course->update($request->all());
        return response()->json(['message' => 'Course updated successfully.', 'data' => $course]);
    }
    

    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(['message' => 'Course deleted successfully.']);
    }
}
