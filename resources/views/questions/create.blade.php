@extends('layouts.app')

@section('content')
<div class="main-container">
        
    <h1>Add New Question</h1>
    <form action="{{ route('courses.lessons.questions.store', [$course->id, $lesson->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="question_text">Question</label>
            <input type="text" name="question_text" id="question_text" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Save Question</button>
    </form>
    </div>
@endsection
