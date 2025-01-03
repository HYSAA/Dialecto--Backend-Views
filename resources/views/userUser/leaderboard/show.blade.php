@extends('layouts.app')

@section('content')

<div class="main-container">
<h1 class="text-center">Leaderboard: {{ $courseName }}</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Rank</th>
                <th>User ID</th>
                <th>Course</th>
                <th>Total Score</th>
            </tr>
        </thead>
       <tbody>
        @php $rank = 1; @endphp <!-- Initialize rank counter -->
        @foreach ($rankings as $ranking)
<tr>
    <td>{{ $rank++ }}</td>
    <td>{{ $ranking['user_name'] }}</td>
    <td>{{ $ranking['course_id'] }}</td>
    <td>{{ $ranking['total_course_score'] }}</td>
</tr>
@endforeach

    </tbody>
    </table>

</div>

@endsection