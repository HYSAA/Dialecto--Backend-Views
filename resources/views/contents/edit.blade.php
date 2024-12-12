@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="row">

        <div class="col-lg-6">
            <h2>Edit Content</h2>
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

    <form action="{{ route('admin.contents.update', [$courseId, $lessonId, $contentId]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ $course['name'] }} Text</strong>
                    <textarea class="form-control" style="height:150px" name="text" placeholder="Dialect Text">{{ $content['text'] }}</textarea>
                </div>
                <div class="form-group">
                    <strong>English Text</strong>
                    <textarea class="form-control" style="height:150px" name="english" placeholder="English Text">{{ $content['english'] }}</textarea>
                </div>

                <div class="form-group">
                    <strong>Video:</strong>
                    <input type="file" name="video" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary" style="margin-bottom: 5px;">Save</button>
                <a class="btn btn-danger" style="margin-bottom: 5px;" href="{{ route('admin.lessons.show', [$courseId, $lessonId]) }}">Back</a>
            </div>
        </div>

    </form>
</div>

@endsection
