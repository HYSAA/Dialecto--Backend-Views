@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="row">
        <div class="col-lg-6">
            <h2>Add Lesson to {{ $course['name'] }}</h2>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('courses.lessons.store', $courseId) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Title:</strong>
                    <input type="text" name="title" class="form-control" placeholder="Title" required>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="image">Lesson Image:</label>
                    <input type="file" class="form-control" name="image" id="image" required>
                    <small class="form-text text-muted">Allowed file types: jpeg, png, jpg, gif, svg. Max size: 10MB.</small>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('courses.show', $courseId) }}" class="btn btn-danger">Discard</a>
            </div>
        </div>
    </form>
</div>
@endsection