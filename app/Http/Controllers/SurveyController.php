<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    // Show the survey page if the user hasn't taken it
    public function showSurvey(Request $request)
    {
        $uid = Auth::user()->firebase_id;

        // Initialize Firebase
        $firebase = (new Factory)->withServiceAccount(config_path('firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $firebase->createDatabase();

        // Check if the user has already taken the survey
        $surveyTaken = $database->getReference('users/' . $uid . '/survey_taken')->getValue();

        if ($surveyTaken == 1) {
            // If survey is already taken, redirect to the dashboard
            return redirect()->route('user.dashboard');
        }

        // If not taken, show the survey page
        return view('survey.survey');
    }

    // Update survey_taken when the user submits the survey
    public function submitSurvey(Request $request)
    {
        $uid = Auth::user()->firebase_id;

        // Initialize Firebase
        $firebase = (new Factory)->withServiceAccount(config_path('firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        $database = $firebase->createDatabase();

        // Get the survey responses from the request
        $responses = $request->all();

        // Determine proficiency level based on survey answers
        $proficiency = $this->determineProficiencyLevel($responses);

        // Update the user's proficiency level and survey_taken status in Firebase
        $database->getReference('users/' . $uid)
            ->update([
                'survey_taken' => 1,
                'user_type' => $proficiency  // Update proficiency level after survey
            ]);

        // Redirect to dashboard
        return redirect()->route('user.dashboard');
    }

    // Determine the proficiency level based on survey responses
    public function determineProficiencyLevel(array $responses)
    {
        // Example scoring system for determining proficiency level
        $score = 0;

        // Adjust the logic according to the actual questions and options in your survey
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
        
        

        // if ($responses['learning_apps_experience'] == 'frequent') {
        //     $score += 2;
        // } elseif ($responses['learning_apps_experience'] == 'occasionally') {
        //     $score += 1;
        // }


        // Determine proficiency level based on total score
        if ($score <= 9) {
            return 'Beginner';
        } elseif ($score <= 11) {
            return 'Intermediate';
        } else {
            return 'Advanced';
        }
   
    }

    // Count the number of completed lessons for a specific course
    public function countCompleteLessons(Request $request, $courseId)
    {
        $uid = Auth::user()->firebase_id;

        // Initialize Firebase
        $firebase = (new Factory)->withServiceAccount(config_path('firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $firebase->createDatabase();

        // Get the completed lessons for the user and course
        $completedLessons = $database->getReference('users/' . $uid . '/completed_lessons/' . $courseId)->getValue();

        // Count the completed lessons
        $completeCount = is_array($completedLessons) ? count($completedLessons) : 0;

        return response()->json([
            'course_id' => $courseId,
            'completed_lessons' => $completeCount, // Returning the count of completed lessons
        ]);
    }

    // Mark a lesson as completed for a user
    public function completeLesson(Request $request, $courseId, $lessonId)
    {
        $uid = Auth::user()->firebase_id;

        // Initialize Firebase
        $firebase = (new Factory)->withServiceAccount(config_path('firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        $database = $firebase->createDatabase();

        // Retrieve the current list of completed lessons for this user and course
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
