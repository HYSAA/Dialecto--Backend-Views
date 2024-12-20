@extends('layouts.app')

@section('content')

<div class="main-container">
    <!-- Display the course title -->
    <h1 class="text-center" style="
            font-size: 2.5rem; 
            font-weight: bold; 
            color: #ffffff; 
            background-color: #FFCA58; 
            padding: 15px; 
            text-align: center; 
            border-radius: 8px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    ">{{ $course['name'] ?? 'Course Details' }}</h1>
    <p>{{ $course['description'] ?? 'No description available for this course.' }}</p>

    <!-- Display paginated lessons -->
    @if ($paginatedLessons->count())
        <div class="lessons-container"
            style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; padding: 10px;">
            @foreach ($paginatedLessons as $lesson)
                <div class="lesson-card"
                    style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;">
                    <!-- Display lesson title -->
                    <h2 style="font-size: 2rem; font-weight: bold; color: #333; margin-bottom: 10px;">{{ $lesson['title'] }}</h2>

                    <!-- Display proficiency level -->
                    <p style="font-size: 1.5rem; color: #666; font-style: italic; margin-bottom: 15px;">
                        Proficiency Level: 
                        <span style="font-weight: bold; color: #007BFF;">{{ $lesson['proficiency_level'] }}</span>
                    </p>

                    <!-- Display contents of the lesson -->
                    @if (!empty($lesson['contents']))


                        <ul>
                            @foreach($lesson['contents'] as $contentId => $content)
                                <li style="font-size: 1.2rem;"><b>English:</b> {{ $content['english'] ?? 'Unnamed Content' }}
                                    <b>Dialect:</b> {{ $content['text'] ?? 'Unnamed Content' }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No contents available for this lesson.</p>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Paginate lessons -->
        <div class="pagination mt-3">
            {{ $paginatedLessons->links() }}
        </div>
    @else
        <p>No lessons available for this course.</p>
    @endif

    <div class="mt-4">
        <a href="{{ route('user.dictionary') }}" class="btn btn-secondary" style="
            font-size: 1.2rem; 
            padding: 10px 20px; 
            background-color: #6c757d; 
            color: #fff; 
            border: none; 
            border-radius: 5px; 
            text-decoration: none;">
            Back
        </a>
    </div>




    @endsection