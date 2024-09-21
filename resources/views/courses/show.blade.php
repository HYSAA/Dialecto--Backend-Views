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
                    <th>Lesson Image</th>
                    <th width="280px">Action</th>
                </tr>
                @foreach ($course['lessons'] ?? [] as $lessonId => $lesson)
                <tr>
                    <td>{{ $lesson['title'] ?? 'Unknown Title' }}</td>
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
                        <!-- Use `$id` for the course id instead of `$course['id']` if necessary -->
                        <form action="{{ route('admin.lessons.destroy', [$id, $lessonId]) }}" method="POST">
                            <a class="btn btn-success" href="{{ route('admin.lessons.edit', [$id, $lessonId]) }}">Edit</a>
                            <a class="btn btn-primary" href="{{ route('admin.lessons.show', [$id, $lessonId]) }}">View</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

</div>
@endsection
