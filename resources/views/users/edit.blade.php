@extends('layouts.app')


@section('content')


<div class="main-container">



    <div class="container-fluid mb-1  ">
        <h1>Lessons - {{ $user->name }}</h1>
    </div>

    <div class="container-fluid ">

        <div class="container-fluid">
            <div class="row">


            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>User ID:</strong>
                            <input type="text" name="id" value="{{ $user->id }}" class="form-control" placeholder="User ID" readonly>

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Name:</strong>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="Name">

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Email:</strong>
                            <input type="text" name="email" value="{{ $user->email }}" class="form-control" placeholder="Email" readonly>

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Usertype:</strong>
                            <select name="Usertype" class="form-control">
                                <option value="admin" {{ $user->usertype === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ $user->usertype === 'user' ? 'selected' : '' }}>User</option>
                                <option value="contributor" {{ $user->usertype === 'contributor' ? 'selected' : '' }}>Contributor</option>
                            </select>
                        </div>
                    </div>







                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-view-courses">Submit</button>
                        <a class="btn btn-view-courses" href="{{ route('courses.index') }}"> Back</a>


                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


@endsection