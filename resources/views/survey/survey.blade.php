@extends('layouts.app')

@section('content')
<div class="main-container">
<!-- Full-Screen Modal -->
<div id="surveyModal" class="modal fade show" tabindex="-1" aria-hidden="true" style="display: block; background: rgba(0, 0, 0, 0.5); z-index: 1050;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Language Learning Survey</h2>
            </div>
            <div class="modal-body">
                <form action="{{ route('survey.submit') }}" method="POST">
                    @csrf
                    <label>1. How familiar are you with languages?</label><br>
                    <input type="radio" name="familiarity" value="beginner" required> I'm new
                    <input type="radio" name="familiarity" value="intermediate"> Heard from others
                    <input type="radio" name="familiarity" value="advanced"> Can speak with confidence
                    <br><br>

                    <label>2. How much experience do you have with language-learning apps?</label><br>
                    <input type="radio" name="language_experience" value="beginner" required> Beginner
                    <input type="radio" name="language_experience" value="intermediate"> Intermediate
                    <input type="radio" name="language_experience" value="advanced"> Advanced
                    <br><br>

                    <label>3. What is your biggest challenge in learning a new language?</label><br>
                    <input type="radio" name="learning_challenge" value="grammar" required> Grammar
                    <input type="radio" name="learning_challenge" value="vocabulary" required> Vocabulary
                    <input type="radio" name="learning_challenge" value="pronunciation" required> Pronunciation
                    <input type="radio" name="learning_challenge" value="speaking_fluency" required> Speaking Fluency
                    <br><br>

                    <label>4. Which of the following learning resources do you use most frequently?</label><br>
                    <input type="radio" name="learning_resource" value="textbooks" required> Textbooks
                    <input type="radio" name="learning_resource" value="online_courses" required> Online courses
                    <input type="radio" name="learning_resource" value="language_exchange" required> Language exchange partners
                    <input type="radio" name="learning_resource" value="language_apps" required> Language apps
                    <br><br>

                    <label>5. How motivated are you to learn a new language?</label><br>
                    <input type="radio" name="motivation_level" value="1" required> Not at all
                    <input type="radio" name="motivation_level" value="2" required> Somewhat
                    <input type="radio" name="motivation_level" value="3" required> Very
                    <br><br>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
