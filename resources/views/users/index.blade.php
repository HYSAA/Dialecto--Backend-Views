@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="container-fluid mb-2  ">
        <h1>Users</h1>

        <!-- change the set up -->

    </div>

    <!-- Card container -->


    <table class="table table-bordered">
        <tr>


            <th>User ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Usertype</th>

        </tr>



        @foreach ($users as $user)
        <tr>

            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->usertype }}</td>

            <td>

                <form action="{{ route('users.destroy', [$user->id]) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('users.show', [$user->id]) }}">Show</a>

                    <a class="btn btn-primary" href="{{ route('users.edit', [$user->id]) }}">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>


            </td>

        </tr>


</div>


</td>
</tr>
@endforeach
</table>

</div>

@endsection