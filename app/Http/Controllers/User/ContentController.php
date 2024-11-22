<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Content;
//Added
use App\Models\UserProgress;
use Illuminate\Support\Facades\App;
//
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Database;

use Kreait\Firebase\Storage as FirebaseStorage;

class ContentController extends Controller
{
    protected $firebaseStorage;
    protected $database;

    public function __construct(Database $database)
    {
        // Initialize the database property
        $this->database = $database;

        // Set up Firebase credentials and storage
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
        $content->english = $request->english;

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













    // public function show($courseId, $lessonId, $contentId)
    // {
    //     // Retrieve the course, lesson, and content from the Firebase Realtime Database
    //     $course = $this->database->getReference('courses/' . $courseId)->getValue();
    //     $lesson = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId)->getValue();
    //     $content = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents/' . $contentId)->getValue();



    //     // Retrieve all contents for the lesson
    //     $allContents = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents')->getValue();

    //     // Initialize nextContent and previousContent variables
    //     $nextContent = null;
    //     $previousContent = null;

    //     if ($allContents) {
    //         // Convert contents to an array
    //         $contentsArray = [];
    //         foreach ($allContents as $key => $value) {
    //             $contentsArray[] = [
    //                 'id' => $key,
    //                 'data' => $value,
    //             ];
    //         }

    //         // Sort contents by ID
    //         usort($contentsArray, function ($a, $b) {
    //             return $a['id'] <=> $b['id'];
    //         });

    //         // Find the next content after the current content ID
    //         foreach ($contentsArray as $item) {
    //             if ($item['id'] > $contentId) {
    //                 $nextContent = $item; // This is the next content
    //                 break; // Exit the loop once we find the next content
    //             }
    //         }

    //         // Find the previous content before the current content ID
    //         for ($i = count($contentsArray) - 1; $i >= 0; $i--) {
    //             if ($contentsArray[$i]['id'] < $contentId) {
    //                 $previousContent = $contentsArray[$i]; // This is the previous content
    //                 break; // Exit the loop once we find the previous content
    //             }
    //         }
    //     }




    //     if ($nextContent) {
    //         $userId = auth()->id(); // Replace with the user's ID
    //         $userProgressRef = 'user_progress/' . $userId . '/' . $courseId . '/' . $lessonId;
    
    //         // Storing progress with the next content ID
    //         $this->database->getReference($userProgressRef . '/' . $nextContent['id'])
    //             ->set([
    //                 'content_id' => $nextContent['id'],
    //                 'progress_at' => now()->toDateTimeString() // Timestamp of progress
    //             ]);
    //     }
    //     return view('userUser.contents.show', compact('course', 'courseId', 'lesson', 'lessonId', 'content', 'contentId', 'nextContent', 'previousContent'));
    // }


    public function show($courseId, $lessonId, $contentId)
{
    // Retrieve the course, lesson, and content from the Firebase Realtime Database
    $course = $this->database->getReference('courses/' . $courseId)->getValue();
    $lesson = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId)->getValue();
    $content = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents/' . $contentId)->getValue();

    // Retrieve all contents for the lesson
    $allContents = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents')->getValue();

    // Initialize nextContent and previousContent variables
    $nextContent = null;
    $previousContent = null;

    if ($allContents) {
        // Convert contents to an array
        $contentsArray = [];
        foreach ($allContents as $key => $value) {
            $contentsArray[] = [
                'id' => $key,
                'data' => $value,
            ];
        }

        // Sort contents by ID
        usort($contentsArray, function ($a, $b) {
            return strcmp($a['id'], $b['id']); // String comparison for Firebase keys
        });

        // Find the next content after the current content ID
        foreach ($contentsArray as $item) {
            if ($item['id'] > $contentId) {
                $nextContent = $item; // This is the next content
                break; // Exit the loop once we find the next content
            }
        }

        // Find the previous content before the current content ID
        for ($i = count($contentsArray) - 1; $i >= 0; $i--) {
            if ($contentsArray[$i]['id'] < $contentId) {
                $previousContent = $contentsArray[$i]; // This is the previous content
                break; // Exit the loop once we find the previous content
            }
        }
    }

    // User progress section
    $firebaseId = auth()->user()->firebase_id; // Get the user's Firebase ID
    $userProgressRef = 'user_progress/' . $firebaseId . '/' . $courseId . '/' . $lessonId;

    // Check if the current content progress already exists
    $existingProgress = $this->database->getReference($userProgressRef . '/' . $contentId)->getValue();

    if (!$existingProgress) {
        // If progress for this content doesn't exist, store it in Firebase
        $this->database->getReference($userProgressRef . '/' . $contentId)
            ->set([
                'content_id' => $contentId,
                'progress_at' => now()->toDateTimeString(), // Timestamp of progress
                'view_count' => 1 // Initialize view count to 1
            ]);
    } else {
        // If progress exists, increment the view count in Firebase
        $this->database->getReference($userProgressRef . '/' . $contentId)
            ->update([
                'view_count' => $existingProgress['view_count'] + 1,
                'progress_at' => now()->toDateTimeString() // Update timestamp for the new view
            ]);
    }

    // Pass data to the view
    return view('userUser.contents.show', compact('course', 'courseId', 'lesson', 'lessonId', 'content', 'contentId', 'nextContent', 'previousContent'));
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
