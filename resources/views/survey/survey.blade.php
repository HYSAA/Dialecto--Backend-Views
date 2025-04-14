@extends('layouts.app')

@section('content')
<div class="main-container">
    <!-- Survey Modal -->
    <div id="surveyModal" class="modal fade show" tabindex="-1" aria-hidden="true" style="display: block; background: rgba(0, 0, 0, 0.7); z-index: 1050;">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 80%;">
            <div class="modal-content shadow">
                <div class="modal-header" style="background-color: #FFCA58; color: black;">
                    <h2 class="modal-title">Language Learning Survey</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="survey-progress mb-3">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar" role="progressbar" style="width: 0%;"></div>
                        </div>
                    </div>
                    <form action="{{ route('survey.submit') }}" method="POST" id="multiPageSurvey">
                        @csrf
                        <!-- Survey Pages -->

                        <input type="hidden" name="courseId" value="{{ $courseId }}">


                        <div class="survey-page active animate__animated" data-page="1">
                            <label class="form-label fw-bold">1. How well can you communicate in a foreign language?</label>


                            <div class="form-check">
                                <input type="radio" id="familiarity-beginner" name="familiarity" value="beginner" class="form-check-input" required>
                                <label class="form-check-label" for="familiarity-beginner">I can only use basic phrases and words.</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="familiarity-intermediate" name="familiarity" value="intermediate" class="form-check-input">
                                <label class="form-check-label" for="familiarity-intermediate">I can hold simple conversations but struggle with complex topics.</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="familiarity-advanced" name="familiarity" value="advanced" class="form-check-input">
                                <label class="form-check-label" for="familiarity-advanced">I can discuss complex topics fluently and confidently</label>
                            </div>

                        </div>
                        <div class="survey-page animate__animated d-none" data-page="2">
                            <label class="form-label fw-bold">2. How often do you use these language in real-life situations (e.g., work, travel, or social interactions)?</label>
                            <!-- Radio options here -->
                            <div class="form-check">
                                <input type="radio" id="experience-beginner" name="language_experience" value="beginner" class="form-check-input" required>
                                <label class="form-check-label" for="experience-beginner">Rarely or never.</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="experience-intermediate" name="language_experience" value="intermediate" class="form-check-input">
                                <label class="form-check-label" for="experience-intermediate">Occasionally, but I still struggle.</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="experience-advanced" name="language_experience" value="advanced" class="form-check-input">
                                <label class="form-check-label" for="experience-advanced">Frequently and with confidence.</label>
                            </div>
                        </div>




                        <div class="survey-page animate__animated d-none" data-page="3">
                            <label class="form-label fw-bold">3. What is your biggest challenge in learning a new language?</label>
                            <!-- Radio options here -->
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





                        <div class="survey-page animate__animated d-none" data-page="4">
                            <label class="form-label fw-bold">4. Which of the following learning resources do you use most frequently?</label>
                            <!-- Radio options here -->
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



                        <!-- More survey pages here -->
                        <div class="survey-page animate__animated d-none" data-page="5">
                            <label class="form-label fw-bold">5. What is your primary goal for learning this language?</label>
                            <!-- Radio options here -->
                            <div class="form-check">
                                <input type="radio" id="motivation-1" name="motivation_level" value="1" class="form-check-input" required>
                                <label class="form-check-label" for="motivation-1">I want to learn basic phrases for travel or fun.</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="motivation-2" name="motivation_level" value="2" class="form-check-input">
                                <label class="form-check-label" for="motivation-2">I want to improve my skills for work or study.</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="motivation-3" name="motivation_level" value="3" class="form-check-input">
                                <label class="form-check-label" for="motivation-3">I want to achieve fluency for professional or personal mastery.</label>
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
        <div id="welcomePopup" class="welcome-popup d-none">
            <p>Welcome to your language journey! 🎉</p>

        </div>


    </div>


</div>
<div class="modal fade" id="customAlert" tabindex="-1" aria-labelledby="customAlertLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="customAlertLabel">Incomplete Answer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please answer the question before proceeding to the next page or submitting the survey.</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pages = document.querySelectorAll('.survey-page');
        const progressBar = document.getElementById('progressBar');
        const prevButton = document.getElementById('prevButton');
        const nextButton = document.getElementById('nextButton');
        const submitButton = document.getElementById('submitButton');
        const welcomePopup = document.getElementById('welcomePopup');
        const customAlert = new bootstrap.Modal(document.getElementById('customAlert')); // Bootstrap Modal instance

        let currentPage = 1;
        const totalPages = pages.length;

        const updatePage = () => {
            pages.forEach(page => {
                page.classList.add('d-none');
                if (parseInt(page.getAttribute('data-page')) === currentPage) {
                    page.classList.remove('d-none');
                }
            });

            prevButton.disabled = currentPage === 1;
            nextButton.classList.toggle('d-none', currentPage === totalPages);
            submitButton.classList.toggle('d-none', currentPage !== totalPages);

            // Update progress bar
            const progress = (currentPage / totalPages) * 100;
            progressBar.style.width = `${progress}%`;
            progressBar.setAttribute('aria-valuenow', progress);
        };

        const validateCurrentPage = () => {
            const currentInputs = pages[currentPage - 1].querySelectorAll('input[type="radio"]');
            for (let input of currentInputs) {
                if (input.checked) {
                    return true; // At least one option is selected
                }
            }
            return false; // No option selected
        };

        nextButton.addEventListener('click', () => {
            if (validateCurrentPage()) {
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePage();
                }
            } else {
                customAlert.show(); // Show the custom alert modal
            }
        });

        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updatePage();
            }
        });

        submitButton.addEventListener('click', (event) => {
            if (!validateCurrentPage()) {
                event.preventDefault();
                customAlert.show();
            } else {
                // Show the welcome pop-up
                welcomePopup.classList.add('show');
                setTimeout(() => {
                    welcomePopup.classList.remove('d-none');
                }, 100);

                // Optional: Redirect to dashboard after a delay
                setTimeout(() => {
                    // window.location.href = "{{ route('user.dashboard') }}";


                    window.location.href = "{{ route('user.courses.show', $courseId) }}";








                }, 300);
            }
        });


        function showAlertModal() {

            const customAlert = new bootstrap.Modal(document.getElementById('customAlert'));

            customAlert.show();


            setTimeout(() => {
                customAlert.hide();
            }, 1000); //set nako to 1 sec interval lol
        }
        //para modal 
        // Example usage:
        // showAlertModal();


        updatePage(); // Initialize the first page view
    });
</script>

<style>
    .survey-page {
        min-height: 200px;
    }

    .progress-bar {
        transition: width 0.5s ease;
        background-color: #CB9219 !important;
    }

    .animate__animated {
        animation-duration: 0.5s;
    }

    .welcome-popup {
        position: fixed;
        bottom: -100px;
        left: 50%;
        transform: translateX(-50%);
        background: #28a745;
        color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: bottom 0.5s ease-in-out;
        text-align: center;
    }

    .welcome-popup.show {
        bottom: 20px;
    }

    .welcome-popup p {
        margin: 0;
        font-size: 16px;
    }
</style>
@endsection