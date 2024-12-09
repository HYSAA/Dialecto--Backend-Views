@extends('layouts.app')

@section('content')

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