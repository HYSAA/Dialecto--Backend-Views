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
                    <th>Status</th>
                    <th width="280px">Action</th>
                </tr>


                <!-- @foreach($userWords as $word)
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
        </div> -->
          @foreach($userWords as $word)
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


                @foreach($expertWords as $word)
                <tr>
                    <td>{{ $word->text }}</td>
                    <td>{{ $word->english }}</td>
                    <td>{{ $word->course->name ?? 'No course found' }}</td>
                    <td>{{ $word->lesson->title ?? 'No lesson found' }}</td>




                    <td style="display: flex; justify-content: center; align-items: center; height: 100%;">

                        <div class="box ">

                            @if ($word->video)
                            <video controls class="vid-content">
                                <source src="{{ $word->video }}" type="video/mp4">
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