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
                        <form action="{{ route('admin.courses.destroy', $id) }}" method="POST" style="display: inline;" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm delete-btn">Delete</button>
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

<!-- Custom Confirmation Modal -->
<div id="confirmModal" class="modal hidden">
    <div class="modal-content">
        <h3>Confirm Deletion</h3>
        <p>Are you sure you want to delete this course?</p>
        <div class="modal-buttons">
            <button id="confirmYes" class="btn btn-danger">Yes</button>
            <button id="confirmNo" class="btn btn-secondary">No</button>
        </div>
    </div>
</div>

<style>
.card {
    margin: 1px;
    padding: 10px;
    flex: 1 1 calc(33.33% - 20px);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    width: 90%;
    max-width: 400px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    text-align: center;
}

.modal-content h3 {
    color: #dc3545;
    margin-bottom: 15px;
}

.modal-content p {
    color: #333;
    margin-bottom: 20px;
}

.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.btn {
    padding: 8px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* Hidden class to ensure modal is hidden on page load */
.hidden {
    display: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('.delete-form');
    const modal = document.getElementById('confirmModal');
    const confirmYes = document.getElementById('confirmYes');
    const confirmNo = document.getElementById('confirmNo');
    let activeForm = null;

    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            activeForm = this;
            modal.classList.remove('hidden');
        });
    });

    confirmYes.addEventListener('click', function() {
        if (activeForm) {
            activeForm.submit();
        }
        modal.classList.add('hidden');
    });

    confirmNo.addEventListener('click', function() {
        modal.classList.add('hidden');
        activeForm = null;
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
            activeForm = null;
        }
    });

    // Ensure modal is hidden on page load
    modal.classList.add('hidden');
});
</script>
@endsection