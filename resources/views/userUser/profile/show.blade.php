@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row">
        <div class="col-lg-12">

            <div class="pull-left mb-2">
                <h2>Profile </h2>
            </div>

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif


            <div class="pull-right mb-2">

                @if ($credentials['status'] == 'pending' && !empty($credentials))
                <!-- Show "Pending" if credentials boolean is 1 -->
                <span class="btn " style="background-color: #d4edda; color: #155724;border-color: #c3e6cb; box-sizing: border-box; border-radius: 4px; text-align: center; text-decoration: none; box-shadow: none;">
                    Pending Expert Approval</span>


                @else


                <a class="btn btn-back-main" href="{{ route('user.profile.applyExpert', ['id' => $userId]) }}">Apply as verifier</a>

                @if ($credentials['status'] == 'denied' )

                <a class="btn btn-back-main" href="{{ route('user.profile.applyExpert', ['id' => $userId]) }}" style="pointer-events: none; cursor: not-allowed; background-color: #f8d7da; color: #721c24; border-color: #f5c6cb;">Application has been denied. Submit another application.</a>
                @endif



                @endif




            </div>

        </div>
    </div>




    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <div class="form-group">
                <strong>Name:</strong><span> {{ $user['name'] ?? 'N/A' }}</span>

            </div>

        </div>
        <div class="col-lg-12 margin-tb">

            <div class="form-group">

                <strong>Email:</strong><span> {{ $user['email'] ?? 'N/A' }}</span>
            </div>

        </div>





    </div>

    <div class="card-container"
        style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 20px; overflow-y: auto;">

        @if ($courses)

        @foreach($courses as $courseId => $courseData)
        <!-- Row Header for Course Name -->
        <div style="grid-column: span 4; margin-bottom: 10px;">
            <span style="font-weight: bold; font-size: 18px;">{{ $courseData['name'] }}</span>
        </div>

        @foreach($lessonWithScore as $id => $lesson)
        <div class="card"
            style="border: 1px solid #ddd; border-radius: 10px; padding: 15px; text-align: center; max-height: 300px; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
            <h5 style="margin: 10px 0; height: 25px; overflow: auto;">{{ $lesson['score'] }}</h5>
        </div>
        @endforeach
        @endforeach

        @else
        <!-- Code to execute when $courses is not set or empty -->

        <div class="row">
            <div class="col-lg-12">
                <div class="pull-left mb-2">
                    <strong>Take quizes to show badges </strong>
                </div>
            </div>
        </div>


        @endif

    </div>




    <!-- 
    <div class="row">
        <div class="col-lg-12">
            <div class="pull-left">
                <h2>Badges </h2>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            here goes the badges
        </div>
    </div> -->








</div>


@endsection