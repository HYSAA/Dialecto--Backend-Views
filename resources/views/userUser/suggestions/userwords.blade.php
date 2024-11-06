@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-2">
        <div class="col-lg-12">
            <div class="pull-left mb-2">
                <a class="btn btn-main" href="{{ route('user.selectUserCourseLesson') }}"> Suggest A Word</a>
            </div>

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
        </div>
    </div>

    <div class="row" id="pendingTable" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <table class="table table-striped table-bordered" style="content:center">
                <tr>
                    <th>Word</th>
                    <th>English Equivalent</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Video</th>
                    <th>Status</th>
                    <th style="width: 280px;">Actions</th>
                </tr>

                @foreach ($suggestedWords as $wordId => $word)
                @php
                $statusColor = 'black';
                $statusText = 'N/A';

                if (isset($word['status'])) {
                $statusText = $word['status'];
                switch ($statusText) {
                case 'approved':
                $statusColor = 'green';
                break;
                case 'disapproved':
                $statusColor = 'red';
                break;
                case 'pending':
                $statusColor = 'gray';
                break;
                }
                }

                // Button should be clickable only if the status is 'pending'
                $isClickable = $statusText === 'pending';
                @endphp
                <tr>
                    <td>{{ $word['text'] ?? 'N/A' }}</td>

                    <td>{{ $word['english'] ?? 'N/A' }}</td>
                    <td>{{ $word['courseName'] ?? 'N/A' }}</td>
                    <td>{{ $word['lessonTitle'] ?? 'N/A' }}</td>

                    <td style="display: flex; justify-content: center; align-items: center; height: 100%;">
                        <div class="box">
                            @if (isset($word['video']) && $word['video'] !== null && $word['video'] !== 'null')
                            <video controls class="vid-content">
                                <source src="{{ $word['video'] }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            @else
                            No video available
                            @endif
                        </div>
                    </td>

                    <td style="color: {{ $statusColor }}">
                        {{ $statusText }}
                    </td>

                    <td>


                        <span>{{ route('user.viewUpdateSelected', ['id' => $word['user_id'] ?? $wordId]) }}</span>

                        <a class="btn btn-success {{ !$isClickable ? 'disabled' : '' }}"
                            href="{{ $isClickable ? route('user.viewUpdateSelected', $wordId ) : '#' }}"
                            {{ !$isClickable ? 'aria-disabled=true' : '' }}>
                            Update Selected
                        </a>


                        <a href="{{ route('user.deleteSelectedWord', $wordId) }}" class="btn btn-danger {{ !$isClickable ? 'disabled' : '' }}">Delete</a>


                    </td>
                </tr>
                @endforeach

            </table>

        </div>
    </div>
</div>

<style>
    table.no-border td,
    table.no-border th {
        border: none;
        padding: 10px;
    }

    table.no-border tr {
        border-bottom: 1px solid #ddd;
    }

    td {
        text-align: left;
        justify-content: center;
    }

    .disabled {
        pointer-events: none;
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

@endsection