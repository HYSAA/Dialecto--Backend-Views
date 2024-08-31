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

    <form action="{{ route('admin.contents.update', [$course->id, $lesson->id, $content->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Text:</strong>
                    <textarea class="form-control" style="height:150px" name="text" placeholder="Text">{{ $content->text }}</textarea>
                </div>
                <div class="form-group">
                    <strong>Image:</strong>
                    <input type="file" name="image" class="form-control">
                    @if ($content->image)
                    <img src="{{ $content->image }}" width="300px" class="mt-2">
                    @endif
                    <strong>Video:</strong>
                    <input type="file" name="video" class="form-control">
                    @if ($content->video)
                    <video width="150px" controls>
                        <source src="{{ $content->video }}" type="video/mp4">
                    </video>
                    @endif



                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-danger" href="{{ route('admin.courses.index') }}">Back</a>

            </div>
        </div>

    </form>
</div>

@endsection