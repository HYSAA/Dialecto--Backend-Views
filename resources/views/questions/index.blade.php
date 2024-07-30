@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Questions for {{ $content->text }}</h2>
    <a href="{{ route('contents.questions.create', $content->id) }}" class="btn btn-primary">Add Question</a>
    <table class="table">
        <thead>
            <tr>
                <th>Question Text</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($questions as $question)
                <tr>
                    <td>{{ $question->question_text }}</td>
                    <td>
                        <a href="{{ route('contents.questions.edit', [$content->id, $question->id]) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('contents.questions.destroy', [$content->id, $question->id]) }}" method="POST" style="display:inline;">
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
@endsection
