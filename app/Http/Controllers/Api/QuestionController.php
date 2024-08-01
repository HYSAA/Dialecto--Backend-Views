<?php

namespace App\Http\Controllers\Api;

use App\Models\Content;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class QuestionController extends BaseController
{
    // Fetch questions for a specific content, randomize questions and their answers
    public function index($courseId, $lessonId, $contentId)
    {
        try {
            // Find content and fetch related questions
            $content = Content::findOrFail($contentId);
            $questions = $content->questions()->with('answers')->get();

            // Randomize questions
            $questions = $questions->shuffle();

            // Randomize answers for each question
            foreach ($questions as $question) {
                $question->answers = $question->answers->shuffle();
            }

            return response()->json($questions);
        } catch (\Exception $e) {
            // Handle any errors and return a 500 response
            return response()->json(['error' => 'Failed to fetch questions.'], 500);
        }
    }

    // Store a new question for a specific content
    public function store(Request $request, $courseId, $lessonId, $contentId)
    {
        $request->validate([
            'question_text' => 'required|string',
        ]);

        try {
            $question = new Question();
            $question->question_text = $request->question_text;
            $question->content_id = $contentId;
            $question->save();

            return response()->json($question, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create question.'], 500);
        }
    }

    // Show a specific question with its answers
    public function show($courseId, $lessonId, $contentId, $questionId)
    {
        try {
            $question = Question::with('answers')->findOrFail($questionId);
            return response()->json($question);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Question not found.'], 404);
        }
    }

    // Update a specific question
    public function update(Request $request, $courseId, $lessonId, $contentId, $questionId)
    {
        $request->validate([
            'question_text' => 'required|string',
        ]);

        try {
            $question = Question::findOrFail($questionId);
            $question->update($request->only('question_text'));

            return response()->json($question);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update question.'], 500);
        }
    }

    // Delete a specific question
    public function destroy($courseId, $lessonId, $contentId, $questionId)
    {
        try {
            $question = Question::findOrFail($questionId);
            $question->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete question.'], 500);
        }
    }
}
