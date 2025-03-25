@extends('layouts.app')

@section('content')

    <div class="main-container" style="padding: 15px;">

        <div class="row" style="overflow-y: auto;">
            <div class="col-lg-12 margin-tb">
                <h1 class="mb-2">Select Course</h1>
                <div class="row">
                    <!-- Loop through courses retrieved from Firebase -->
                    @foreach ($courses as $courseId => $course)
                        <div class="card mb-2 mr-2" style="width: 300px; height: fit-content;">
                            <div class="top">
                                <!-- Check for the course image from Firebase -->
                                @if(isset($course['image']))
                                    <img src="{{ $course['image'] }}" alt="Course Image" class="card-img">
                                @else
                                    <img src="{{ asset('images/cebuano.png') }}" alt="Course Image" class="card-img">
                                @endif

                                <div>
                                    <div class="d-flex align-items-center mt-3 mb-3" style="height: 50px; padding: 15px;">
                                        <h3 class="mb-0 me-2" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;">{{ $course['name'] }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content text-center mb-1">
                                <!-- Trigger modal -->
                                <button class="btn btn-main" data-toggle="modal" data-target="#lessonModal{{ $courseId }}" style="width: 70%;"
                                    data-backdrop="false">
                                    Select
                                </button>
                            </div>
                        </div>

                        <!-- Modal for Lessons -->
                        <div class="modal fade" id="lessonModal{{ $courseId }}" tabindex="-1" role="dialog"
                            aria-labelledby="lessonModalLabel{{ $courseId }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="lessonModalLabel{{ $courseId }}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;">Select Lesson for
                                            {{ $course['name'] }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        @if (isset($course['lessons']))
                                            @foreach ($course['lessons'] as $lessonId => $lesson)
                                            <div class="d-flex align-items-center mt-3 mb-3" style="height: 50px; padding: 15px;">

                                                <a class="btn btn-select btn-block mb-2"
                                                    href="{{ route('user.addUserSuggestedWord', ['courseId' => $courseId, 'lessonId' => $lessonId]) }}">
                                                    {{ $lesson['title'] }}
                                                </a>
                                            </div>
                                            @endforeach
                                        @else
                                            <p>No lessons available for this course.</p>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->

                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Make sure you include Bootstrap JS if not already -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <style>
    .btn-select {
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    border-radius: 5px;
    border-color: #CB9219;
    background-color: #CB9219;
    color: #fff;
    transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-select:hover {
        background-color: #a67c1c;
        color: #fff;
    }
    </style>
@endsection