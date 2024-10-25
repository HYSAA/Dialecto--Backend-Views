@extends('layouts.app')

@section('content')

<div class="main-container" style="padding: 15px;">

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">
            <h1 class="mb-2">Select Course</h1>
            <div class="row">
                <!-- Loop through courses retrieved from Firebase -->
                @foreach ($courses as $courseId => $course)
                <div class="card mb-2 mr-2" style="overflow-y: auto;">
                    <div class="top">
                        <td>
                            <!-- Check for the course image from Firebase -->
                            @if(isset($course['image']))
    <img src="{{ $course['image'] }}" alt="Course Image" class="card-img">
@else
    <img src="{{ asset('images/cebuano.png') }}" alt="Course Image" class="card-img">
@endif

                        </td>
                        <div class="row align-items-center mt-3 mb-3 " style="height: 50px;">
                            <div class="col-6 d-flex align-items-center ">
                                <h3 class="card-title mb-0">{{ $course['name'] }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <!-- Loop through lessons under the current course -->
                        @if (isset($course['lessons']))
                            @foreach ($course['lessons'] as $lessonId => $lesson)
                            <a class="btn btn-main pull-right " style="margin:5px" href="{{ route('user.addUserSuggestedWord', ['courseId' => $courseId, 'lessonId' => $lessonId]) }}">
                                {{ $lesson['title'] }}
                            </a>
                            @endforeach
                        @else
                            <p>No lessons available for this course.</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
