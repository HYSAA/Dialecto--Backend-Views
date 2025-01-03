@extends('layouts.app')

@section('content')
<div class="main-container">
    <h1 class="text-center">Select a Course</h1>
    <div class="list-group">
        @foreach ($courses as $course)
            <a href="{{ route('user.leaderboard.show', ['courseName' => $course['name']]) }}" class="list-group-item">
                {{ $course['name'] }}
            </a>
        @endforeach
    </div>
</div>
@endsection
