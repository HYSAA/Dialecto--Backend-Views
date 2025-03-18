
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

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <div class="row">

                @if ($course['lessons'] ?? null)

                @foreach ($course['lessons'] as $lessonId => $lesson)

                <div class="card mb-2 mr-2">
                    <div class="top">

                        <td>
                            @if(isset($lesson['image']))
                            <img src="{{ $lesson['image'] }}" alt="Lesson Image" class="card-img">
                            @else
                            <img src="{{ asset('images/cebuano.png') }}" alt="Lesson Image" class="card-img">
                            @endif
                        </td>

                        <div class="row align-items-center mt-3" style="height: 50px;">
                            <div class="col-7 d-flex align-items-center">
                                <h3 class="card-title mb-0">{{ $lesson['title'] ?? 'Unknown Title' }}</h3>
                            </div>

                            <div class="col-5 d-flex justify-content-end addborder pr-3">
                                <a href="{{ route('admin.lessons.show', [$id, $lessonId]) }}" class="btn btn-main pull-right" style="width: 100%;">View Contents</a>
                            </div>
                        </div>

                        <div class="row align-items-center justify-content-end" style="height: 50px;">
                            <div class="col-5 d-flex justify-content-end" style="padding: 0;">
                                <div class="col-6 d-flex justify-content-end" style="padding: 0;">
                                    <a href="{{ route('admin.lessons.edit', [$id, $lessonId]) }}" class="btn btn-success btn-sm" style="width: 100%;">Edit</a>
                                </div>

                                <div class="col-6 d-flex justify-content-end" style="padding: 0;">
                                    <form action="{{ route('admin.lessons.destroy', [$id, $lessonId]) }}" method="POST" style="display: inline; margin: 0; padding: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%; margin: 0;">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-content" style="overflow-y: auto;">
                        <h5>Description</h5>
                        <p class="card-description">{{ $lesson['description'] ?? 'No description available' }}</p>
                    </div>
                </div>

                @endforeach

                @else
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pull-left mb-2">
                            <strong>There are no lessons.</strong>
                        </div>
                    </div>
                </div>
                @endif

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

@endsection