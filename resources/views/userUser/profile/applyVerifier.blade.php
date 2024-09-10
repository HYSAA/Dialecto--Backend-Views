@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row">
        <div class="col-lg-12">

            <div class="pull-left mb-2">
                <h2>Submit Expert Credential Form </h2>

            </div>




        </div>
    </div>




    <form action="{{ route('user.profile.postCredentials') }}" method="POST" enctype="multipart/form-data">


        @csrf

        <div class="row">


            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Language Experties:</strong>
                    <select name="langExperties" id="experties" class="form-control" required>
                        <option value="" disabled selected>Select a language expertise</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->name }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">

                <h1 style="color: grey;">To apply as an expert in a language, one must submit a certificate verifying that the user is a BEED teacher.</h1>

                <div class="form-group ">
                    <strong>Credentials</strong>

                    <input type="file" class="form-control" name="image" id="image" required>
                </div>
            </div>






            <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label for="image"><strong>Course Image:</strong> </label>
                            <input type="file" class="form-control" name="image" id="image">
                        </div>
                    </div> -->




            <div class="col-xs-12 col-sm-12 col-md-12 text-center">


                <button type="submit" class="btn btn-primary">Save</button>


                <!-- <a class="btn btn-danger" href="{{ route('admin.courses.index') }}">Back</a> -->

            </div>


        </div>
    </form>

















</div>


@endsection