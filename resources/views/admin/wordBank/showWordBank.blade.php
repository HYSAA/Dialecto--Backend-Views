@extends('layouts.app')

@section('content')

<div class="main-container">
    <div class="row mb-2">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Word Bank Select Course</h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 margin-tb" style="padding: 0;">
            <div class="row">


                @if($courses)
                @foreach ($courses as $courseID => $course)

                @if($course['notif'] == true)

                <div class="wordBankCard" style="overflow: hidden; box-shadow: rgba(228, 44, 12, 0.89) 0px 3px 8px;">

                    @else


                    <div class="wordBankCard" style="overflow: hidden;">

                        @endif



                        <div style="width: 100%; height: 150px;">
                            @if(!empty($course['image']))
                            <img src="{{ $course['image'] }}" alt="Course Image" class="image-thumbnail">

                            @else
                            No image available
                            @endif
                        </div>

                        <div class="row" style="height: 100%;">
                            <div class="col-lg-6 pt-2">
                                {{ $course['name'] }}
                            </div>

                            <div class="col-lg-6 text-center pt-2">
                                <a href="{{ route('admin.wordBankCourse', $courseID) }}" class="btn btn-main pull-right" style="width: 100%;">View</a>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @else
                    <div class="col-lg-12">
                        <strong>No words found.</strong>
                    </div>

                    @endif




                </div>
            </div>
        </div>
    </div>

    @endsection