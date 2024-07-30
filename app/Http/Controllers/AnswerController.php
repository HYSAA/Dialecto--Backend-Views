<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function store(Request $request, Question $question)
    {
        $request->validate([
            'answer_text' => 'required',
            'is_correct' => 'boolean',
        ]);

        $answer = new Answer();
        $answer->answer_text = $request->answer_text;
        $answer->is_correct = $request->is_correct;
        $answer->question_id = $question->id;
        $answer->save();

        return redirect()->route('questions.show', $question->id)
                         ->with('success', 'Answer created successfully.');
    }

    public function edit(Answer $answer)
    {
        return view('answers.edit', compact('answer'));
    }

    public function update(Request $request, Answer $answer)
    {
        $request->validate([
            'answer_text' => 'required',
            'is_correct' => 'boolean',
        ]);

        $answer->update($request->only('answer_text', 'is_correct'));

        return redirect()->route('questions.show', $answer->question_id)
                         ->with('success', 'Answer updated successfully.');
    }

    public function destroy(Answer $answer)
    {
        $answer->delete();

        return redirect()->route('questions.show', $answer->question_id)
                         ->with('success', 'Answer deleted successfully.');
    }
}
