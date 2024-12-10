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

                <br>



                <ul>
                    <strong>Here's a history of your quiz</strong>
                    <br>
                    @foreach($quizHistory as $index => $quiz)
                    <li>

                        <br>

                        <p>Question number: {{ $index + 1 }}</p> <!-- Incrementing the question number -->

                        @if($quiz['Remarks'] == 1)

                        <strong>Question:</strong> {{$quiz['Question']}}.<br>
                        <strong>Your answer:</strong> {{$quiz['Answer']}}.<br>
                        <strong>Correct answer:</strong> {{$quiz['Answer']}}.<br>
                        <strong style="color: black;">Remarks:</strong> <span style="color: green;">Correct</span>.<br>




                        @else

                        <strong>Question:</strong> {{$quiz['Question']}}.<br>
                        <strong>Your answer:</strong> {{$quiz['Answer']}}.<br>
                        <strong>Correct answer:</strong> {{$quiz['CorrectAnswer']}}.<br>
                        <strong style="color: black;">Remarks:</strong> <span style="color: red;">Wrong</span>.<br>






                        @endif
                    </li>
                    @endforeach

                </ul>







            </div>



        </div>
    </div>

</div>

@endsection