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
        @if(count($courses) > 0)
            <table class="table table-bordered">
                <tr>

                    <th>Course Name</th>
                    <th>Course Description</th>
                    <th>Course Image</th>

                    <th width="280px">Action</th>
                </tr>

                @foreach ($courses as $id => $course)
<tr>
    <td>{{ $course['name'] }}</td>
    <td>{{ $course['description'] }}</td>
    <td style="width: 150px;">
        <div style="width: 150px; height: 150px;">
            @if(isset($course['image']))
                <img src="{{ $course['image'] }}" alt="Course Image" class="image-thumbnail">
            @else
                No image available
            @endif
        </div>
    </td>
    <td>
        <form action="{{ route('admin.courses.destroy', $id) }}" method="POST">
            <a href="{{ route('admin.courses.edit', $id) }}" class="btn btn-success">Edit</a>
            <a href="{{ route('admin.courses.show', $id) }}" class="btn btn-primary">View</a>
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </td>
</tr>
@endforeach
@else
    <p>No courses found.</p>
@endif
            </table>

        </div>
    </div>

</div>

@endsection