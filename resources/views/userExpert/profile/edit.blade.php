@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row">
        <div class="col-lg-12">

            <div class="pull-left">
                <h2>Profile </h2>
            </div>


            <div class="pull-right">

            <form action="{{ route('expert.update', $user->id) }}" method="POST"></form>

                <a class="btn btn-main" href="{{ route('expert.profile.update', [$currentUserId->id]) }}">Edit Profiless</a>

                

            </div>

        </div>
    </div>  




    
</div>


@endsection