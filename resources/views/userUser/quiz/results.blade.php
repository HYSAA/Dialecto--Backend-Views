@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - {{ $lesson->title }} - Results</h2>
            </div>

        </div>
    </div>



    <div class="row  justify-content-center">
        <div class="col-lg-8 margin-tb  text-center " style="padding-top: 70px;">

            <h1>Finished quiz on</h1>
            <h2>{{$lesson->title}}</h2>
            <h4 style="color: #90949C;">Completed {{ $score }} questions</h4>
            <h4 style="color: #90949C;">Total Score: {{ $score }}</h4>

            <div class="row  justify-content-center">


                <div class="col-lg-5 margin-tb  pt-4 d-flex flex-column">
                    <a class="btn btn-back-main mb-2" href="{{ route('user.courses.index') }}">Go Back to Courses</a>
                    <a class="btn btn-main" href="{{ route('user.quiz.show', [$course->id, $lesson->id]) }}">Retake Quiz</a>


                </div>
            </div>





        </div>
    </div>

</div>
@endsection