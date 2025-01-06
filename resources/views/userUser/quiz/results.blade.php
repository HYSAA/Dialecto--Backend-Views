@extends('layouts.app')

@section('content')
<div class="main-container" style="overflow-y: auto;">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">

            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-4 margin-tb text-center " style="padding-top: 70px;">
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

        <div class="col-lg-4 margin-tb text-center " style="padding-top: 70px; display: flex; justify-content: center; ">

            <!-- Each card occupies 3 columns in medium screens and larger, with a 10px gap -->
            <div class="card" style="height: 400px; background-color: #333333;">
                <!-- Image with consistent size -->
                <img src="{{ $quizData['badge-image'] }}" class="card-img-top" alt="Course Image" style="object-fit: cover; height: 200px; margin-top: 20px;">
                <div class="card-body" style="background-color: #333333;">
                    <!-- Card details -->
                    <Strong class="card-text" style="color: white;">{{ $quizData['lesson-name'] }}</Strong>
                    <p class="card-text" style="color: white;">Badge: {{ $quizData['badge'] }}</p>
                    <p class="card-text" style="color: white;">Score: {{ $quizData['score'] }}</p>
                    <p class="card-text" style="color: white;">Total quiz score: {{ $quizData['total-score'] }}</p>

                </div>

            </div>


        </div>
    </div>

</div>

@endsection