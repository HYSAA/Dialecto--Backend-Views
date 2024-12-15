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

    <div class="card-container"
        style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 20px; overflow-y: auto;">
        @foreach($coursesWithLessonsAndContents as $courseId => $courseData)
            @foreach ($courseData['lessons'] as $lessonId => $lessonData)
                @php
                    // Get user progress count for the current lesson
                    $userProgressCount = isset($progressData[$courseId][$lessonId]) ? count($progressData[$courseId][$lessonId]) : 0;
                    // Get the total content count for the lesson
                    $totalContentCount = $lessonData['content_count'];
                @endphp

                <div class="card"
                    style="border: 1px solid #ddd; border-radius: 10px ; padding: 15px; text-align: center; max-height: 300px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                    <!-- Display the lesson image -->
                    <img src="{{ $lessonData['lesson']['image'] ?? '' }}" alt="Lesson Image" class="card-img-top"
                        style="max-width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">

                    <!-- Lesson title -->
                    <h5 style="margin: 10px 0;height: 25px;overflow:auto">{{ $lessonData['lesson']['title'] }}</h5>

                    <!-- Progress details -->
                    <p style="margin-bottom: 10px;">Progress: {{ $userProgressCount }} / {{ $totalContentCount }} contents</p>

                    <!-- Progress bar -->
                    <div class="progress" style="height: 20px;">
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
                </div>
            @endforeach
        @endforeach
    </div>

    <br>
    <div>
        <a href="{{ route('users.index') }}" class="btn btn-primary">Back to Users</a>
    </div>

</div>
@endsection