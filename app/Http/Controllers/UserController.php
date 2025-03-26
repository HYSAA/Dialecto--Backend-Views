<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Credential;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage as FirebaseStorage;

use Kreait\Firebase\Database;
use Kreait\Firebase\Storage;
use App\Models\Lesson;
use App\Models\suggestedWord;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $firebaseStorage;
    protected $database;
    public function __construct()
    {
        $firebaseCredentialsPath = config('firebase.credentials') ?: base_path('config/firebase_credentials.json');

        if (!file_exists($firebaseCredentialsPath) || !is_readable($firebaseCredentialsPath)) {
            throw new \Exception("Firebase credentials file is not found or readable at: {$firebaseCredentialsPath}");
        }

        // Initialize the Realtime Database
        $this->database = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->withDatabaseUri('https://dialecto-c14c1-default-rtdb.asia-southeast1.firebasedatabase.app/') // Use the correct URL
            ->createDatabase();

        // Initialize Firebase Storage
        $this->firebaseStorage = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->createStorage();

        // Check if the Storage instance is properly initialized
        if ($this->firebaseStorage === null) {
            throw new \Exception("Failed to initialize Firebase Storage.");
        }
    }

    public function index(Request $request)
    {
        $currentUserId = Auth::id();
        $firebaseBaseUrl = env('FIREBASE_DATABASE_URL');
    
        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);
    
        // Fetch users
        $usersResponse = file_get_contents($firebaseBaseUrl . '/users.json', false, $context);
        $allUsers = json_decode($usersResponse, true) ?? [];
    
        // Fetch credentials
        $credentialsResponse = file_get_contents($firebaseBaseUrl . '/credentials.json', false, $context);
        $allCredentials = json_decode($credentialsResponse, true) ?? [];
    
        // Filter out current user and admin users
        $filteredUsers = [];
        foreach ($allUsers as $userId => $userData) {
            if ($userId == $currentUserId || ($userData['usertype'] ?? '') === 'admin') {
                continue; // Skip the current user and admins
            }
    
            $courseName = $allCredentials[$userId]['courseName'] ?? null;
            $filteredUsers[] = [
                'id' => $userId,
                'data' => $userData,
                'courseName' => $courseName,
            ];
        }
    
        // Handle search
        $search = strtolower($request->input('search'));
        if ($search) {
            $filteredUsers = array_filter($filteredUsers, function ($user) use ($search) {
                $name = strtolower($user['data']['name'] ?? '');
                $email = strtolower($user['data']['email'] ?? '');
                $usertype = strtolower($user['data']['usertype'] ?? '');
    
                return str_contains($name, $search) || str_contains($email, $search) || str_contains($usertype, $search);
            });
        }
    
        return view('users.index', compact('filteredUsers'));
    }
    






    // public function show($userId)
    // {
    //     $courses = Course::all();

    //     $lessons = Lesson::withCount('contents')->get();

    //     $userProgress = UserProgress::where('user_id', $userId)
    //         ->select('lesson_id', DB::raw('count(*) as count'))
    //         ->groupBy('lesson_id')
    //         ->get();

    //     $user = User::findOrFail($userId);

    //     // Calculate content counts for each lesson under each course
    //     foreach ($courses as $course) {
    //         foreach ($course->lessons as $lesson) {
    //             $lesson->contents_count = $lesson->contents->count();
    //         }
    //     }

    //     // Pass all the necessary data to the view
    //     return view('users.show', compact('courses', 'lessons', 'user', 'userProgress'));
    // }

    public function show($id)
    {
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

        // Prepare an array to store lessons and content counts for each course
        $coursesWithLessonsAndContents = [];

        // Loop through each course and fetch lessons and content count
        foreach ($courses as $courseId => $courseData) {
            $lessonsWithContentCount = [];

            if (isset($courseData['lessons'])) {
                foreach ($courseData['lessons'] as $lessonId => $lessonData) {
                    // Fetch contents for each lesson
                    $contents = isset($lessonData['contents']) ? $lessonData['contents'] : [];
                    // Count the contents
                    $contentCount = count($contents);

                    // Store lesson name and content count
                    $lessonsWithContentCount[$lessonId] = [
                        'lesson' => $lessonData,
                        'content_count' => $contentCount
                    ];
                }
            }

            // Store course data and its lessons with content count
            $coursesWithLessonsAndContents[$courseId] = [
                'course' => $courseData,
                'lessons' => $lessonsWithContentCount
            ];
        }

        // Reference to the user's progress in the database
        $progressRef = $this->database->getReference('user_progress/' . $id);

        // Get the progress data
        $progressSnapshot = $progressRef->getSnapshot();
        $progressData = $progressSnapshot->getValue();

        // Check if progress data exists
        if (!$progressData) {
            $progressData = [];
        }



        // Pass the user data and courses with lessons and content count to the view
        return view('users.show', compact('user', 'id', 'coursesWithLessonsAndContents', 'progressData'));
    }





    public function edit(User $user)
    {


        return view('users.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {

        // dd($request->all());

        $request->validate([
            'name' => 'required',
            'usertype' => 'nullable',

        ]);



        $user->update([
            'name' => $request->name,
            'usertype' => $request->usertype,

        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Course deleted successfully.');
    }



    /////////////////////////////////////////////////////////////////////
    //second logic// 



    // List word suggestions by current user
    public function wordSuggested()
    {

        $user = Auth::user();
        $userId = $user->firebase_id;
        // $denied_remarks = [];

        // dd($user);

        $suggestedWords = $this->database->getReference("suggested_words/{$userId}")->getValue();

        $denied_remarks = $this->database->getReference("denied_words/{$userId}")->getValue();






        // dd($suggestedWords);

        // Check if $suggestedWords is null and convert it to an empty array if needed
        if (is_null($suggestedWords)) {
            $suggestedWords = []; // Set to an empty array to prevent null error in Blade
        }

        $approved_words = [];
        $disapproved_words = [];
        $pending_words = [];


        foreach ($suggestedWords as $key => $innerArray) {

            if ($innerArray['status'] == 'approved') {

                $approved_words[$key] = $innerArray;
            }
        }

        foreach ($suggestedWords as $key => $innerArray) {

            if ($innerArray['status'] == 'pending') {

                $pending_words[$key] = $innerArray;
            }
        }

        foreach ($suggestedWords as $key => $innerArray) {

            if ($innerArray['status'] == 'disapproved') {

                $disapproved_words[$key] = $innerArray;
            }
        }


        foreach ($disapproved_words as $key => $value) {



            foreach ($denied_remarks as $key2 => $value2) {

                if ($key == $key2) {


                    $disapproved_words[$key]['reason'] = $value2['reason'];

                    // dd($key, $value, $key2, $value2);
                }
            }
        }

        // dd($disapproved_words);



        // dd($suggestedWords, $approved_words, $pending_words, $disapproved_words);






        return view('userUser.suggestions.userwords', compact('suggestedWords', 'approved_words', 'pending_words', 'disapproved_words'));
    }





    public function selectUserCourseLesson()
    {
        // Logic to get courses and lessons, for example:
        $courses = $this->database->getReference('courses')->getValue(); // Adjust this based on your structure

        return view('userUser.suggestions.selectUserCourseLesson', compact('courses'));
    }

    public function addUserSuggestedWord($courseId, $lessonId)
    {
        // Retrieve the course from Firebase Realtime Database
        $courseRef = $this->database->getReference("courses/{$courseId}");
        $course = $courseRef->getValue();

        // Retrieve the lesson from Firebase Realtime Database
        $lessonRef = $this->database->getReference("courses/{$courseId}/lessons/{$lessonId}");
        $lesson = $lessonRef->getValue();

        // Check if course or lesson is null (similar to findOrFail in Eloquent)
        if (!$course || !$lesson) {
            abort(404, 'Course or Lesson not found.');
        }

        return view('userUser.suggestions.addUserSuggestedWord', compact('course', 'lesson', 'courseId', 'lessonId'));
    }



    // View a specific suggested word to update
    public function viewUpdateSelected($id)
    {
        $user = Auth::user();
        $userId = $user->firebase_id;



        $suggestedWord = $this->database->getReference("suggested_words/{$userId}/{$id}")->getValue();

        // dd($suggestedWord);

        // Check if suggestedWord exists
        if ($suggestedWord === null) {
            return redirect()->route('user.wordSuggested')->with('error', 'Suggested word not found.');
        }

        return view('userUser.suggestions.updateSelected', [
            'suggestedWordId' => $id,
            'suggestedWord' => $suggestedWord,
        ]);
    }


    // Delete a word from Firebase
    public function deleteSelectedWord($id)
    {

        $user = Auth::user();
        $userId = $user->firebase_id;

        $word = $this->database->getReference("suggested_words/{$userId}/{$id}")->getValue();

        // dd($word);
        $bucket = $this->firebaseStorage->getBucket();


        // dili ni mo work sa ubos kay walay na create video nga attribute

        if ($word['video']) {

            $videoPath = parse_url($word['video'], PHP_URL_PATH);
            $object = $bucket->object($videoPath);
            if ($object->exists()) {
                $object->delete();
            }
        }

        // Delete the word from Firebase Realtime Database
        $this->database->getReference("suggested_words/{$userId}/{$id}")->remove();

        return redirect()->route('user.wordSuggested')->with('success', 'Translation deleted successfully.');
    }

    // Update a selected word
    public function updateSelected(Request $request, $id)
    {
        $user = Auth::user();
        $userId = $user->firebase_id;

        $request->validate([
            'text' => 'required|string|max:255',
            'english' => 'required|string|max:255',
            'video' => 'nullable|file|mimes:mp4,avi,mov|max:20480',
        ]);

        $bucket = $this->firebaseStorage->getBucket();
        $wordRef = $this->database->getReference("suggested_words/{$userId}/{$id}");

        $updatedWord = [
            'text' => $request->input('text'),
            'english' => $request->input('english')
        ];

        if ($request->hasFile('video')) {

            $word = $wordRef->getValue();
            if (isset($word['video'])) {
                $previousVideoPath = parse_url($word['video'], PHP_URL_PATH);
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

            $updatedWord['video'] = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
        }

        $wordRef->update($updatedWord);

        return redirect()->route('user.wordSuggested')->with('success', 'Word updated successfully.');
    }

    // Submit a new word suggestion
    public function submitWordSuggested(Request $request, $courseId, $lessonId)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'english' => 'required|string|max:255',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480',
        ]);

        $user = Auth::user();
        $userId = $user->firebase_id;
        $videoUrl = null;
        $status = 'pending';



        $courseData = $this->database
            ->getReference("courses/{$courseId}")
            ->getValue();

        $courseName = isset($courseData['name']) ? $courseData['name'] : null;




        $lessonData = $this->database
            ->getReference("courses/{$courseId}/lessons/{$lessonId}")
            ->getValue();

        $lessonName = isset($lessonData['title']) ? $lessonData['title'] : null;




        if ($request->hasFile('video')) {
            $uploadedFile = $request->file('video');
            $firebasePath = 'videos/' . $uploadedFile->getClientOriginalName();
            $bucket = $this->firebaseStorage->getBucket();
            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            $videoUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
        } else {
            $videoUrl = 'null';
        }


        $suggestedWord = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'course_name' => $courseName,
            'lesson_id' => $lessonId,
            'lesson_name' => $lessonName,
            'text' => $request->input('text'),
            'english' => $request->input('english'),
            'video' => $videoUrl,
            'status' => $status,
        ];

        // Save the suggested word in Firebase
        $this->database->getReference("suggested_words/{$userId}")->push($suggestedWord);

        return redirect()->route('user.wordSuggested')->with('success', 'Word suggested successfully.');
    }





    public function showPendingExpert()
    {
        $userCredentials = $this->database
            ->getReference("credentials")
            ->getValue();

        $userDetails = $this->database
            ->getReference("users")
            ->getValue();

        if ($userCredentials) {
            $unverifiedUsers = array_filter($userCredentials, function ($user) {
                return isset($user['status']) && $user['status'] === 'pending';
            });

            $verifiedUsers = array_filter($userCredentials, function ($user) {
                return isset($user['status']) && $user['status'] === 'verified';
            });

            $deniedUsers = array_filter($userCredentials, function ($user) {
                return isset($user['status']) && $user['status'] === 'denied';
            });
        } else {
            $unverifiedUsers = null;
            $verifiedUsers = null;
            $deniedUsers = null;
        }

        // Store unverifiedUsers in the session
        session(['unverifiedUsers' => $unverifiedUsers]);

        return view('users.pendingVerification', compact('unverifiedUsers', 'verifiedUsers', 'deniedUsers', 'userDetails'));
    }


    public function postVerify($userId)
    {

        $type = 'expert';
        $credChange = 'verified';

        $updatedUserType = [
            'usertype' => $type
        ];

        $updatedCredential = [
            'status' => $credChange
        ];

        $userTypeRef = $this->database->getReference("users/{$userId}");

        $userTypeRef->update($updatedUserType);



        $creChangeRef = $this->database->getReference("credentials/{$userId}");


        $creChangeRef->update($updatedCredential);




        return redirect()->route('admin.showPendingExpert')->with('success', 'User has been verified.');
    }

    public function postDeny($userId)
    {

        $credChange = 'denied';


        $updatedCredential = [
            'status' => $credChange
        ];


        $creChangeRef = $this->database->getReference("credentials/{$userId}");


        $creChangeRef->update($updatedCredential);




        return redirect()->route('admin.showPendingExpert')->with('success', 'User application has been denied.');
    }
}
