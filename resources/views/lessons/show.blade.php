@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - Lesson: {{ $lesson->title }}</h2>
            </div>
            <div class="pull-right">
                <!-- <a class="btn btn-primary" href="{{ route('courses.lessons.index', $course->id) }}">Back to Lessons</a>  -->
                <a class="btn btn-primary" href="{{ route('courses.show', $course->id) }}">Back to Course</a>

                <a class="btn btn-success"
                    href="{{ route('courses.lessons.contents.create', [$course->id, $lesson->id]) }}">Add Content</a>


            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Content Text</th>
                        <th>English Text</th>
                        <th width="380px">Video</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lesson->contents as $content)
                    <tr>
                        <td>{{ $content->text }}</td>
                        <td>{{$content->english}}</td>
                        
                        <td>
                        @if ($content->video)
                            <video width="100%" height="100" controls>
                            <source src="{{ $content->video }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-info"
                                href="{{ route('courses.lessons.contents.show', [$course->id, $lesson->id, $content->id]) }}">Show</a>
                            <a class="btn btn-primary"
                                href="{{ route('courses.lessons.contents.edit', [$course->id, $lesson->id, $content->id]) }}">Edit</a>
                            <form
                                action="{{ route('courses.lessons.contents.destroy', [$course->id, $lesson->id, $content->id]) }}"
                                method="POST" style="display:inline">
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