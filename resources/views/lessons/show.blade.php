@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course['name'] }} - Lessons: {{ $lesson['title'] }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-back-main" href="{{ route('admin.courses.show', [$courseId]) }}"> Back To Lesson</a>

                <a class="btn btn-main" href="{{ route('admin.contents.create', [$courseId, $lessonId]) }}">Add Contents</a>
            </div>

        </div>
    </div>

    <div class="row" style="overflow-y: auto;">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ $course['name'] }} Text</th>
                        <th>English Text</th>
                        <th width="380px">Video</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contents as $contentId =>$content)
                    <tr>
                        <td>{{ $content['text'] }}</td>
                        <td>{{ $content['english'] }}</td>
                        <td class=" centered-flex">
                            @if ($content['video'])
                            <video controls class="vid-content ">
                                <source src="{{ $content['video'] }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            @else
                            No video available
                            @endif
                        </td>
                        <td>
                            <!-- Edit Button -->
                            <a class="btn btn-sm btn-primary" href="{{ route('admin.contents.edit', [$courseId, $lessonId, $contentId]) }}">Edit</a>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.contents.destroy', [$courseId, $lessonId, $contentId]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this content?')">Delete</button>
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