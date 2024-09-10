@extends('layouts.app')

@section('content')
<div class="main-container">
    <h1>Pending Suggested Words</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($pendingWords->isEmpty())
        <p>No suggested words pending approval.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Text</th>
                    <th>English</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($pendingWords as $word)
    <tr>
        <td>{{ $word->text }}</td>
        <td>{{ $word->english }}</td>
        <td>{{ $word->course->name ?? 'No course found' }}</td>
        <td>{{ $word->lesson->title ?? 'No lesson found' }}</td>
        <td>
            <form action="{{ route('expert.approveWord', $word->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-success">Approve</button>
            </form>

            <form action="{{ route('expert.disapproveWord', $word->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-danger">Disapprove</button>
            </form>
        </td>
    </tr>
@endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection