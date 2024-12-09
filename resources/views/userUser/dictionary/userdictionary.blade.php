@extends('layouts.app')

@section('content')



<!-- <div class="main-container" style="overflow:auto">
    <h1>Dictionary of Words</h1>
    @if (isset($courses) && count($courses) > 0)
        @foreach ($courses as $course)
            <div class="course">
                <h1>Dialect: {{ $course['name'] }}</h1>
            </div>
            @foreach ($course['lessons'] as $lesson)
                <div class="lesson">
                    <h1>{{ $lesson['title'] ?? 'Untitled Lesson' }}</h1>
                    @if (isset($lesson['contents']))
                        @foreach ($lesson['contents'] as $content)
                            <div class="content">
                            <p>{{ $content['english'] ?? 'No English content' }}</p>
                            <p>{{ $content['text'] ?? 'No text content' }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        @endforeach


    @else
        <p>No courses available.</p>
    @endif
</div> -->

<div class="main-container" style="padding: 20px; font-family: Arial, sans-serif;overflow:auto">
    <h1>Dictionary of Words</h1>
    @if (isset($courses) && count($courses) > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            @foreach ($courses as $course)
                <div style="
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    padding: 16px;
                    background-color: #f9f9f9;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                ">
                    <h2 style="margin: 0 0 10px; font-size: 1.5rem; color: #333;">Dialect: {{ $course['name'] }}</h2>
                    <div style="margin-top: 10px;">
                        @foreach ($course['lessons'] as $lesson)
                            <h3 style="margin: 10px 0 5px; font-size: 1.2rem; color: #555;">{{ $lesson['title'] ?? 'Untitled Lesson' }}</h3>
                            @if (isset($lesson['contents']))
                                @foreach ($lesson['contents'] as $content)
                                    <p style="margin: 5px 0; font-size: 1.5em; color: #666;">
                                        <strong>English:</strong> {{ $content['english'] ?? 'No English content' }}
                                    </p>
                                    <p style="margin: 5px 0; font-size: 1.2em; color: #666;">
                                        <strong>In Dialect:</strong> {{ $content['text'] ?? 'No text content' }}
                                    </p>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>No courses available.</p>
    @endif
</div>




<!-- <div class="main-container" style="overflow:auto">



    <h1>Your Courses</h1>

    @if (isset($courses) && count($courses) > 0)
        @foreach ($courses as $course)
            <div class="course">
                <h2>{{ $course['description'] }}</h2>

                @if (!empty($course['image']))
                    <img src="{{ $course['image'] }}" alt="{{ $course['description'] }}" class="img-fluid">
                @endif

                @foreach ($course['lessons'] as $lesson)
                    <div class="lesson">
                        <h3>{{ $lesson['title'] ?? 'Untitled Lesson' }}</h3>

                        @if (isset($lesson['contents']))
                            @foreach ($lesson['contents'] as $content)
                                <div class="content">
                                    <p>{{ $content['english'] ?? 'No English content' }}</p>
                                    <p>{{ $content['text'] ?? 'No text content' }}</p>

                                    @if (isset($content['video']))
                                        <video controls>
                                            <source src="{{ $content['video'] }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif

                                    @if (isset($content['image']))
                                        <img src="{{ $content['image'] }}" alt="{{ $content['title'] ?? 'Content Image' }}" class="img-fluid">
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    @else
        <p>No courses available.</p>
    @endif
</div> -->
@endsection