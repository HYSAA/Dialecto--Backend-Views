
@extends('layouts.app')

@section('content')

 <div class="main-container">
    <!-- Multi-Page Survey Modal -->
    <div id="surveyModal" class="modal fade show" tabindex="-1" aria-hidden="true" style="display: block; background: rgba(0, 0, 0, 0.7); z-index: 1050;">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 80%;">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h2 class="modal-title">Language Learning Survey</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('survey.submit') }}" method="POST" id="multiPageSurvey">
                        @csrf

                        <!-- Survey Pages -->
                        <div class="survey-progress mb-3">
                            
                        <div class="survey-page" data-page="1">
                            <label class="form-label fw-bold">1. How familiar are you with languages?</label>
                            <div class="form-check">
                                <input type="radio" id="familiarity-beginner" name="familiarity" value="beginner" class="form-check-input" required>
                                <label class="form-check-label" for="familiarity-beginner">I'm new</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="familiarity-intermediate" name="familiarity" value="intermediate" class="form-check-input">
                                <label class="form-check-label" for="familiarity-intermediate">Heard from others</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="familiarity-advanced" name="familiarity" value="advanced" class="form-check-input">
                                <label class="form-check-label" for="familiarity-advanced">Can speak with confidence</label>
                            </div>
                        </div>

                        <div class="survey-page d-none" data-page="2">
                            <label class="form-label fw-bold">2. How much experience do you have with language-learning apps?</label>
                            <div class="form-check">
                                <input type="radio" id="experience-beginner" name="language_experience" value="beginner" class="form-check-input" required>
                                <label class="form-check-label" for="experience-beginner">Beginner</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="experience-intermediate" name="language_experience" value="intermediate" class="form-check-input">
                                <label class="form-check-label" for="experience-intermediate">Intermediate</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="experience-advanced" name="language_experience" value="advanced" class="form-check-input">
                                <label class="form-check-label" for="experience-advanced">Advanced</label>
                            </div>
                        </div>

            
                        
                        <div class="survey-page d-none" data-page="3">
                            <label class="form-label fw-bold">3. What is your biggest challenge in learning a new language?</label>
                            <div class="form-check">
                                <input type="radio" id="challenge-grammar" name="learning_challenge" value="grammar" class="form-check-input" required>
                                <label class="form-check-label" for="challenge-grammar">Grammar</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="challenge-vocabulary" name="learning_challenge" value="vocabulary" class="form-check-input">
                                <label class="form-check-label" for="challenge-vocabulary">Vocabulary</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="challenge-pronunciation" name="learning_challenge" value="pronunciation" class="form-check-input">
                                <label class="form-check-label" for="challenge-pronunciation">Pronunciation</label>
                            </div>
                        </div>
                        <div class="survey-page d-none" data-page="4">
                        

                            <label class="form-label fw-bold">4. Which of the following learning resources do you use most frequently?</label>
                            <div class="form-check">
                                <input type="radio" id="resource-textbooks" name="learning_resource" value="textbooks" class="form-check-input" required>
                                <label class="form-check-label" for="resource-textbooks">Textbooks</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="resource-online" name="learning_resource" value="online_courses" class="form-check-input">
                                <label class="form-check-label" for="resource-online">Online courses</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="resource-exchange" name="learning_resource" value="language_exchange" class="form-check-input">
                                <label class="form-check-label" for="resource-exchange">Language exchange partners</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="resource-apps" name="learning_resource" value="language_apps" class="form-check-input">
                                <label class="form-check-label" for="resource-apps">Language apps</label>
                            </div>
                     </div>
                 

                        <div class="survey-page d-none" data-page="5">
                      
                            <label class="form-label fw-bold">5. How motivated are you to learn a new language?</label>
                            <div class="form-check">
                                <input type="radio" id="motivation-1" name="motivation_level" value="1" class="form-check-input" required>
                                <label class="form-check-label" for="motivation-1">Not at all</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="motivation-2" name="motivation_level" value="2" class="form-check-input">
                                <label class="form-check-label" for="motivation-2">Somewhat</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="motivation-3" name="motivation_level" value="3" class="form-check-input">
                                <label class="form-check-label" for="motivation-3">Very</label>
                            </div>
                   
                          </div>
                        

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" id="prevButton" disabled>Previous</button>
                            <button type="button" class="btn btn-primary" id="nextButton">Next</button>
                            <button type="submit" class="btn btn-success d-none" id="submitButton">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>

document.addEventListener('DOMContentLoaded', () => {
    const pages = document.querySelectorAll('.survey-page');
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');
    const submitButton = document.getElementById('submitButton');

    let currentPage = 1;

    const updatePage = () => {
        pages.forEach(page => {
            page.classList.add('d-none');
            if (parseInt(page.getAttribute('data-page')) === currentPage) {
                page.classList.remove('d-none');
            }
        });

        prevButton.disabled = currentPage === 1;
        nextButton.classList.toggle('d-none', currentPage === pages.length);
        submitButton.classList.toggle('d-none', currentPage !== pages.length);
    };

    nextButton.addEventListener('click', () => {
        if (currentPage < pages.length) {
            currentPage++;
            updatePage();
        }
    });

    prevButton.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updatePage();
        }
    });

    updatePage(); // Initialize the first page view
});


    </script>

@endsection



























