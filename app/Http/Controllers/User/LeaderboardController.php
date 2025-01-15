<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class LeaderboardController extends Controller
{
    protected $database;

    public function __construct()
    {
        // Set up Firebase connection
        $factory = (new Factory)
            ->withServiceAccount(base_path('config/firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        $this->database = $factory->createDatabase();
    }

    /**
     * Display a list of courses for ranking selection.
     */
    public function index()
    {
        // Fetch courses
        $courses = $this->database->getReference('courses')->getValue() ?? [];

        return view('userUser.leaderboard.index', ['courses' => $courses]);
    }

    /**
     * Show leaderboard for a specific course.
     */
    public function show($courseName, Request $request)
    {
        $currentUserId = $request->user()->firebase_id; // Current logged-in user ID

        // Fetch data from Firebase
        $rankings = $this->database->getReference('ranking')->getValue() ?? [];
        $users = $this->database->getReference('users')->getValue() ?? [];

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

        // Sort rankings by total score (descending)
        usort($courseRankings, fn($a, $b) => $b['total_course_score'] <=> $a['total_course_score']);

        // Determine user rank
        foreach ($courseRankings as $index => $ranking) {
            if ($ranking['user_id'] === $currentUserId) {
                $userRank = $index + 1; // Rankings start at 1
                $currentUserRanking = $ranking;
                break;
            }
        }

        // Get the top 10 rankings
        $topRankings = array_slice($courseRankings, 0, 10);

        return view('userUser.leaderboard.show', [
            'courseName' => $courseName,
            'rankings' => $topRankings,
            'userRank' => $userRank,
            'currentUserRanking' => $currentUserRanking,
        ]);
    }
}
