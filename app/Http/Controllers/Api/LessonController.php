<?php

namespace App\Http\Controllers\Api;

use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class LessonController extends BaseController
{
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        return response()->json($course->lessons);
    }

    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $lesson = new Lesson($request->all());
        $course->lessons()->save($lesson);

        return response()->json(['message' => 'Lesson created successfully', 'lesson' => $lesson], 201);
    }

    public function show($courseId, $id)
    {
        $lesson = Lesson::where('course_id', $courseId)->findOrFail($id);
        return response()->json($lesson);
    }

    public function update(Request $request, $courseId, $id)
    {
        $lesson = Lesson::where('course_id', $courseId)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $lesson->update($request->all());

        return response()->json(['message' => 'Lesson updated successfully', 'lesson' => $lesson]);
    }

    public function destroy($courseId, $id)
    {
        $lesson = Lesson::where('course_id', $courseId)->findOrFail($id);
        $lesson->delete();

        return response()->json(['message' => 'Lesson deleted successfully']);
    }
}
