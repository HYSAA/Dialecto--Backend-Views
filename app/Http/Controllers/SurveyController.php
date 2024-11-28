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
           $surveyTaken = $database->getReference( 'users/' . $uid . '/survey_taken')->getValue();

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

           // Update survey_taken to 1 in Firebase
           $database->getReference('users/' . $uid . '/survey_taken')->set(1);

           // Redirect to the dashboard after completing the survey
           return redirect()->route('user.dashboard');
       }
}