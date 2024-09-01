<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Content;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage as FirebaseStorage;

class QuizController extends Controller
{
    public function showQuiz($courseId, $lessonId)
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

        return view('userUser.quiz.quiz', compact('course', 'lesson', 'questions', 'options'));
    }




    public function submitQuiz(Request $request, $courseId, $lessonId)
    {
        $answers = $request->input('answers');
        $score = 0;

        foreach ($answers as $questionId => $selectedOptionId) {
            $content = Content::find($questionId);

            // Check if the selected option is correct
            if ($content->id == $selectedOptionId) {
                $score++;
            }
        }

        return redirect()->route('quiz.result', [$courseId, $lessonId])->with('score', $score);
    }

    public function showResult($courseId, $lessonId)
    {
        $course = Course::find($courseId);
        $lesson = Lesson::find($lessonId);
        $score = session('score');

        return view('userUser.quiz.results', compact('course', 'lesson', 'score'));
    }
}
