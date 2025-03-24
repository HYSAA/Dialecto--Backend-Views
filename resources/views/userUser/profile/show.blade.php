@extends('layouts.app')

@section('content')

<div class="main-container" style="overflow-y: auto;">

    <!-- Profile Header Section -->
    <div class="row" style="margin-bottom: 30px; border-bottom: 1px solid #e0e0e0; padding-bottom: 20px;">
        <!-- Left Side - Profile Info -->
        <div class="col-md-6">
            <div style="padding: 15px;">
                <h1 style="font-size: 2.5rem; font-weight: 600; margin-bottom: 10px;">{{ $user['name'] ?? 'N/A' }}</h1>
                <p style="font-size: 1.5rem; color: #65676B; margin-bottom: 5px;">
                    <i class="fas fa-envelope" style="margin-right: 8px;"></i>{{ $user['email'] ?? 'N/A' }}
                </p>
            </div>
        </div>

        <!-- Right Side - Action Buttons -->
        <div class="col-md-6">
            <div style="display: flex; justify-content: flex-end; gap: 15px; padding: 15px;">
                @if ($credentials['status'] == 'pending' && !empty($credentials))
                <span class="btn" style="font-size: 1.3rem; background-color: #d4edda; color: #155724; border-color: #c3e6cb; border-radius: 6px; padding: 10px 15px; height: fit-content;">
                    <i class="fas fa-clock" style="margin-right: 8px;"></i>Pending Approval
                </span>
                @else
                <a class="btn btn-primary" href="{{ route('user.settings.edit') }}" 
                   style="font-size: 1.3rem; border-radius: 6px; padding: 10px 15px; display: flex; align-items: center;">
                    <i class="fas fa-pencil-alt" style="margin-right: 8px;"></i>Edit Profile
                </a>
                <a class="btn btn-success" href="{{ route('user.profile.applyExpert', ['id' => $userId]) }}" 
                   style="font-size: 1.3rem; border-radius: 6px; padding: 10px 15px; display: flex; align-items: center;">
                    <i class="fas fa-user-shield" style="margin-right: 8px;"></i>Apply as Verifier
                </a>
                @if ($credentials['status'] == 'denied')
                <a class="btn btn-danger" href="{{ route('user.profile.applyExpert', ['id' => $userId]) }}" 
                   style="font-size: 1.3rem; pointer-events: none; border-radius: 6px; padding: 10px 15px; display: flex; align-items: center;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>Resubmit Application.
                </a>
                @endif
                @endif
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success" style="font-size: 1.4rem; padding: 15px 20px; margin-bottom: 25px;">
        <i class="fas fa-check-circle" style="margin-right: 10px;"></i>{{ session('success') }}
    </div>
    @endif

    <!-- Badges Section -->
    <div class="row">
        <div class="col-lg-12">
            <h2 style="font-size: 2rem; font-weight: 600; margin-bottom: 25px; color: #1A1A1A;">
                <i class="fas fa-award" style="margin-right: 12px;"></i>My Badges
            </h2>

            @if($quizResults)
            <div class="badges-container" style="background-color: #f8f9fa; border-radius: 12px; padding: 20px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                @foreach($courses as $courseId => $courseData)
                    @php $hasMatchingResults = false; @endphp
                    @foreach($quizResults as $resultId => $resultData)
                        @if($courseId == $resultData['course'])
                            @php $hasMatchingResults = true; @endphp
                            @break
                        @endif
                    @endforeach

                    @if($hasMatchingResults)
                        <div class="course-section" style="margin-bottom: 20px;">
                            <div class="course-header" style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background-color: #e9ecef; border-radius: 8px; cursor: pointer;" onclick="toggleBadges('{{ $courseId }}')">
                                <h3 style="font-size: 1.5rem; color: #333; margin: 0; font-weight: 500;">
                                    <i class="fas fa-book" style="margin-right: 10px; color: #6c757d;"></i>
                                    {{ $courseData['name'] }}
                                    <span style="font-size: 1.2rem; color: #6c757d; margin-left: 10px;">
                                        ({{ count(array_filter($quizResults, function($result) use ($courseId) { return $result['course'] == $courseId; })) }} badges)
                                    </span>
                                </h3>
                                <i class="fas fa-chevron-down" id="icon-{{ $courseId }}" style="font-size: 1.3rem;"></i>
                            </div>
                            <div class="badges-row" id="badges-{{ $courseId }}" style="display: none; padding: 20px 0; flex-wrap: wrap; gap: 20px;">
                                @foreach($quizResults as $resultId => $resultData)
                                    @if($courseId == $resultData['course'])
                                    <div style="flex: 0 0 calc(25% - 20px); min-width: 250px;">
                                        <div class="badge-card" style="background-color: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 100%; transition: transform 0.2s;">
                                            <div style="padding: 20px; text-align: center;">
                                                <img src="{{ $resultData['badge-image'] }}" class="card-img-top" alt="Badge Image" style="width: 100%; height: 150px; object-fit: contain; margin-bottom: 15px;">
                                                <h4 style="font-size: 1.3rem; color: #333; margin-bottom: 10px; font-weight: 500;">
                                                    {{ $resultData['lesson-name'] }}
                                                </h4>
                                                <div style="font-size: 1.2rem; color: #555; background: #f0f2f5; padding: 8px; border-radius: 6px;">
                                                    Score: <strong>{{ $resultData['score'] }}/{{ $resultData['total-score'] }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @else
            <div class="no-badges" style="background-color: #f8f9fa; border-radius: 12px; padding: 30px; text-align: center; margin-bottom: 30px;">
                <p style="font-size: 1.5rem; color: #666; margin-bottom: 15px;">
                    <i class="fas fa-trophy" style="margin-right: 10px;"></i>No badges yet
                </p>
                <p style="font-size: 1.3rem; color: #6c757d;">
                    Complete lessons and quizzes to earn your first badge!
                </p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function toggleBadges(courseId) {
        const badgeSection = document.getElementById(`badges-${courseId}`);
        const icon = document.getElementById(`icon-${courseId}`);
        
        if (badgeSection.style.display === 'none') {
            badgeSection.style.display = 'flex';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            badgeSection.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }
</script>

<style>
    .badge-card:hover {
        transform: translateY(-5px);
    }
    .course-header:hover {
        background-color: #d1e7dd !important;
        transition: background-color 0.2s;
    }
    .btn {
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>

@endsection