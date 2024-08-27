@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">

                <h2> {{ $course->name }} - Lessons</h2>

            </div>


        </div>
    </div>

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <div class="row">


                @foreach ($course->lessons as $lesson)

                <div class="cardsmall mb-2 mr-2">
                    <div class="top ">
                        <div>
                            @if($course->image)
                            <img src="{{ asset('storage/' . $lesson->image) }}" alt="Course Image" class="card-img-small ">
                            @else
                            <img src="{{ asset('images/cebuano.png') }}" alt="Course Image" class="card-img">
                            @endif
                        </div>

                        <div class="row align-items-center mt-3 mb-3 " style="height: 50px;">
                            <div class="col-6 d-flex align-items-center ">
                                <h3 class="card-title mb-0">{{ $lesson->title }}</h3>

                            </div>

                            <div class="col-6 d-flex justify-content-end ">

                                <!-- <a class="btn btn-main" href="{{ route('user.lessons.show', [$course->id, $lesson->id]) }}">View</a> -->

                                <!-- <button class="btn btn-main lessonButton" data-title="{{ $lesson->title }}">
                                    Show Lesson Title
                                </button> -->

                                <button class="btn btn-main lessonButton"
                                    data-title="{{ $lesson->title }}"
                                    data-contents="{{ json_encode($lesson->contents) }}"
                                    data-course-id="{{ $course->id }}"
                                    data-lesson-id="{{ $lesson->id }}">
                                    View
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach



            </div>
        </div>
    </div>
</div>


<!-- Lesson Modal -->
<div class="l-modal-overlay" id="lessonModal" style="display:none;">
    <div class="l-modal-content text-center" style="padding-top: 100px;">
        <h3 id="modalLessonTitle">Lesson Title</h3>
        <h5 id="modalLessonCount" style="font-weight: 50; color: #90949C;">Total Contents: 0</h5>

        <a class="btn btn-info" id="modalShowButton" href="#">Show</a>

        <div id="modalLessonContents">
            <!-- The lesson contents will be injected here -->
        </div>
        <button type="button" class="btn btn-secondary" id="closeLessonModal">Close</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.lessonButton').forEach(button => {
            button.addEventListener('click', function() {
                // Get the lesson title, contents, course ID, and lesson ID from the data attributes
                const lessonTitle = this.getAttribute('data-title');
                const lessonContents = JSON.parse(this.getAttribute('data-contents'));
                const courseId = this.getAttribute('data-course-id');
                const lessonId = this.getAttribute('data-lesson-id');

                // Update the modal title
                document.getElementById('modalLessonTitle').textContent = lessonTitle;

                // Count the number of contents
                document.getElementById('modalLessonCount').textContent = `Total Contents: ${lessonContents.length}`;

                // Generate the contents and inject them into the modal
                let contentsHtml = '';
                lessonContents.forEach(content => {
                    contentsHtml += `<p>${content.text} - ${content.english}</p>`;
                });
                document.getElementById('modalLessonContents').innerHTML = contentsHtml;

                // Dynamically set the href for the Show button
                const firstContentId = lessonContents.length > 0 ? lessonContents[0].id : null;
                const showButton = document.getElementById('modalShowButton');
                if (firstContentId) {
                    showButton.href = `/user/courses/${courseId}/lessons/${lessonId}/contents/${firstContentId}`;
                    showButton.style.display = 'inline-block';
                } else {
                    showButton.style.display = 'none'; // Hide the button if no content exists
                }

                // Show the modal
                document.getElementById('lessonModal').style.display = 'block';
                document.querySelector('.main-container').classList.add('blurred');
            });
        });

        // Close modal event
        document.getElementById('closeLessonModal').addEventListener('click', function() {
            document.getElementById('lessonModal').style.display = 'none';
            document.querySelector('.main-container').classList.remove('blurred');
        });
    });
</script>

@endsection