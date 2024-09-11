@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row mb-2">

        <div class="col-lg-12 margin-tb">
            <div class="pull-left mb-2">
                <h2 id="title">Words From User</h2>
            </div>

            <div class="pull-right mb-2">
                <a class="btn btn-success" id="myWords" href="#">My Words Contributed</a>
                <a class="btn btn-dark" id="wordsUser" href="#">Words From User</a>

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



    <div class="row" style="overflow-y: auto;">

        <div class="col-lg-12 margin-tb">

            <table class="table table-striped table-bordered">

                <tr>
                    <th>Translation Text</th>
                    <th>English</th>
                    <th>Course</th>
                    <th>Lesson</th>
                    <th>Status</th>
                    <th width="280px">Action</th>
                </tr>


                @foreach($pendingWords as $word)
                <tr>
                    <td>{{ $word->text }}</td>
                    <td>{{ $word->english }}</td>
                    <td>{{ $word->course->name ?? 'No course found' }}</td>
                    <td>{{ $word->lesson->title ?? 'No lesson found' }}</td>


                    <td style="color: 
    @if ($word->status === 'approved')
        green;
    @elseif ($word->status === 'disapproved')
        red;
    @elseif ($word->status === 'pending')
        gray;
    @else
        black; /* Default color */
    @endif
">
                        {{ $word->status }}
                    </td>

                    <td>
                        <form action="{{ route('expert.approveWord', $word->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>

                        <form action="{{ route('expert.disapproveWord', $word->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Disapprove</button>
                        </form>
                    </td>
                </tr>
                @endforeach

            </table>
        </div>
    </div>



    <div class="row" id="approvedTable" style="overflow-y: auto; display: none;">asd</div>


</div>






<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initially show the pending table
        document.getElementById('pendingTable').style.display = 'block';
        document.getElementById('approvedTable').style.display = 'none';
        document.getElementById('deniedTable').style.display = 'none';

        document.getElementById('approvedBtn').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('pendingTable').style.display = 'none';
            document.getElementById('approvedTable').style.display = 'block';
            document.getElementById('deniedTable').style.display = 'none';
            document.getElementById('title').textContent = 'Approved Applications';

        });

        document.getElementById('pendingBtn').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('pendingTable').style.display = 'block';
            document.getElementById('approvedTable').style.display = 'none';
            document.getElementById('deniedTable').style.display = 'none';
            document.getElementById('title').textContent = 'Pending Unverified Experts';

        });

        document.getElementById('deniedBtn').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('pendingTable').style.display = 'none';
            document.getElementById('approvedTable').style.display = 'none';
            document.getElementById('deniedTable').style.display = 'block';
            document.getElementById('title').textContent = 'Denied Applications';

        });
    });
</script>
@endsection