@extends('layouts.app')

@section('content')

<div class="main-container">
    <div class="container" style="overflow-y: auto;">
        <h2>User Details</h2>
        <p><strong>User ID:</strong> {{ $id }}</p>
        <p><strong>Name:</strong> {{ $user['name'] ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $user['email'] ?? 'N/A' }}</p>
        <p><strong>Usertype:</strong> {{ $user['usertype'] ?? 'N/A' }}</p>
        
        @foreach($coursesWithLessonsAndContents as $courseId => $courseData)
            @foreach ($courseData['lessons'] as $lessonId => $lessonData)
                @php
                    // Get user progress count for the current lesson
                    $userProgressCount = isset($progressData[$courseId][$lessonId]) ? count($progressData[$courseId][$lessonId]) : 0;

                    // Get the total content count for the lesson
                    $totalContentCount = $lessonData['content_count'];
                @endphp

                <li>
                    <!-- Display the lesson title and progress -->
                    {{ $lessonData['lesson']['title'] }}
                    ({{ $userProgressCount }} / {{ $totalContentCount }} contents)

                    <!-- Progress bar -->
                    <div class="progress mt-2" style="height: 20px;">
                        @if($totalContentCount > 0)
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ ($userProgressCount / $totalContentCount) * 100 }}%;"
                                aria-valuenow="{{ ($userProgressCount / $totalContentCount) * 100 }}" aria-valuemin="0" aria-valuemax="100">
                                
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


        <a href="{{ route('users.index') }}" class="btn btn-primary">Back to Users</a>


    </div>
</div>
@endsection