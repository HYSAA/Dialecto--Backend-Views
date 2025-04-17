@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row mb-2">

        <div class="col-lg-12 margin-tb ">


            <div class="pull-left mb-2">
                <h2 id="title">User Report > Completed Lessons > {{$curUser[$id]['name']}} </h2>
            </div>

            <div class="d-flex mb-2 gap-2">


                <a class="btn btn-main " style="margin-right: 3px;" id="btn-learner" href="{{ route('admin.user-report') }}">Back</a>

            </div>
        </div>

    </div>

    <div class=" row" id="expertTable" style="overflow-y: auto;">
        <div class="col-lg-5 margin-tb mx-auto">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Course Name</th>
                    <th>Lesson Name</th>
                    <th>Proficiency</th>
                </tr>

                @if (!empty($completedLessons))
                @foreach($courses as $course)
                @if(isset($course['lessons']) && is_array($course['lessons']))
                @foreach($course['lessons'] as $lessonId => $lesson)
                @if(array_key_exists($lessonId, $completedLessons))
                <tr>
                    <td>{{ $course['name'] ?? 'N/A' }}</td>
                    <td>{{ $lesson['title'] ?? 'N/A' }}</td>
                    <td>{{ $lesson['proficiency_level'] ?? 'N/A' }}</td>
                </tr>
                @endif
                @endforeach
                @endif
                @endforeach
                @else
                <tr>
                    <td colspan="3" class="text-center fw-bold">Empty {{ $id }} report.</td>
                </tr>
                @endif
            </table>

        </div>
    </div>

</div>





@endsection