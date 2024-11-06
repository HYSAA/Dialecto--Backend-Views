@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-2">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left mb-2">
                <h2 id="title">Pending Unverified Experts</h2>
            </div>

            <div class="pull-right mb-2">
                <a class="btn btn-success" id="approvedBtn" href="#">Approved Experts</a>
                <a class="btn btn-dark" id="pendingBtn" href="#">Pending Unverified Experts</a>
                <a class="btn btn-danger" id="deniedBtn" href="#">Denied Applications</a>
            </div>

            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

        </div>
    </div>

    <div class="row" id="pendingTable" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <table class="table table-striped table-bordered">

                <tbody>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>List of Languages</th>
                        <th>Credentials</th>
                        <th width=" 280px">Action</th>
                    </tr>


                    @foreach ($unverifiedUsers as $userId => $user) <!-- Loop through unverified users -->
                    @if (isset($userDetails[$userId])) <!-- Check if user details exist for this user -->
                    @php
                    $userDetail = $userDetails[$userId]; // Get the user details for the current user
                    @endphp
                    <tr>
                        <td>{{ $userDetail['name'] }}</td>
                        <td>{{ $userDetail['email'] }}</td>
                        <td>{{ $user['courseName'] ?? 'N/A' }}</td>
                        <td style="width: 350px;">
                            <div style="width: 350px; height: 350px;">
                                @if($user['image'])
                                <img src="{{ $user['image'] }}" alt="Credential Image" class="image-thumbnail">
                                @else
                                No image available
                                @endif
                            </div>
                        </td>
                        <td>
                            <a class="btn btn-success" href="{{ route('admin.postVerify', ['id' => $userId]) }}">Verify</a>
                            <a class="btn btn-danger" href="{{ route('admin.postDeny', ['id' => $userId]) }}">Deny</a>

                        </td>
                    </tr>
                    @endif
                    @endforeach

                </tbody>

            </table>
        </div>
    </div>






    <div class="row" id="approvedTable" style="overflow-y: auto; display: none;">
        <div class="col-lg-12 margin-tb">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>List of Languages</th>
                    <th>Credentials</th>

                </tr>


                @foreach ($verifiedUsers as $userId => $user) <!-- Loop through unverified users -->
                @if (isset($userDetails[$userId])) <!-- Check if user details exist for this user -->
                @php
                $userDetail = $userDetails[$userId]; // Get the user details for the current user
                @endphp
                <tr>
                    <td>{{ $userDetail['name'] }}</td>
                    <td>{{ $userDetail['email'] }}</td>
                    <td>{{ $user['courseName'] ?? 'N/A' }}</td>
                    <td style="width: 350px;">
                        <div style="width: 350px; height: 350px;">
                            @if($user['image'])
                            <img src="{{ $user['image'] }}" alt="Credential Image" class="image-thumbnail">
                            @else
                            No image available
                            @endif
                        </div>
                    </td>

                </tr>
                @endif
                @endforeach


            </table>
        </div>
    </div>
    <div class="row" id="deniedTable" style="overflow-y: auto; display: none;">
        <div class="col-lg-12 margin-tb">
            <table class="table table-bordered">
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>List of Languages</th>
                    <th>Credentials</th>

                </tr>

                @foreach ($deniedUsers as $userId => $user) <!-- Loop through unverified users -->
                @if (isset($userDetails[$userId])) <!-- Check if user details exist for this user -->
                @php
                $userDetail = $userDetails[$userId]; // Get the user details for the current user
                @endphp
                <tr>
                    <td>{{ $userDetail['name'] }}</td>
                    <td>{{ $userDetail['email'] }}</td>
                    <td>{{ $user['courseName'] ?? 'N/A' }}</td>
                    <td style="width: 350px;">
                        <div style="width: 350px; height: 350px;">
                            @if($user['image'])
                            <img src="{{ $user['image'] }}" alt="Credential Image" class="image-thumbnail">
                            @else
                            No image available
                            @endif
                        </div>
                    </td>

                </tr>
                @endif
                @endforeach





            </table>
        </div>
    </div>























</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initially show the pending table
        document.getElementById('pendingTable').style.display = 'block';
        document.getElementById('approvedTable').style.display = 'none';
        document.getElementById('deniedTable').style.display = 'none';

        document.getElementById('approvedBtn').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('pendingTable').style.display = 'none';
            document.getElementById('approvedTable').style.display = 'block';
            document.getElementById('deniedTable').style.display = 'none';
            document.getElementById('title').textContent = 'Approved Applications';

        });

        document.getElementById('pendingBtn').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('pendingTable').style.display = 'block';
            document.getElementById('approvedTable').style.display = 'none';
            document.getElementById('deniedTable').style.display = 'none';
            document.getElementById('title').textContent = 'Pending Unverified Experts';

        });

        document.getElementById('deniedBtn').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('pendingTable').style.display = 'none';
            document.getElementById('approvedTable').style.display = 'none';
            document.getElementById('deniedTable').style.display = 'block';
            document.getElementById('title').textContent = 'Denied Applications';

        });
    });
</script>


@endsection