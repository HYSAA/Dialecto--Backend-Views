<?php

namespace App\Http\Controllers\Expert;

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
    public function showQuiz($courseId, $lessonId)
    {
        $course = Course::find($courseId);
        $lesson = Lesson::find($lessonId);

        $contents = Content::where('lesson_id', $lessonId)->get();

        $questions = $contents->shuffle();

        $options = $contents->shuffle();
        // Count of Content_id Does not increment but stores the id that is done dependent ont eh button nextcontent
        foreach ($contents as $content) {
            UserProgress::firstOrCreate([
                'user_id' => auth()->id(),
                'course_id' => $courseId,
                'lesson_id' => $lessonId,
                'content_id' => $content->id,
            ]);
        }
        return view('userExpert.quiz.quiz', compact('course', 'lesson', 'questions', 'options'));
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

        return view('userExpert.quiz.quizMC', compact('course', 'lesson', 'questions', 'options'));
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


        return redirect()->route('expert.quiz.result', [$courseId, $lessonId])->with('score', $score);
    }




    public function showResult($courseId, $lessonId)
    {
        $course = Course::find($courseId);
        $lesson = Lesson::find($lessonId);
        $score = session('score');

        return view('userExpert.quiz.results', compact('course', 'lesson', 'score'));
    }
}
