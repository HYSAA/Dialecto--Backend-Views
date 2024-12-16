@extends('layouts.app')

@section('content')

<div class="main-container " style="overflow:auto">
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



    <div class="lessons-container"
        style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; padding: 10px;">
        @foreach($lessonsWithContents as $lesson)
            <div class="lesson-card"
                style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;">

                <h2 style="font-size: 2rem; font-weight: bold; color: #333; margin-bottom: 10px;">{{ $lesson['title'] }}
                </h2>
                <p style="font-size: 1.5rem; color: #666; font-style: italic; margin-bottom: 15px;">Proficiency Level: <span
                        style="font-weight: bold; color: #007BFF;">{{ $lesson['proficiency_level'] }}</span>
                </p>

                <ul>
                    @foreach($lesson['contents'] as $contentId => $content)
                        <li style="font-size: 1.2rem;"><b>English:</b> {{ $content['english'] ?? 'Unnamed Content' }}
                            <b>Dialect:</b> {{ $content['text'] ?? 'Unnamed Content' }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

</div>


<!-- <div class="main-container my-4" style="overflow:auto">
    <h1 class="text-center">{{ $course['name'] ?? 'Course Details' }}</h1>

    <div class="row">
        @foreach($lessonsWithContents as $lesson)
            <div class="col-md-4 mb-4">
                <div class="lesson-card" style="background-image: url('{{ $lesson['image'] ?? 'default-image.jpg' }}'); background-size: cover; background-position: center; padding: 20px; color: white;">
                    <h2 class="lesson-title">{{ $lesson['title'] }}</h2>
                    <p>Proficiency Level: {{ $lesson['proficiency_level'] }}</p>

                    <ul>
                        @foreach($lesson['contents'] as $contentId => $content)
                            <li>{{ $content['english'] ?? 'Unnamed Content' }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</div> -->

<!-- <style>
    .lesson-card {
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay for readability */
        border-radius: 8px;
    }

    .lesson-title {
        font-size: 1.5rem;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7); /* Title shadow for better contrast */
    }
</style> -->