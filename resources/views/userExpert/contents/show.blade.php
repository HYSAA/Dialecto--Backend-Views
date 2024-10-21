@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course['name'] }} - {{ $lesson['title'] }} - Contents</h2>

            </div>
            <div class="pull-right">

                <a class="btn btn-main" href="{{ route('expert.courses.show', $courseId) }}">Back To Lesson</a>

            </div>
        </div>
    </div>

    <!-- <div class=" row mt-2">
                    <div class="col-lg-6 " style="padding-left: 20px;">
                        <h6>Word Counter</h6>
                    </div>
            </div> -->

    <div class="row justify-content-center mt-5">
        <div class="col-lg-12 box-container">

            <div class="box">
                @if (!empty($content['video']))
                <video controls class="vid-content">
                    <source src="{{ $content['video'] }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                @else
                <p>No video available</p>
                @endif
            </div>

            <div class="box-2 mt-3">
                <div class="row">
                    <h2>English</h2>
                </div>
                <div class="row">
                    <h5>{{ $content['english'] }}</h5> <!-- English text -->
                </div>
                <div class="row" style="margin-top: 30px;">
                    <h2>{{ $course['name'] }}</h2> <!-- Course name -->
                </div>
                <div class="row">
                    <h5>{{ $content['text'] }}</h5> <!-- Main text -->
                </div>

                <hr style="border: 1px solid #90949C; margin: 2rem 0;">

                <div class="row" style="margin-top: 50px;">
                    @if (!empty($content['video']))
                    <button class="btn btn-main" id="playAudioButton">Play Audio</button>

                    <audio id="audioElement" controls style="display: none;">
                        <source src="{{ $content['video'] }}" type="audio/mp4">
                        Your browser does not support the audio tag.
                    </audio>
                    @else
                    <p>No audio available</p>
                    @endif
                </div>
            </div>

            <div class="box-2">
                <div class="row">
                    @if($nextContent)
                    <a class="btn btn-main" style="width: 100%; margin-bottom: 5px;" type="button" href="{{ route('expert.contents.show', [$courseId, $lessonId, $nextContent['id']]) }}">Next Word</a>
                    @else
                    <a class="btn btn-main" style="width: 100%; margin-bottom: 5px;" type="button" href="{{ route('expert.quiz.show', [$courseId, $lessonId]) }}">Take Quiz</a>
                    @endif
                </div>

                <div class="row">
                    @if($previousContent)
                    <a class="btn btn-back-main" style="width: 100%; margin-bottom: 5px;" type="button" href="{{ route('expert.contents.show', [$courseId, $lessonId, $previousContent['id']]) }}">Previous Word</a>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const playAudioButton = document.getElementById('playAudioButton');
            const audioElement = document.getElementById('audioElement');

            if (playAudioButton && audioElement) {
                playAudioButton.addEventListener('click', function() {
                    // Toggle audio playback
                    if (audioElement.style.display === 'none' || !audioElement.style.display) {
                        audioElement.style.display = 'none'; // Show the audio player
                        audioElement.play(); // Start playing the audio
                    } else {
                        audioElement.pause(); // Pause the audio
                        audioElement.style.display = 'none'; // Hide the audio player
                    }
                });
            }
        });
    </script>


</div>
@endsection