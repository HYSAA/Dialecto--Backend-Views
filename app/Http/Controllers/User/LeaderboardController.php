<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class LeaderboardController extends Controller
{
    /**
     * Display a list of courses for ranking selection.
     */
    public function index()
    {
        // Set up Firebase connection
        $factory = (new Factory)
            ->withServiceAccount(base_path('config/firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $factory->createDatabase();

        // Fetch courses
        $courses = $database->getReference('courses')->getValue();

        return view('userUser.leaderboard.index', ['courses' => $courses]);
    }

    /**
     * Show leaderboard for a specific course.
     */
    public function show($courseName)
    {
        // Set up Firebase connection
        $factory = (new Factory)
            ->withServiceAccount(base_path('config/firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
    
        $database = $factory->createDatabase();
    
        // Retrieve rankings
        $rankings = $database->getReference('ranking')->getValue();
        $users = $database->getReference('users')->getValue();
        
        $courseRankings = [];
    
        // Retrieve users
        $users = $database->getReference('users')->getValue();
    
        // Process the rankings
        if ($rankings) {
            foreach ($rankings as $userId => $courses) {
                // Loop through each course for the user
                foreach ($courses as $courseId => $data) {
                    if ($data['course_name'] === $courseName) {
                        // Fetch user name from the 'users' node, fallback to userId if name is not found
                        $userName = $users[$userId]['name'] ?? $userId;

                        $courseNameFromDatabase = $courses[$courseId]['course_name'] ?? $courseId;

    
                        // Push ranking data for each user
                        $courseRankings[] = [
                            'user_name' => $userName,
                            'course_id' =>  $courseNameFromDatabase,
                            'total_course_score' => $data['total_course_score'],
                        ];
                    }
                }
            }
    
            // Sort rankings by total course score in descending order
            usort($courseRankings, fn($a, $b) => $b['total_course_score'] <=> $a['total_course_score']);
            $courseRankings = array_slice($courseRankings, 0 , 10);
        }
    
        return view('userUser.leaderboard.show', [
            'courseName' => $courseName,
            'rankings' => $courseRankings,
        ]);
    }
    
    

}
