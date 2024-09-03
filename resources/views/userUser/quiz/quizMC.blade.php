@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - {{ $lesson->title }} - Quiz</h2>
            </div>

        </div>
    </div>


    <div class="row  justify-content-center mt-5 addborder ">



        <div class="col-lg-8 addborder" style="padding: 0;">

            <div class="row addborder" style="margin-bottom: 100px;">

                <div class="col-lg-10 addborder" style="padding: 0;">


                    <div class="col-lg-12 addborder" style="padding: 0; margin-bottom: 60px;">
                        <h2>Pick the correct answer</h2>
                    </div>


                    <div class=" col-lg-12 text-center addborder" style="padding: 0;">
                        <h3>Good Morning!</h3>
                    </div>

                </div>


                <div class="col-lg-2 addborder" style="padding: 0;">
                    <p> NO Side Testing Side</p>
                </div>

            </div>

            <div class="col-lg-10 addborder " style="padding: 0;">

                <div class="row justify-content-center ">
                    <button>hehe</button>
                    <button>hehe</button>
                </div>

                <div class="row justify-content-center">
                    <button>hehe</button>
                    <button>hehe</button>
                </div>


            </div>


        </div>








    </div>






</div>
@endsection