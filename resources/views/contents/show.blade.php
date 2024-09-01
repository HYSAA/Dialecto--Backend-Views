@extends('layouts.app')

@section('content')
<div class="main-container">
    <br><br>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - {{ $lesson->title }} - Contents</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('admin.lessons.show', [$course->id, $lesson->id]) }}">Back To Lesson</a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <b class="fs-1" style="font-size: 50px">{{ $content->text }}</b>
    </div>

    <div class="row justify-content-center">
        <p>English Equivalent</p>
        <i class="fs-1" style="font-size: 40px">{{ $content->english }}</i>
    </div>

    <div class="row justify-content-center">
        @if ($content->video)
            <video width="500px" controls>
                <source src="{{ $content->video }}" type="video/mp4">
            </video>
        @endif
    </div>

    <!-- mugawas ra ang proceed button if naay next content-->
    <div class="row justify-content-center">
    @if($previousContent)
                    <a class="btn btn-primary" type="button" href="{{ route('user.contents.show', [$course->id, $lesson->id, $previousContent->id]) }}">Back</a>
                @endif
        @if($nextContent)
            <a class="btn btn-primary" type="button" href="{{ route('user.contents.show', [$course->id, $lesson->id, $nextContent->id]) }}">Proceed</a>
        @endif
    </div>
</div>
@endsection
