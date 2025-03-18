<?php

namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

use Illuminate\Pagination\LengthAwarePaginator;


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
        abort(404, 'Course not found.');
    }

    $lessons = $course['lessons'] ?? [];
    $lessonsWithContents = [];
    foreach ($lessons as $lessonId => $lesson) {
        $lessonContents = $lesson['contents'] ?? [];
        $proficiencyLevel = $lesson['proficiency_level'] ?? 'Not Specified';

        $lessonsWithContents[] = [
            'id' => $lessonId,
            'title' => $lesson['title'] ?? 'Unnamed Lesson',
            'proficiency_level' => $proficiencyLevel,
            'contents' => $lessonContents,
        ];
    }

    // Handle proficiency level filter
    $filterProficiency = request()->input('proficiency_level', null);
    if ($filterProficiency) {
        $lessonsWithContents = array_filter($lessonsWithContents, function ($lesson) use ($filterProficiency) {
            return $lesson['proficiency_level'] === $filterProficiency;
        });
    }

    // Handle search query
    $searchQuery = request()->input('search', null);
    if ($searchQuery) {
        // Filter logic stays as it is
        $lessonsWithContents = array_filter($lessonsWithContents, function ($lesson) use ($searchQuery) {
            $titleMatches = stripos($lesson['title'], $searchQuery) !== false;
            $contentsMatch = collect($lesson['contents'])->filter(function ($content) use ($searchQuery) {
                return stripos($content['english'] ?? '', $searchQuery) !== false || 
                       stripos($content['text'] ?? '', $searchQuery) !== false;
            })->isNotEmpty();
    
            return $titleMatches || $contentsMatch;
        });
    
        // SIMPLE HIGHLIGHT FUNCTION
        $highlight = function($text, $searchQuery) {
            return str_ireplace($searchQuery, '<mark>' . $searchQuery . '</mark>', $text);
        };
    
        // Add highlights
        foreach ($lessonsWithContents as &$lesson) {
            if (stripos($lesson['title'], $searchQuery) !== false) {
                $lesson['title'] = $highlight($lesson['title'], $searchQuery);
            }
    
            foreach ($lesson['contents'] as &$content) {
                if (!empty($content['english']) && stripos($content['english'], $searchQuery) !== false) {
                    $content['english'] = $highlight($content['english'], $searchQuery);
                }
                if (!empty($content['text']) && stripos($content['text'], $searchQuery) !== false) {
                    $content['text'] = $highlight($content['text'], $searchQuery);
                }
            }
        }
        unset($lesson); // cleanup reference
    }

    // Sort lessons by proficiency level
    $proficiencyOrder = ['Beginner', 'Intermediate', 'Advanced'];
    usort($lessonsWithContents, function ($a, $b) use ($proficiencyOrder) {
        $aIndex = array_search($a['proficiency_level'], $proficiencyOrder);
        $bIndex = array_search($b['proficiency_level'], $proficiencyOrder);

        $aIndex = $aIndex === false ? PHP_INT_MAX : $aIndex;
        $bIndex = $bIndex === false ? PHP_INT_MAX : $bIndex;

        return $aIndex <=> $bIndex;
    });

    // Paginate lessons
    $perPage = 8;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $itemCollection = collect($lessonsWithContents);
    $currentPageItems = $itemCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
    $paginatedLessons = new LengthAwarePaginator(
        $currentPageItems,
        $itemCollection->count(),
        $perPage,
        $currentPage,
        ['path' => LengthAwarePaginator::resolveCurrentPath()]
    );

    $allProficiencies = collect($lessonsWithContents)->pluck('proficiency_level')->unique();

    return view('userExpert.dictionary.expertdictionaryshow', compact(
        'course', 
        'paginatedLessons', 
        'allProficiencies', 
        'filterProficiency', 
        'searchQuery', 
        'id'
    ));
}

}
