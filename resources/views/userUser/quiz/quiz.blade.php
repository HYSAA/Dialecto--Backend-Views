@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="row mb-3">
        <div class="col-lg-12">
            <h2>{{ $courseName }} - {{ $lessonName }} - Quiz</h2>
        </div>
    </div>

    <div class="row justify-content-center mt-5  " style="overflow-y: auto;">


        <div class="progress-container">
            <div class="progress-bar" id="progressBar"
                style="width: {{ ($currentIndex + 1) / count($questions) * 100 }}%;">
            </div>
        </div>


        <div class="col-lg-8 text-center pb-5 pt-5 " style=" border-radius: 10px;">
            <div class="mb-2">
                <span style="font-size: 50px;">Match the phrases</span>
            </div>


            <span style="font-size: 20px;">Question {{ $currentIndex + 1 }} of {{ count($questions) }}</span> <br>
            <span style="font-size: 30px;" class="mb-5">{{ $currentQuestion['question'] }}</span>


            <!-- paste here -->

            <form action="{{ route('user.question.submit', [$courseId, $lessonId]) }}" method="POST">
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
                <button type="submit" class="choice-item2" style="margin-top: 10px;">Submit</button>


            </form>


        </div>
    </div>

    `<script>
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

    .choice-item2 {
        padding: 15px;

        /* padding-left: 50px;
        padding-right: 50px; */
        /* background-color: #FFCA58; */
        /* box-shadow: 5px 5px 0px #CB9219; */



        width: 40%;

        border: 2px solid #ccc;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .choice-item2:hover {
        background-color: #CB9219;
        border-color: #FFCA58;
        color: white;
    }

    .choice-item2.selected {
        background-color: #007bff;
        color: #fff;
        border-color: #0056b3;
    }

    .progress-container {
        width: 80%;
        height: 20px;
        background-color: #e0e0e0;
        border-radius: 300px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #FFCA58, #CB9219);
        transition: width 0.8s ease-in-out;
        border-radius: 6px;
        position: relative;
    }

    /* Bounce Animation */
    @keyframes bounceProgress {
        0% {
            transform: scaleX(1);
        }

        50% {
            transform: scaleX(1.02);
        }

        100% {
            transform: scaleX(1);
        }
    }

    .progress-bar.animated {
        animation: bounceProgress 0.4s ease-out;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const progressBar = document.getElementById('progressBar');

        if (progressBar) {
            progressBar.classList.add('animated');
            setTimeout(() => {
                progressBar.classList.remove('animated'); // Remove after animation
            }, 400);
        }
    });
</script>

@endsection