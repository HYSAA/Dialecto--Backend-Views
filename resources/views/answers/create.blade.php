@extends('layouts.app')

@section('content')
<div class="main-container">
    <h1>Add New Answer</h1>
    <form action="{{ route('courses.lessons.questions.answers.store', [$course->id, $lesson->id, $question->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="answer_text">Answer Text</label>
            <input type="text" name="answer_text" id="answer_text" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="is_correct">Is Correct?</label>
            <input type="checkbox" name="is_correct" id="is_correct">
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save Answer</button>
    </form>
    </div>
@endsection
