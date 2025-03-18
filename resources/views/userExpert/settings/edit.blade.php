@extends('layouts.app')

@section('content')
<div class="main-container py-5">
    <div class="container" style="max-width: 600px; margin: 0 auto;">
        <h2 class="text-center mb-4">Edit Profile</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-light p-4 rounded shadow-sm">
            <form action="{{ route('expert.settings.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user['name'] ?? '') }}" placeholder="Enter your name" required>
                    @error('name')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user['email'] ?? '') }}" placeholder="Enter your email" required>
                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
