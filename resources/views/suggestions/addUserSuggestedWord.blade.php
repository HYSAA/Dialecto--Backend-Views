@extends('layouts.app')

@section('content')

<div class="main-container" style="padding: 15px;">
    <h1>Current Contents in Selected Lesson</h1>
    @foreach ($lesson->contents as $content)
        <li>{{$content->text}} - {{ $content->english }}</li>
    @endforeach

    <form action="{{ route('user.submitWordSuggested', [$course->id, $lesson->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">

        <div>
            <label for="video">Video:</label>
            <input type="file" name="video" class="form-control">
        </div>

        <div>
            <label for="text">Text:</label>
            <textarea name="text" id="text" required></textarea>
        </div>

        <div>
            <label for="english">English:</label>
            <input type="text" name="english" id="english" required>
        </div>

        <button type="submit">Submit</button>
    </form>

</div>