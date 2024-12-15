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

    public function expertdictionary()
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
        return view('userExpert.dictionary.expertdictionary', compact('courses'));

    }
    public function expertdictionaryshow($id)
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

        return view('userExpert.dictionary.expertdictionaryshow', compact('course', 'lessonsWithContents'));
    }
}
