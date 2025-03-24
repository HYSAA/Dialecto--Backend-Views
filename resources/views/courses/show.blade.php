@extends('layouts.app')

@section('content')

<div class="main-container" style="overflow:auto">

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Lessons - {{ $course['name'] ?? 'Unknown Course' }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-back-main" href="{{ route('admin.courses.index') }}">Back To Courses</a>
                <a class="btn btn-main" href="{{ route('admin.lessons.create', $id) }}">Create Lesson</a>
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

    <div class="row">

        @if ($course['lessons'] ?? null)

        @foreach ($course['lessons'] as $lessonId => $lesson)
        <div class="col-md-3 mb-4">
            <div class="card d-flex flex-column" style="max-height: 450px; overflow-y: auto;">
                <div class="card-body">
                    <h5 class="card-title" style="height: 30px;padding: 5px;white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;">{{ $lesson['title'] ?? 'Unknown Title' }}</h5>
                    <p class="card-text" style="padding:5px;"><strong>Proficiency Level:</strong> {{ $lesson['proficiency_level'] ?? 'N/A' }}</p>
                    <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                        @if(isset($lesson['image']))
                        <img src="{{ $lesson['image'] }}" alt="Lesson Image" class="card-img-top" style="object-fit: contain; max-height: 200px; max-width: 200px;">
                        @else
                        <p>No image available</p>
                        @endif
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a class="btn btn-success mb-2" href="{{ route('admin.lessons.edit', [$id, $lessonId]) }}">Edit</a>
                    <a class="btn btn-primary mb-2" href="{{ route('admin.lessons.show', [$id, $lessonId]) }}">View Contents</a>
                    <a class="btn btn-back-main mb-2" href="{{ route('admin.quizzes.index', [$id, $lessonId]) }}">Quizzes</a>


                    <button type="button" class="btn btn-danger mb-2 delete-btn" data-lesson-id="{{ $lessonId }}">
                        Delete
                    </button>

                    <!-- Delete Form -->
                    <form id="deleteForm{{ $lessonId }}" action="{{ route('admin.lessons.destroy', [$id, $lessonId]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal ni -->
        <div id="deleteModal{{ $lessonId }}" class="custom-modal">
            <div class="custom-modal-content">
             
                <p>Are you sure you want to delete this lesson?</p>
                <div class="custom-modal-actions">
                    <button class="custom-btn custom-btn-secondary cancel-btn" data-lesson-id="{{ $lessonId }}">Cancel</button>
                    <button class="custom-btn custom-btn-danger confirm-delete-btn" data-lesson-id="{{ $lessonId }}">Delete</button>
                </div>
            </div>
        </div>
        @endforeach

        @else
        <div class="row">
            <div class="col-lg-12">
                <div class="pull-left mb-2">
                    <strong>There are no lessons. </strong>
                </div>
            </div>
        </div>
        @endif

    </div>

</div>


<style>
   
    .custom-modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); 
    }

    /* Modal Content */
    .custom-modal-content {
        background-color: #fff;
        margin: 20% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 90%;
        max-width: 500px; 
        border-radius: 8px;
        text-align: center;
        position: relative;
    }


    .custom-close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .custom-close:hover,
    .custom-close:focus {
        color: black;
    }

    .custom-modal-actions {
        margin-top: 20px;
    }

    .custom-btn {
        padding: 10px 20px;
        margin: 0 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .custom-btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .custom-btn-danger {
        background-color: #dc3545;
        color: white;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const lessonId = this.getAttribute('data-lesson-id');
                const modal = document.getElementById(`deleteModal${lessonId}`);
                modal.style.display = 'block';
            });
        });

        // Close modal 
        document.querySelectorAll('.custom-close, .cancel-btn').forEach(button => {
            button.addEventListener('click', function () {
                const lessonId = this.getAttribute('data-lesson-id');
                const modal = document.getElementById(`deleteModal${lessonId}`);
                modal.style.display = 'none';
            });
        });

        // submit ang form 
        document.querySelectorAll('.confirm-delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const lessonId = this.getAttribute('data-lesson-id');
                const form = document.getElementById(`deleteForm${lessonId}`);
                form.submit();
            });
        });

        // close and modal ig mo click gawas
        window.addEventListener('click', function (event) {
            if (event.target.classList.contains('custom-modal')) {
                event.target.style.display = 'none';
            }
        });
    });
</script>

@endsection