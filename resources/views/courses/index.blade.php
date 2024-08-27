@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">

                <h2>Courses</h2>
            </div>
            <div class="pull-right ">
                <a class="btn btn-main" href="{{ route('admin.courses.create') }}"> Create Course</a>
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
                    <th>ID Number</th>
                    <th>Course Name</th>
                    <th>Course Description</th>
                    <th>Course Image</th>

                    <th width="280px">Action</th>
                </tr>

                @foreach ($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->description }}</td>

                    <td>
                        @if($course->image)
                        <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image" class="image-thumbnail">
                        @else
                        No image available
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST">
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-success ">Edit</a>
                            <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-primary">View</a>

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

@endsection