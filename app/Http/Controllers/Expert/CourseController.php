<?php


namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        // dd($courses);
        return view('userExpert.courses.index', compact('courses'));
    }


    public function show(Course $course)

    {
        // dd($course->lessons);
        return view('userExpert.courses.show', compact('course'));
    }
}
