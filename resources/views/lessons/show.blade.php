@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - Lesson: {{ $lesson->title }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('courses.lessons.index', $course->id) }}">Back to Lessons</a>
                <a class="btn btn-success" href="{{ route('lessons.contents.create', [$course->id, $lesson->id]) }}">Add Content</a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Title:</strong>
                {{ $lesson->title }}
            </div>
            <div class="form-group mt-4">
                <strong>Contents:</strong>
                <ul>
                    @forelse($lesson->contents as $content)
                        <li>{{ $content->content }}</li>
                    @empty
                        <li>No contents available.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
