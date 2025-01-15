@extends('layouts.app')

@section('content')

<div class="main-container" style="overflow:auto">
    <!-- Header Row -->
    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Courses</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-main" href="{{ route('admin.courses.create') }}">Create Course</a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if ($message = Session::get('success'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Courses as Cards -->
    <div class="row g-2">
        @if(count($courses) > 0)
            @foreach ($courses as $id => $course)
            <div class="col-md-4 mb-10 px-10 ">
                <div class="card shadow-sm h-100">
                    <!-- Course Image -->
                    <img 
                        src="{{ $course['image'] ?? 'https://via.placeholder.com/150' }}" 
                        class="card-img-top" 
                        alt="Course Image" 
                        style="object-fit: cover; height: 200px;">

                    <!-- Card Body -->
                    <div class="card-body">
                        <h5 class="card-title">{{ $course['name'] }}</h5>
                        <p class="card-text" style="max-height: 100px; overflow-y: auto;">
                            {{ $course['description'] }}
                        </p>
                    </div>
                    

                    <!-- Card Footer -->
                    <div class="card-footer d-flex justify-content-center gap-2">
                        <a href="{{ route('admin.courses.show', $id) }}" class="btn btn-primary btn-sm">View</a>
                        <a href="{{ route('admin.courses.edit', $id) }}" class="btn btn-success btn-sm">Edit</a>
                        <form action="{{ route('admin.courses.destroy', $id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <div class="col-lg-12">
            <p>No courses found.</p>
        </div>
        @endif
    </div>
</div>


<style>
    .card {
    margin: 1px; /* Adjust to control the space between cards */
    padding: 10px; /* Adjust to control space inside cards */
 
    flex: 1 1 calc(33.33% - 20px); /* This allows the cards to have space and be responsive */
    
}

</style>
@endsection
