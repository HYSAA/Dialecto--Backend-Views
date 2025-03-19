@extends('layouts.app')

@section('content')

<div class="main-container">
@if(empty($filteredLessons))
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <p style="font-size: 1.5rem; color: #666;">No lessons available yet. The admins will update this soon.</p>
    </div>
    
@else

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">

                <h2>{{ $course['name'] ?? 'Course Name' }} - Lessons </h2>
                <h2 style="color: green;">Level: {{ $currentLevel ?? 'Not Specified' }} </h2>
                <strong style="font-size: small;">Earn a silver or higher rating on all lessons in the current level to be promoted to the next level. To check your ratings for all lessons, go to your profile and view your badges.</strong>

            </div>
        </div>
    </div>



    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">
            <div class="row">
                @foreach ($filteredLessons as $lessonId => $lesson)
                <div class="cardsmall mb-2 mr-2" style="overflow-y: auto; height: fit-content;">
                    <div class="top">
                        <div>
                            @if(isset($lesson['image']) && $lesson['image'])
                            <img src="{{ $lesson['image'] }}" alt="Lesson Image" class="card-img-small" style="max-height: 200px;">
                            @else
                            <img src="{{ asset('images/cebuano.png') }}" alt="Lesson Image" class="card-img" style="max-height: 200px;">
                            @endif
                        </div>


                        <div class="row align-items-center mt-3 mb-3" style="height: 90px;">


                            <div class="col-6 d-flex align-items-center">
                                <h3 class="card-title mb-0"  style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;">
                                    <span class="badge bg-{{ 
                            $lesson['proficiency_level'] === 'Beginner' ? 'success' : 
                            ($lesson['proficiency_level'] === 'Intermediate' ? 'warning' : 'primary') }} ms-2 small" style="margin-left: 0;">
                                        {{ $lesson['proficiency_level'] }}
                                    </span> <br>
                                    {{ $lesson['title'] ?? 'Lesson Title' }}
                                    {{-- Optional: Add proficiency level badge --}}

                                </h3>
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
    @endif
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