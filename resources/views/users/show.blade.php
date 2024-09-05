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

                    <h5>Lesson's Progress:</h5>

                        <!-- @foreach($userProgress as $progress)
                            @foreach($courses as $course)
                                <h1>{{$course->name}}</h1>
                                @foreach($course->lessons as $lesson)
                                    @if($lesson->id == $progress->lesson_id)
                                        <p>{{ $lesson->title }} =  {{ $progress->count }} / {{ $lesson->contents_count }}</p>
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach -->
                        <!-- @foreach($courses as $course)
                            <h1>{{ $course->name }}</h1>
                            @foreach($userProgress as $progress)
                                @foreach($course->lessons as $lesson)
                                    @if($lesson->id == $progress->lesson_id)
                                        <p>{{ $lesson->title }} = {{ $progress->count }} / {{ $lesson->contents_count }}</p>
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach -->
                        @foreach($courses as $course)
                            @php
                                $hasProgress = false;
                                foreach ($userProgress as $progress) {
                                    foreach ($course->lessons as $lesson) {
                                        if ($lesson->id == $progress->lesson_id) {
                                            $hasProgress = true;
                                            break 2;
                                        }
                                    }
                                }
                            @endphp

                            @if($hasProgress)
                                <h4>{{ $course->name }}</h4>
                                @foreach($userProgress as $progress)
                                    @foreach($course->lessons as $lesson)
                                        @if($lesson->id == $progress->lesson_id)
                                            <p>{{ $lesson->title }} = {{ $progress->count }} / {{ $lesson->contents_count }}</p> <!-- Display lesson progress -->
                                        @endif
                                    @endforeach
                                @endforeach
                            @endif
                        @endforeach


                    </div>
                </div>
                <!-- @foreach ($courses as $course)
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
                            <h5>User Scores:</h5>
                            <div>
                                
                            @foreach($course->lessons as $lesson)
                            <p>{{$lesson->title}}</p>
                            @endforeach
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div> -->


    @endsection