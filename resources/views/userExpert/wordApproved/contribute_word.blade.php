@extends('layouts.app')

@section('content')
    <h1>Contribute a New Word</h1>

    <form action="{{ route('expert.submitContributeWord') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="course">Course:</label>
        <select name="course_id" id="course">
            @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->name }}</option>
            @endforeach
        </select>

        <label for="lesson">Lesson:</label>
        <select name="lesson_id" id="lesson">
            @foreach($lessons as $lesson)
                <option value="{{ $lesson->id }}">{{ $lesson->name }}</option>
            @endforeach
        </select>

        <label for="text">Text:</label>
        <input type="text" name="text" id="text" required>

        <label for="english">English:</label>
        <input type="text" name="english" id="english" required>

        <label for="video">Video (optional):</label>
        <input type="file" name="video" id="video" accept="video/*">

        <button type="submit">Submit</button>
    </form>
@endsection
