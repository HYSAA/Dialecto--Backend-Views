<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Content;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // QuestionController.php

    public function index($courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);
        $questions = Question::where('lesson_id', $lessonId)->get();

        return view('questions.index', compact('course', 'lesson', 'questions'));
    }

    public function create($courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);

        return view('questions.create', compact('course', 'lesson'));
    }

    public function store(Request $request, $courseId, $lessonId)
    {
        // Validate the incoming request data
        $request->validate([
            'question_text' => 'required|string|max:255',
        ]);

        // Find the course and lesson to associate with the question
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);

        // Create a new question and associate it with the lesson
        $question = new Question();
        $question->question_text = $request->input('question_text');
        $question->lesson_id = $lessonId;
        $question->save();

        // Redirect back to the list of questions with a success message
        return redirect()->route('courses.lessons.questions.index', [$courseId, $lessonId])
            ->with('success', 'Question created successfully.');
    }

    public function edit($courseId, $lessonId, $questionId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);
        $question = Question::findOrFail($questionId);

        return view('questions.edit', compact('course', 'lesson', 'question'));
    }

    public function show($courseId, $lessonId, $questionId)
    {
        $course = Course::findOrFail($courseId);
        $lesson = Lesson::findOrFail($lessonId);
        $question = Question::findOrFail($questionId);
        $answers = $question->answers;

        return view('questions.show', compact('course', 'lesson', 'question', 'answers'));
    }
    public function update(Request $request, $courseId, $lessonId, $questionId)
    {

        $request->validate([
            'question_text' => 'required|string|max:255',
        ]);
        //pangitaon niya ang question id
        $question = Question::findOrFail($questionId);

        $question->question_text = $request->input('question_text');
        $question->save();

        return redirect()->route('courses.lessons.questions.index', [$courseId, $lessonId])->with('success', 'Question updated');
    }


    public function destroy($courseId, $lessonId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->delete();

        return redirect()->route('courses.lessons.questions.index', [$courseId, $lessonId])->with('success', 'Lesson deleted successfully.');
    }
}
