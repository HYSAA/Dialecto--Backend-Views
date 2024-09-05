<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    protected $database;
    protected $coursesRef;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->coursesRef = $this->database->getReference('courses');
    }

    public function index($courseId = null)
    {
        if ($courseId) {
            $course = $this->coursesRef->getChild($courseId)->getValue();
            $lessons = $course['lessons'] ?? [];
        } else {
            $lessons = [];
            $courses = $this->coursesRef->getValue();
            foreach ($courses as $courseId => $course) {
                if (isset($course['lessons'])) {
                    foreach ($course['lessons'] as $lessonId => $lesson) {
                        $lessons[] = [
                            'id' => $lessonId,
                            'title' => $lesson['title'],
                            'course_id' => $courseId,
                            'course_name' => $course['name']
                        ];
                    }
                }
            }
        }

        return view('lessons.index', compact('lessons', 'courseId'));
    }

    public function create($courseId)
    {
        $course = $this->coursesRef->getChild($courseId)->getValue();
        return view('lessons.create', compact('course', 'courseId'));
    }

    public function store(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $newLesson = $this->coursesRef->getChild($courseId)->getChild('lessons')->push([
            'title' => $request->title,
            'image' => $imagePath,
        ]);

        return redirect()->route('courses.show', $courseId)->with('success', 'Lesson created successfully.');
    }

    public function show($courseId, $lessonId)
    {
        $course = $this->coursesRef->getChild($courseId)->getValue();
        $lesson = $course['lessons'][$lessonId] ?? null;
        $contents = $lesson['contents'] ?? [];

        return view('lessons.show', compact('course', 'lesson', 'contents', 'courseId', 'lessonId'));
    }

    public function edit($courseId, $lessonId)
    {
        $course = $this->coursesRef->getChild($courseId)->getValue();
        $lesson = $course['lessons'][$lessonId] ?? null;

        return view('lessons.edit', compact('course', 'lesson', 'courseId', 'lessonId'));
    }

    public function update(Request $request, $courseId, $lessonId)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $this->coursesRef->getChild($courseId)->getChild('lessons')->getChild($lessonId)->update([
            'title' => $request->title,
        ]);

        return redirect()->route('courses.show', $courseId)->with('success', 'Lesson updated successfully.');
    }

    public function destroy($courseId, $lessonId)
    {
        $this->coursesRef->getChild($courseId)->getChild('lessons')->getChild($lessonId)->remove();

        return redirect()->route('courses.show', $courseId)->with('success', 'Lesson deleted successfully.');
    }
}