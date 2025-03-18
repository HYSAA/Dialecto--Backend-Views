@extends('layouts.app')

@section('content')

<div class="main-container" style="overflow:auto">


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
    <br>
    <!-- <p>{{ $course['description'] ?? 'No description available for this course.' }}</p> -->
<!-- <p>Course ID: {{ $id }}</p> -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <!-- Proficiency Filter Buttons -->
    <form method="GET" action="{{ route('user.dictionary.show', $id) }}" class="d-flex flex-wrap gap-2">
        @foreach($allProficiencies as $proficiency)
            <button 
                type="submit" 
                name="proficiency_level" 
                value="{{ $proficiency }}" 
                class="btn {{ $filterProficiency == $proficiency ? 'btn-primary' : 'btn-secondary' }}">
                {{ $proficiency }}
            </button>
        @endforeach

        <!-- Reset Button -->
        <button 
            type="submit" 
            name="proficiency_level" 
            value="" 
            class="btn {{ !$filterProficiency ? 'btn-primary' : 'btn-secondary' }}">
            Show All
        </button>
    </form>

    <!-- Search Bar (Aligned to the Right) -->
    <form method="GET" action="{{ route('user.dictionary.show', $id) }}" class="d-flex">
    <input 
        type="text" 
        name="search" 
        class="form-control me-2" 
        placeholder="Search by lesson title or English text..." 
        value="{{ $searchQuery ?? '' }}"
        style="font-size: 1.2rem; padding: 10px; width: 500px;">

        <button type="submit" class="btn btn-primary" style="font-size: 1.2rem; padding: 10px 20px;">
            Search
        </button>

        <!-- Hidden Fields to Maintain Filters -->
        @if ($filterProficiency)
            <input type="hidden" name="proficiency_level" value="{{ $filterProficiency }}">
        @endif
    </form>
</div>


    <!-- Display paginated lessons -->
    @if ($paginatedLessons->count())
        <div class="lessons-container"
            style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; padding: 10px;">
            @foreach ($paginatedLessons as $lesson)
                <div class="lesson-card"
                    style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9; height: fit-content;">
                    <!-- Display lesson title -->
                    <h2 style="font-size: 2rem; font-weight: bold; color: #333; margin-bottom: 10px;">{!! $lesson['title'] !!}</h2>

                    <!-- Display proficiency level -->
                    <p style="font-size: 1.5rem; color: #666; font-style: italic; margin-bottom: 15px;">
                        Proficiency Level: 
                        <span style="font-weight: bold; color: #007BFF;">{!! $lesson['proficiency_level'] !!}</span>
                    </p>

                    <!-- Display contents of the lesson -->
                    @if (!empty($lesson['contents']))


                    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 1.2rem;">
                        <thead>
                            <tr>
                                <th style="font-size: 25px;">English</th>
                                <th style="font-size: 25px;">Dialect</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lesson['contents'] as $contentId => $content)
                                <tr>
                                    <td>{!!  $content['english'] ?? 'Unnamed Content' !!}</td>
                                    <td>{!! $content['text'] ?? 'Unnamed Content' !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        <p>No contents available for this lesson.</p>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Paginate lessons -->
        <div class="pagination mt-3">
        {{ $paginatedLessons->appends(request()->query())->links() }}
        </div>
    @else
        <p>No lesson or Text Found for this course.</p>
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