@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row">
        <div class="col-lg-12">

            <div class="pull-left mb-2">
                <h2>Profile </h2>
            </div>


            <div class="pull-right mb-2">

                <span class="btn " style="background-color: #d4edda; color: #155724;border-color: #c3e6cb; box-sizing: border-box; border-radius: 4px; text-align: center; text-decoration: none; box-shadow: none;">
                    Verified Expert for {{$currentUserId->credential->language_experty}}</span>

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