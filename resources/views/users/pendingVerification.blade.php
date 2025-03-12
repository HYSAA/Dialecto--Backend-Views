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
                    
                        <th width=" 280px">Action</th>
                    </tr>

                    @if($unverifiedUsers)

                    @foreach ($unverifiedUsers as $userId => $user) <!-- Loop through unverified users -->
                    @if (isset($userDetails[$userId])) <!-- Check if user details exist for this user -->
                    @php
                    $userDetail = $userDetails[$userId]; // Get the user details for the current user
                    @endphp
                    <tr>
                        <td>{{ $userDetail['name'] }}</td>
                        <td>{{ $userDetail['email'] }}</td>
                        <td>{{ $user['courseName'] ?? 'N/A' }}</td>
                        <!-- <td style="width: 350px;">
                            <div style="width: 350px; height: 350px;">
                                @if($user['image'])
                                <img src="{{ $user['image'] }}" alt="Credential Image" class="image-thumbnail">
                                @else
                                No image available
                                @endif
                            </div>
                        </td> -->
                        <td>
                            <a class="btn btn-success" href="{{ route('admin.postVerify', ['id' => $userId]) }}">Verify</a>
                            <a class="btn btn-danger" href="{{ route('admin.postDeny', ['id' => $userId]) }}">Deny</a>
                            <button class="btn btn-info view-details" data-user-id="{{ $userId }}" data-languages="{{ $user['courseName'] ?? 'N/A' }}" data-image="{{ $user['image'] ?? '' }}">
    <i class="fas fa-eye"></i> <!-- Font Awesome eye icon -->
</button>
                        </td>
                    </tr>
                    @endif
                    @endforeach

                    @else

                    <tr>
                        <td colspan="100%" style="font-weight: bold;">No users found.</td>
                    </tr>


                    @endif




                </tbody>

            </table>
            <!-- Modal -->
<!-- Modal -->
<div id="detailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times;</span>
        </div>
        
        <div class="modal-body">
            <h6>Credentials:</h6>
            <div id="modalImageContainer">
                <img id="modalImage" src="" alt="Credential Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>

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

                @if($verifiedUsers)


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


                @else
                <tr>
                    <td colspan="100%" style="font-weight: bold;">No users found.</td>
                </tr>
                @endif



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

                @if($deniedUsers)

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


                @else
                <tr>
                    <td colspan="100%" style="font-weight: bold;">No users found.</td>
                </tr>
                @endif






            </table>
        </div>
    </div>























</div>

<style>

    /* Modal Styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
     backdrop-filter: blur(5px);
    background-color: rgba(0, 0, 0, 0.5); /* Black with opacity */
    justify-content: center; /* Center horizontally */
    /* border-radius: 40px; */
    align-items: center; /* Center vertically */
}

.modal-content {
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border: 1px solid #888;
    border-radius: 8px;
    width: 80%; /* Adjust width as needed */
    max-width: 600px; /* Maximum width */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    position: absolute; /* Position the modal */
    top: 50%; /* Move the top edge to the center */
    left: 50%; /* Move the left edge to the center */
    transform: translate(-50%, -50%); /* Shift the modal back by 50% of its own width and height */
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
}

.modal-body {
    margin: 20px 0;
}

.modal-footer {
    text-align: right;
}

.btn-close {
    background-color: #6c757d;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-close:hover {
    background-color: #5a6268;
}
#modalImageContainer {
    display: flex;
    justify-content: center;
    padding: 15px 0;
}

#modalImage {
    max-width: 100%;
    height: auto;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}
</style>

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
        const viewDetailsButtons = document.querySelectorAll('.view-details');
    const modal = document.getElementById('detailsModal');
    const modalImage = document.getElementById('modalImage');
    const modalImageContainer = document.getElementById('modalImageContainer');
    const closeModalButtons = document.querySelectorAll('.close');

    // Open modal when eye icon is clicked
    viewDetailsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const image = button.getAttribute('data-image');

            // Populate modal content
            if (image) {
                modalImage.src = image;
                modalImageContainer.style.display = 'block';
            } else {
                modalImageContainer.style.display = 'none';
            }

            // Show the modal
            modal.style.display = 'block';
        });
    });

    // Close modal when close button is clicked
    closeModalButtons.forEach(button => {
        button.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    });

    // Close modal when clicking outside the modal
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    });
</script>


@endsection