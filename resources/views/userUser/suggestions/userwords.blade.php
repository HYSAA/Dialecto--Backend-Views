@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-2">


        <div class="col-lg-12">

            <div class="pull-left mb-2">
                <h2 id="title">Pending Words</h2>
            </div>



            <div class="pull-left mb-2">
                <a class="btn btn-main" href="{{ route('user.selectUserCourseLesson') }}"> Suggest A Word</a>
            </div>

            <div class="d-flex mb-2 gap-2">



                <a class="btn btn-success" id="btn-approvedWords" href="#">Approved Words</a>

                <a class="btn btn-dark" id="btn-pendingWords" href="#">Pending Words</a>

                <a class="btn btn-danger" id="btn-deniedWords" href="#">Denied Words</a>






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
                    <th style="width: 150px;">Actions</th>
                </tr>


                @if (!empty($pending_words))

                @foreach ($pending_words as $wordId => $word)
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
                    <td>{{ $word['course_name'] ?? 'N/A' }}</td>
                    <td>{{ $word['lesson_name'] ?? 'N/A' }}</td>

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


                        <!-- <span>{{ route('user.viewUpdateSelected', ['id' => $word['user_id'] ?? $wordId]) }}</span> -->




                        <a href="{{ route('user.deleteSelectedWord', $wordId) }}" class="btn btn-danger {{ !$isClickable ? 'disabled' : '' }}">Delete</a>


                    </td>
                </tr>
                @endforeach
                @else
                <span style="font-weight: bold;">Pending words empty.</span>

                @endif




            </table>






        </div>
    </div>



    <div class="row" id="approvedTable" style="overflow-y: auto; display: none;">
        <div class="col-lg-12 margin-tb">

            <table class="table table-striped table-bordered" style="content:center">
                <tr>
                    <th>Word</th>
                    <th>English Equivalent</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Video</th>
                    <th>Status</th>

                </tr>


                @if (!empty($approved_words))

                @foreach ($approved_words as $wordId => $word)
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
                    <td>{{ $word['course_name'] ?? 'N/A' }}</td>
                    <td>{{ $word['lesson_name'] ?? 'N/A' }}</td>

                    <td style="display: flex; justify-content: center; align-items: center; height: 100%;">
                        <div class="box">
                            @if (isset($word['video']) && $word['video'] !== null && $word['video'] !== 'null')
                            <video controls class=" vid-content">
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


                </tr>
                @endforeach



                @else
                <span style="font-weight: bold;">Approved words empty.</span>

                @endif





            </table>



        </div>
    </div>


    <div class="row" id="deniedTable" style="overflow-y: auto; display: none;">
        <div class="col-lg-12 margin-tb">

            <table class="table table-striped table-bordered" style="content:center">
                <tr>
                    <th>Word</th>
                    <th>English Equivalent</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Video</th>
                    <th>Status</th>
                    <th style="width: 400px;">Remarks from experts</th>
                </tr>


                @if (!empty($disapproved_words))

                @foreach ($disapproved_words as $wordId => $word)
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
                    <td>{{ $word['course_name'] ?? 'N/A' }}</td>
                    <td>{{ $word['lesson_name'] ?? 'N/A' }}</td>

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
                        {{ $word['reason'] ?? 'N/A' }} <br>
                        <span style="color: green;">Comply and resubmit entry</span>


                    </td>
                </tr>
                @endforeach



                @else
                <span style="font-weight: bold;">Denied words empty.</span>

                @endif




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



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initially show the pending table
        // document.getElementById('wordsFromUser').style.display = 'block';
        // document.getElementById('myContributedWords').style.display = 'none';

        document.getElementById('btn-approvedWords').addEventListener('click', function(e) {
            e.preventDefault();

            document.getElementById('approvedTable').style.display = 'block';
            document.getElementById('pendingTable').style.display = 'none';
            document.getElementById('deniedTable').style.display = 'none';


            document.getElementById('title').textContent = 'Approved Words';

        });

        document.getElementById('btn-pendingWords').addEventListener('click', function(e) {
            e.preventDefault();

            document.getElementById('deniedTable').style.display = 'none';
            document.getElementById('pendingTable').style.display = 'block';
            document.getElementById('approvedTable').style.display = 'none';

            document.getElementById('title').textContent = 'Pending Words';

        });


        document.getElementById('btn-deniedWords').addEventListener('click', function(e) {
            e.preventDefault();

            document.getElementById('pendingTable').style.display = 'none';
            document.getElementById('deniedTable').style.display = 'block';
            document.getElementById('approvedTable').style.display = 'none';

            document.getElementById('title').textContent = 'Denied Words';

        });




    });
</script>

@endsection