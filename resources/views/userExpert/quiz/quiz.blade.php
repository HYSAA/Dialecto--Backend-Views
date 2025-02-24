@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="row mb-3">
        <div class="col-lg-12">
            <h2>{{ $courseName }} - {{ $lessonName }} - Quiz</h2>
        </div>
    </div>

    <div class="row justify-content-center mt-5  " style="overflow-y: auto;">
        <div class="col-lg-6 text-center">
            <div class="mb-5">
                <h2>Match the phrases.</h2>
            </div>


            <h1>Question {{ $currentIndex + 1 }} of {{ count($questions) }}</h1>
            <p>{{ $currentQuestion['question'] }}</p>


            <!-- paste here -->

            <form action="{{ route('expert.question.submit', [$courseId, $lessonId]) }}" method="POST">
                @csrf

                <div class="choices-grid">
                    @foreach ($currentQuestion['choices'] as $index => $choice)
                    <div class="choice-item play-audio-button" type="button" data-audio-index="{{ $index }}" data-choice-value="{{ $choice['text'] }}">
                        <label>{{ $choice['text'] }}</label>




                        <audio id="audioElement-{{ $index }}" style="display: none;">
                            <source src="{{ $choice['audioRef'] }}" type="audio/mp4">
                            Your browser does not support the audio tag.
                        </audio>





                    </div>
                    @endforeach
                </div>

                <input type="hidden" name="answer" id="selectedChoice" value="">
                <button type="submit" class="btn btn-submit">Submit</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const playAudioButtons = document.querySelectorAll('.play-audio-button');
            const choiceItems = document.querySelectorAll('.choice-item');
            const selectedChoiceInput = document.getElementById('selectedChoice');

            // Handle audio playback
            playAudioButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.stopPropagation(); // Prevent triggering choice selection
                    const audioIndex = button.getAttribute('data-audio-index');
                    const audioElement = document.getElementById(`audioElement-${audioIndex}`);

                    if (audioElement) {
                        if (audioElement.paused) {
                            audioElement.style.display = 'none';
                            audioElement.play();
                        } else {
                            audioElement.pause();
                            audioElement.currentTime = 0;
                        }
                    }
                });
            });

            // Handle choice selection
            choiceItems.forEach(item => {
                item.addEventListener('click', function() {
                    choiceItems.forEach(i => i.classList.remove('selected')); // Remove 'selected' from all items
                    item.classList.add('selected'); // Mark clicked item as selected
                    selectedChoiceInput.value = item.getAttribute('data-choice-value'); // Update hidden input value
                });
            });
        });
    </script>



</div>

</div> <!-- Closing your last div -->

<style>
    .choices-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        /* Two columns */
        gap: 15px;
        /* Space between grid items */
    }

    .choice-item {
        padding: 20px;
        border: 2px solid #ccc;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .choice-item:hover {
        background-color: #f0f8ff;
        border-color: #007bff;
    }

    .choice-item.selected {
        background-color: #007bff;
        color: #fff;
        border-color: #0056b3;
    }
</style>

@endsection