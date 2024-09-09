<?php


namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;

use Illuminate\Http\Request;

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

    public function show(Course $course, Lesson $lesson)
    {
        $contents = $lesson->contents; // Fetch contents associated with the lesson
        return view('lessons.show', compact('course', 'lesson', 'contents'));
    }
}
