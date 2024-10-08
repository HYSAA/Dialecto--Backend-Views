<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Content;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage as FirebaseStorage;
//Added
use App\Models\UserProgress;

class QuizController extends Controller
{
    // public function showQuiz($courseId, $lessonId)
    // {
    //     $course = Course::find($courseId);
    //     $lesson = Lesson::find($lessonId);

    //     $contents = Content::where('lesson_id', $lessonId)->get();

    //     $questions = $contents->shuffle();

    //     $options = $contents->shuffle();

    //     foreach ($contents as $content) {
    //         UserProgress::firstOrCreate([
    //             'user_id' => auth()->id(),
    //             'course_id' => $courseId,
    //             'lesson_id' => $lessonId,
    //             'content_id' => $content->id,
    //         ]);
    //     }



    //     return view('userUser.quiz.quiz', compact('course', 'lesson', 'questions', 'options', 'contents'));
    // }


    public function showQuiz($courseId, $lessonId)
    {
        $course = Course::find($courseId);
        $lesson = Lesson::find($lessonId);

        $contents = Content::where('lesson_id', $lessonId)->get();

        // Shuffle contents to randomize questions
        $questions = $contents->shuffle();

        // Prepare the questions and options
        $questionsWithOptions = [];

        foreach ($questions as $question) {
            // Get the correct answer
            $correctAnswer = $question;

            // Get a random selection of 3 other options and include the correct answer
            $options = $contents->where('id', '!=', $question->id)
                ->shuffle()
                ->take(3)
                ->push($correctAnswer)
                ->shuffle();

            // Add to the array of questions with options
            $questionsWithOptions[] = [
                'question' => $question,
                'options' => $options
            ];
        }

        // Track user progress
        foreach ($contents as $content) {
            UserProgress::firstOrCreate([
                'user_id' => auth()->id(),
                'course_id' => $courseId,
                'lesson_id' => $lessonId,
                'content_id' => $content->id,
            ]);
        }

        return view('userUser.quiz.quiz', compact('course', 'lesson', 'questionsWithOptions'));
    }



    public function multipleChoice($courseId, $lessonId)
    {
        // Get the course and lesson
        $course = Course::find($courseId);
        $lesson = Lesson::find($lessonId);

        // Retrieve all contents for the lesson
        $contents = Content::where('lesson_id', $lessonId)->get();

        // Create an array of questions (English content)
        $questions = $contents->shuffle();

        // Create an array of shuffled options (Content text)
        $options = $contents->shuffle();

        return view('userUser.quiz.quizMC', compact('course', 'lesson', 'questions', 'options'));
    }




    public function submitQuiz(Request $request, $courseId, $lessonId)
    {
        $answers = $request->input('answers');
        $score = 0;


        $lessons = Lesson::where('id', $lessonId)->get();


        $contents = Content::where('lesson_id', $lessonId)->get();



        // dd($items);

        foreach ($answers as $questionId => $selectedOptionId) {
            $content = Content::find($questionId);

            // Check if the selected option is correct
            if ($content->id == $selectedOptionId) {
                $score++;
            }
        }


        // dd($score, $lessonId);

        $items = count($contents);

        return redirect()->route('quiz.result', [$courseId, $lessonId])
            ->with([
                'score' => $score,
                'items' => $items
            ]);


        // return redirect()->route('quiz.result', [$courseId, $lessonId])->with('score', $score);
    }




    public function showResult($courseId, $lessonId)
    {
        $course = Course::find($courseId);
        $lesson = Lesson::find($lessonId);
        $score = session('score');
        $items = session('items');

        return view('userUser.quiz.results', compact('course', 'lesson', 'score', 'items'));
    }
}
