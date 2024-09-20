@extends('layouts.app')

@section('content')

@endsection


<div class="main-container">

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">

                <h2>Lessons - {{ $course->name }}</h2>

            </div>

            <div class="pull-right ">
                <a class="btn btn-main" href="{{ route('admin.lessons.create', $course->id) }}">Create Lesson</a>
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
                    <!-- <th>Lesson ID</th> -->
                    <th>Title</th>
                    <th>Lesson Image</th>

                    <th width="280px">Action</th>
                </tr>

                @foreach ($course['lessons'] ?? [] as $lessonId => $lesson)
<tr>
    <td>{{ $lesson['title'] }}</td>
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