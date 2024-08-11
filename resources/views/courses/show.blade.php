@extends('layouts.app')

@section('content')

@endsection


<div class="main-container">

    <div class="container-fluid mb-2  ">
        <h1>Lessons - {{ $course->name }}</h1>

        <!-- change the set up -->

        @if(Auth::user()->usertype == 'admin')
        <div class="pull-right">
            <!-- <a class="btn btn-primary" href="{{ route('courses.index') }}">Back to Courses</a> -->
            <a class="btn btn-view-courses" href="{{ route('courses.lessons.create', $course->id) }}">Add Lesson</a>
        </div>
        @endif
    </div>
    <div class="container-fluid card-container">
        @foreach ($course->lessons as $lesson)

        <div class="cardsmall">

            <div class="img-cont">
                <img src="{{ asset('images/cebuano.png') }}" alt="Card Image">
            </div>

            <div class="heading">
                <h4>{{ $lesson->title }}</h4>
            </div>


            <div class="button-container">
                <a class="btn btn-view-courses" href="{{ route('courses.lessons.show', [$course->id, $lesson->id]) }}">Show</a>
                <a class="btn btn-2" href="{{ route('courses.lessons.edit', [$course->id, $lesson->id]) }}">Edit</a>
                <form action="{{ route('courses.lessons.destroy', [$course->id, $lesson->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-3">Delete</button>
                </form>
            </div>

        </div>
        @endforeach
    </div>

</div>