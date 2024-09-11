@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-2">
        <div class="col-lg-12 ">
            <h2>Users</h2>
        </div>
    </div>


    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">
            <table class="table table-bordered">
                <tr>


                    <!-- <th>User ID</th> -->
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Usertype</th>
                    <th width="280px">Action</th>

                </tr>
                @foreach ($users as $user)
                <tr>

                    <!-- <td>{{ $user->id }}</td> -->
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->usertype }}</td>

                    <td>

                        <form action="{{ route('users.destroy', [$user->id]) }}" method="POST">
                            <a class="btn btn-success" href="{{ route('users.edit', [$user->id]) }}">Edit</a>
                            <a class="btn btn-primary" href="{{ route('users.show', [$user->id]) }}">View</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach

            </table>
        </div>
    </div>








</div>


</td>
</tr>

</table>

</div>

@endsection