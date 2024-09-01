@extends('layouts.app')

@section('content')
<div class="main-container">
    <h1>Questions for Lesson {{ $lesson->title }}</h1>
    <a href="{{ route('courses.lessons.questions.create', [$course->id, $lesson->id]) }}" class="btn btn-primary">Add New Question</a>
    <a  class="btn btn-primary" href="{{ route('admin.lessons.show', [$course->id, $lesson->id]) }}">Back</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($questions as $question)
                <tr>
                    <td>{{ $question->id }}</td>
                    <td>{{ $question->question_text }}</td>
                    <td>
                        <a href="{{ route('courses.lessons.questions.edit', [$course->id, $lesson->id, $question->id]) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('courses.lessons.questions.destroy', [$course->id, $lesson->id, $question->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <a href="{{ route('courses.lessons.questions.show', [$course->id, $lesson->id, $question->id]) }}" class="btn btn-info">View Answers</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
