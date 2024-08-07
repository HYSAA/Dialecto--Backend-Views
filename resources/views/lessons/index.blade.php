@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                @if(isset($course))
                    <h2>Lessons for {{ $course->name }}</h2>
                @else
                    <h2>All Lessons</h2>
                @endif
            </div>
            @if(isset($course))
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('courses.lessons.create', $course->id) }}">Create New Lesson</a>
                </div>
            @endif
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Course</th>
            <th width="280px">Action</th>
        </tr>
        @php
            $i = 0;
        @endphp
        @foreach ($lessons as $lesson)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $lesson->title }}</td>
            <td>{{ $lesson->course->name }}</td>
            <td>
                <form action="{{ route('courses.lessons.destroy', [$lesson->course->id, $lesson->id]) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('courses.lessons.show', [$lesson->course->id, $lesson->id]) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('courses.lessons.edit', [$lesson->course->id, $lesson->id]) }}">Edit</a>

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
