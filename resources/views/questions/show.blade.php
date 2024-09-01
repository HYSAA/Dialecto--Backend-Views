@extends('layouts.app')

@section('content')
  <div class="main-container">
    <h1>Question Details</h1>
    <div class="mb-3">
        <h3>Question: {{ $question->question_text }}</h3>
        <a href="{{route('courses.lessons.questions.index', [$course->id, $lesson->id, $question->id]) }}" class="btn btn-primary">Back</a>"
        <a href="{{ route('courses.lessons.questions.answers.create', [$course->id, $lesson->id, $question->id]) }}" class="btn btn-primary">Add New Answer</a>
       
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Answer</th>
                    <th>Correct</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($answers as $answer)
                    <tr>
                        <td>{{ $answer->id }}</td>
                        <td>{{ $answer->answer_text }}</td>
                        <td>{{ $answer->is_correct ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('courses.lessons.questions.answers.edit', [$course->id, $lesson->id, $question->id, $answer->id]) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('courses.lessons.questions.answers.destroy', [$course->id, $lesson->id, $question->id, $answer->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
  </div>  
@endsection
