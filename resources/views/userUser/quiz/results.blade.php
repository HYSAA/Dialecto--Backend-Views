@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">

            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10 margin-tb text-center" style="padding-top: 70px;">
            <h1>Finished quiz on</h1>
            <div class="container">
                <h1>Quiz Results</h1>





                <p>Total Questions: {{ $totalQuestions }}</p>
                <p>Total score for this quiz: {{ $totalScore }}</p>
                <p>Congratulations!! You gather a total score of {{ $score }}</p>



            </div>



        </div>
    </div>

</div>

@endsection