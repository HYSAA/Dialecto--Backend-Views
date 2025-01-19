<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;



class UserDictionary extends Controller
{
   
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
    //     // Set up Firebase connection
    //     $factory = (new Factory)
    //         ->withServiceAccount(base_path('config/firebase_credentials.json'))
    //         ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
    
    //     $database = $factory->createDatabase();
    
    //     // Retrieve the course by ID
    //     $courseRef = $database->getReference('courses/' . $id);
    //     $course = $courseRef->getValue();
    //     if (!$course) {
    //         // Handle course not found
    //         abort(404, 'Course not found.');
    //     }
    
    //     // Retrieve the lessons nested under the course
    //     $lessons = $course['lessons'] ?? [];
    
    //     // Optionally retrieve contents and proficiency level within each lesson
    //     $lessonsWithContents = [];
    //     foreach ($lessons as $lessonId => $lesson) {
    //         $lessonContents = $lesson['contents'] ?? [];
    //         $proficiencyLevel = $lesson['proficiency_level'] ?? 'Not Specified'; // Default value
    
    //         $lessonsWithContents[] = [
    //             'id' => $lessonId,
    //             'title' => $lesson['title'] ?? 'Unnamed Lesson',
    //             'proficiency_level' => $proficiencyLevel,
    //             'contents' => $lessonContents,
    //         ];
    //     }
    
    //     // Filter lessons based on proficiency level if a filter is set
    //     $filterProficiency = request()->input('proficiency_level', null); // Default is null
    
    //     if ($filterProficiency) {
    //         $lessonsWithContents = array_filter($lessonsWithContents, function ($lesson) use ($filterProficiency) {
    //             return $lesson['proficiency_level'] === $filterProficiency;
    //         });
    //     }
    
    //     // Sort lessons by proficiency level
    //     $proficiencyOrder = ['Beginner', 'Intermediate', 'Advanced'];
    //     usort($lessonsWithContents, function ($a, $b) use ($proficiencyOrder) {
    //         $aIndex = array_search($a['proficiency_level'], $proficiencyOrder);
    //         $bIndex = array_search($b['proficiency_level'], $proficiencyOrder);
    
    //         $aIndex = $aIndex === false ? PHP_INT_MAX : $aIndex;
    //         $bIndex = $bIndex === false ? PHP_INT_MAX : $bIndex;
    
    //         return $aIndex <=> $bIndex;
    //     });
    
    //     // Paginate the filtered/sorted lessons
    //     $perPage = 4;
    //     $currentPage = LengthAwarePaginator::resolveCurrentPage();
    //     $itemCollection = collect($lessonsWithContents);
    //     $currentPageItems = $itemCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
    //     $paginatedLessons = new LengthAwarePaginator(
    //         $currentPageItems,
    //         $itemCollection->count(),
    //         $perPage,
    //         $currentPage,
    //         [
    //             'path' => LengthAwarePaginator::resolveCurrentPath(),
    //             'query' => request()->query(), // Include existing query parameters in pagination links
    //         ]
    //     );
    
    //     // Get unique proficiency levels for the filter
    //     $allProficiencies = collect($lessonsWithContents)->pluck('proficiency_level')->unique();
    
    //     return view('userUser.dictionary.userdictionaryshow', compact('course', 'paginatedLessons', 'allProficiencies', 'filterProficiency', 'id'));
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
        $lessonsWithContents = array_filter($lessonsWithContents, function ($lesson) use ($searchQuery) {
            $titleMatches = stripos($lesson['title'], $searchQuery) !== false;
            $contentsMatch = collect($lesson['contents'])->filter(function ($content) use ($searchQuery) {
                return stripos($content['english'] ?? '', $searchQuery) !== false || 
                       stripos($content['text'] ?? '', $searchQuery) !== false;
            })->isNotEmpty();

            return $titleMatches || $contentsMatch;
        });
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

    return view('userUser.dictionary.userdictionaryshow', compact(
        'course', 
        'paginatedLessons', 
        'allProficiencies', 
        'filterProficiency', 
        'searchQuery', 
        'id'
    ));
}


}
