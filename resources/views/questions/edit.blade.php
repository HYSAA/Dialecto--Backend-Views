@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($answer) ? 'Edit Answer' : 'Add Answer' }}</h2>
    <form action="{{ isset($answer) ? route('questions.answers.update', [$answer->question_id, $answer->id]) : route('questions.answers.store', $question->id) }}" method="POST">
        @csrf
        @if(isset($answer))
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="answer_text">Answer Text</label>
            <input type="text" class="form-control" id="answer_text" name="answer_text" value="{{ old('answer_text', $answer->answer_text ?? '') }}">
        </div>
        <div class="form-group">
            <label for="is_correct">Is Correct?</label>
            <input type="checkbox" id="is_correct" name="is_correct" {{ old('is_correct', $answer->is_correct ?? false) ? 'checked' : '' }}>
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($answer) ? 'Update' : 'Create' }}</button>
    </form>
</div>
@endsection
