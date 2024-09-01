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



    <div class="row">
        <div class="container">

            <p>Your score for this quiz is {{ $score }}</p>

        </div>
    </div>

</div>
@endsection