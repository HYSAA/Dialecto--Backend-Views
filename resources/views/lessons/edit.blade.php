@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row">
        <div class="col-lg-12">
            <h2>{{ $lesson->title }} - Edit Lesson</h2>
        </div>
    </div>

    <!-- @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif -->

    <form action="{{ route('admin.lessons.update', [$course->id, $lesson->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Title:</strong>
                    <input type="text" name="title" value="{{ $lesson->title }}" class="form-control" placeholder="Title">
                </div>
            </div>



            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-danger" href="{{ route('admin.courses.index') }}">Discard</a>
            </div>




        </div>
    </form>
</div>
@endsection