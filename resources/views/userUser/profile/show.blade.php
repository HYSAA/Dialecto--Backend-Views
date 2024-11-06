@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row">
        <div class="col-lg-12">

            <div class="pull-left mb-2">
                <h2>Profile </h2>
            </div>

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif


            <div class="pull-right mb-2">

                @if ($credentials)
                <!-- Show "Pending" if credentials boolean is 1 -->
                <span class="btn " style="background-color: #d4edda; color: #155724;border-color: #c3e6cb; box-sizing: border-box; border-radius: 4px; text-align: center; text-decoration: none; box-shadow: none;">
                    Pending Expert Approval</span>
                @else
                <!-- Show "Apply as verifier" button if credentials boolean is 0 -->
                <a class="btn btn-back-main" href="{{ route('user.profile.applyExpert', ['id' => $userId]) }}">Apply as verifier</a>

                @endif


            </div>

        </div>
    </div>




    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <div class="form-group">
                <strong>Name:</strong><span> {{ $user['name'] ?? 'N/A' }}</span>

            </div>

        </div>
        <div class="col-lg-12 margin-tb">

            <div class="form-group">

                <strong>Email:</strong><span> {{ $user['email'] ?? 'N/A' }}</span>
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