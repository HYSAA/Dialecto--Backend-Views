@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row">
<<<<<<< HEAD
        <div class="col-lg-6 addborder">
            <h2>{{$lesson->title}}</h2>
=======
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - {{ $lesson->title }} - Contents</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('admin.lessons.show', [$course->id, $lesson->id]) }}">Back To Lesson</a>
            </div>
>>>>>>> 96c328122c16b7c1c4a4c58aaae5c4f191cf150b
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 addborder">
            <h6>Word Counter</h6>
        </div>
    </div>


    <div class="row addborder">
        <div class="col-lg-12 box-container addborder">
            <div class="box">
                @if ($content->video)
                <video controls class="vid-thumbnail">
                    <source src="{{ $content->video }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                @else
                No video available
                @endif
            </div>
            <div class="box">box 2</div>
            <div class="box">box 3</div>
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
<<<<<<< HEAD
        @if($previousContent)
        <a class="btn btn-primary" type="button" href="{{ route('courses.lessons.contents.show', [$course->id, $lesson->id, $previousContent->id]) }}">Back</a>
        @endif
        @if($nextContent)
        <a class="btn btn-primary" type="button" href="{{ route('courses.lessons.contents.show', [$course->id, $lesson->id, $nextContent->id]) }}">Proceed</a>
=======
    @if($previousContent)
                    <a class="btn btn-primary" type="button" href="{{ route('user.contents.show', [$course->id, $lesson->id, $previousContent->id]) }}">Back</a>
                @endif
        @if($nextContent)
            <a class="btn btn-primary" type="button" href="{{ route('user.contents.show', [$course->id, $lesson->id, $nextContent->id]) }}">Proceed</a>
>>>>>>> 96c328122c16b7c1c4a4c58aaae5c4f191cf150b
        @endif
    </div>
</div>
@endsection