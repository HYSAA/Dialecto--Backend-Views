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

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <div class="row">

                @foreach($courses as $id => $course)

                <div class="card mb-2 mr-2">
                    <div class="top">

                        <td>
                            @if($course['image'])
                            <img src="{{ $course['image'] }}" alt="Course Image" class="card-img">
                            @else
                            <img src="{{ asset('images/cebuano.png') }}" alt="Course Image" class="card-img">
                            @endif
                        </td>

                        <div class="row align-items-center mt-3" style="height: 50px;">
                            <div class="col-7 d-flex align-items-center">
                                <h3 class="card-title mb-0"  style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;">{{ $course['name'] }}</h3>
                            </div>

                            <div class="col-5 d-flex justify-content-end pr-1" style="padding: 0;">
                                <a href="{{ route('admin.courses.show', $id) }}" class="btn btn-main pull-right" style="width: 100%;">View</a>
                            </div>
                        </div>

                        <div class="row align-items-center justify-content-end" style="height: 50px;">
                            <div class="col-5 d-flex justify-content-end pr-1" style="padding: 0;">
                                <div class="col-6 d-flex justify-content-end" style="padding: 0;margin-right: 5px;">
                                    <a href="{{ route('admin.courses.edit', $id) }}" class="btn btn-success btn-sm" style="width: 100%;">Edit</a>
                                </div>
                                <div class="col-6 d-flex justify-content-end" style="padding: 0;">
                                    <button type="button" class="btn btn-danger btn-sm delete-course" data-course-id="{{ $id }}" style="width: 100%; margin: 0;">Delete</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-content" style="overflow-y: auto;">
                        <h5>Description</h5>
                        <p class="card-description">{{ $course['description'] ?? 'No description available'}}</p>
                    </div>
                </div>

                @endforeach

            </div>

        </div>
    </div>

</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCourseModal" tabindex="-1" role="dialog" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Centered modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCourseModalLabel">Confirm Delete</h5>
               
            </div>
            <div class="modal-body">
                Are you sure you want to delete this course?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteCourseForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        margin: 1px;
        padding: 10px;
        flex: 1 1 calc(33.33% - 20px);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteButtons = document.querySelectorAll('.delete-course');
        var deleteForm = document.getElementById('deleteCourseForm');

        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var courseId = this.getAttribute('data-course-id');
                var action = "{{ route('admin.courses.destroy', ':id') }}";
                action = action.replace(':id', courseId);
                deleteForm.setAttribute('action', action);
                $('#deleteCourseModal').modal('show');
            });
        });
    });
</script>

@endsection