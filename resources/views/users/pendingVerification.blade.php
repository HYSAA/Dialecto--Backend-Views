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
                        <th>Username</th>
                        <th>Email</th>
                        <th>List of Languages</th>
                        <th width="280px">Action</th>
                    </tr>
                    @if($unverifiedUsers)
                    @foreach ($unverifiedUsers as $userId => $user)
                    @if (isset($userDetails[$userId]))
                    @php
                    $userDetail = $userDetails[$userId];
                    @endphp
                    <tr>
                        <td>{{ $userDetail['name'] }}</td>
                        <td>{{ $userDetail['email'] }}</td>
                        <td>{{ $user['courseName'] ?? 'N/A' }}</td>
                        <td>
                            <a class="btn btn-success" href="{{ route('admin.postVerify', ['id' => $userId]) }}">Verify</a>
                            <a class="btn btn-danger" href="{{ route('admin.postDeny', ['id' => $userId]) }}">Deny</a>
                            <button class="btn btn-info view-details" data-user-id="{{ $userId }}" data-languages="{{ $user['courseName'] ?? 'N/A' }}" data-image="{{ $user['image'] ?? '' }}">
                                <i class="fas fa-eye"></i>
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
        </div>
    </div>

    <div class="row" id="approvedTable" style="overflow-y: auto; display: none;">
        <div class="col-lg-12 margin-tb">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Language</th>
                    <th>Credentials</th>
                </tr>
                @if($verifiedUsers)
                @foreach ($verifiedUsers as $userId => $user)
                @if (isset($userDetails[$userId]))
                @php
                $userDetail = $userDetails[$userId];
                @endphp
                <tr>
                    <td>{{ $userDetail['name'] }}</td>
                    <td>{{ $userDetail['email'] }}</td>
                    <td>{{ $user['courseName'] ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-info view-details" data-user-id="{{ $userId }}" data-languages="{{ $user['courseName'] ?? 'N/A' }}" data-image="{{ $user['image'] ?? '' }}">
                            <i class="fas fa-eye"></i>
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
            </table>
        </div>
    </div>

    <div class="row" id="deniedTable" style="overflow-y: auto; display: none;">
        <div class="col-lg-12 margin-tb">
            <table class="table table-bordered">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Language</th>
                    <th>Credentials</th>
                </tr>
                @if($deniedUsers)
                @foreach ($deniedUsers as $userId => $user)
                @if (isset($userDetails[$userId]))
                @php
                $userDetail = $userDetails[$userId];
                @endphp
                <tr>
                    <td>{{ $userDetail['name'] }}</td>
                    <td>{{ $userDetail['email'] }}</td>
                    <td>{{ $user['courseName'] ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-info view-details" data-user-id="{{ $userId }}" data-languages="{{ $user['courseName'] ?? 'N/A' }}" data-image="{{ $user['image'] ?? '' }}">
                            <i class="fas fa-eye"></i>
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
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <h6>Credentials:</h6>
                <div id="modalImageContainer">
                    <img id="modalImage" src="" alt="Credential Image" class="img-fluid clickable-image">
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox -->
    <div id="lightbox" class="lightbox">
        <span class="lightbox-close">&times;</span>
        <img class="lightbox-content" id="lightboxImage">
    </div>

</div>

<style>
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(5px);
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: rgba(255, 255, 255, 0.9);
        padding: 20px;
        border: 1px solid #888;
        border-radius: 8px;
        width: 80%;
        max-width: 600px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
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

    .lightbox {
        display: none;
        position: fixed;
        z-index: 1001;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
        justify-content: center;
        align-items: center;
    }

    .lightbox-content {
        max-width: 90%;
        max-height: 90vh;
        border-radius: 8px;
    }

    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 20px;
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .lightbox-close:hover,
    .lightbox-close:focus {
        color: #ccc;
        text-decoration: none;
    }
    .position-relative {
        position: relative;
    }

    .position-absolute {
        position: absolute;
    }

    .top-0 {
        top: 0;
    }

    .start-100 {
        left: 100%;
    }

    .translate-middle {
        transform: translate(-50%, -50%);
    }

    .badge {
        padding: 0.5em 0.75em;
        border-radius: 50%;
        background-color: red;
        color: white;
        font-size: 1.1em; /* Increase font size */
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

        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightboxImage');
        const lightboxClose = document.querySelector('.lightbox-close');
        const clickableImages = document.querySelectorAll('.clickable-image');

        // Open lightbox when image is clicked
        clickableImages.forEach(image => {
            image.addEventListener('click', function() {
                lightboxImage.src = this.src; // Set the lightbox image source
                lightbox.style.display = 'flex'; // Show the lightbox
            });
        });

        // Close lightbox when close button is clicked
        lightboxClose.addEventListener('click', function() {
            lightbox.style.display = 'none'; // Hide the lightbox
        });

        // Close lightbox when clicking outside the image
        lightbox.addEventListener('click', function(event) {
            if (event.target === lightbox) {
                lightbox.style.display = 'none'; // Hide the lightbox
            }
        });
    });
</script>

@endsection