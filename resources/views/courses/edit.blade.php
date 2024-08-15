@extends('layouts.app')


@section('content')


<div class="main-container">



    <div class="container-fluid mb-1  ">
        <h1>Lessons - {{ $course->name }}</h1>
    </div>

    <div class="container-fluid ">

        <div class="container-fluid">
            <div class="row">


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

            <form action="{{ route('courses.update', $course->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            <input type="text" name="name" value="{{ $course->name }}" class="form-control" placeholder="Name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Description:</strong>
                            <textarea class="form-control" style="height:150px" name="description" placeholder="Description">{{ $course->description }}</textarea>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-view-courses">Submit</button>
                        <a class="btn btn-view-courses" href="{{ route('courses.index') }}"> Back</a>
                        <a class="btn btn-view-courses" href="{{ route('courses.index') }}"> Edit</a>

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


@endsection