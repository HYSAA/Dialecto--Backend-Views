@extends('layouts.app')

@section('content')

<div class="main-container" style="overflow:auto">

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Lessons - {{ $course['name'] ?? 'Unknown Course' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-back-main" href="{{ route('admin.courses.index') }}">Back To Courses</a>
                <a class="btn btn-main" href="{{ route('admin.lessons.create', $id) }}">Create Lessons</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        @foreach ($course['lessons'] ?? [] as $lessonId => $lesson)
        <div class="col-md-3 mb-4">
        <div class="card d-flex flex-column" style="max-height: 400px; overflow-y: auto;">
                <div class="card-body">
                    <h5 class="card-title">{{ $lesson['title'] ?? 'Unknown Title' }}</h5>
                    <p class="card-text"><strong>Proficiency Level:</strong> {{ $lesson['proficiency_level'] ?? 'N/A' }}</p>
                    <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                        @if(isset($lesson['image']))
                        <img src="{{ $lesson['image'] }}" alt="Lesson Image" class="card-img-top fixed-dimensions">
                        @else
                        <p>No image available</p>
                        @endif
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a class="btn btn-success mb-2" href="{{ route('admin.lessons.edit', [$id, $lessonId]) }}">Edit</a>
                    <a class="btn btn-primary mb-2" href="{{ route('admin.lessons.show', [$id, $lessonId]) }}">View Contents</a>
                    <a class="btn btn-back-main mb-2" href="{{ route('admin.quizzes.index', [$id, $lessonId]) }}">Quizzes</a>

                    <form action="{{ route('admin.lessons.destroy', [$id, $lessonId]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger mb-2">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>


<style>
    .fixed-dimensions {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
</style>
@endsection
