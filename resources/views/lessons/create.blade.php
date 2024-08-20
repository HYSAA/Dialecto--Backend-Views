@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row">
        <div class="col-lg-6">
            <h2>Add Lesson to {{ $course->name }}</h2>
        </div>

    </div>



    <!-- <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('courses.lessons.index', $course->id) }}"> Back</a>
            </div> -->



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

    <form action="{{ route('courses.lessons.store', $course->id) }}" method="POST" enctype="multipart/form-data">


        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Title:</strong>
                    <input type="text" name="title" class="form-control" placeholder="Title">
                </div>
            </div>


            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="image">Lesson Image:</label>
                    <input type="file" class="form-control" name="image" id="image" required>
                </div>
            </div>






            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-danger">Discard</a>
            </div>
        </div>

    </form>
</div>
@endsection