@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($question) ? 'Edit Question' : 'Add Question' }}</h2>
    <form action="{{ isset($question) ? route('contents.questions.update', [$content->id, $question->id]) : route('contents.questions.store', $content->id) }}" method="POST">
        @csrf
        @if(isset($question))
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="question_text">Question Text</label>
            <input type="text" class="form-control" id="question_text" name="question_text" value="{{ old('question_text', $question->question_text ?? '') }}">
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($question) ? 'Update' : 'Create' }}</button>
    </form>
</div>
@endsection
