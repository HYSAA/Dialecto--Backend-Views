<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Contract\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    protected $database;
    protected $storage;

    public function __construct(Database $database, Storage $storage)
    {
        $this->database = $database;
        $this->storage = $storage;
    }

    public function index($courseId = null)
{
    // Initialize an empty array to store lessons
    $lessons = [];

    if ($courseId) {
        // Fetch lessons for the specific course
        $lessons = $this->database->getReference('courses/' . $courseId . '/lessons')->getValue();
        if ($lessons !== null) {
            $lessons[$courseId] = $lessons; // Store lessons under course ID
        }
    } else {
        // Fetch all courses
        $courses = $this->database->getReference('courses')->getValue();

        if ($courses !== null) {
            // Loop through each course and fetch lessons
            foreach ($courses as $course => $courseData) {
                if (isset($courseData['lessons'])) {
                    $lessons[$course] = $courseData['lessons'];
                }
            }
        }
    }

    // If no lessons are found, return an empty array
    if (empty($lessons)) {
        $lessons = [];
    }

    // Pass all lessons to the view
    return view('lessons.index', compact('lessons'));
}


    public function create($courseId)
    {
        // Fetch course details using courseId
        $course = $this->database->getReference('courses/' . $courseId)->getValue();

        // Ensure the course exists
        if (!$course) {
            return redirect()->route('admin.courses.index')->with('error', 'Course not found.');
        }

        // Pass both course and courseId to the view
        return view('lessons.create', compact('course', 'courseId'));
    }

    public function store(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required | max:25 ',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'proficiency_level' =>'required|in:Beginner,Intermediate,Advanced',
        ]);

        $lessonData = [
            'title' => $request->title,
            'proficiency_level' => $request->proficiency_level,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $bucket = $this->storage->getBucket();
            $bucket->upload(
                file_get_contents($image->getRealPath()),
                ['name' => 'images/' . $imageName]
            );
            $lessonData['image'] = $bucket->object('images/' . $imageName)->signedUrl(new \DateTime('+ 1000 years'));
        }

        // Store lesson in Firebase Realtime Database
        $this->database->getReference('courses/' . $courseId . '/lessons')->push($lessonData);

        return redirect()->route('admin.courses.show', $courseId)->with('success', 'Lesson created successfully.');
    }

    // public function show($courseId, $lessonId)
    // {
    //     // Fetch course and lesson data from Firebase Realtime Database
    //     $course = $this->database->getReference('courses/' . $courseId)->getValue();
    //     $lesson = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId)->getValue();
    //     $contents = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents')->getValue();

    //     if ($contents === null) {
    //         $contents = [];
    //     }

    //     return view('lessons.show', compact('course', 'lesson', 'contents', 'courseId', 'lessonId'));
    // }

    public function show($courseId, $lessonId)
    {
        // Fetch course data (including description and image) from Firebase Realtime Database
        $course = $this->database->getReference('courses/' . $courseId)->getValue();
    
        // Fetch all lessons from the course
        $lessons = $this->database->getReference('courses/' . $courseId . '/lessons')->getValue();
    
        // Fetch the specific lesson data
        $lesson = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId)->getValue();
    
        // Fetch the contents of the specific lesson (if applicable)
        $contents = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId . '/contents')->getValue();
    
        // Handle case where no contents are found
        if ($contents === null) {
            $contents = [];
        }
    
        // Return the view with course, all lessons, specific lesson, and its contents
        return view('lessons.show', compact('course', 'lessons', 'lesson', 'contents', 'courseId', 'lessonId'));
    }
    
    public function edit($courseId, $lessonId)
    {
        $lesson = $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId)->getValue();
        return view('lessons.edit', compact('lesson', 'courseId', 'lessonId'));
    }

    public function update(Request $request, $courseId, $lessonId)
    {
        $request->validate([
            'title' => 'required | max:25 ',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'proficiency_level' => 'required|in:Beginner,Intermediate,Advanced',
        ]);

        $lessonData = [
            'title' => $request->title,
            'proficiency_level' => $request->proficiency_level,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $bucket = $this->storage->getBucket();
            $bucket->upload(
                file_get_contents($image->getRealPath()),
                ['name' => 'images/' . $imageName]
            );
            $lessonData['image'] = $bucket->object('images/' . $imageName)->signedUrl(new \DateTime('+ 1000 years'));
        }

        // Update lesson in Firebase Realtime Database
        $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId)->update($lessonData);

        return redirect()->route('admin.courses.show', $courseId)->with('success', 'Lesson updated successfully.');
    }

    public function destroy($courseId, $lessonId)
    {
        // Delete lesson from Firebase Realtime Database
        $this->database->getReference('courses/' . $courseId . '/lessons/' . $lessonId)->remove();

        return redirect()->route('admin.courses.show', $courseId)->with('success', 'Lesson deleted successfully.');
    }
}
