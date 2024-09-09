@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - {{ $lesson->title }} - Contents</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-main" href="{{ route('expert.courses.show', $course->id) }}">Back To Lesson</a>

            </div>
        </div>
    </div>

    <!-- <div class=" row mt-2">
                    <div class="col-lg-6 " style="padding-left: 20px;">
                        <h6>Word Counter</h6>
                    </div>
            </div> -->

    <div class="row  justify-content-center mt-5">
        <div class="col-lg-12 box-container ">

            <div class="box">
                @if ($content->video)
                <video controls class="vid-content">
                    <source src="{{ $content->video }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                @else
                No video available
                @endif
            </div>




            <div class="box-2 mt-3">
                <div class="row ">
                    <h2>English</h2> <!-- Using h2 for larger text -->
                </div>
                <div class="row ">
                    <h5>{{ $content->english }}</h5> <!-- Using h2 for larger text -->
                </div>
                <div class="row " style="margin-top: 30px;">
                    <h2>{{ $course->name }}</h2> <!-- Using h4 for smaller text -->
                </div>
                <div class="row ">
                    <h5>{{ $content->text }}</h5> <!-- Using h4 for smaller text -->
                </div>

                <hr style="border: 1px solid #90949C; margin: 2rem 0;">

                <div class="row " style="margin-top: 50px;">

                    @if ($content->video)
                    <button class="btn btn-main" id="playAudioButton">Play Audio</button>

                    <video id="videoElement" controls class="vid-content" style="display: none;">
                        <source src="{{ $content->video }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>






                    <audio id="audioElement" controls style="display: none;">
                        <source src="{{ $content->video }}" type="audio/mp4">
                        Your browser does not support the audio tag.
                    </audio>

                    @else
                    No audio available
                    @endif

                </div>

            </div>


            <div class="box-2">
                <div class="row">
                    @if($nextContent)
                    <a class="btn btn-main" style="width: 100%; margin-bottom: 5px;" type="button" href="{{ route('expert.contents.show', [$course->id, $lesson->id, $nextContent->id]) }}">Next Word</a>


                    @else

                    <a class="btn btn-main" style="width: 100%; margin-bottom: 5px;" type="button" href="{{ route('expert.quiz.show', [$course->id, $lesson->id]) }}">Take Quiz</a>

                    <!-- <a class="btn btn-main" style="width: 100%; margin-bottom: 5px;" type="button" href="{{ route('user.multipleChoice.show', [$course->id, $lesson->id]) }}">Take Quiz Multiple Choice</a> -->



                    @endif


                </div>

                <div class="row">
                    @if($previousContent)
                    <a class="btn btn-back-main" style="width: 100%; margin-bottom: 5px;" type="button" href="{{ route('expert.contents.show', [$course->id, $lesson->id, $previousContent->id]) }}">Previous Word</a>

                    @endif
                </div>



            </div>

        </div>
    </div>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const playAudioButton = document.getElementById('playAudioButton');
            const videoElement = document.getElementById('videoElement');
            const audioElement = document.getElementById('audioElement');

            playAudioButton.addEventListener('click', function() {
                videoElement.style.display = 'none'; // Hide the video
                audioElement.style.display = 'block'; // Show the audio player
                audioElement.controls = false; // Hide the audio controls
                audioElement.play(); // Start playing the audio
            });
        });
    </script>


</div>
@endsection