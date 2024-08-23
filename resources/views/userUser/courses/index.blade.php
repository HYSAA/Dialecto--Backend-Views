@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Courses</h2>
            </div>
        </div>
    </div>

    @php
    $i = 0;
    @endphp

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <div class="row">

                @foreach ($courses as $course)

                <div class="card mb-2 mr-2">
                    <div class="top">

                        <td>
                            @if($course->image)
                            <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image" class="card-img">
                            @else
                            <img src="{{ asset('images/cebuano.png') }}" alt="Course Image" class="card-img">
                            @endif
                        </td>







                        <div class="row align-items-center mt-3 mb-3 " style="height: 50px;">
                            <div class="col-6 d-flex align-items-center ">
                                <h3 class="card-title mb-0">{{ $course->name }}</h3>
                            </div>


                            <div class="col-6 d-flex justify-content-end ">

                                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-main pull-right ">View</a>

                            </div>
                        </div>

                    </div>
                    <div class="card-content">
                        <h5>Description</h5>
                        <p class="card-description">{{ $course->description }}</p>
                    </div>
                </div>

                @endforeach


            </div>


        </div>
    </div>














</div>

@endsection