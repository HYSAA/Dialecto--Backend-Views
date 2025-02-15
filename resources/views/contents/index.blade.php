@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ $course['name'] }} - Lesson: {{ $lesson['title'] }}</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('courses.lessons.index', $courseId) }}">Back to Lessons</a>
                    <a class="btn btn-success" href="{{ route('courses.lessons.contents.create', [$courseId, $lessonId]) }}">Add Content</a>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Content</th>
                            <th width="280px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contents as $contentId => $content)
                        <tr>
                            <td>
                                @if (isset($content['text']))
                                <p>{{ $content['text'] }}</p>
                                @endif
                                @if (isset($content['english']))
                                <p>{{ $content['english'] }}</p>
                                @endif
                                @if (isset($content['image']))
                                <img src="{{ $content['image'] }}" width="150px" alt="Content Image">
                                @endif
                                @if (isset($content['video']))
                                <video width="150px" controls>
                                    <source src="{{ $content['video'] }}" type="video/mp4">
                                </video>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-info" href="{{ route('courses.lessons.contents.show', [$courseId, $lessonId, $contentId]) }}">Show</a>
                                <a class="btn btn-primary" href="{{ route('courses.lessons.contents.edit', [$courseId, $lessonId, $contentId]) }}">Edit</a>
                                <form action="{{ route('admin.contents.destroy', [$courseId, $lessonId, $contentId]) }}" method="POST" style="display:inline">
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
</div>
@endsection
