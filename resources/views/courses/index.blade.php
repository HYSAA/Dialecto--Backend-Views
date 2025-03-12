@extends('layouts.app')

@section('content')

<div class="main-container" style="overflow:auto">
    <!-- Header Row -->
    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Courses</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-main" href="{{ route('admin.courses.create') }}">Create Course</a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if ($message = Session::get('success'))
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>
    @endif

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

                        <!-- test design -->





                        <div class="row align-items-center mt-3  " style="height: 50px;">
                            <div class="col-7 d-flex align-items-center ">
                                <h3 class="card-title mb-0">{{ $course['name'] }}</h3>
                            </div>

                            <div class="col-5 d-flex justify-content-end addborder pr-3">

                                <a href="{{ route('admin.courses.show', $id) }}" class="btn btn-main pull-right" style="width: 100%;">Views</a>

                            </div>
                        </div>



                        <div class="row align-items-center justify-content-end " style="height: 50px;">

                            <div class="col-5 d-flex justify-content-end   " style="padding: 0;">


                                <div class="col-6 d-flex justify-content-end   " style="padding: 0;">
                                    <a href="{{ route('admin.courses.edit', $id) }}" class="btn btn-success btn-sm" style="width: 100%;">Edit</a>
                                </div>


                                <div class="col-6 d-flex justify-content-end   " style="padding: 0; ">
                                    <form action="{{ route('admin.courses.destroy', $id) }}" method="POST" style="display: inline; margin: 0; padding: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%; margin: 0;">Delete</button>
                                    </form>
                                </div>








                            </div>
                        </div>






                    </div>
                    <div class="card-content" style="overflow-y: auto;">
                        <h5>Description</h5>
                        <p class="card-description">{{ $course['description'] }}</p>
                    </div>
                </div>

                @endforeach


            </div>





        </div>
    </div>



















</div>


<style>
    .card {
        margin: 1px;
        /* Adjust to control the space between cards */
        padding: 10px;
        /* Adjust to control space inside cards */

        flex: 1 1 calc(33.33% - 20px);
        /* This allows the cards to have space and be responsive */

    }
</style>
@endsection