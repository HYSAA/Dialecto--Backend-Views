<?php


namespace App\Http\Controllers\Expert;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;

class CourseController extends Controller
{
    protected $database;

    public function __construct(Database $database){
      $this->database = $database;
    }
    public function index()
    {
        $courses = $this->database->getReference('courses')->getValue();
        
         if($courses==null){
            $courses =[];
         }
        return view('userExpert.courses.index', compact('courses'));
    }


    public function show($courseId)
    {
        // Fetch course data from Firebase
        $database = app('firebase.database');
        $courseRef = $database->getReference('courses/' . $courseId);
    
        // Fetch the course data and its lessons
        $course = $courseRef->getValue();
        $lessons = $course['lessons'] ?? [];
    
        // Pass both course and lessons to the view
        return view('userExpert.courses.show', compact('course', 'lessons', 'courseId'));
    }
}
