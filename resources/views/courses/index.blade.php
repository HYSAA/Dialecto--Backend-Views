@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Courses</h2>
            </div>

            <!-- Button for admins to create new courses -->
            @if(Auth::user()->usertype == 'admin')
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('courses.create') }}"> Create New Course</a>
            </div>
            @endif

            <!-- Button for users to view available courses (if needed) -->
            @if(Auth::check() && Auth::user()->usertype != 'admin')
            <div class="pull-right">
                <!-- If you want a button to redirect users to a different page or filter courses -->
                <a class="btn btn-primary" href="{{ route('courses.index') }}">View Available Courses</a>
            </div>
            @endif
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Description</th>
            @if(Auth::user()->usertype == 'admin')
            <th width="280px">Action</th>
            @endif
        </tr>
        @php
            $i = 0;
        @endphp
        @foreach ($courses as $course)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $course->name }}</td>
            <td>{{ $course->description }}</td>
            @if(Auth::user()->usertype == 'admin')
            <td>
                <form action="{{ route('courses.destroy', $course->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('courses.show', $course->id) }}">Show Lessons</a>
                    <a class="btn btn-primary" href="{{ route('courses.edit', $course->id) }}">Edit</a>

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
            @endif
        </tr>
        @endforeach
    </table>
</div>
@endsection
