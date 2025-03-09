@extends('layouts.app')

@section('content')

<div class="main-container">

    <h1 style="
            font-size: 2.5rem; 
            font-weight: bold; 
            color: #ffffff; 
            background-color: #FFCA58; 
            padding: 15px; 
            text-align: center; 
            border-radius: 8px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        ">
        Dictionary of Words
    </h1>
    <div class="row" style="overflow: auto;">
        @foreach($courses as $course)
            <div class="col-md-6 mb-2" style="padding: 10px;">
                <div class="course-card position-relative text-center">
                    <a href="{{ route('expert.dictionary.show', $course['id']) }}">

                    <!-- Blurred Background Image -->
                    <div class="course-image">
                        <img src="{{ $course['image'] ?? 'default-image.jpg' }}" alt="Course Image" class="img-fluid"
                            style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px;">
                    </div>

                    <!-- Course Name Overlay -->
                    <div class="course-name text-white">
                        <h3>{{ $course['name'] ?? 'Unnamed Course' }}</h3>
                    </div>

                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection


<style>
    .course-card {
        width: 100%;
        height: 200px;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .course-card:hover {
        transform: scale(1.05);
        /* Slightly enlarge on hover */
    }

    .course-image {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        filter: blur(5px);
        /* Blur effect */
        transition: filter 0.3s;
        /* Smooth transition for blur */
    }

    .course-card:hover .course-image {
        filter: blur(0);
        /* Remove blur on hover */
    }

    .course-name {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
        font-size: 1.5rem;
        font-weight: bold;
    }
</style>