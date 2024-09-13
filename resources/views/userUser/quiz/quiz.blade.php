@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="row mb-3">
        <div class="col-lg-12">
            <h2>{{ $course->name }} - {{ $lesson->title }} - Quiz</h2>
        </div>
    </div>

    <div class="row justify-content-center mt-5  " style="overflow-y: auto;">
        <div class="col-lg-6 text-center">
            <div class="mb-5">
                <h2>Match the phrases.</h2>
            </div>



            <form method="POST" action="{{ route('quiz.submit', [$course->id, $lesson->id]) }}">
                @csrf

                @foreach($questionsWithOptions as $item)
                <div class="quiz-item mb-4" style="border: 1px solid #0d6efd; border-radius: 5px; padding: 15px;">
                    <p class="mb-4"><strong>{{ $item['question']->english }}</strong></p>





                    <div style="display: flex; flex-wrap: wrap; width: 100%; ">


                        @foreach($item['options'] as $option)
                        <div class="" style="width: 50%; box-sizing: border-box;">
                            <input type="radio" name="answers[{{ $item['question']->id }}]"
                                value="{{ $option->id }}"
                                id="option{{ $option->id }}-{{ $item['question']->id }}"
                                class="d-none"
                                onclick="selectOption(this)">

                            @if ($option->video)
                            <label class="btn btn-outline-primary playAudioButton" style="width: 95%;"
                                for="option{{ $option->id }}-{{ $item['question']->id }}">
                                {{ $option->text }}
                            </label>

                            <video class="videoElement vid-content" controls style="display: none;">
                                <source src="{{ $option->video }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>

                            <audio class="audioElement" style="display: none;">
                                <source src="{{ $option->video }}" type="audio/mp4">
                                Your browser does not support the audio tag.
                            </audio>
                            @else
                            No audio available
                            @endif
                        </div>
                        @endforeach


                    </div>
                </div>
                @endforeach

                <button type="submit" class="btn btn-primary mt-1">Submit Answers</button>
            </form>










        </div>
    </div>

    <script>
        function selectOption(radio) {
            var labels = document.querySelectorAll('input[name="answers[' + radio.name.split('[')[1].split(']')[0] + ']"] + label');
            labels.forEach(function(label) {
                label.classList.remove('selected');
            });
            radio.nextElementSibling.classList.add('selected');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const playAudioButtons = document.querySelectorAll('.playAudioButton');

            playAudioButtons.forEach(function(button, index) {
                button.addEventListener('click', function() {
                    // Hide all videos and audios except the current one
                    const videoElements = document.querySelectorAll('.videoElement');
                    const audioElements = document.querySelectorAll('.audioElement');

                    videoElements.forEach(function(video) {
                        video.style.display = 'none';
                    });

                    audioElements.forEach(function(audio) {
                        audio.style.display = 'none';
                        audio.pause(); // Pause any playing audio
                    });

                    const videoElement = videoElements[index];
                    const audioElement = audioElements[index];

                    videoElement.style.display = 'none'; // Hide the video
                    audioElement.style.display = 'block'; // Show the audio player
                    audioElement.controls = false; // Hide the audio controls
                    audioElement.play(); // Start playing the audio
                });
            });
        });
    </script>
</div>
@endsection