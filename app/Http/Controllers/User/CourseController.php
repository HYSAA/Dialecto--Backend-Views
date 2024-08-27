<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        // dd($courses);
        return view('userUser.courses.index', compact('courses'));
    }


    public function show(Course $course)

    {
        // dd($course->lessons);
        return view('userUser.courses.show', compact('course'));
    }
}
