@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="container-fluid mb-2  ">
        <h1>Courses</h1>

        <!-- change the set up -->

        @if(Auth::user()->usertype == 'admin')
        <div class="pull-right">
            <a class="btn btn-view-courses" href="{{ route('courses.create') }}"> Create Nsssew Course</a>
        </div>
        @endif

    </div>


    @php
    $i = 0;
    @endphp


    <!-- Card container -->
    <div class="container-fluid card-container">

        @foreach ($courses as $course)
        <!-- Card item -->
        <div class="card ">
            <div class="top">


                <img src="{{ asset('images/cebuano.png') }}" alt="Card Image" class="card-img">


                <div class="row align-items-center mt-3 mb-3">
                    <div class="col-6 d-flex align-items-center">
                        <h3 class="card-title mb-0">{{ $course->name }}</h3>
                    </div>


                    <div class="col-6">
                        <div class="d-flex justify-content-between">

                            @if(Auth::user()->usertype == 'admin')
                            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-view-courses ">Edit</a>

                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-2 ">View</a>

                            @endif
                        </div>
                    </div>




                </div>
            </div>
            <div class="card-content">
                <!-- <h5>Regions:</h5>
                <p class="card-description">
                    Central Visayas, eastern Negros Island, Cebu, Bohol, Siquijor, parts of Leyte and Southern Leyte, Mindanao, and a few parts of Masbate.
                </p>
                <h5>Cities:</h5>
                <p class="card-description">
                    Cebu City, Davao City, Cagayan de Oro, and Dumaguete.
                </p>
                <h5>Fun Facts:</h5>
                <p class="card-description">
                    Cebuano is sometimes simply called "Bisaya," though Bisaya can also refer to other Visayan languages.
                </p> -->

                <h5>Description</h5>

                <p class="card-description">{{ $course->description }}</p>

            </div>
        </div>
        @endforeach

    </div>

    <!-- 
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Courses</h2>
            </div>

           


            @if(Auth::check() && Auth::user()->usertype != 'admin')
            <div class="pull-right">
                
                <a class="btn btn-primary" href="{{ route('courses.index') }}">View Available Courses</a>
            </div>
            @endif
        </div>
    </div> -->


</div>

@endsection