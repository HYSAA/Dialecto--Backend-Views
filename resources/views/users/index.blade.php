@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-2">
        <div class="col-lg-12">
            <h2>Users</h2>
        </div>
    </div>

    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">
            @php
                // Convert array to collection and sort by usertype
                $sortedUsers = collect($filteredUsers)->sortBy(function ($user) {
                    return $user['data']['usertype'] ?? '';
                });
            @endphp

            <table class="table table-bordered">
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Usertype</th>
                </tr>
                @foreach ($sortedUsers as $user)
                    <tr>
                        <td>{{ $user['data']['name'] ?? 'N/A' }}</td>
                        <td>{{ $user['data']['email'] ?? 'N/A' }}</td>
                        <td>{{ $user['data']['usertype'] ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

@endsection