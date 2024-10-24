@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row">
        <div class="col-lg-12">
            <h2>{{ $lesson['title'] }} - Edit Lesson</h2>
        </div>
    </div>

    <form action="{{ route('admin.lessons.update', [$courseId, $lessonId]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Title:</strong>
                    <input type="text" name="title" value="{{ $lesson['title'] }}" class="form-control" placeholder="Title" required>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="image"><strong>Lesson Image:</strong></label>
                    <input type="file" class="form-control" name="image" id="image">
                    <small>If you want to change the image, upload a new one. Otherwise, leave it blank.</small>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('admin.courses.show', $courseId) }}" class="btn btn-danger">Discard</a>
            </div>
        </div>
    </form>
</div>
@endsection
