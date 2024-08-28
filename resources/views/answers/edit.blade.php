@extends('layouts.app')

@section('content')
<div class="main-container">
    <h1>Edit Answer</h1>
    <form action="{{ route('courses.lessons.questions.answers.update', [$course->id, $lesson->id, $question->id, $answer->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="answer_text">Answer Text</label>
            <input type="text" name="answer_text" id="answer_text" class="form-control" value="{{ $answer->answer_text }}" required>
        </div>
        <div class="form-group">
            <label for="is_correct">Is Correct?</label>
            <input type="checkbox" name="is_correct" id="is_correct" {{ $answer->is_correct ? 'checked' : '' }}>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Answer</button>
    </form>
    </div>
@endsection
