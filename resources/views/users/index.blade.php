@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row mb-2">
        <div class="col-lg-12">
            <h2>Current Users of Dialecto</h2>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-lg-12">
            <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by name, email, or user type" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">
            @php
                $sortedUsers = collect($filteredUsers)->sortBy(function ($user) {
                    return $user['data']['usertype'] ?? '';
                });
            @endphp

            <table class="table table-bordered">
                <tr>
                    <th>User Full Name</th>
                    <th>User Email</th>
                    <th>User Type</th>
                    <!-- <th>View User Progress</th> -->
                </tr>
                @foreach ($sortedUsers as $user)
                    <tr>
                        <td>{{ $user['data']['name'] ?? 'N/A' }}</td>
                        <td>{{ $user['data']['email'] ?? 'N/A' }}</td>
                        <td>{{ $user['data']['usertype'] ?? 'N/A' }}</td>
                        <!-- <td>
                            <a class="btn btn-back-main" style="width: 100%; margin-bottom: 5px;" type="button" href="{{ route('users.show', [$user['id']]) }}">View User Progress</a>
                        </td> -->
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

@endsection