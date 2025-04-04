@extends('layouts.app')

@section('content')

<div class="main-container" style="overflow:auto">

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

    @if ($courses)

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <div class="row">

                @foreach($courses as $id => $course)

                <div class="card mb-2 mr-2">
                    <div class="top">

                        <td>
                            @if($course['image'])
                            <img src="{{ $course['image'] }}" alt="Course Image" class="card-img">
                            @else
                            <img src="{{ asset('images/cebuano.png') }}" alt="Course Image" class="card-img">
                            @endif
                        </td>


                        <div class="d-flex align-items-center mt-3 mb-3" style="height: 50px; padding: 15px;">
                            <h3 class="mb-0 me-2" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;">
                                {{ $course['name'] }}
                            </h3>
                            <a href="{{ route('user.courses.show', $id) }}" class="btn btn-main">View</a>
                        </div>

                    </div>
                    <div class="card-content" style="overflow-y: auto;">
                        <h5>Description</h5>
                        <p class="card-description">{{ $course['description'] ?? 'No description available'}}</p>
                    </div>
                </div>

                @endforeach


            </div>





        </div>
    </div>

    @else


    <div class="row">
        <div class="col-lg-12">
            <div class="pull-left mb-2">
                <strong>There are no courses available. </strong>
            </div>
        </div>
    </div>


    @endif




</div>

@endsection