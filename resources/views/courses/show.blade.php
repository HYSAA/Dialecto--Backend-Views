@extends('layouts.app')

@section('content')

@endsection


<div class="main-container">

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">

                <h2>Lessons - {{ $course->name }}{{ $course->course_id }}</h2>

            </div>

            <div class="pull-right ">
                <a class="btn btn-main" href="{{ route('courses.lessons.create', $course->id) }}">Create Lesson</a>
            </div>

        </div>
    </div>


    @if ($message = Session::get('success'))

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>

    @endif

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">
            <table class="table table-bordered">
                <tr>
                    <th>Lesson ID</th>
                    <th>Course Name</th>
                    <th>Course Image</th>

                    <th width="280px">Action</th>
                </tr>

                @foreach ($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->name }}</td>

                    <td>
                        @if($course->image)
                        <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image" class="image-thumbnail">
                        @else
                        No image available
                        @endif
                    </td>




                    <td>
                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST">
                            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-success ">Edit</a>
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary">View</a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>

    </div>


    @if(Auth::user()->usertype == 'user')


    <div class="container-fluid card-container">
        @foreach ($course->lessons as $lesson)

        <div class="cardsmall">

            <div class="img-cont">
                <img src="{{ asset('images/cebuano.png') }}" alt="Card Image">
            </div>

            <div class="heading">
                <h4>{{ $lesson->title }}</h4>
            </div>


            <div class="button-container">
                <a class="btn btn-view-courses" href="{{ route('courses.lessons.show', [$course->id, $lesson->id]) }}">Show</a>
                <a class="btn btn-2" href="{{ route('courses.lessons.edit', [$course->id, $lesson->id]) }}">Edit</a>
                <form action="{{ route('courses.lessons.destroy', [$course->id, $lesson->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-3">Delete</button>
                </form>
            </div>

        </div>
        @endforeach
    </div>
    @endif
</div>