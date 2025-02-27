@extends('layouts.app')

@section('content')

    <div class="main-container">
        <h1 style="font-size: 2rem; font-weight: bold; color: #333; margin-bottom: 10px;">Your Progress</h1>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('user.progress', ['id' => Auth::user()->firebase_id]) }}"
            style="margin-bottom: 20px;">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Search Course,Lesson or Profeciency Level" value="{{ $searchQuery ?? '' }}">
                <button type="submit" class="btn btn-primary"
                    style="font-size: 1.2rem; padding: 10px 20px;margin-left: 10px;">Search</button>
            </div>
        </form>

        @if (count($coursesWithLessonsAndContents) > 0)

            <div class="card-container"
                style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 20px; overflow-y: auto;height: fit-content;">
                @foreach($coursesWithLessonsAndContents as $courseId => $courseData)
                    <h2
                        style="font-size: 1.8rem; font-weight: bold; color: #444; margin: 20px 0 10px; grid-column: 1 / -1; width: 100%;height: fit-content;">
                        {{ $courseData['course']['name'] }}
                    </h2>

                    @foreach ($courseData['lessons'] as $lessonId => $lessonData)
                        @php
                            // Get user progress count for the current lesson
                            $userProgressCount = isset($progressData[$courseId][$lessonId]) ? count($progressData[$courseId][$lessonId]) : 0;
                            // Get the total content count for the lesson
                            $totalContentCount = $lessonData['content_count'];
                        @endphp

                        <div class="card" style="border: 1px solid #ddd; 
                                   border-radius: 10px; 
                                   padding: 15px; 
                                   text-align: center; 
                                   max-height: 35vh; 
                                   max-width: 90vw; 
                                   display: flex; 
                                   flex-direction: column; 
                                   box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
                                   margin: auto;">
                            <!-- Display the lesson image -->
                            <img src="{{ $lessonData['lesson']['image'] ?? '' }}" alt="Lesson Image" class="card-img-top"
                                style="max-width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">

                            <!-- <h1 style="margin: 10px ;height: 25px;"><b>{{ $courseData['course']['name'] }}</b></h1> -->
                            <h2 style="margin: 10px ;height: 25px;"><b>{{ $lessonData['lesson']['proficiency_level'] }}</b></h2>

                            <!-- Lesson title -->
                            <h1 style="height: 25px;overflow:auto">{{ $lessonData['lesson']['title'] }}</h1>

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

        @else
            <div style="text-align: center; margin-top: 20px;">
                <h3>User Has No Progress</h3>
            </div>
        @endif
        <br>

        <div>
            <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Back</a>
        </div>

@endsection