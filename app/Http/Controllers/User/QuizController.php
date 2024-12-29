<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Content;
use Kreait\Firebase\Storage as FirebaseStorage;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Auth;

use App\Models\UserProgress;

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





    public function showQuiz($courseId, $lessonId)
    {

        // dd('asdasssss');


        $courseName = $this->database->getReference("courses/$courseId")->getValue()['name'];
        $lessonName = $this->database->getReference("courses/$courseId/lessons/$lessonId")->getValue()['title'];

        $currentIndex = session('currentIndex', 0);
        $score = session('score', 0);



        $questions  = $this->database->getReference("quizzes/$lessonId")->getValue();



        $questions = array_values($questions);
        session(['questions' => $questions]);


        for ($i = 0; $i < count($questions); $i++) {
            shuffle($questions[$i]['choices']);
        }



        $currentQuestion = $questions[$currentIndex];



        // Use compact to pass variables to the view
        return view('userUser.quiz.quiz', compact(
            'currentQuestion',
            'currentIndex',
            'questions',
            'score',
            'lessonId',
            'courseId',
            'courseName',
            'lessonName'
        ));
    }



    public function submitAnswer(Request $request, $courseId, $lessonId)
    {


        // Retrieve the current index and score from session
        $currentIndex = session('currentIndex', 0);
        $score = session('score', 0);
        $questions = session('questions', []);
        $quizHistory = session('quizHistory', []);

        // Convert the associative array to a numerically indexed array
        $questions = array_values($questions);

        // dd($currentIndex, session()->all());

        // Ensure there are questions for the lesson and that the currentIndex is valid
        if (empty($questions)) {
            return redirect()->route('user.question.results', [$courseId, $lessonId])
                ->with('error', 'No questions found for this lesson.');
        }

        // Get the selected answer
        $selectedAnswer = $request->input('answer');
        $currentQuestion = $questions[$currentIndex];



        // Check if the answer is correct and add points
        if ($selectedAnswer === $currentQuestion['correct']) {

            $score += $currentQuestion['points'];

            $quizHistory[$currentIndex] = [
                'Question' => $currentQuestion['question'],
                'Answer' => $selectedAnswer,
                'Remarks' => 1

            ];
        } else {

            $quizHistory[$currentIndex] = [
                'Question' => $currentQuestion['question'],
                'Answer' => $selectedAnswer,
                'CorrectAnswer' => $currentQuestion['correct'],
                'Remarks' => 0
            ];
        }




        // Move to the next question by incrementing currentIndex
        $currentIndex++;

        // Store the updated score and current index in session
        session([
            'score' => $score,
            'currentIndex' => $currentIndex,
            'quizHistory' => $quizHistory,
        ]);

        // Check if this was the last question
        if ($currentIndex >= count($questions)) {
            // Redirect to the results page
            return redirect()->route('user.question.results', [$courseId, $lessonId]);
        }

        // Redirect to the next question
        return redirect()->route('user.quiz.show', [$courseId, $lessonId]);
    }





    public function showResults($courseId, $lessonId)
    {
        $totalScore  = $this->database->getReference("quizzes/$lessonId")->getValue();

        $totalScore = array_values($totalScore);



        $ttscore = 0;



        for ($i = 0; $i < count($totalScore); $i++) {

            $ttscore = $ttscore + $totalScore[$i]['points'];
        }


        $score = session('score', 0);
        $questions = session('questions', []);
        $quizHistory = session('quizHistory', []);



        $quizData = [
            'score' => $score,
            'total-score' => $ttscore
        ];

        $user = Auth::user(); // Get the authenticated user
        $user = $user->firebase_id; // Dump



        // Fetch existing quiz results
        $userResults = $this->database->getReference("quiz_results/$user/$lessonId")->getValue();

        // Check if userResults is null or 0
        if (is_null($userResults) || $userResults['score'] == 0) {
            // Save quiz data directly
            $this->database->getReference("quiz_results/$user/$lessonId")->set($quizData);
        } else {
            // Check if the new total score is higher
            if ($quizData['score'] >= $userResults['score']) {
                // Update with the higher score
                $this->database->getReference("quiz_results/$user/$lessonId")->set($quizData);
            }
        }




        // dd($dd);










        return view('userUser.quiz.results', [
            'score' => $score,
            'totalQuestions' => count($questions),
            'courseId' => $courseId,
            'lessonId' => $lessonId,
            'totalScore' => $ttscore,
            'quizHistory' => $quizHistory,
        ]);
    }
}
