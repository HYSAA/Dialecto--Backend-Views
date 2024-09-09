@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row">
        <div class="col-lg-12">

            <div class="pull-left">
                <h2>Profile </h2>
            </div>


            <div class="pull-right">

                <a class="btn btn-main" href="{{ route('expert.profile.edit', [$currentUserId->id]) }}">Edit Profile</a>

                <a class="btn btn-back-main" href="# ">Apply as verifier</a>

            </div>

        </div>
    </div>

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" value="{{ $currentUserId->name }}" class="form-control"
                    placeholder="Name" required>
            </div>

        </div>
        <div class="col-lg-12 margin-tb">

            <div class="form-group">
                <strong>Email:</strong>
                <input type="text" name="name" value="{{ $currentUserId->email }}" class="form-control"
                    placeholder="Email" required>
            </div>

        </div>
        




    </div>

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
    </div>







    
</div>


@endsection