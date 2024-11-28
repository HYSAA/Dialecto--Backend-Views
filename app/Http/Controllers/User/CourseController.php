<?php


namespace App\Http\Controllers\User;

use Kreait\Firebase\Contract\Database;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CourseController extends Controller
{

    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        $courses = $this->database->getReference('courses')->getValue();
        // $uid = Auth::user();
        // dd($uid);

        if ($courses === null) {
            $courses = [];
        }
        return view('userUser.courses.index', compact('courses'));
    }


    //     public function show($courseId)
    // {
    //     $course = $this->database->getReference('courses/' . $courseId)->getValue();

    //     if ($course === null) {
    //         // Handle the case where the course is not found
    //         return redirect()->route('user.courses.index')->with('error', 'Course not found.');
    //     }

    //     return view('userUser.courses.show', compact('course'));
    // }

    public function show($id)
    {
        $course = $this->database->getReference('courses/' . $id)->getValue();

        // dd($id);
        // dd($course['id']);
        return view('userUser.courses.show', compact('course', 'id'));
    }
}
