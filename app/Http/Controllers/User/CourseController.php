<?php


namespace App\Http\Controllers\User;

use Kreait\Firebase\Contract\Database;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{

    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        $courses = $this->database->getReference('courses')->getValue();

        session([
            'score' => 0,
            'currentIndex' => 0,
            'questions' => [],
            'quizHistory' => []
        ]);

        // dd(session()->all());



        if ($courses === null) {
            $courses = [];
        }
        return view('userUser.courses.index', compact('courses'));
    }


    //     public function show($courseId)
    // {
    //     $course = $this->database->getReference('courses/' . $courseId)->getValue();

    //     if ($course === null) {
    //         // Handle the case where the course is not found
    //         return redirect()->route('user.courses.index')->with('error', 'Course not found.');
    //     }

    //     return view('userUser.courses.show', compact('course'));
    // }
    public function show($id)
    {
        // Retrieve the course data
        $course = $this->database->getReference('courses/' . $id)->getValue();

        if (!$course || !isset($course['lessons'])) {
            return view('userUser.courses.show', compact('course', 'id'))->with('error', 'No lessons found.');
        }

        // Get the user's Firebase ID
        $firebaseId = Auth::user()->firebase_id;

        // Retrieve the user's data from Firebase using their firebase_id
        $userData = $this->database->getReference('users/' . $firebaseId)->getValue();

        if (!$userData || !isset($userData['user_type'])) {
            // Handle the case if user data or proficiency level is not found
            return redirect()->route('user.dashboard')->with('error', 'User proficiency level not set.');
        }

        // Get the user's proficiency level from the Firebase user data
        $proficiencyLevel = $userData['user_type'];

        $levelOrder = ['Beginner', 'Intermediate', 'Advanced'];

        // Filter lessons based on proficiency level
        $filteredLessons = collect($course['lessons'])
            ->filter(function ($lesson) use ($proficiencyLevel, $levelOrder) {
                // Get the indices for the lesson and user proficiency levels
                $lessonLevelIndex = array_search($lesson['proficiency_level'], $levelOrder);
                $userLevelIndex = array_search($proficiencyLevel, $levelOrder);

                return $lessonLevelIndex <= $userLevelIndex; // Include lessons up to the user's level
            })
            ->sortBy(function ($lesson) use ($levelOrder) {
                return array_search($lesson['proficiency_level'], $levelOrder);
            });

        return view('userUser.courses.show', compact('course', 'filteredLessons', 'id'));
    }
}
