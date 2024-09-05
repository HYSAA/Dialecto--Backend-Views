<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Content;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage as FirebaseStorage;
use Kreait\Firebase\Contract\Database;

class ContentController extends Controller
{
    protected $firebaseStorage;
    protected $firebaseDatabase;

    public function __construct()
    {
        $firebaseCredentialsPath = config('firebase.credentials') ?: base_path('config/firebase_credentials.json');

        if (!file_exists($firebaseCredentialsPath) || !is_readable($firebaseCredentialsPath)) {
            throw new \Exception("Firebase credentials file is not found or readable at: {$firebaseCredentialsPath}");
        }

        $firebase = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath);

        $this->firebaseStorage = $firebase->createStorage();
        $this->firebaseDatabase = $firebase->createDatabase();
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
            'english' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,avi,mov|max:10000', // Add validation for video files
        ]);

        $content = new Content();
        $content->english = $request->english;
        $content->text = $request->text;
        $content->lesson_id = $lessonId;

        $bucket = $this->firebaseStorage->getBucket();

        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $firebasePath = 'images/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            $imageUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
            $content->image = $imageUrl;
        }

        if ($request->hasFile('video')) {
            $uploadedFile = $request->file('video');
            $firebasePath = 'videos/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            $videoUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
            $content->video = $videoUrl;
        }

        $content->save();

        // Save content data to Firebase Realtime Database
        $this->firebaseDatabase
            ->getReference("courses/{$courseId}/lessons/{$lessonId}/contents/{$content->id}")
            ->set([
                'english' => $content->english,
                'text' => $content->text,
                'image' => $content->image,
                'video' => $content->video,
            ]);

        return redirect()->route('courses.lessons.show', [$courseId, $lessonId])
            ->with('success', 'Content created successfully.');
    }

    public function update(Request $request, Course $course, Lesson $lesson, Content $content)
    {
        $request->validate([
            'text' => 'nullable',
            'english' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,avi,mov|max:10000', // Add validation for video files
        ]);

        $content->text = $request->text;
        $content->english = $request->english;

        $bucket = $this->firebaseStorage->getBucket();

        if ($request->hasFile('image')) {
            if ($content->image) {
                $previousImagePath = parse_url($content->image, PHP_URL_PATH);
                $object = $bucket->object($previousImagePath);
                if ($object->exists()) {
                    $object->delete();
                }
            }

            $uploadedFile = $request->file('image');
            $firebasePath = 'images/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            $imageUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
            $content->image = $imageUrl;
        }

        if ($request->hasFile('video')) {
            if ($content->video) {
                $previousVideoPath = parse_url($content->video, PHP_URL_PATH);
                $object = $bucket->object($previousVideoPath);
                if ($object->exists()) {
                    $object->delete();
                }
            }

            $uploadedFile = $request->file('video');
            $firebasePath = 'videos/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            $videoUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
            $content->video = $videoUrl;
        }

        $content->save();

        // Update content data in Firebase Realtime Database
        $this->firebaseDatabase
            ->getReference("courses/{$course->id}/lessons/{$lesson->id}/contents/{$content->id}")
            ->update([
                'english' => $content->english,
                'text' => $content->text,
                'image' => $content->image,
                'video' => $content->video,
            ]);

        return redirect()->route('courses.lessons.contents.index', [$course->id, $lesson->id])
            ->with('success', 'Content updated successfully.');
    }

    public function destroy(Course $course, Lesson $lesson, Content $content)
    {
        $bucket = $this->firebaseStorage->getBucket();

        if ($content->image) {
            $imagePath = parse_url($content->image, PHP_URL_PATH);
            $object = $bucket->object($imagePath);
            if ($object->exists()) {
                $object->delete();
            }
        }

        if ($content->video) {
            $videoPath = parse_url($content->video, PHP_URL_PATH);
            $object = $bucket->object($videoPath);
            if ($object->exists()) {
                $object->delete();
            }
        }

        $content->delete();

        // Delete content data from Firebase Realtime Database
        $this->firebaseDatabase
            ->getReference("courses/{$course->id}/lessons/{$content->lesson_id}/contents/{$content->id}")
            ->remove();

        return redirect()->route('courses.lessons.contents.index', [$course->id, $lesson->id])
            ->with('success', 'Content deleted successfully.');
    }

    public function show($courseId, $lessonId, $contentId)
    {
        $course = Course::find($courseId);
        $lesson = Lesson::find($lessonId);
        $content = Content::find($contentId);

        $nextContent = Content::where('lesson_id', $lessonId)->where('id', '>', $contentId)->orderBy('id')->first();
        $previousContent = Content::where('lesson_id', $lessonId)->where('id', '<', $contentId)->orderBy('id', 'desc')->first();

        return view('contents.show', compact('course', 'lesson', 'content', 'nextContent', 'previousContent'));
    }

    public function edit($courseId, $lessonId, $contentId)
    {
        $content = Content::findOrFail($contentId);
        $lesson = Lesson::findOrFail($lessonId);
        $course = Course::findOrFail($courseId);

        return view('contents.edit', compact('content', 'lesson', 'course'));
    }

    public function index(Course $course, Lesson $lesson)
    {
        $contents = $lesson->contents;
        return view('contents.index', compact('course', 'lesson', 'contents'));
    }
}
