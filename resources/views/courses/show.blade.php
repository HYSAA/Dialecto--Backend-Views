@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Lessons - {{ $course['name'] }}</h2>
            </div>
            <div class="pull-right ">
                <a class="btn btn-main" href="{{ route('courses.lessons.create', $id) }}">Create Lesson</a>
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
                    <th>Lesson ID</th>
                    <th>Title</th>
                    <th>Lesson Image</th>
                    <th width="280px">Action</th>
                </tr>

                @if(isset($course['lessons']))
                    @foreach ($course['lessons'] as $lessonId => $lesson)
                    <tr>
                        <td>{{ $lessonId }}</td>
                        <td>{{ $lesson['title'] }}</td>
                        <td>
                            @if(isset($lesson['image']))
                                <img src="{{ asset('storage/' . $lesson['image']) }}" alt="Lesson Image" class="image-thumbnail">
                            @else
                                No image available
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('courses.lessons.destroy', [$id, $lessonId]) }}" method="POST">
                                <a class="btn btn-success" href="{{ route('courses.lessons.edit', [$id, $lessonId]) }}">Edit</a>
                                <a class="btn btn-primary" href="{{ route('courses.lessons.show', [$id, $lessonId]) }}">View</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No lessons available for this course.</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection