@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Lessons for {{ $course->name }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('courses.lessons.create', $course->id) }}"> Create New Lesson</a>
            </div>
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
            <th width="280px">Action</th>
        </tr>
        @php
            $i = 0;
        @endphp
        @foreach ($lessons as $lesson)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $lesson->title }}</td>
            <td>
                <form action="{{ route('courses.lessons.destroy', [$course->id, $lesson->id]) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('courses.lessons.show', [$course->id, $lesson->id]) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('courses.lessons.edit', [$course->id, $lesson->id]) }}">Edit</a>

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
