@extends('layouts.app')

@section('content')


<div class="main-container" style="padding: 15px;">
    <div class="pull-right " style="padding-bottom: 15px;">
        <a class="btn btn-main" href="{{ route('user.selectUserCourseLesson') }}"> Suggest A Word To Add</a>
    </div>

    <table class="table table-striped table-hover no-border " style="content:center">
        <tr>
            <th>Word</th>
            <th>English Equivalent</th>
            <th>Course</th>
            <th>Lesson</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <tr>
            <td>Word</td>
            <td>Definition Word</td>
            <td>Course ID</td>
            <td>Lesson ID</td>
            <td>Status</td>
            <td>Edit and Delete</td>
        </tr>
    </table>
</div>

<style>
    table.no-border td,table.no-border th {
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
</style>