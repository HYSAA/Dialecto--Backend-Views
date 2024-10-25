@extends('layouts.app')

@section('content')

<div class="main-container" style="padding: 15px;">
    <div class="pull-right" style="padding:15px;">
        <a class="btn btn-main" href="{{ route('user.selectUserCourseLesson') }}">Back</a>
    </div>

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <div class="row justify-content-center" style="width: 100%;">

                <!-- Display current content in the selected course -->
                <div class="card mb-2 mr-2" style="padding:15px;">
                    <div style="background:white;border-radius: 10px;padding:15px;height:100%;">
                        <h4 class="mb-2">Current content in {{ $course['name'] }}</h4>

                        <!-- Ensure contents are displayed from Firebase -->
                        @if (isset($lesson['contents']) && count($lesson['contents']) > 0)
                            <ul>
                                @foreach ($lesson['contents'] as $contentId => $content)
                                    <li>{{ $content['text'] }} - {{ $content['english'] }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>No contents found for this lesson.</p>
                        @endif
                    </div>
                </div>

                <!-- Form for adding a new suggested word -->
                <div class="card mb-2 mr-2" style="padding:15px;">
                <form action="{{ route('user.submitWordSuggested', [$courseId, $lessonId]) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Hidden fields for user, course, and lesson IDs -->
    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    <input type="hidden" name="course_id" value="{{ $courseId }}">
    <input type="hidden" name="lesson_id" value="{{ $lessonId }}">

    <!-- Video upload input -->
    <div class="col-xs-12 col-sm-12 col-md-12" style="width:100%">
        <div>
            <strong for="video">Video:</strong><br>
            <input type="file" name="video" class="form-control form-control-lg">
        </div>

        <!-- Input for the course-specific word -->
        <div style="padding-top:15px;">
            <strong for="text" class="form-group">{{ $course['name'] }} text:</strong><br>
            <input type="text" name="text" id="text" required class="form-control form-control-lg">
        </div>

        <!-- Input for the English translation -->
        <div style="padding-top:15px;">
            <strong for="english">English text:</strong><br>
            <input type="text" name="english" id="english" required class="form-control form-control-lg">
        </div>

        <!-- Submit button -->
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
