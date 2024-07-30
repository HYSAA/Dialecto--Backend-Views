<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class ContentController extends BaseController
{
    public function index(Course $course, Lesson $lesson)
    {
        $contents = $lesson->contents;

        return response()->json($contents);
    }

    public function store(Request $request, $courseId, $lessonId)
    {
        $request->validate([
            'text' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,avi,mov|max:10240'
        ]);

        $lesson = Lesson::findOrFail($lessonId);

        $content = new Content();
        $content->text = $request->text;
        $content->lesson_id = $lessonId;

        if ($request->hasFile('image')) {
            $content->image = $request->file('image')->store('images', 'public');
        }

        $content->save();

        return response()->json([
            'message' => 'Content created successfully.',
            'content' => $content
        ], 201);
    }

    public function show(Course $course, Lesson $lesson, Content $content)
    {
        return response()->json($content);
    }

    public function update(Request $request, Course $course, Lesson $lesson, Content $content)
    {
        $request->validate([
            'text' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,avi,mov|max:10240'
        ]);

        $content->text = $request->text;

        if ($request->hasFile('image')) {
            if ($content->image) {
                Storage::disk('public')->delete($content->image);
            }
            $content->image = $request->file('image')->store('images', 'public');
        }

        $content->save();

        return response()->json([
            'message' => 'Content updated successfully.',
            'content' => $content
        ]);
    }

    public function destroy(Course $course, Lesson $lesson, Content $content)
    {
        if ($content->image) {
            Storage::disk('public')->delete($content->image);
        }

        $content->delete();

        return response()->json([
            'message' => 'Content deleted successfully.'
        ]);
    }
}
