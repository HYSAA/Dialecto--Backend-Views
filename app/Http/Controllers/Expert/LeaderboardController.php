<?php

namespace App\Http\Controllers\Expert;

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

        return view('userExpert.leaderboard.index', ['courses' => $courses]);
    }

    /**
     * Show leaderboard for a specific course.
     */
    public function show($courseName, Request $request)
    {

        $currentUserId = $request->user()->firebase_id;
        // Set up Firebase connection
        $factory = (new Factory)
            ->withServiceAccount(base_path('config/firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $factory->createDatabase();
        // Fetch data from Firebase
        $rankings = $database->getReference('ranking')->getValue() ?? [];
        $users = $database->getReference('users')->getValue() ?? [];

        $courseRankings = [];
        $userRank = null;
        $currentUserRanking = null;

        // Process rankings
        foreach ($rankings as $userId => $userCourses) {
            foreach ($userCourses as $courseId => $data) {
                if ($data['course_name'] === $courseName) {
                    $courseRankings[] = [
                        'user_id' => $userId,
                        'user_name' => $users[$userId]['name'] ?? 'Unknown User',
                        'course_id' => $data['course_name'],
                        'total_course_score' => $data['total_course_score'] ?? 0,
                    ];
                }
            }
        }


        usort($courseRankings, fn($a, $b) => $b['total_course_score'] <=> $a['total_course_score']);

        // Determine user rank
        foreach ($courseRankings as $index => $ranking) {
            if ($ranking['user_id'] === $currentUserId) {
                $userRank = $index + 1;
                $currentUserRanking = $ranking;
            } else {
                $ranking['user_name'] = '****';
            }
        }
        unset($ranking);

        // Get the top 10 rankings
        $topRankings = array_slice($courseRankings, 0, 10);

        return view('userExpert.leaderboard.show', [
            'courseName' => $courseName,
            'rankings' => $topRankings,
            'userRank' => $userRank,
            'currentUserRanking' => $currentUserRanking,
        ]);
    }
}
