@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Lessons - {{ $course['name'] ?? 'Unknown Course' }}</h2>
            </div>
            <div class="pull-right">
                <!-- Use the `$id` variable passed from the controller if the id is not within the course data -->
                <a class="btn btn-main" href="{{ route('admin.lessons.create', $id) }}">Create Lesson</a>
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

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">
            <table class="table table-bordered">
                <tr>
                    <th>Title</th>
                    <th>Proficiency Level</th>
                    <th>Lesson Image</th>
                    <th width="350px">Action</th>
                </tr>
                @foreach ($course['lessons'] ?? [] as $lessonId => $lesson)
                <tr>
                    <td>{{ $lesson['title'] ?? 'Unknown Title' }}</td>
                    <td>{{ $lesson['proficiency_level'] ?? 'N/A' }}</td>
                    <td style="width: 150px;">
                        <div style="width: 150px; height: 150px;">
                            @if(isset($lesson['image']))
                            <img src="{{ $lesson['image'] }}" alt="Lesson Image" class="image-thumbnail">
                            @else
                            No image available
                            @endif
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('admin.lessons.destroy', [$id, $lessonId]) }}" method="POST">
                            <a class="btn btn-success" style="margin-bottom: 5px;" href="{{ route('admin.lessons.edit', [$id, $lessonId]) }}">Edit</a>
                            <a class="btn btn-primary" style="margin-bottom: 5px;" href="{{ route('admin.lessons.show', [$id, $lessonId]) }}">View</a>


                            <a class="btn btn-back-main" style="margin-bottom: 5px;" href="{{ route('admin.quizzes.index', [$id, $lessonId]) }}">Quizzes</a>


                            @csrf
                            @method('DELETE')
                            <button type="submit" style="margin-bottom: 5px;" class="btn btn-danger">Delete</button>
                        </form>









                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

</div>
@endsection