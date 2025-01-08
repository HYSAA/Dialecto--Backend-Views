<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Storage as FirebaseStorage;

use Kreait\Firebase\Factory;

class ContentController extends Controller
{
    protected $firebaseStorage;
    protected $database;

    public function __construct(Database $database, FirebaseStorage $firebaseStorage)
    {
        $firebaseCredentialsPath = config('firebase.credentials') ?: base_path('config/firebase_credentials.json');

        if (!file_exists($firebaseCredentialsPath) || !is_readable($firebaseCredentialsPath)) {
            throw new \Exception("Firebase credentials file is not found or readable at: {$firebaseCredentialsPath}");
        }

        $this->firebaseStorage = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->createStorage();

        $this->database = $database;
        // $this->firebaseStorage = $firebaseStorage;
    }

    public function create($courseId, $lessonId)
    {
        // Fetch course and lesson details from Firebase Realtime Database
        $course = $this->database->getReference('courses/' . $courseId)->getValue();
        $lesson = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId)->getValue();

        if (!$course || !$lesson) {
            return redirect()->route('admin.courses.index')->with('error', 'Course or Lesson not found.');
        }

        // Pass $courseId and $lessonId to the view
        return view('contents.create', compact('course', 'lesson', 'courseId', 'lessonId'));
    }

    public function store(Request $request, $courseId, $lessonId)
    {
        $request->validate([
            'text' => 'nullable',
            'english' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,avi,mov|max:10000',
        ]);




        $contentData = [
            'english' => $request->english,
            'text' => $request->text,
        ];

        $bucket = $this->firebaseStorage->getBucket();

        if ($request->hasFile('image')) {
            // Upload image to Firebase Storage
            $uploadedFile = $request->file('image');
            $firebasePath = 'images/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            $imageUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
            $contentData['image'] = $imageUrl;
        }

        if ($request->hasFile('video')) {
            $uploadedFile = $request->file('video');
            $firebasePath = 'videos/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            $videoUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
            $contentData['video'] = $videoUrl;
        }

        // Save content to Firebase Realtime Database
        $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents')->push($contentData);

        return redirect()->route('admin.lessons.show', [$courseId, $lessonId])
            ->with('success', 'Content created successfully.');
    }




















    public function update(Request $request, $courseId, $lessonId, $contentId)
    {
        $request->validate([
            'text' => 'nullable',
            'english' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,avi,mov|max:10000',
        ]);

        $contentData = [
            'english' => $request->english,
            'text' => $request->text,
        ];

        $bucket = $this->firebaseStorage->getBucket();
        $contentReference = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents/' . $contentId);

        // Handle image
        if ($request->hasFile('image')) {
            $uploadedFile = $request->file('image');
            $firebasePath = 'images/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(fopen($uploadedFile->getRealPath(), 'r'), ['name' => $firebasePath]);
            $imageUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
            $contentData['image'] = $imageUrl;
        }

        // Handle video
        if ($request->hasFile('video')) {
            $uploadedFile = $request->file('video');
            $firebasePath = 'videos/' . $uploadedFile->getClientOriginalName();

            $bucket->upload(fopen($uploadedFile->getRealPath(), 'r'), ['name' => $firebasePath]);
            $videoUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
            $contentData['video'] = $videoUrl;
        }

        // Update content in Firebase Realtime Database
        $contentReference->update($contentData);

        return redirect()->route('admin.lessons.show', [$courseId, $lessonId])
            ->with('success', 'Content updated successfully.');
    }

    public function destroy($courseId, $lessonId, $contentId)
    {
        // Get the reference to the specific content in Firebase
        $contentReference = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents/' . $contentId);

        // Remove the content entry from Firebase Realtime Database
        $contentReference->remove();

        // Redirect back to the lesson view with a success message
        return redirect()->route('admin.lessons.show', [$courseId, $lessonId])
            ->with('success', 'Content deleted successfully.');
    }



    public function show($courseId, $lessonId, $contentId)
    {
        $content = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents/' . $contentId)->getValue();
        $nextContent = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents')
            ->orderByKey()->startAt($contentId)->limitToFirst(2)->getValue();
        $previousContent = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents')
            ->orderByKey()->endAt($contentId)->limitToLast(2)->getValue();

        return view('contents.show', compact('content', 'nextContent', 'previousContent'));
    }

    public function edit($courseId, $lessonId, $contentId)
    {
        $course = $this->database->getReference('courses/' . $courseId)->getValue();
        $lesson = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId)->getValue();
        $content = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents/' . $contentId)->getValue();


        return view('contents.edit', compact('content', 'course', 'lesson', 'courseId', 'lessonId', 'contentId'));
    }

    public function index($courseId, $lessonId)
    {
        $contents = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents')->getValue();
        return view('contents.index', compact('contents', 'courseId', 'lessonId'));
    }
}
