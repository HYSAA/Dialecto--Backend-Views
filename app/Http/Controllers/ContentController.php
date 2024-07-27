<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function index(Course $course, Lesson $lesson)
    {
        $contents = $lesson->contents;
        return view('contents.index', compact('course', 'lesson', 'contents'));
    }

    public function create($courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);
        return view('contents.create', compact('course', 'lesson'));
    }

    public function store(Request $request, $courseId, $lessonId)
    {
        $request->validate([
            'text' => 'nullable',
            'image' => 'nullable|image|max:2048'
        ]);

        $content = new Content();
        $content->text = $request->text;
        $content->lesson_id = $lessonId;

        if ($request->hasFile('image')) {
            $content->image = $request->file('image')->store('images', 'public');
        }

        $content->save();

        return redirect()->route('courses.lessons.show', [$courseId, $lessonId])
                         ->with('success', 'Content created successfully.');
    }

    public function show(Course $course, Lesson $lesson, Content $content)
    {
        return view('contents.show', compact('course', 'lesson', 'content'));
    }

    public function edit(Course $course, Lesson $lesson, Content $content)
    {
        return view('contents.edit', compact('course', 'lesson', 'content'));
    }

    public function update(Request $request, Course $course, Lesson $lesson, Content $content)
    {
        $request->validate([
            'text' => 'nullable',
            'image' => 'nullable|image|max:2048'
        ]);

        $content->text = $request->text;

        if ($request->hasFile('image')) {
            if ($content->image) {
                Storage::disk('public')->delete($content->image);
            }
            $content->image = $request->file('image')->store('images', 'public');
        }

        $content->save();

        return redirect()->route('courses.lessons.contents.index', [$course->id, $lesson->id])
                         ->with('success', 'Content updated successfully.');
    }

    public function destroy(Course $course, Lesson $lesson, Content $content)
    {
        if ($content->image) {
            Storage::disk('public')->delete($content->image);
        }

        $content->delete();

        return redirect()->route('courses.lessons.contents.index', [$course->id, $lesson->id])
                         ->with('success', 'Content deleted successfully.');
    }
}
