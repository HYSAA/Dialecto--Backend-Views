@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row">
        <div class="col-lg-12">

            <div class="pull-left mb-2">
                <h2>Profile </h2>
            </div>


            <div class="pull-right mb-2">

                <span class="btn " style="background-color: #d4edda; color: #155724;border-color: #c3e6cb; box-sizing: border-box; border-radius: 4px; text-align: center; text-decoration: none; box-shadow: none;">
                    Verified Expert for {{$languageExperty}}</span>

            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">

            <div class="form-group">
                <strong>Name:</strong><span> {{ $user['name'] ?? 'N/A' }}</span>

            </div>

        </div>
        <div class="col-lg-12 margin-tb">

            <div class="form-group">

                <strong>Email:</strong><span> {{ $user['email'] ?? 'N/A' }}</span>
            </div>

        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="pull-left mb-2">
                <strong>My badges</strong>
            </div>
        </div>
    </div>


    @if($quizResults)




    @foreach($courses as $courseId => $courseData)
    <!-- Flag to track if there are any matching results -->
    @php $hasMatchingResults = false; @endphp

    <!-- Iterate over quizResults to find matching courseId -->
    @foreach($quizResults as $resultId => $resultData)
    @if($courseId == $resultData['course']) <!-- Compare courseId with courseId in resultData -->
    @php $hasMatchingResults = true; @endphp
    @break <!-- Exit the loop once we find a match, no need to continue checking -->
    @endif
    @endforeach

    <!-- If there is a match, create the row and display the course name -->
    @if($hasMatchingResults)
    <div class="row">
        <div class="col-lg-12">
            <div class="pull-left mb-2">
                <h2>{{ $courseData['name'] }}</h2>
            </div>
        </div>

        <!-- Display the cards for this courseId -->
        @foreach($quizResults as $resultId => $resultData)
        @if($courseId == $resultData['course']) <!-- Compare courseId with courseId in resultData -->
        <div class="col-md-3 mb-3"> <!-- Each card occupies 3 columns in medium screens and larger, with a 10px gap -->
            <div class="card" style="height: 400px; background-color: #333333;">
                <!-- Image with consistent size -->
                <img src="{{ $resultData['badge-image'] }}" class="card-img-top" alt="Course Image" style="object-fit: cover; height: 200px; margin-top: 20px;">
                <div class="card-body" style="background-color: #333333;">
                    <!-- Card details -->
                    <Strong class="card-text" style="color: white;">{{ $resultData['lesson-name'] }}</Strong>
                    <p class="card-text" style="color: white;">Badge: {{ $resultData['badge'] }}</p>
                    <p class="card-text" style="color: white;">Score: {{ $resultData['score'] }}</p>
                    <p class="card-text" style="color: white;">Total quiz score: {{ $resultData['total-score'] }}</p>

                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif
    @endforeach
    @else
    <!-- If $quizResults is null or empty -->
    <div class="col-lg-12">
        <div class="pull-left mb-2">

            <strong>No badges to show yet. Finish a lesson and take a quiz to show badges.</strong>
        </div>
    </div>
    @endif




</div>












<div class="row">
    <div class="col-lg-12">
        <div class="pull-left">
            <h2>Badges </h2>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        here goes the badges
    </div>
</div>








</div>


@endsection