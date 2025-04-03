<?php


namespace App\Http\Controllers\Expert;

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
        // 1. Original course listing (unchanged)
        $courses = $this->database->getReference('courses')->getValue();
    
        session([
            'score' => 0,
            'currentIndex' => 0,
            'questions' => [],
            'quizHistory' => []
        ]);
    
        if ($courses === null) {
            $courses = [];
        }
    
        // 2. NEW: Get pending words count (simplified)
        $pendingCount = 0;
        $user = Auth::user();
        $userId = $user->firebase_id;
    
        // Only fetch credentials if user is logged in
        if ($userId) {
            $credentials = $this->database->getReference("credentials/$userId")->getValue();
            $courseId = $credentials['langExperties'] ?? null;
    
            if ($courseId) {
                $pendingWords = $this->database->getReference("suggested_words")->getValue();
    
                if ($pendingWords) {
                    foreach ($pendingWords as $topLevelKey => $innerArray) {
                        foreach ($innerArray as $word) {
                            if (isset($word['course_id']) && $word['course_id'] === $courseId && ($word['status'] ?? '') === 'pending') {
                                $pendingCount++;
                            }
                        }
                    }
                }
            }
        }
    
        session(['pendingWordsCount' => $pendingCount]);
    
        // 3. Return view (same as before)
        return view('userExpert.courses.index', compact('courses'));
    }

    public function show($id)
    {


        $firebaseId = Auth::user()->firebase_id;
        $courseId = $id;

        // dd("show");

        $survey = $this->database->getReference("survey/user/$firebaseId/course/$courseId")->getValue();


        if ($survey) {
            $currentLevel = $survey;
        } else {
            $currentLevel = null;
        }

        // check if user has taken survey for this course

        if (!$survey) {

            return redirect()->route('expert.survey.show', ['courseId' => $courseId]);
        }


        $course = $this->database->getReference('courses/' . $id)->getValue();

        if (!$course || !isset($course['lessons'])) {
            return view('userExpert.courses.show', compact('course', 'id'))->with('error', 'No lessons found.');
        }

        // Get the user's Firebase ID
        $firebaseId = Auth::user()->firebase_id;

        // Retrieve the user's data from Firebase using their firebase_id
        $userData = $this->database->getReference('users/' . $firebaseId)->getValue();


        // Get the user's proficiency level from the Firebase user data
        $proficiencyLevel = $survey;

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

        return view('userExpert.courses.show', compact('course', 'filteredLessons', 'id', 'currentLevel'));
    }
}
