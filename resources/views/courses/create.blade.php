@extends('layouts.app')

@section('content')


<div class="main-container">


    <div class="row">
        <div class="col-lg-6">
            <h1>Create Course</h1>
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


    <form action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Description:</strong>
                    <textarea class="form-control" style="height:150px" name="description" placeholder="Description"></textarea>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="image">Course Image:</label>
                    <input type="file" class="form-control" name="image" id="image" required>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <a class="btn btn-back" href="{{ route('courses.index') }}">Back</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>


</div>
@endsection