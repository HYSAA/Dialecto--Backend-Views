@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row mb-2">

        <div class="col-lg-12 margin-tb">
            <div class="pull-left mb-2">
                <h2 id="title">Words From User</h2>
            </div>

            <div class="pull-right mb-2">
                <a class="btn btn-success" id="btn-myContributedWords" href="#">My Words Contributed</a>
                <a class="btn btn-dark" id="btn-wordsFromUser" href="#">Words From User</a>


                <a class="btn btn-main" id="btn-contribute" href="{{route('expert.contributeWord')}}">Contribute Words</a>

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

    <div class="row" id="wordsFromUser" style="overflow-y: auto;">

        <div class="col-lg-12 margin-tb">

            <table class="table table-striped table-bordered">

                <tr>
                    <th>Translation Text</th>
                    <th>English</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Video</th>
                    <th>Status</th>
                    <th>Approve</th>
                    <th>Denied</th>
                    <th width="280px">Action</th>
                </tr>
                @foreach($userWords as $key => $word)
                <tr>
                    <td>{{ $word['text'] }}</td>
                    <td>{{ $word['english'] }}</td>
                    <td>{{ $word['course_name'] ?? 'No course found' }}</td>
                    <td>{{ $word['lesson_title'] ?? 'No lesson found' }}</td>

                    <td style="display: flex; justify-content: center; align-items: center; height: 100%;">
                        <div class="box ">
                            @if ($word['video'])
                            <video controls class="vid-content">
                                <source src="{{ $word['video'] }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            @else
                            No video available
                            @endif
                        </div>
                    </td>


                    <td style="color: 
    @if ($word['status'] === 'approved')
        green;
    @elseif ($word['status'] === 'disapproved')
        red;
    @elseif ($word['status'] === 'pending')
        gray;
    @else
        black; /* Default color */
    @endif
">
                        {{ $word['status'] }}
                    </td>



                    <td style="color: green;">{{ $word['approve_count'] }}/3</td>

                    <td style="color: red;">{{ $word['disapproved_count'] }}</td>

                    @if((isset($word['approved']) && $word['approved'] === true) || (isset($word['approved']) && $word['approved'] === false))
                    <td>
                        <!-- Form for Approving -->
                        <form action="{{ route('expert.approveWord', $key) }}" method="POST" style="display:inline;">
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-success"
                                style="pointer-events: none; opacity: 0.6; cursor: not-allowed;"
                                @if($word['status']==='approved' ) disabled @endif>
                                Approve
                            </button>
                        </form>

                        <!-- Form for Disapproving -->
                        <form action="{{ route('expert.disapproveWord', $key) }}" method="POST" style="display:inline;">
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-danger"
                                style="pointer-events: none; opacity: 0.6; cursor: not-allowed;"
                                @if($word['status']==='disapproved' ) disabled @endif>
                                Disapprove
                            </button>
                        </form>
                    </td>


                    @else


                    <td>
                        <!-- Form for Approving -->
                        <form action="{{ route('expert.approveWord', $key) }}" method="POST" style="display:inline;">
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-success"
                                @if($word['status']==='approved' ) disabled @endif>
                                Approve
                            </button>
                        </form>

                        <!-- Form for Disapproving -->
                        <form action="{{ route('expert.disapproveWord', $key) }}" method="POST" style="display:inline;">
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-danger"
                                @if($word['status']==='disapproved' ) disabled @endif>
                                Disapprove
                            </button>
                        </form>
                    </td>

                    @endif













                </tr>
                @endforeach

            </table>
        </div>
    </div>



    <div class="row" id="myContributedWords" style="overflow-y: auto; display: none;">
        <div class="col-lg-12 margin-tb">

            <table class="table table-striped table-bordered">

                <tr>
                    <th>Translation Text</th>
                    <th>English</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Video</th>

                </tr>

                @foreach($expertWords as $key => $word)
                <tr>
                    <td>{{ $word['text'] }}</td>
                    <td>{{ $word['english'] }}</td>
                    <td>{{ $word['course_name'] ?? 'No course found' }}</td>
                    <td>{{ $word['lesson_title'] ?? 'No lesson found' }}</td>




                    <td style="display: flex; justify-content: center; align-items: center; height: 100%;">

                        <div class="box ">

                            @if ($word['video'])
                            <video controls class="vid-content">
                                <source src="{{ $word['video'] }}" type="video/mp4">
                            </video>
                            @else
                            No video available
                            @endif

                        </div>

                </tr>
                @endforeach

            </table>
        </div>
    </div>


</div>






<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initially show the pending table
        // document.getElementById('wordsFromUser').style.display = 'block';
        // document.getElementById('myContributedWords').style.display = 'none';

        document.getElementById('btn-wordsFromUser').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('myContributedWords').style.display = 'none';
            document.getElementById('wordsFromUser').style.display = 'block';

            document.getElementById('title').textContent = 'Words From User';

        });

        document.getElementById('btn-myContributedWords').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('myContributedWords').style.display = 'block';
            document.getElementById('wordsFromUser').style.display = 'none';

            document.getElementById('title').textContent = 'My Contributed Words';

        });


    });
</script>
@endsection