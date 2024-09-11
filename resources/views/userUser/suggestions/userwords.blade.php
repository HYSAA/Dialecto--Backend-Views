@extends('layouts.app')

@section('content')


<div class="main-container">


    <div class="row mb-2">

        <div class="col-lg-12 ">
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


            <table class="table table-striped table-bordered " style="content:center">
                <tr>
                    <th>Word</th>
                    <th>English Equivalent</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Status</th>
                    <th style="width: 280px;">Actions</th>
                </tr>
                <!-- @foreach ($suggestedWords as $word)
             @php
                $isClickable = $word->status === 'pending';
                $rowClass = $isClickable ? '' : 'disabled-row';
            @endphp
            <tr>
                <td>{{ $word->text }}</td>
                <td>{{ $word->english }}</td>
                <td>{{ $word->course->name }}</td>
                <td>{{ $word->lesson->title }}</td>
                <td>{{ $word->status }}</td>
                <td>
                    @if ($isClickable)
                        <a class="btn btn-main" href="{{ route('user.viewUpdateSelected', ['id' => $word->id]) }}">Update Selected</a>
                            <a class="btn btn-main">Delete</a>
                        @else
                            <span class="btn btn-main disabled">Update Selected</span>
                            <span class="btn btn-main disabled">Delete</span>
                        @endif
                    </td>
                </tr>
        @endforeach -->
                @foreach ($suggestedWords as $word)
                @php
                $isClickable = $word->status === 'pending';
                @endphp
                <tr>
                    <td>{{ $word->text }}</td>
                    <td>{{ $word->english }}</td>
                    <td>{{ $word->course->name }}</td>
                    <td>{{ $word->lesson->title }}</td>

                    <td style="color: {{ $word->status === 'approved' ? 'green' : 'red' }};">
                        {{ $word->status }}
                    </td>


                    <td>
                        <a class="btn btn-success {{ !$isClickable ? 'disabled' : '' }}"
                            href="{{ $isClickable ? route('user.viewUpdateSelected', ['id' => $word->id]) : '#' }}" {{ !$isClickable ? 'aria-disabled="true"' : '' }}>
                            Update Selected
                        </a>


                        <a href="{{ route('user.deleteSelectedWord', ['id' => $word->id]) }}" class="btn btn-danger {{ !$isClickable ? 'disabled' : '' }}">Delete</a>




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

    /* Add underline for each row */
    table.no-border tr {
        border-bottom: 1px solid #ddd;
    }

    td {
        text-align: left;
        justify-content: center;
    }

    .disabled {
        pointer-events: none;
        /* Disables click events for buttons */
        opacity: 0.6;
        /* Optional: makes the button look disabled */
        cursor: not-allowed;
        /* Optional: changes the cursor to indicate the button is not clickable */
    }
</style>