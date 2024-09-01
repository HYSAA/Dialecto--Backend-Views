<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Content;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage as FirebaseStorage;

class ContentController extends Controller
{
    protected $firebaseStorage;


    ///ang construct is to validate if working ba ang imo configuration sa firebase
    //mura siyag nahug na gate na tig check if complete ba imo config before accesing firebase
    public function __construct()
    {
        $firebaseCredentialsPath = config('firebase.credentials') ?: base_path('config/firebase_credentials.json');

        if (!file_exists($firebaseCredentialsPath) || !is_readable($firebaseCredentialsPath)) {
            throw new \Exception("Firebase credentials file is not found or readable at: {$firebaseCredentialsPath}");
        }

        $this->firebaseStorage = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->createStorage();
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
            // Upload image to Firebase Storage
            $uploadedFile = $request->file('image');
            $firebasePath = 'images/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            // Get the public URL of the uploaded image
            $imageUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));

            // Save the image URL to the database
            $content->image = $imageUrl;
        }

        if ($request->hasFile('video')) {
            // Upload video to Firebase Storage
            $uploadedFile = $request->file('video');
            $firebasePath = 'videos/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            // Get the public URL of the uploaded video
            $videoUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));

            // Save the video URL to the database
            $content->video = $videoUrl;
        }

        $content->save();

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

        $bucket = $this->firebaseStorage->getBucket();

        if ($request->hasFile('image')) {
            if ($content->image) {
                // Delete the previous image from Firebase Storage if needed
                $previousImagePath = parse_url($content->image, PHP_URL_PATH);
                $object = $bucket->object($previousImagePath);
                if ($object->exists()) {
                    $object->delete();
                }
            }

            // Upload new image to Firebase Storage
            $uploadedFile = $request->file('image');
            $firebasePath = 'images/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            // Get the public URL of the uploaded image
            $imageUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));

            // Update the image URL in the database
            $content->image = $imageUrl;
        }

        if ($request->hasFile('video')) {
            if ($content->video) {
                // Delete the previous video from Firebase Storage if needed
                $previousVideoPath = parse_url($content->video, PHP_URL_PATH);
                $object = $bucket->object($previousVideoPath);
                if ($object->exists()) {
                    $object->delete();
                }
            }

            // Upload new video to Firebase Storage
            $uploadedFile = $request->file('video');
            $firebasePath = 'videos/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            // Get the public URL of the uploaded video
            $videoUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));

            // Update the video URL in the database
            $content->video = $videoUrl;
        }

        $content->save();

        return redirect()->route('courses.lessons.contents.index', [$course->id, $lesson->id])
            ->with('success', 'Content updated successfully.');
    }

    public function destroy(Course $course, Lesson $lesson, Content $content)
    {
        if ($content->image) {
            // Delete image from Firebase Storage
            $imagePath = parse_url($content->image, PHP_URL_PATH);
            $bucket = $this->firebaseStorage->getBucket();
            $object = $bucket->object($imagePath);
            if ($object->exists()) {
                $object->delete();
            }
        }

        if ($content->video) {
            // Delete video from Firebase Storage
            $videoPath = parse_url($content->video, PHP_URL_PATH);
            $bucket = $this->firebaseStorage->getBucket();
            $object = $bucket->object($videoPath);
            if ($object->exists()) {
                $object->delete();
            }
        }

        $content->delete();

        return redirect()->route('courses.lessons.contents.index', [$course->id, $lesson->id])
            ->with('success', 'Content deleted successfully.');
    }

    public function show($courseId, $lessonId, $contentId)
    {
        $course = Course::find($courseId);
        $lesson = Lesson::find($lessonId);
        $content = Content::find($contentId);

        // diri mag retrieve sa new ug before na content
        $nextContent = Content::where('lesson_id', $lessonId)->where('id', '>', $contentId)->orderBy('id')->first();
        $previousContent = Content::where('lesson_id', $lessonId)->where('id', '<', $contentId)->orderBy('id', 'desc')->first();

        return view('userUser.contents.show', compact('course', 'lesson', 'content', 'nextContent', 'previousContent'));
    }


    public function edit($courseId, $lessonId, $contentId)
    {
        // Fetch the content, lesson, and course from the database
        $content = Content::findOrFail($contentId);
        $lesson = Lesson::findOrFail($lessonId);
        $course = Course::findOrFail($courseId);

        // Return the view with the data to edit
        return view('contents.edit', compact('content', 'lesson', 'course'));
    }




    public function index(Course $course, Lesson $lesson)
    {
        $contents = $lesson->contents;
        return view('contents.index', compact('course', 'lesson', 'contents'));
    }
}
