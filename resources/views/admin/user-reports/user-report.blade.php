@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row mb-2">

        <div class="col-lg-12 margin-tb ">


            <div class="pull-left mb-2">
                <h2 id="title">User Report</h2>
            </div>

            <div class="d-flex mb-2 gap-2">


                <a class="btn btn-main " style="margin-right: 3px;" id="btn-learner" href="#">Learner</a>

                <a class="btn btn-dark " style="margin-right: 3px;" id="btn-expert" href="#">Experts</a>



            </div>
        </div>

    </div>

    @if (session('success'))

    <div class="row mb-2">

        <div class="col-lg-12 margin-tb">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    </div>


    @endif

    @if (session('error'))
    <div class="row mb-2">

        <div class="col-lg-12 margin-tb">
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        </div>
    </div>
    @endif


    <div class="row" id="expertTable" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Translation Text</th>
                    <th>English</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Suggested By</th>
                    <th>Video</th>
                    <th>Status</th>
                    <th>Approve Count</th>
                    <th width="280px">Action</th>
                </tr>

                @if (!empty($pending_words) && count($pending_words) > 0)
                @foreach($pending_words as $key => $word)
                <tr>
                    <td>{{ $word['text'] }}</td>
                    <td>{{ $word['english'] }}</td>
                    <td>{{ $word['course_name'] ?? 'No course found' }}</td>
                    <td>{{ $word['lesson_name'] ?? 'No lesson found' }}</td>
                    <td>{{ $word['user_name'] }}</td>
                    <td>{{ $word['video'] ?? 'N/A' }}</td>
                    <td>{{ $word['status'] ?? 'Pending' }}</td>
                    <td>{{ $word['approve_count'] ?? 0 }}</td>
                    <td>
                        <!-- Action buttons go here -->
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="9" style="text-align: center; font-weight: bold;">Empty expert report.</td>
                </tr>
                @endif
            </table>
        </div>
    </div>



    <div class="row" id="learnerTable" style="overflow-y: auto; display: none;">
        <div class="col-lg-5 margin-tb mx-auto">
            <table class="table table-striped table-bordered ">
                <tr>
                    <th>Full Name</th>
                    <th>Completed Lessons</th>
                    <th>Total Words Suggested</th>
                </tr>

                @if (!empty($users))
                @foreach($users as $key => $word)
                <tr>
                    <td>{{ $word['name'] }}</td>

                    <td class="text-end" style="color: {{ isset($word['completed_lessons']) && count($word['completed_lessons']) > 0 ? '#28a745' : '#dc3545' }}">
                        <div class="d-flex align-items-center justify-content-end gap-2">
                            <span>{{ isset($word['completed_lessons']) ? count($word['completed_lessons']) : 0 }}</span>
                            @if(isset($word['completed_lessons']) && count($word['completed_lessons']) > 0)
                            <a href="{{ route('admin.checkLessons', ['id' => $key]) }}" class="btn btn-sm btn-primary">Check</a>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled>Check</button>
                            @endif
                        </div>
                    </td>

                    <td class="text-end" style="color: {{ isset($word['suggestCount']) && $word['suggestCount'] > 0 ? '#28a745' : '#dc3545' }}">
                        <div class="d-flex align-items-center justify-content-end gap-2">
                            <span>{{ $word['suggestCount'] ?? 0 }}</span>
                            @if(isset($word['suggestCount']) && $word['suggestCount'] > 0)
                            <a href="{{ route('admin.checkSuggestions', ['id' => $key]) }}" class="btn btn-sm btn-primary">Check</a>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled>Check</button>
                            @endif
                        </div>
                    </td>



                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="3" style="text-align: center; font-weight: bold;">Empty learner report.</td>
                </tr>
                @endif
            </table>
        </div>
    </div>








    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initially show the pending table
            // document.getElementById('wordsFromUser').style.display = 'block';
            // document.getElementById('myContributedWords').style.display = 'none';


            document.getElementById('btn-learner').addEventListener('click', function(e) {

                e.preventDefault();

                document.getElementById('learnerTable').style.display = 'block';
                document.getElementById('expertTable').style.display = 'none';

                document.getElementById('title').textContent = 'User Leaner Report';


            });

            document.getElementById('btn-expert').addEventListener('click', function(e) {

                e.preventDefault();


                document.getElementById('learnerTable').style.display = 'none';
                document.getElementById('expertTable').style.display = 'block';

                document.getElementById('title').textContent = 'User Expert Report';


            });



        });
    </script>
    @endsection