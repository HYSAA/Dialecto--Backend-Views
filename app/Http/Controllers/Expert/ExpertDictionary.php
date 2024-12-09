<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class ExpertDictionary extends Controller
{
    //
    public function expertdictionary($id)
    {
        // Get the firebase_id of the authenticated user
        $userId = Auth::user()->firebase_id;

        // Check if the ID matches the authenticated user's firebase_id
        if ($id !== $userId) {
            abort(403, 'Unauthorized access.');
        }

        // change the config to the firebase_credentials
        $factory = (new Factory)
            ->withServiceAccount(base_path('config/dialecto-c14c1-firebase-adminsdk-q80as-7401c37304.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $factory->createDatabase();

        // Fetch courses from Firebase
        $coursesRef = $database->getReference('courses');
        $courses = $coursesRef->getValue(); // Retrieve all courses data

        $filteredCourses = [];
        if ($courses) {
            // Iterate through courses and include lessons and contents
            foreach ($courses as $courseId => $course) {
                if (isset($course['lessons'])) {
                    foreach ($course['lessons'] as $lessonId => $lesson) {
                        // Include contents if available
                        if (isset($lesson['contents'])) {
                            $course['lessons'][$lessonId]['contents'] = $lesson['contents'];
                        }
                    }
                    $filteredCourses[$courseId] = [
                        'id' => $courseId,
                        'name'=>$course['name'],
                        'description' => $course['description'] ?? '',
                        'image' => $course['image'] ?? '',
                        'lessons' => $course['lessons']?? '',
                    ];
                }
            }
        }

        // Pass the filtered courses to the view
        return view('userUser.dictionary.userdictionary', [
            'courses' => $filteredCourses
        ]);
    }
}
