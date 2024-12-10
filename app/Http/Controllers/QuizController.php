<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Storage as FirebaseStorage;
use Kreait\Firebase\Factory;


class QuizController extends Controller
{
    protected $firebaseStorage;
    protected $database;

    public function __construct(Database $database, FirebaseStorage $firebaseStorage)
    {
        $firebaseCredentialsPath = config('firebase.credentials') ?: base_path('config/firebase_credentials.json');

        if (!file_exists($firebaseCredentialsPath) || !is_readable($firebaseCredentialsPath)) {
            throw new \Exception("Firebase credentials file is not found or readable at: {$firebaseCredentialsPath}");
        }

        $this->firebaseStorage = (new Factory)
            ->withServiceAccount($firebaseCredentialsPath)
            ->createStorage();

        $this->database = $database;
        // $this->firebaseStorage = $firebaseStorage;
    }

    public function index($courseId, $lessonId)
    {



        // dd($userId);

        $courseName = $this->database->getReference("courses/$courseId")->getValue();
        $courseName = $courseName['name'];

        $lessonName = $this->database->getReference("courses/$courseId/lessons/$lessonId")->getValue();
        $lessonName = $lessonName['title'];

        // dd($lessonName, $courseName);

        $quizzes = $this->database->getReference("quizzes/$lessonId")->getValue();

        // dd($quizzes);


        return view('quizzes.index', compact('quizzes', 'lessonId', 'courseId', 'courseName', 'lessonName'));
    }


    public function create($courseId, $lessonId)
    {
        $contents = $this->database->getReference("courses/$courseId/lessons/$lessonId/contents")->getValue();

        $courseName = $this->database->getReference("courses/$courseId")->getValue();
        $courseName = $courseName['name'];

        $lessonName = $this->database->getReference("courses/$courseId/lessons/$lessonId")->getValue();
        $lessonName = $lessonName['title'];

        //dd($courseName, $lessonName);

        return view('quizzes.create', compact('lessonId', 'courseId', 'courseName', 'lessonName', 'contents'));
    }


    public function store(Request $request, $courseId, $lessonId)
    {


        // dd($request->all());

        $contentData = [
            'question' => $request->question,
            'choices' => [
                [
                    'text' => $request->answer,
                    'audioRef' => $request->answerRef ?: null, // Default to null if no value
                ],
                [
                    'text' => $request->choiceA,
                    'audioRef' => $request->choiceARef ?: null,
                ],
                [
                    'text' => $request->choiceB,
                    'audioRef' => $request->choiceBRef ?: null,
                ],
                [
                    'text' => $request->choiceC,
                    'audioRef' => $request->choiceCRef ?: null,
                ],
            ],
            'correct' => $request->answer, // Replace with your logic to identify the correct answer
            'points' => $request->points,
        ];

        $this->database->getReference("quizzes/{$lessonId}")->push($contentData);


        return redirect()->route('admin.quizzes.index', [$courseId, $lessonId])
            ->with('success', 'Content created successfully.');
    }
}
