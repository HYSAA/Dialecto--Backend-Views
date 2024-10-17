@extends('layouts.app')

@section('content')

<div class="main-container">
    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course['name'] ?? 'Course Name' }} - Lessons</h2> <!-- Use 'description' if necessary -->
            </div>
        </div>
    </div>

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">
            <div class="row">
                @foreach ($course['lessons'] ?? [] as $lessonId => $lesson)

                <div class="cardsmall mb-2 mr-2">
                    <div class="top">
                        <div>
                            @if(isset($lesson['image']) && $lesson['image'])
                            <img src="{{ $lesson['image'] }}" alt="Lesson Image" class="card-img-small">
                            @else
                            <img src="{{ asset('images/cebuano.png') }}" alt="Lesson Image" class="card-img">
                            @endif
                        </div>

                        <div class="row align-items-center mt-3 mb-3" style="height: 50px;">
                            <div class="col-6 d-flex align-items-center">
                                <h3 class="card-title mb-0">{{ $lesson['title'] ?? 'Lesson Title' }}</h3>
                            </div>

                            <div class="col-6 d-flex justify-content-end">

                                <button class="btn btn-main lessonButton"
                                    data-title="{{ $lesson['title'] }}"
                                    data-lesson-id="{{ $lessonId }}"
                                    data-contents="{{ json_encode($lesson['contents'] ?? []) }}">
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
    <div class="l-modal-content text-center">
        <div class="container-fluid position-relative" style="height: 80px;">
            <i class="bi bi-x-circle position-absolute btn" id="closeLessonModal" style="top: 0; right: 0; padding: 10px; font-size: 32px;"></i>
        </div>

        <h3 id="modalLessonTitle">Lesson Title</h3>
        <h5 id="modalLessonCount" style="font-weight: 50; color: #90949C; margin-bottom: 10px;">Total Contents: 0</h5>

        <a class="btn btn-main" id="modalShowButton" style="margin-bottom: 20px;" href="#">Show</a>

        <div id="modalLessonContents"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.lessonButton').forEach(button => {
            button.addEventListener('click', function() {

                const lessonTitle = this.getAttribute('data-title');
                console.log('lesson title');
                console.log(lessonTitle);


                const lessonContents = JSON.parse(this.getAttribute('data-contents'));
                console.log('lesson contents');
                console.log(lessonContents);

                const courseId = '{{ $id }}';
                console.log('course id');
                console.log(courseId);

                const lessonId = this.getAttribute('data-lesson-id');
                console.log('lesson id');
                console.log(lessonId);

                // Update the modal title
                document.getElementById('modalLessonTitle').textContent = lessonTitle;

                // Count the number of contents
                const contentCount = Object.keys(lessonContents).length;
                console.log(`Lesson is object has ${contentCount} contents`);
                document.getElementById('modalLessonCount').textContent = `${contentCount} Words`;


                // Generate the contents HTML and inject into modal
                let contentsHtml = '';

                Object.entries(lessonContents).forEach(([key, content]) => {
                    contentsHtml += `
                    <div class="content-row">
                        <div class="content-text">${content.text}</div>
                        <div class="content-separator">-</div>
                        <div class="content-english">${content.english}</div>
                    </div>
                    <hr>`;
                });

                document.getElementById('modalLessonContents').innerHTML = contentsHtml;

                // Set the href for the Show button to point to the first content

                const entries = Object.entries(lessonContents);

                console.log('entries');
                console.log(entries);


                const firstEntry = entries.length > 0 ? entries[0] : null; // Get the first entry or null if no entries
                let firstContentId;

                if (firstEntry) {
                    console.log('First Entry:', firstEntry); // Log the first entry
                    firstContentId = firstEntry[0]; // This is the key (acting as an ID)
                    const content = firstEntry[1]; // This is the content object
                    console.log('First Content ID (Firebase key):', firstContentId); // Log the key
                    console.log('First Content Data:', content); // Log the content data
                } else {
                    console.log('No entries available');
                }

                console.log('firstContentId');
                console.log('First Content ID  asdas(Firebase key):', firstContentId); // Log the key



                const showButton = document.getElementById('modalShowButton');


                if (firstContentId) {
                    showButton.href = `/user/courses/${courseId}/lessons/${lessonId}/contents/${firstContentId}`;

                    // Show the button if firstContentId exists
                    showButton.style.display = 'inline-flex'; // Change this to 'block', 'inline', or 'inline-block' based on your needs
                } else {
                    // Hide the button if no content exists
                    showButton.style.display = 'none';
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