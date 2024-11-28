@extends('layouts.app')

@section('content')

<div class="main-container">
    <div class="top-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div class="container" style="border: 1px #ccc; padding: 10px;">
            <h2>User Details</h2>
            <p><strong>Name:</strong> {{ $user['name'] ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $user['email'] ?? 'N/A' }}</p>
        </div>

        <!-- Placeholder for Additional Details or Quiz Data -->
        <div class="container" style="border: 1px solid #ccc; padding: 10px;">
            <h2>Quiz Data</h2>
            <p>Placeholder for quiz-related information or other user-specific data.</p>
        </div>
    </div>

    <div class="card-container" style="overflow-y: auto; margin-top: 20px;">
        @foreach($coursesWithLessonsAndContents as $courseId => $courseData)
            @foreach ($courseData['lessons'] as $lessonId => $lessonData)
                @php
                    // Get user progress count for the current lesson
                    $userProgressCount = isset($progressData[$courseId][$lessonId]) ? count($progressData[$courseId][$lessonId]) : 0;
                    // Get the total content count for the lesson
                    $totalContentCount = $lessonData['content_count'];
                @endphp

                <li style="list-style: none;">
                    <!-- Display the lesson title and progress -->
                    <img src="{{ $lessonData['lesson'] ['image'] ?? " " }}" alt="Course Image" class="card-img-small">

                    {{ $lessonData['lesson']['title'] }}
                    ({{ $userProgressCount }} / {{ $totalContentCount }} contents)

                    <!-- Progress bar -->
                    <div class="progress mt-2" style="height: 20px;">
                        @if($totalContentCount > 0)
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ ($userProgressCount / $totalContentCount) * 100 }}%;"
                                aria-valuenow="{{ ($userProgressCount / $totalContentCount) * 100 }}" aria-valuemin="0"
                                aria-valuemax="100">

                                {{ round(($userProgressCount / $totalContentCount) * 100) }}%
                            </div>
                        @else
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100">
                                No content available
                            </div>
                        @endif
                    </div>
                </li>
            @endforeach

        @endforeach
    </div>
    <br>
    <div>
        <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Back</a>
    </div>

@endsection