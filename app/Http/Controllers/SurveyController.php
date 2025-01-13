<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    // Show the survey page if the user hasn't taken it
    public function showSurvey(Request $request, $courseId)
    {

        $userId = Auth::user()->firebase_id;



        // Initialize Firebase
        $firebase = (new Factory)->withServiceAccount(config_path('firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $firebase->createDatabase();



        // if wala pa naka take si user redirect siya diri na view 
        return view('survey.survey', compact('courseId'));
    }




    // kani na function para mo update if naka send nas user sa survey
    public function submitSurvey(Request $request)
    {
        $userId = Auth::user()->firebase_id;

        // Initialize Firebase
        $firebase = (new Factory)->withServiceAccount(config_path('firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        $database = $firebase->createDatabase();

        // kuhaon niya ang response sa survey example answers  sa questions 
        $responses = $request->all();




        $courseId = $responses['courseId'];
        // i labay niyas function na determineproflevel ang proficiency 
        $proficiency = $this->determineProficiencyLevel($responses);



        // create ug resibo kung unsa nga profeciency si user sa course



        $database->getReference("survey/user/$userId/course/$courseId")->set($proficiency);

        // Redirect to dashboard
        return redirect()->route('user.dashboard');
    }

    // Determine the proficiency level based on survey responses
    public function determineProficiencyLevel(array $responses)
    {
        // mga questions rani lol
        $score = 0;


        if ($responses['language_experience'] == 'beginner') {
            $score += 1;
        } elseif ($responses['language_experience'] == 'intermediate') {
            $score += 2;
        } elseif ($responses['language_experience'] == 'advanced') {
            $score += 3;
        }

        if ($responses['learning_challenge'] == 'grammar') {
            $score += 1;
        } elseif ($responses['learning_challenge'] == 'vocabulary') {
            $score += 2;
        } elseif ($responses['learning_challenge'] == 'pronunciation') {
            $score += 3;
        } elseif ($responses['learning_challenge'] == 'speaking_fluency') {
            $score += 2;
        }


        if ($responses['learning_resource'] == 'textbooks') {
            $score += 1;
        } elseif ($responses['learning_resource'] == 'online_courses') {
            $score += 2;
        } elseif ($responses['learning_resource'] == 'language_exchange') {
            $score += 3;
        } elseif ($responses['learning_resource'] == 'language_apps') {
            $score += 2;
        }


        if ($responses['motivation_level'] == '1') {
            $score += 1;
        } elseif ($responses['motivation_level'] == '2') {
            $score += 2;
        } elseif ($responses['motivation_level'] == '3') {
            $score += 3;
        }




        // logic ra pag determine si user proficiency
        if ($score <= 9) {
            return 'Beginner';
        } elseif ($score <= 11) {
            return 'Intermediate';
        } else {
            return 'Advanced';
        }
    }


    public function countCompleteLessons(Request $request, $courseId)
    {
        $uid = Auth::user()->firebase_id;

        // Initialize Firebase
        $firebase = (new Factory)->withServiceAccount(config_path('firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $firebase->createDatabase();

        // retrieve ang lessons 
        $completedLessons = $database->getReference('users/' . $uid . '/completed_lessons/' . $courseId)->getValue();

        // Count the completed lessons
        $completeCount = is_array($completedLessons) ? count($completedLessons) : 0;

        return response()->json([
            'course_id' => $courseId,
            'completed_lessons' => $completeCount, // Returning the count of completed lessons
        ]);
    }


    public function completeLesson(Request $request, $courseId, $lessonId)
    {
        $uid = Auth::user()->firebase_id;

        // Initialize Firebase
        $firebase = (new Factory)->withServiceAccount(config_path('firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        $database = $firebase->createDatabase();

        //KUHAON TANANG LESSONS NI USERS
        $completedLessonsRef = $database->getReference('users/' . $uid . '/completed_lessons/' . $courseId);
        $completedLessons = $completedLessonsRef->getValue() ?? [];

        // Add the new lesson to the completed lessons array if not already present
        if (!in_array($lessonId, $completedLessons)) {
            $completedLessons[] = $lessonId;
            $completedLessonsRef->set($completedLessons);  // Update Firebase
        }

        return response()->json([
            'message' => 'Lesson marked as completed',
            'completed_lessons' => $completedLessons,
        ]);
    }
}
