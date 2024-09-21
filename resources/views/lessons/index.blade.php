@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="row" style="overflow-y: auto;">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    @if(isset($course))
                    <h2>Lessons for {{ $course['name'] }}</h2>
                    @else
                    <h2>All Lessons</h2>
                    @endif
                </div>
                @if(isset($course))
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('admin.lessons.create', $courseId) }}">Create New Lesson</a>
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
                <th>Actions</th>
            </tr>
            @php
            $i = 0;
            @endphp
            @foreach ($lessons as $id => $lesson)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $lesson['title'] }}</td>
                <td>
                    <form action="{{ route('admin.lessons.destroy', [$courseId, $id]) }}" method="POST">
                        <a class="btn btn-info" href="{{ route('admin.lessons.show', [$courseId, $id]) }}">Show</a>
                        <a class="btn btn-primary" href="{{ route('admin.lessons.edit', [$courseId, $id]) }}">Edit</a>

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
@endsection
