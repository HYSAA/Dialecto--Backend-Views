@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row mb-2">

        <div class="col-lg-12 margin-tb ">


            <div class="pull-left mb-2">
                <h2 id="title">Pending Words</h2>
            </div>

            <div class="d-flex mb-2 gap-2">


                <a class="btn btn-success " style="margin-right: 3px;" id="btn-approved" href="#">Approved</a>

                <a class="btn btn-dark " style="margin-right: 3px;" id="btn-pending" href="#">Pending</a>

                <a class="btn btn-danger" id="btn-disapproved" href="#">Disapproved</a>


                <a class="btn btn-success ml-auto" style="margin-right: 3px;" id="btn-myContributedWords" href="#">My Words Contributed</a>



                <a class="btn btn-main" id=" btn-contribute" href="{{route('expert.contributeWord')}}">Contribute Words</a>








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







    <div class="row" id="pendingTable" style="overflow-y: auto;">

        <div class="col-lg-12 margin-tb">

            <table class="table table-striped table-bordered">

                <tr>
                    <th>Translation Text</th>
                    <th>English</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Video</th>
                    <th>Status</th>
                    <th>Approve Count</th>

                    <th width="280px">Action</th>
                </tr>

                @if (!empty($pending_words))

                @foreach($pending_words as $key => $word)
                <tr>
                    <td>{{ $word['text'] }}</td>


                    <!-- <td>{{ $key }}</td> -->




                    <td>{{ $word['english'] }}</td>
                    <td>{{ $word['course_name'] ?? 'No course found' }}</td>
                    <td>{{ $word['lesson_name'] ?? 'No lesson found' }}</td>



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



                    <td style="color: green;">
                        {{ isset($word['approve_count']) ? $word['approve_count'] : 0 }}/3
                    </td>





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

                            <!-- Hidden input to send userId via POST -->
                            <input type="hidden" name="userId" value="{{ $word['user_id'] }}">

                            <button
                                type="submit"
                                class="btn btn-success"
                                @if($word['status']==='approved' ) disabled @endif>
                                Approve
                            </button>
                        </form>


                        <!-- Form for Disapproving -->
                        <button type="button"
                            class="btn btn-danger"
                            onclick="openCustomModal('{{ $key }}', '{{ $word['user_id'] }}')"
                            @if($word['status']==='disapproved' ) disabled @endif>
                            Disapprove
                        </button>



                    </td>

                    @endif
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

            <table class="table table-striped table-bordered">

                <tr>
                    <th>Translation Text</th>
                    <th>English</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Video</th>
                    <th>Status</th>
                    <th>Approve</th>

                </tr>

                @if (!empty($approved_words))

                @foreach($approved_words as $key => $word)
                <tr>
                    <td>{{ $word['text'] }}</td>


                    <!-- <td>{{ $key }}</td> -->




                    <td>{{ $word['english'] }}</td>
                    <td>{{ $word['course_name'] ?? 'No course found' }}</td>
                    <td>{{ $word['lesson_name'] ?? 'No lesson found' }}</td>



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



                    <td style="color: green;">
                        {{ isset($word['approve_count']) ? $word['approve_count'] : 0 }}/3
                    </td>








                    @if((isset($word['approved']) && $word['approved'] === true) || (isset($word['approved']) && $word['approved'] === false))



                    @else




                    @endif




                </tr>
                @endforeach


                @else
                <span style="font-weight: bold;">Approved words empty.</span>

                @endif

            </table>
        </div>
    </div>

    <div class="row" id="disapprovedTable" style="overflow-y: auto; display: none;">

        <div class="col-lg-12 margin-tb">

            <table class="table table-striped table-bordered">

                <tr>
                    <th>Translation Text</th>
                    <th>English</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Video</th>
                    <th>Status</th>
                    <th style="width: 300px;">Remarks</th>

                </tr>

                @if (!empty($disapproved_words))

                @foreach($disapproved_words as $key => $word)
                <tr>
                    <td>{{ $word['text'] }}</td>


                    <!-- <td>{{ $key }}</td> -->




                    <td>{{ $word['english'] }}</td>
                    <td>{{ $word['course_name'] ?? 'No course found' }}</td>
                    <td>{{ $word['lesson_name'] ?? 'No lesson found' }}</td>



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
                    <td>
                        {{ $word['reason'] }} <br>

                    </td>











                </tr>
                @endforeach


                @else
                <span style=" font-weight: bold;">Dispproved words empty.</span>

                @endif

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

                    <td>{{ $word['lesson_name'] ?? 'No lesson foundss' }}</td>




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


    <!-- Disapprove Modal (Initially Hidden) -->
    <div id="customDisapproveModal" style="display: none;">
        <div class="modal-content2">
            <h3>Disapprove Word</h3>


            <!-- for debugging -->





            <form id="customDisapproveForm" action="" method="POST">
                @csrf
                <input type="hidden" name="word_id" id="customWordId">

                <input type="hidden" name="user_id" id="customUserId">

                <label for="customReason">Reason for Disapproval:</label>
                <textarea name="reason" id="customReason" rows="3" required></textarea>
                <br><br>

                <button type="submit" class=" btn btn-danger">Submit</button>
                <button type="button" id="closeCustomModal" class="btn btn-gray">Cancel</button>
            </form>
        </div>
    </div>



</div>






<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initially show the pending table
        // document.getElementById('wordsFromUser').style.display = 'block';
        // document.getElementById('myContributedWords').style.display = 'none';




        document.getElementById('btn-approved').addEventListener('click', function(e) {

            e.preventDefault();

            document.getElementById('myContributedWords').style.display = 'none';



            document.getElementById('approvedTable').style.display = 'block';
            document.getElementById('pendingTable').style.display = 'none';
            document.getElementById('disapprovedTable').style.display = 'none';

            document.getElementById('title').textContent = 'Approved Words';


        });

        document.getElementById('btn-pending').addEventListener('click', function(e) {

            e.preventDefault();

            document.getElementById('myContributedWords').style.display = 'none';



            document.getElementById('approvedTable').style.display = 'none';
            document.getElementById('pendingTable').style.display = 'block';
            document.getElementById('disapprovedTable').style.display = 'none';

            document.getElementById('title').textContent = 'Pending Words';


        });

        document.getElementById('btn-disapproved').addEventListener('click', function(e) {

            e.preventDefault();

            document.getElementById('myContributedWords').style.display = 'none';
            document.getElementById('approvedTable').style.display = 'none';
            document.getElementById('pendingTable').style.display = 'none';
            document.getElementById('disapprovedTable').style.display = 'block';

            document.getElementById('title').textContent = 'Denied Words';


        });


        document.getElementById('btn-myContributedWords').addEventListener('click', function(e) {
            e.preventDefault();



            document.getElementById('myContributedWords').style.display = 'block';


            document.getElementById('approvedTable').style.display = 'none';
            document.getElementById('pendingTable').style.display = 'none';
            document.getElementById('disapprovedTable').style.display = 'none';


            document.getElementById('title').textContent = 'My Contributed Words';

        });


    });


    function openCustomModal(wordId, userID) {


        // for debugging
        console.log("Word ID Clicked:", wordId);
        console.log("user ID Clicked:", userID);



        // end of debug



        document.getElementById("customWordId").value = wordId;

        document.getElementById("customUserId").value = userID;


        document.getElementById("customDisapproveForm").action = "/expert/disapprove-word/" + wordId;
        document.getElementById("customDisapproveModal").style.display = "flex";
    }

    document.getElementById("closeCustomModal").addEventListener("click", function() {
        document.getElementById("customDisapproveModal").style.display = "none";
    });

    window.addEventListener("click", function(event) {
        var modal = document.getElementById("customDisapproveModal");
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
</script>
@endsection