<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class UserDictionary extends Controller
{
    // public function userdictionary($id)
    // {
    //     // Get the firebase_id of the authenticated user
    //     $userId = Auth::user()->firebase_id;

    //     // Check if the ID matches the authenticated user's firebase_id
    //     if ($id !== $userId) {
    //         abort(403, 'Unauthorized access.');
    //     }

    //     // change the config to the firebase_credentials
    //     $factory = (new Factory)
    //         ->withServiceAccount(base_path('config/firebase_credentials.json'))
    //         ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

    //     $database = $factory->createDatabase();

    //     // Fetch courses from Firebase
    //     $coursesRef = $database->getReference('courses');
    //     $courses = $coursesRef->getValue(); // Retrieve all courses data

    //     $filteredCourses = [];
    //     if ($courses) {
    //         // Iterate through courses and include lessons and contents
    //         foreach ($courses as $courseId => $course) {
    //             if (isset($course['lessons'])) {
    //                 foreach ($course['lessons'] as $lessonId => $lesson) {
    //                     // Include contents if available
    //                     if (isset($lesson['contents'])) {
    //                         $course['lessons'][$lessonId]['contents'] = $lesson['contents'];
    //                     }
    //                 }
    //                 $filteredCourses[$courseId] = [
    //                     'id' => $courseId,
    //                     'name' => $course['name'],
    //                     'description' => $course['description'] ?? '',
    //                     'image' => $course['image'] ?? '',
    //                     'lessons' => $course['lessons'] ?? '',
    //                 ];
    //             }
    //         }
    //     }

    //     // Pass the filtered courses to the view
    //     return view('userUser.dictionary.userdictionary', [
    //         'courses' => $filteredCourses
    //     ]);
    // }

    public function userdictionary()
    {
        $factory = (new Factory)
            ->withServiceAccount(base_path('config/firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $factory->createDatabase();
        $coursesRef = $database->getReference('courses');
        $coursesData = $coursesRef->getValue();

        if ($coursesData) {
            foreach ($coursesData as $key => $course) {
                $course['id'] = $key; // Include Firebase key as an ID
                $courses[] = $course; // Push to the courses array
            }
        }
        return view('userUser.dictionary.userdictionary', compact('courses'));

    }

    // public function userdictionaryshow($id)
    // {


    //     return view('userUser.dictionary.userdictionaryshow', compact('courses,lessons'));

    // }

    public function userdictionaryshow($id)
    {
        // Set up Firebase connection
        $factory = (new Factory)
            ->withServiceAccount(base_path('config/firebase_credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        $database = $factory->createDatabase();

        // Retrieve the course by ID
        $courseRef = $database->getReference('courses/' . $id);
        $course = $courseRef->getValue();

        if (!$course) {
            // Handle course not found
            abort(404, 'Course not found.');
        }

        // Retrieve the lessons nested under the course
        $lessons = $course['lessons'] ?? [];

        // Optionally retrieve contents and proficiency level within each lesson
        $lessonsWithContents = [];
        foreach ($lessons as $lessonId => $lesson) {
            $lessonContents = $lesson['contents'] ?? [];
            $proficiencyLevel = $lesson['proficiency_level'] ?? 'Not Specified'; // Default value

            $lessonsWithContents[] = [
                'id' => $lessonId,
                'title' => $lesson['title'] ?? 'Unnamed Lesson',
                'proficiency_level' => $proficiencyLevel,
                'contents' => $lessonContents,
            ];
        }

        return view('userUser.dictionary.userdictionaryshow', compact('course', 'lessonsWithContents'));
    }



}
