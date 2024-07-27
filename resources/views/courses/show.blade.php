@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - Lessons</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('courses.index') }}">Back to Courses</a>
                <a class="btn btn-success" href="{{ route('courses.lessons.create', $course->id) }}">Add Lesson</a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Lesson Title</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($course->lessons as $lesson)
                        <tr>
                            <td>{{ $lesson->title }}</td>
                            <td>
                                <a class="btn btn-info" href="{{ route('courses.lessons.show', [$course->id, $lesson->id]) }}">Show</a>
                                <a class="btn btn-primary" href="{{ route('courses.lessons.edit', [$course->id, $lesson->id]) }}">Edit</a>
                                <form action="{{ route('courses.lessons.destroy', [$course->id, $lesson->id]) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection