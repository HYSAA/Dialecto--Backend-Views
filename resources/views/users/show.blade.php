@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row">
        <div class="col-lg-12 ">
            <h2> Current Progression of {{$user->name}} </h2>
        </div>
    </div>


    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">
            <div class="row">
                <div class="card mb-2 mr-2" style="padding: 10px;">
                    <!-- emptycell for now -->
                     <!-- much better if it also say which course it would be -->
                    <div class="top"  style="padding: 5px;">

                    <h5>User Lesson Progress:</h5>
                    @foreach($userProgress as $progress)
                        @foreach($courses as $course)
                            @foreach($course->lessons as $lesson)
                                @if($lesson->id == $progress->lesson_id)
                                    <p>{{ $lesson->title }} =  {{ $progress->count }} / {{ $lesson->contents_count }}</p>
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
                    </div>
                </div>
                @foreach ($courses as $course)
                    <div class="card mb-2 mr-2">
                        <div class="top">

                            <td>
                                @if($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image" class="card-img">
                                @else
                                    <img src="{{ asset('images/cebuano.png') }}" alt="Course Image" class="card-img">
                                @endif
                            </td>
                            <div class="row align-items-center mt-3 mb-3 " style="height: 50px;">
                                <div class="col-6 d-flex align-items-center ">
                                    <h3 class="card-title mb-0">{{ $course->name }}</h3>
                                </div>

                                <div class="col-6 d-flex justify-content-end ">
                                    <!-- @foreach ($course->lessons as $lesson)
                                                                    <div class="lesson-info">
                                                                        <p>{{$lesson->name}}: {{ $lesson->contents_count }}</p>
                                                                    </div>
                                                                @endforeach -->
                                    <!-- @foreach($course->lessons as $lesson)
                                                                <div class="lesson">
                                                                    <h3>{{ $lesson->title }}</h3>
                                                                    <p>Contents in this Lesson: {{ $lesson->contents_count }}</p>
                                                                </div>
                                                            @endforeach -->

                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <h5>Course Percentage Completed</h5>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="50%"
                                    aria-valuemin="0" aria-valuemax="100">
                                    Current Completion %
                                </div>
                            </div>
                            <!-- {{$userProgress}} -->
                            <h5>User Scores:</h5>
                            <!-- @foreach($userProgress as $progress)
                                                            <p>Lesson ID: {{ $progress->lesson_id }} = {{ $progress->count }}</p>
                                                        @endforeach -->
                            <!-- @foreach($userProgress as $progress)

                                                            @foreach ($course->lessons as $lessons)
                                                            <p>{{ $lesson->title }} = {{ $lesson->contents_count }}</p>
                                                            @endforeach 
                                                            /{{ $progress->count }}
                                                        @endforeach -->

                            <!-- @foreach($userProgress as $progress)
                                @foreach($courses as $course)
                                    @foreach($course->lessons as $lesson)
                                        @if($lesson->id == $progress->lesson_id)
                                            <p>{{ $lesson->title }} = {{ $lesson->contents_count }} / {{ $progress->count }}</p>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach -->

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    @endsection