<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Content;
use Illuminate\Http\Request;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage as FirebaseStorage;
use Illuminate\Support\Facades\Response;
use Illuminate\Routing\Controller as BaseController;

class ContentController extends BaseController
{
    protected $firebaseStorage;

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

    public function store(Request $request, $courseId, $lessonId)
    {
        $request->validate([
            'text' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,avi,mov|max:10000', // Add validation for video files
        ]);

        $content = new Content();
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

        return Response::json(['message' => 'Content created successfully.', 'data' => $content], 201);
    }

    public function update(Request $request, Course $course, Lesson $lesson, Content $content)
    {
        $request->validate([
            'text' => 'nullable',
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

            // imo kuhaon ang url sa image 
            $imageUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));

            // mag update sa image url
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

        return Response::json(['message' => 'Content updated successfully.', 'data' => $content], 200);
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

        return Response::json(['message' => 'Content deleted successfully.'], 200);
    }


    ///might reuse this parameters in the future 
    //stay put
    public function show($courseId, $lessonId, $contentId)
    {
        $content = Content::findOrFail($contentId);
        return Response::json(['data' => $content], 200);
    }

    public function index($courseId, $lessonId)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $contents = $lesson->contents;
        return Response::json(['data' => $contents], 200);
    }
}