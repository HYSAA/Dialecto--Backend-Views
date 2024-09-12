@extends('layouts.app')

@section('content')


<div class="main-container" style="padding: 15px; max-height: max-content; overflow-y: auto;"">
    <div class="pull-right " style="padding-bottom: 15px;">
        <a class="btn btn-main" href="{{ route('user.selectUserCourseLesson') }}"> Suggest A Word To Add</a>
    </div>

    <table class="table table-striped table-hover no-border ">
        <tr>
            <th>Word</th>
            <th>English Equivalent</th>
            <th>Course</th>
            <th>Lesson</th>
            <th>Status</th>
            <th colspan="2">Actions</th>
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
                    <td>{{ $word->status }}</td>
                    <td>
                        <a class="btn btn-main {{ !$isClickable ? 'disabled' : '' }}"
                            href="{{ $isClickable ? route('user.viewUpdateSelected', ['id' => $word->id]) : '#' }}" {{ !$isClickable ? 'aria-disabled="true"' : '' }}>
                            Update Selected
                        </a>
                        <!-- <a class="btn btn-main" >Delete</a> -->
                        <form action="{{ route('user.removeWordSuggested', ['id' => $word->id]) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this word?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-main">Delete</button>
                        </form>
                    </td>
                </tr>
        @endforeach

    </table>
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

    /* .disabled-row {
        pointer-events: none;
        opacity: 0.6;
    }

    .disabled {
        pointer-events: none;
        opacity: 0.6;
    } */
</style>