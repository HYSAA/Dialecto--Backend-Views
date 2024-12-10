<?php

namespace App\Http\Controllers\User;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\Storage as FirebaseStorage;

class UserProgressController extends Controller
{
    protected $database;
    protected $firebaseStorage;

    //


    public function userprogress($id)
    {
        $userId = Auth::user()->firebase_id;

        // Check if the ID matches the authenticated user's firebase_id
        if ($id !== $userId) {
            abort(403, 'Unauthorized access.');
        }

        // Firebase URL for user data
        $firebaseUserUrl = env('FIREBASE_DATABASE_URL') . '/users/' . $id . '.json';
        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);

        // Get the user data from Firebase
        $response = file_get_contents($firebaseUserUrl, false, $context);
        $user = json_decode($response, true);

        if (!$user) {
            abort(404, 'User not found.');
        }

        // Fetch courses data from Firebase
        $firebaseCoursesUrl = env('FIREBASE_DATABASE_URL') . '/courses.json';
        $courseResponse = file_get_contents($firebaseCoursesUrl, false, $context);
        $courses = json_decode($courseResponse, true);

        if (!$courses) {
            abort(404, 'Courses not found.');
        }

        // Process courses and lessons
        $coursesWithLessonsAndContents = [];
        foreach ($courses as $courseId => $courseData) {
            $lessonsWithContentCount = [];

            if (isset($courseData['lessons'])) {
                foreach ($courseData['lessons'] as $lessonId => $lessonData) {
                    $contents = isset($lessonData['contents']) ? $lessonData['contents'] : [];
                    $contentCount = count($contents);

                    $lessonsWithContentCount[$lessonId] = [
                        'lesson' => $lessonData,
                        'content_count' => $contentCount
                    ];
                }
            }

            $coursesWithLessonsAndContents[$courseId] = [
                'course' => $courseData,
                'lessons' => $lessonsWithContentCount
            ];
        }

        // Get progress data
        $progressUrl = env('FIREBASE_DATABASE_URL') . '/user_progress/' . $id . '.json';
        $progressResponse = file_get_contents($progressUrl, false, $context);
        $progressData = json_decode($progressResponse, true);

        if (!$progressData) {
            $progressData = [];
        }

        // Pass data to the view
        return view('userUser.progress.userprogress', compact('user', 'coursesWithLessonsAndContents', 'progressData'));
    }

    public function index()
    {
        $currentUserId = Auth::id(); // Get the currently authenticated user's ID

        $firebaseUrl = env('FIREBASE_DATABASE_URL') . '/users.json';
        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);
        // Use file_get_contents with the created context
        $response = file_get_contents($firebaseUrl, false, $context);
        // Decode the JSON response
        $allUsers = json_decode($response, true);
        // Filter out the current user

        if (!$allUsers) {
            return redirect()->route('survey.show'); // Redirect to survey if users data is unavailable
        }

        // Get the currently authenticated user's data
        $currentUser = $allUsers[$currentUserId] ?? null;

        if (!$currentUser) {
            return redirect()->route('survey.survey'); // Redirect to survey if current user not found
        }

        // Check if the current user has completed the survey
        if (isset($currentUser['survey_taken']) && $currentUser['survey_taken'] == 0) {
            return redirect()->route('survey.survey'); // Redirect to the survey page
        }

  
  



        $users = array_filter($allUsers, function ($userId) use ($currentUserId) {
            return $userId !== $currentUserId;
        }, ARRAY_FILTER_USE_KEY);
        // Convert filtered user IDs into an array of user objects if necessary
        $filteredUsers = [];
        foreach ($users as $userId => $userData) {
            $filteredUsers[] = [
                'id' => $userId,
                'data' => $userData,
            ];
        }
        return view('users.index', compact('filteredUsers'));
    }

}
