@extends('layouts.app')

@section('content')
<div class="main-container">
    <br><br>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $courseName }} - {{ $lessonTitle }} - Contents</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('courses.lessons.show', [$courseId, $lessonId]) }}">Back To Lesson</a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <b class="fs-1" style="font-size: 50px;">{{ $content['text'] }}</b>
        <h2 class="mt-3">Translation:</h2>
        <h5>{{ $content['english'] }}</h5>
        @if ($content['image'])
            <img src="{{ $content['image'] }}" class="img-fluid mt-2" alt="Image">
        @endif
        @if ($content['video'])
            <video width="300px" controls>
                <source src="{{ $content['video'] }}" type="video/mp4">
            </video>
        @endif
    </div>
</div>
@endsection
