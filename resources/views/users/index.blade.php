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
                    <th>User Lessons Progress</th>

                </tr>
                @foreach ($sortedUsers as $user)
                    @if ($user['id'] !== auth()->user()->firebase_id)
                        <tr>
                            <td>{{$user['id']}}</td>
                            <td>{{ $user['data']['name'] ?? 'N/A' }}</td>
                            <td>{{ $user['data']['email'] ?? 'N/A' }}</td>
                            <td>{{ $user['data']['usertype'] ?? 'N/A' }}</td>
                            <td>
                                <a class="btn btn-back-main" style="width: 100%; margin-bottom: 5px;" type="button" href="{{ route('users.show', [$user['id']]) }}">View User Progress</a>
                            </td>

                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>
</div>

@endsection