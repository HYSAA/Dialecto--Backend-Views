<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Storage as FirebaseStorage;
use Kreait\Firebase\Factory;

use function Laravel\Prompts\text;

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

        // dd($contents);


        return view('quizzes.create', compact('lessonId', 'courseId', 'courseName', 'lessonName', 'contents'));
    }


    public function store(Request $request, $courseId, $lessonId)
    {


        // dd($request->all());


        $question = $request->input('question');
        $question = json_decode($question, true);


        $answerRef = $request->input('answerRef');
        $answerRef = json_decode($answerRef, true);

        $choiceARef = $request->input('choiceARef');
        $choiceARef = json_decode($choiceARef, true);

        $choiceBRef = $request->input('choiceBRef');
        $choiceBRef = json_decode($choiceBRef, true);

        $choiceCRef = $request->input('choiceCRef');
        $choiceCRef = json_decode($choiceCRef, true);

        $contentData = [
            'question' => $question['english'],

            'choices' => [
                [
                    'text' =>  $question['text'],
                    'audioRef' => $question['video'] ?: null, // Default to null if no value
                ],
                [
                    'text' => $choiceARef['text'],
                    'audioRef' => $choiceARef['video'] ?: null,
                ],

                [
                    'text' => $choiceBRef['text'],
                    'audioRef' => $choiceBRef['video'] ?: null,
                ],

                [
                    'text' => $choiceCRef['text'],
                    'audioRef' => $choiceCRef['video'] ?: null,
                ],
            ],


            'correct' => $question['text'], // Replace with your logic to identify the correct answer
            'points' => $request->points,
        ];

        // dd($contentData);

        $this->database->getReference("quizzes/{$lessonId}")->push($contentData);


        return redirect()->route('admin.quizzes.index', [$courseId, $lessonId])
            ->with('success', 'Content created successfully.');
    }


    public function edit(Request $request, $quizId, $courseId, $lessonId)
    {

        $contents = $this->database->getReference("courses/$courseId/lessons/$lessonId/contents")->getValue();

        $courseName = $this->database->getReference("courses/$courseId")->getValue();
        $courseName = $courseName['name'];

        $lessonName = $this->database->getReference("courses/$courseId/lessons/$lessonId")->getValue();
        $lessonName = $lessonName['title'];

        $placeholder = $this->database->getReference("quizzes/{$lessonId}/{$quizId}")->getValue();

        // dd($placeholder);

        $placeholder['questionFinal']['english'] = $placeholder['question'];
        $placeholder['questionFinal']['text'] = $placeholder['correct'];


        foreach ($placeholder["choices"] as $key => $value) {

            // dd();

            if ($value["text"] == $placeholder['questionFinal']['text']) {
                // dd($placeholder['questionFinal']['text'], $value["text"]);


                $placeholder['questionFinal']['video'] = $value['audioRef'];
            }
        }



        return view('quizzes.edit', compact('lessonId', 'courseId', 'quizId', 'courseName', 'lessonName', 'contents', 'placeholder'));
    }


    public function postEdit(Request $request, $quizId, $courseId, $lessonId)
    {





        $question = $request->input('question');
        $question = json_decode($question, true);






        $answerRef = $request->input('answerRef');
        $answerRef = json_decode($answerRef, true);

        $choiceARef = $request->input('choiceARef');
        $choiceARef = json_decode($choiceARef, true);





        $choiceBRef = $request->input('choiceBRef');
        $choiceBRef = json_decode($choiceBRef, true);

        $choiceCRef = $request->input('choiceCRef');
        $choiceCRef = json_decode($choiceCRef, true);

        // dd($question);


        $contentData = [
            'question' => $question['english'],

            'choices' => [
                [
                    'text' =>  $question['text'],
                    'audioRef' => isset($question['audioRef']) ? $question['audioRef'] : (isset($question['video']) ? $question['video'] : null),
                ],

                [
                    'text' => $choiceARef['text'],
                    'audioRef' => isset($choiceARef['audioRef']) ? $choiceARef['audioRef'] : (isset($choiceARef['video']) ? $choiceARef['video'] : null),

                ],

                [
                    'text' => $choiceBRef['text'],
                    'audioRef' => isset($choiceBRef['audioRef']) ? $choiceBRef['audioRef'] : (isset($choiceBRef['video']) ? $choiceBRef['video'] : null),

                ],

                [
                    'text' => $choiceCRef['text'],
                    'audioRef' => isset($choiceCRef['audioRef']) ? $choiceCRef['audioRef'] : (isset($choiceCRef['video']) ? $choiceCRef['video'] : null),

                ],
            ],


            'correct' => $question['text'], // Replace with your logic to identify the correct answer
            'points' => $request->points,
        ];

        // dd($contentData);
        $this->database->getReference("quizzes/{$lessonId}/{$quizId}")->set($contentData);


        return redirect()->route('admin.quizzes.index', [$courseId, $lessonId])
            ->with('success', 'Content edited successfully.');
    }

















    public function delete($quizId, $courseId, $lessonId)
    {

        $quizRef = $this->database->getReference("quizzes/{$lessonId}/{$quizId}");

        // Delete the quiz
        $quizRef->remove();

        return redirect()->route('admin.quizzes.index', [$courseId, $lessonId])
            ->with('success', 'Content Deleted successfully.');
    }
}
