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
                    <th>Title</th>
                    <th>Lesson Image</th>

                    <th width="280px">Action</th>
                </tr>

                @foreach ($course->lessons as $lesson)
                <tr>
                    <td>{{ $lesson->id }}</td>
                    <td>{{ $lesson->title }}</td>

                    <td>
                        @if($course->image)
                        <!-- <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image" class="image-thumbnail"> -->
                        @else
                        No image available
                        @endif
                    </td>




                    <td>
                        <form action="{{ route('courses.lessons.destroy', [$course->id, $lesson->id]) }}" method="POST">


                            <a class="btn btn-success" href="{{ route('courses.lessons.edit', [$course->id, $lesson->id]) }}">Edit</a>
                            <a class="btn btn-primary" href="{{ route('courses.lessons.show', [$course->id, $lesson->id]) }}">View</a>


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



</div>