@extends('layouts.app')

@section('content')
<div class="main-container">
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Content</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('courses.lessons.show', [$course->id, $lesson->id]) }}">Back</a>
                <!-- <a class="btn btn-success" href="{{ route('courses.lessons.contents.questions.create', [$course->id, $lesson->id, $content->id ?? 0]) }}">Manage Questions</a> -->
             
            </div>
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

    <form action="{{ route('courses.lessons.contents.store', [$course->id, $lesson->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Text:</strong>
                    <textarea class="form-control" style="height:150px" name="text" placeholder="Text"></textarea>
                </div>
                <div class="form-group">
                    <strong>English Equivalent:</strong>
                    <textarea class="form-control" style="height:150px" name="english" placeholder="English Equivalent"></textarea>
                </div>
                <div class="form-group">
                    <strong>Image:</strong>
                    <input type="file" name="image" class="form-control">
                </div>
                <div class="form-group">
                    <strong>Video:</strong>
                    <input type="file" name="video" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
</div>
</div>
@endsection
