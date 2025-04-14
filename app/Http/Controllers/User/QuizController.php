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

        // dd('safd');




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
        $course = $this->database->getReference("courses/$courseId")->getValue();
        $courseName = $course['name'];

        $lesson = $course['lessons'];
        $lessonName = null;


        foreach ($lesson as $key => $value) {

            if ($lessonId == $key) {
                $lessonName = $value['title'];
            }
        }

        $totalScore = array_values($totalScore);

        $ttscore = 0;



        for ($i = 0; $i < count($totalScore); $i++) {

            $ttscore = $ttscore + $totalScore[$i]['points'];
        }


        $score = session('score', 0);
        $questions = session('questions', []);
        $quizHistory = session('quizHistory', []);

        $badges = $this->database->getReference("badges/")->getValue();

        $badgeName = null;
        $badgeImage = null;

        // dd($badges);

        if ($ttscore > 0) {
            $percentage = ($score / $ttscore) * 100;
        } else {
            $percentage = 0; // Prevent division by zero if no total score
        }

        // Determine the medal based on percentage
        if ($percentage >= 90) {
            $badgeName = "gold";
            $badgeImage = $badges['gold'];
        } elseif ($percentage >= 60) {
            $badgeName = "silver";
            $badgeImage = $badges['silver'];
        } elseif ($percentage >= 0) {
            $badgeName = "bronze";
            $badgeImage = $badges['bronze'];
        } else {
            $medal = "No Medal"; // This case handles negative percentages, but should not occur
        }

        // dd($badgeName, $badgeImage);


        $quizData = [
            'score' => $score,
            'total-score' => $ttscore,
            'course' => $courseId,
            'lesson' => $lessonId,
            'lesson-name' => $lessonName,
            'badge' => $badgeName,
            'badge-image' => $badgeImage,

        ];

        $user = Auth::user(); // Get the authenticated user
        $user = $user->firebase_id; // Dump








        // Fetch existing quiz results
        $userResults = $this->database->getReference("quiz_results/$user/$lessonId")->getValue();

        // Check if userResults is null or 0
        if ($userResults != null) {
            // Save quiz data directly

            if ($quizData['score'] >= $userResults['score']) {
                // Update with the higher score
                $this->database->getReference("quiz_results/$user/$lessonId")->set($quizData);
            }
        } else {
            $this->database->getReference("quiz_results/$user/$lessonId")->set($quizData);
        }

        // refetch nato and tanan results sa quizes ni user

        $userResults = $this->database->getReference("quiz_results/$user")->getValue();

        // kay random mani sila, i filter nato based sa  course niya

        $filteredUserResults = [];

        if (!empty($userResults)) {
            foreach ($userResults as $key => $value) {
                if (isset($value['course']) && $courseId == $value['course']) {
                    // Add matching result to filtered results
                    $filteredUserResults[$key] = $value;
                }
            }
        }

        // after ma filter, i add tanan score into one variable

        $sumOfAllLesson = 0;

        foreach ($filteredUserResults as $key => $value) {

            $sumOfAllLesson = $sumOfAllLesson + $value['score'];
        }

        $totalScoreForCourse = $sumOfAllLesson;

        // push the sum to firebase



        $sumOfCourse = [
            'total_course_score' => $totalScoreForCourse,
            'course_name' => $courseName

        ];


        $this->database->getReference("ranking/$user/$courseId")->set($sumOfCourse);






        // here kay ato i check if angay i promote si user sa next nga level

        // way pag check is i check if silver tanan sa kana nga level

        // initialize mga variables i reference nato


        $userId = $user;
        $currentLevel = $this->database->getReference("survey/user/$userId/course/$courseId")->getValue();
        $lesson; // kani siya tanan lessons inside course
        $lessonFilterByLevel = []; // current level ang gi compare ani
        $userResults = $this->database->getReference("quiz_results/$user/$lessonId")->getValue();

        // filter by current level ang lesson

        foreach ($lesson as $key => $value) {

            if ($value['proficiency_level'] == $currentLevel) {
                $lessonFilterByLevel[$key] = $value;
            }
        }
        // count pila ka buok
        $countFillteredtLessonsByLevel = count($lessonFilterByLevel);

        // resuse tag code sa pag get sa user results

        $userResults = $this->database->getReference("quiz_results/$user")->getValue();



        // kay random mani sila, i filter nato based sa  course niya

        $filteredUserResultsByCourse = [];

        if (!empty($userResults)) {



            foreach ($userResults as $key => $value) {

                if (isset($value['course']) && $courseId == $value['course']) {


                    // Add matching result to filtered results
                    $filteredUserResultsByCourse[$key] = $value;
                }
            }
        }

        // filtered nato sya by proficiency

        $filteredUserResultsByLevel = [];





        foreach ($filteredUserResultsByCourse as $keyA => $valueA) {
            // $valueA['lesson'];
            $userResLessonID = $valueA['lesson'];

            foreach ($lesson as $keyB => $valueB) {

                if ($userResLessonID == $keyB) { // i check ang lesson id ani



                    if ($valueB['proficiency_level'] == $currentLevel) {


                        $filteredUserResultsByLevel[$keyA] = $valueA;
                    }
                }
            }
        }

        // dd($filteredUserResultsByLevel);

        $countUserResultsByLevel = count($filteredUserResultsByLevel);

        $promote = true;

        if ($countUserResultsByLevel == $countFillteredtLessonsByLevel) {
            foreach ($filteredUserResultsByLevel as $key => $value) {
           
                if ($value['badge'] == 'bronze') {
                    $promote = false; // if bronze siya di siya ma promote :(
                    break; 
                }
            }
        } else {
            $promote = false; 
        }
        


        $congratulationsMessage = null;

        if ($promote) {

            if ($currentLevel === 'Advanced') {
                $congratulationsMessage = null;
            } else {

                $nextLevel = $this->getNextLevel($currentLevel);
                $this->database->getReference("survey/user/$user/course/$courseId")->set($nextLevel);
                $congratulationsMessage = "Congratulations! You’ve been promoted to {$nextLevel}!";
            }
        } else {
            $congratulationsMessage = null;
        }



        // dd($congratulationsMessage);



        return view('userUser.quiz.results', [
            'score' => $score,
            'totalQuestions' => count($questions),
            'courseId' => $courseId,
            'lessonId' => $lessonId,
            'totalScore' => $ttscore,
            'quizHistory' => $quizHistory,
            'quizData' => $quizData,
            'congratulationsMessage' => $congratulationsMessage,
        ]);
    }



    private function getNextLevel($currentLevel)
    {
        $levels = ['Beginner', 'Intermediate', 'Advanced'];
        $currentIndex = array_search($currentLevel, $levels);
        return $levels[$currentIndex + 1] ?? $currentLevel; // If no next level, stay at current level
    }
}
