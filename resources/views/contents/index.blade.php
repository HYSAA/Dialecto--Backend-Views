@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - {{ $lesson->title }} - Contents</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('courses.lessons.contents.create', [$course->id, $lesson->id]) }}"> Create New Content</a>
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
            <th>Text</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($contents as $content)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $content->text }}</td>
            <td>
                <form action="{{ route('courses.lessons.contents.destroy', [$course->id, $lesson->id, $content->id]) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('courses.lessons.contents.show', [$course->id, $lesson->id, $content->id]) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('courses.lessons.contents.edit', [$course->id, $lesson->id, $content->id]) }}">Edit</a>

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
