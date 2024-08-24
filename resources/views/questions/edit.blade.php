@extends('layouts.app')

@section('content')
<div class="main-container">
    <h1>Edit Question</h1>
    <form action="{{ route('courses.lessons.questions.update', [$course->id, $lesson->id, $question->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="question_text">Question</label>
            <input type="text" name="question_text" id="question_text" class="form-control" value="{{ $question->question_text }}" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update Question</button>
    </form>
</div>
@endsection
