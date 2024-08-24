<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function store(Request $request, $courseId, $lessonId, $questionId)
    {
        // Convert checkbox value to integer
        $isCorrect = $request->has('is_correct') ? 1 : 0;
    
        Answer::create([
            'question_id' => $questionId,
            'answer_text' => $request->input('answer_text'),
            'is_correct' => $isCorrect,
        ]);
    
        return redirect()->route('courses.lessons.questions.show', [$courseId, $lessonId, $questionId])
                         ->with('success', 'Answer created successfully.');
    }
    
    public function update(Request $request, $courseId, $lessonId, $questionId, $id)
    {
        $answer = Answer::findOrFail($id);
    
        // Convert checkbox value to integer
        $isCorrect = $request->has('is_correct') ? 1 : 0;
    
        $answer->update([
            'answer_text' => $request->input('answer_text'),
            'is_correct' => $isCorrect,
        ]);
    
        return redirect()->route('courses.lessons.questions.show', [$courseId, $lessonId, $questionId])
                         ->with('success', 'Answer updated successfully.');
    }
    
    public function destroy($courseId, $lessonId, $questionId, $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->delete();
    
        return redirect()->route('courses.lessons.questions.show', [$courseId, $lessonId, $questionId]);
    }
    public function create($courseId, $lessonId, $questionId)
{
    $course = Course::findOrFail($courseId);
    $lesson = Lesson::findOrFail($lessonId);
    $question = Question::findOrFail($questionId);

    return view('answers.create', compact('course', 'lesson', 'question'));
}
public function edit($courseId, $lessonId, $questionId, $answerId)
{
    $course = Course::findOrFail($courseId);
    $lesson = Lesson::findOrFail($lessonId);
    $question = Question::findOrFail($questionId);
    $answer = Answer::findOrFail($answerId);

    return view('answers.edit', compact('course', 'lesson', 'question', 'answer'));
}
    
}
