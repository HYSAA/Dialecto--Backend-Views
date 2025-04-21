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

                @foreach($courses as $courseID => $courseData)

                @if(isset($courseData['lessons']) && is_array($courseData['lessons']))

                @foreach($lessons as $lessonID => $lessonData)

                @foreach($completedLessons as $compID => $compData)

                @if($compID == $lessonID)


                <tr>

                    <td>{{ $compID ?? 'N/A' }}</td>
                    <td>{{ $courseData['name'] ?? 'N/A' }}</td>
                    <td>{{ $lessonData['title'] ?? 'N/A' }}</td>
                </tr>


                @endif

                @endforeach
                @endforeach
                @endif
                @endforeach








                <tr>
                    <td colspan="3" class="text-center fw-bold">Empty {{ $id }} report.</td>
                </tr>

            </table>

        </div>
    </div>

</div>





@endsection