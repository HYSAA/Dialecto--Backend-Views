@extends('layouts.app')

@section('content')

<div class="main-container">
    @if(Auth::user()->usertype == 'user')

    <div class="row">
        <div class="col-lg-12 ">
            <h1>Courses</h1>
        </div>
    </div>


    @php
    $i = 0;
    @endphp

    <!-- Card container -->
    <!-- <div class="container-fluid card-container"> -->
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


                    <div class="col-6 d-flex justify-content-end ">
                        <!-- <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-main ">Edit</a> -->
                        <a href="{{ route('courses.show', $course->id) }}" class="btn btn-2 pull-right ">View</a>

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
    @endif



    @if(Auth::user()->usertype == 'admin')

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">

                <h2>Courses</h2>

            </div>

            <div class="pull-right ">
                <a class="btn btn-main" href="{{ route('courses.create') }}"> Create Course</a>
            </div>

        </div>
    </div>


    @if ($message = Session::get('success'))

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>

    @endif


    <div class="row" style="overflow-y: auto;">

        <div class="col-lg-12 margin-tb">



            <table class="table table-bordered">
                <tr>
                    <th>ID Number</th>
                    <th>Course Name</th>
                    <th>Course Image</th>

                    <th width="280px">Action</th>
                </tr>

                @foreach ($courses as $course)
                <tr>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->name }}</td>

                    <td>
                        @if($course->image)
                        <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image" class="image-thumbnail">
                        @else
                        No image available
                        @endif
                    </td>




                    <td>
                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST">
                            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-success ">Edit</a>
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary">View</a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>

        </div>
    </div>



    @endif






</div>

@endsection