<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Database;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index($courseId)
    {
        // Fetch lessons for the specified course
        $lessons = $this->database->getReference('courses/' . $courseId . '/lessons')->getValue() ?? [];

        return view('userExpert.lessons.index', compact('lessons', 'courseId'));
    }

    public function show($courseId, $lessonId)
    {
        // Fetch the specific lesson
        $lesson = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId)->getValue();

        return view('userExpert.lessons.show', compact('lesson', 'courseId'));
    }
}
