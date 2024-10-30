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
     */ protected $firebaseStorage;
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


    public function index()
    {
        $currentUserId = Auth::id(); // Get the currently authenticated user's ID

        $firebaseUrl = env('FIREBASE_DATABASE_URL') . '/users.json';

        // Create a context with SSL verification disabled
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






    // public function show($userId)
    // {
    //     $courses = Course::all();
    //     $lessons = Lesson::withCount('contents')->get();

    //     $userProgress = UserProgress::where('user_id', $userId)
    //         ->select('lesson_id')
    //         ->distinct()
    //         ->get();
    //     foreach($userProgress as $progress) {
    //         $lessonid = $progress->lesson_id;
    //     }

    //     $contents = Content::all();

    //     $user = User::findOrFail($userId);

    //     foreach ($courses as $course) {
    //         foreach ($course->lessons as $lesson) {
    //             $lesson->contents_count = $lesson->contents->count();
    //         }
    //     }

    //     return view('users.show', compact('courses', 'lessons', 'user', 'userProgress'));
    // }

    public function show($userId)
    {
        $courses = Course::all();

        $lessons = Lesson::withCount('contents')->get();

        $userProgress = UserProgress::where('user_id', $userId)
            ->select('lesson_id', DB::raw('count(*) as count'))
            ->groupBy('lesson_id')
            ->get();

        $user = User::findOrFail($userId);

        // Calculate content counts for each lesson under each course
        foreach ($courses as $course) {
            foreach ($course->lessons as $lesson) {
                $lesson->contents_count = $lesson->contents->count();
            }
        }

        // Pass all the necessary data to the view
        return view('users.show', compact('courses', 'lessons', 'user', 'userProgress'));
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

        // dd($user);



        $suggestedWords = $this->database
            ->getReference("suggested_words/{$userId}")
            ->getValue();

        // dd($suggestedWords); 

        // Check if $suggestedWords is null and convert it to an empty array if needed
        if (is_null($suggestedWords)) {
            $suggestedWords = []; // Set to an empty array to prevent null error in Blade
        }



        return view('userUser.suggestions.userwords', compact('suggestedWords'));
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

        // if ($word['video']) {

        //     $videoPath = parse_url($word['video'], PHP_URL_PATH);
        //     $object = $bucket->object($videoPath);
        //     if ($object->exists()) {
        //         $object->delete();
        //     }
        // }

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
            // 'video' => 'nullable|file|mimes:mp4,avi,mov|max:20480',
        ]);

        $bucket = $this->firebaseStorage->getBucket();
        $wordRef = $this->database->getReference("suggested_words/{$userId}/{$id}");

        $updatedWord = [
            'text' => $request->input('text'),
            'english' => $request->input('english')
        ];

        //please fix ang video. dapat bisag walay vid ang entry dapat naay vid nga attribute




        // if ($request->hasFile('video')) {

        //     $word = $wordRef->getValue();
        //     if (isset($word['video'])) {
        //         $previousVideoPath = parse_url($word['video'], PHP_URL_PATH);
        //         $object = $bucket->object($previousVideoPath);
        //         if ($object->exists()) {
        //             $object->delete();
        //         }
        //     }

        //     // Upload new video to Firebase Storage
        //     $uploadedFile = $request->file('video');
        //     $firebasePath = 'videos/' . $uploadedFile->getClientOriginalName();
        //     $bucket->upload(
        //         fopen($uploadedFile->getRealPath(), 'r'),
        //         ['name' => $firebasePath]
        //     );

        //     $updatedWord['video'] = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
        // }

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

        if ($request->hasFile('video')) {
            $uploadedFile = $request->file('video');
            $firebasePath = 'videos/' . $uploadedFile->getClientOriginalName();
            $bucket = $this->firebaseStorage->getBucket();
            $bucket->upload(
                fopen($uploadedFile->getRealPath(), 'r'),
                ['name' => $firebasePath]
            );

            $videoUrl = $bucket->object($firebasePath)->signedUrl(new \DateTime('+100 years'));
        }

        $suggestedWord = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'text' => $request->input('text'),
            'english' => $request->input('english'),
            'video' => $videoUrl,
            'status' => $status,
        ];

        // Save the suggested word in Firebase
        $this->database->getReference("suggested_words/{$userId}")->push($suggestedWord);

        return redirect()->route('user.wordSuggested')->with('success', 'Word suggested successfully.');
    }











    //////////////////////////////////////////////////////////////////////




    public function showPendingExpert()
    {

        // $unverifiedUsers = User::where('usertype', 'user')
        //     ->where('credentials', 1)
        //     ->with('credential')
        //     ->get();


        $unverifiedUsers = User::where('usertype', 'user')
            ->where('credentials', 1)
            ->whereHas('credential', function ($query) {
                $query->where('status', null);
            })
            ->with('credential')
            ->get();



        $verifiedUsers = User::where('usertype', 'expert')
            ->where('credentials', 1)
            ->whereHas('credential', function ($query) {
                $query->where('status', 1);
            })
            ->with('credential')
            ->get();

        $deniedUsers = User::where('usertype', 'user')
            ->where('credentials', 1)
            ->whereHas('credential', function ($query) {
                $query->where('status', 0);
            })
            ->with('credential')
            ->get();
        // return view('suggestions.addUserSuggestedWord', compact());
        return view('users.pendingVerification', compact('unverifiedUsers', 'verifiedUsers', 'deniedUsers'));
    }


    public function postVerify($id)
    {
        // Find the user by ID
        $user = User::find($id);

        // Check if the user exists
        if ($user) {
            // Update the usertype to 'expert'
            $user->usertype = 'expert';

            // Save the changes to the database
            $user->save();

            // Optional: Fetch related credentials if needed
            $cred = $user->credential;

            $cred->status = '1';

            $cred->save();




            return redirect()->route('admin.showPendingExpert')->with('success', 'User has been verified.');
        }
    }

    public function postDeny()
    {
        return view('users.pendingVerification', compact('unverifiedUsers', 'verifiedUsers', 'deniedUsers'));
    }
}
