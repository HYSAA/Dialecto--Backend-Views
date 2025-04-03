@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">

            </div>
        </div>
    </div>

    <div class="row justify-content-center" style="overflow-y: auto;">
        <div class="col-lg-4 margin-tb text-center " style="padding-top: 70px;">
            <h1>Finished quiz on</h1>
            <div class="container ">
                <h1>Quiz Results</h1>

                <p>Total Questions: {{ $totalQuestions }}</p>
                <p>Total score for this quiz: {{ $totalScore }}</p>
                <p>Congratulations!! You gather a total score of {{ $score }}</p>

                <br>

                <strong>Here's a history of your quiz</strong>
                <br>
                @foreach($quizHistory as $index => $quiz)

                <div style="border: gray solid; width: 100%; border-radius: 10px; margin-bottom: 10px; padding-bottom: 10px;">



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

                </div>

                @endforeach


            </div>

        </div>

        <div class="col-lg-4 margin-tb text-center " style="height: 100%; padding-top: 70px; display: flex; justify-content: center; ">

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



    @if (isset($congratulationsMessage) && $congratulationsMessage)
    <!-- Modal Structure -->
    <div id="congrats-modal" class="modal">
        <div class="modal-content">
            <h2>{{ $congratulationsMessage }}</h2>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        // Display the modal
        document.getElementById('congrats-modal').style.display = 'flex';

        function closeModal() {
            document.getElementById('congrats-modal').style.display = 'none';
        }
    </script>

    <style>
        /* Modal styling */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Dark background */
            display: none;
            /* Hidden by default */
            align-items: center;
            /* Center vertically */
            justify-content: center;
            /* Center horizontally */
            z-index: 1000;
            /* Ensure it appears above everything */
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            /* Limit width */
            width: 90%;
            /* Responsive width */
        }

        .modal-content h2 {
            margin-bottom: 20px;
        }

        .modal-content button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal-content button:hover {
            background: #0056b3;
        }
    </style>

    @endif

</div>

@endsection