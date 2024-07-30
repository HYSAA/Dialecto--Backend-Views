<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Content $content)
    {
        $questions = $content->questions;
        return view('questions.index', compact('content', 'questions'));
    }

    public function create(Content $content)
    {
        return view('questions.create', compact('content'));
    }

    public function store(Request $request, Content $content)
    {
        $request->validate([
            'question_text' => 'required',
        ]);

        $question = new Question();
        $question->question_text = $request->question_text;
        $question->content_id = $content->id;
        $question->save();

        return redirect()->route('courses.lessons.contents.questions.index', $content->id)
                         ->with('success', 'Question created successfully.');
    }

    public function edit(Content $content, Question $question)
    {
        return view('questions.edit', compact('content', 'question'));
    }

    public function update(Request $request, Content $content, Question $question)
    {
        $request->validate([
            'question_text' => 'required',
        ]);

        $question->update($request->only('question_text'));

        return redirect()->route('contents.questions.index', $content->id)
                         ->with('success', 'Question updated successfully.');
    }

    public function destroy(Content $content, Question $question)
    {
        $question->delete();

        return redirect()->route('contents.questions.index', $content->id)
                         ->with('success', 'Question deleted successfully.');
    }
}

