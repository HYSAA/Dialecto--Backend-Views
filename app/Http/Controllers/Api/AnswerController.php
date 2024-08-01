<?php

namespace App\Http\Controllers\Api;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class AnswerController extends BaseController
{
    public function index($courseId, $lessonId, $contentId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        $answers = $question->answers;

        return response()->json($answers);
    }

    public function store(Request $request, $courseId, $lessonId, $contentId, $questionId)
    {
        $request->validate([
            'answer_text' => 'required',
            'is_correct' => 'boolean',
        ]);

        $question = Question::findOrFail($questionId);

        $answer = new Answer();
        $answer->answer_text = $request->answer_text;
        $answer->is_correct = $request->is_correct;
        $answer->question_id = $question->id;
        $answer->save();

        return response()->json([
            'message' => 'Answer created successfully.',
            'answer' => $answer
        ], 201);
    }

    public function show($courseId, $lessonId, $contentId, $questionId, $answerId)
    {
        $answer = Answer::findOrFail($answerId);

        return response()->json($answer);
    }

    public function update(Request $request, $courseId, $lessonId, $contentId, $questionId, $answerId)
    {
        $request->validate([
            'answer_text' => 'required',
            'is_correct' => 'boolean',
        ]);

        $answer = Answer::findOrFail($answerId);
        $answer->update($request->only('answer_text', 'is_correct'));

        return response()->json([
            'message' => 'Answer updated successfully.',
            'answer' => $answer
        ]);
    }

    public function destroy($courseId, $lessonId, $contentId, $questionId, $answerId)
    {
        $answer = Answer::findOrFail($answerId);
        $answer->delete();

        return response()->json([
            'message' => 'Answer deleted successfully.'
        ]);
    }
}
