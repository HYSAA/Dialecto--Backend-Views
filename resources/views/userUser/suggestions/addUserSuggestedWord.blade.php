@extends('layouts.app')

@section('content')

<div class="main-container" style="padding: 15px;">
    <div class="pull-right" style="padding:15px;">
        <a class="btn btn-main" href="{{ route('user.selectUserCourseLesson') }}">Back</a>
    </div>

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <div class="row justify-content-center" style="width: 100%;">

                <div class="card mb-2 mr-2" style="padding:15px;">
                    <div style="background:white;border-radius: 10px;padding:15px;height:100%;">
                        <h4 class="mb-2">Current content in {{ $course->name }}</h4>

                        @foreach ($lesson->contents as $content)
                            <li>{{ $content->text }} - {{ $content->english }}</li>
                        @endforeach
                    </div>
                </div>

                <div class="card mb-2 mr-2" style="padding:15px;">
                    <form action="{{ route('user.submitWordSuggested', [$course->id, $lesson->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">

                        <div class="col-xs-12 col-sm-12 col-md-12" style="width:100%">
                            <div>
                                <strong for="video">Video:</strong><br>
                                <input type="file" name="video" class="form-control form-control-lg">
                            </div>

                            <div style="padding-top:15px;">
                                <strong for="text" class="form-group">{{ $course->name }} text:</strong><br>
                                <input type="text" name="text" id="text" required class="form-control form-control-lg">
                            </div>

                            <div style="padding-top:15px;">
                                <strong for="english">English text:</strong><br>
                                <input type="text" name="english" id="english" required class="form-control form-control-lg">
                            </div>

                            <div style="padding-top:15px;">
                                <button class="btn btn-back-main" style="margin-left:35%;" type="submit">Submit</button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection
