@extends('layouts.app')

@section('content')
<div class="main-container">
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Content</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('courses.lessons.show', [$courseId, $lessonId]) }}">Back</a>
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

    <form action="{{ route('courses.lessons.contents.update', [$courseId, $lessonId, $contentId]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Text:</strong>
                    <textarea class="form-control" style="height:150px" name="text" placeholder="Text">{{ $content['text'] }}</textarea>
                </div>
                <div class="form-group">
                    <strong>English Equivalent:</strong>
                    <textarea class="form-control" style="height:150px" name="english" placeholder="English Equivalent">{{ $content['english'] }}</textarea>
                </div>
                <div class="form-group">
                    <strong>Image:</strong>
                    <input type="file" name="image" class="form-control">
                    @if ($content['image'])
                        <img src="{{ $content['image'] }}" width="300px" class="mt-2">
                    @endif
                </div>
                <div class="form-group">
                    <strong>Video:</strong>
                    <input type="file" name="video" class="form-control">
                    @if ($content['video'])
                        <video width="150px" controls>
                            <source src="{{ $content['video'] }}" type="video/mp4">
                        </video>
                    @endif
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
