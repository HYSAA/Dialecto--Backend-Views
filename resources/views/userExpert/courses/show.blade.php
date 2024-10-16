@extends('layouts.app')

@section('content')

<div class="main-container">
    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course['name'] }} - Lessons</h2>
            </div>
        </div>
    </div>

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">
            <div class="row">
                @if (count($lessons) > 0)
                    @foreach ($lessons as $lessonId => $lesson)
                    <div class="cardsmall mb-2 mr-2">
                        <div class="top">
                            <div>
                                @if(isset($lesson['image']))
                                <img src="{{ $lesson['image'] }}" alt="Lesson Image" class="card-img-small">
                                @else
                                <img src="{{ asset('images/cebuano.png') }}" alt="Lesson Image" class="card-img">
                                @endif
                            </div>

                            <div class="row align-items-center mt-3 mb-3" style="height: 50px;">
                                <div class="col-6 d-flex align-items-center">
                                    <h3 class="card-title mb-0">{{ $lesson['title'] }}</h3>
                                </div>

                                <div class="col-6 d-flex justify-content-end">
                                    <button class="btn btn-main lessonButton"
                                        data-title="{{ $lesson['title'] }}"
                                        data-contents="{{ json_encode($lesson['contents'] ?? []) }}"
                                        data-course-id="{{ $courseId }}"
                                        data-lesson-id="{{ $lessonId }}">
                                        View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p>No lessons available for this course.</p>
                @endif
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
        <h5 id="modalLessonCount" style="font-weight: 50; color: #90949C; margin-bottom: 30px;">Total Contents: 0</h5>
        <a class="btn btn-main" id="modalShowButton" style="margin-bottom: 20px;" href="#">Show</a>

        <div id="modalLessonContents"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.lessonButton').forEach(button => {
            button.addEventListener('click', function() {
                const lessonTitle = this.getAttribute('data-title');
                const lessonContents = JSON.parse(this.getAttribute('data-contents'));
                const courseId = this.getAttribute('data-course-id');
                const lessonId = this.getAttribute('data-lesson-id');

                document.getElementById('modalLessonTitle').textContent = lessonTitle;
                document.getElementById('modalLessonCount').textContent = `${lessonContents.length} Words`;

                let contentsHtml = '';
                lessonContents.forEach(content => {
                    contentsHtml += `<div class="content-row">
                        <div class="content-text">${content.text}</div>
                        <div class="content-separator">-</div>
                        <div class="content-english">${content.english}</div>
                    </div><hr>`;
                });
                document.getElementById('modalLessonContents').innerHTML = contentsHtml;

                const firstContentId = lessonContents.length > 0 ? lessonContents[0].id : null;
                const showButton = document.getElementById('modalShowButton');
                if (firstContentId) {
                    showButton.href = `/expert/courses/${courseId}/lessons/${lessonId}/contents/${firstContentId}`;
                } else {
                    showButton.style.display = 'none';
                }

                document.getElementById('lessonModal').style.display = 'block';
                document.querySelector('.main-container').classList.add('blurred');
            });
        });

        document.getElementById('closeLessonModal').addEventListener('click', function() {
            document.getElementById('lessonModal').style.display = 'none';
            document.querySelector('.main-container').classList.remove('blurred');
        });
    });
</script>

@endsection
