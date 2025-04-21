@extends('layouts.app')

@section('content')
<div class="main-container" style="overflow:auto">
  <div class="Big-box">
    <div id="header">
      <h1>Leaderboard</h1>
    <!-- ADDED THIS For filter  check the tables for Profeciency-->
    <form method="GET" action="{{ route('expert.leaderboard.show', ['courseName' => $courseName]) }}">
        <label for="proficiency">Filter by Proficiency:</label>
        <select name="proficiency" id="proficiency" onchange="this.form.submit()">
          <option value="">All</option>
          <option value="Beginner" {{ request('proficiency') === 'Beginner' ? 'selected' : '' }}>Beginner</option>
          <option value="Intermediate" {{ request('proficiency') === 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
          <option value="Advanced" {{ request('proficiency') === 'Advanced' ? 'selected' : '' }}>Advanced</option>
        </select>
      </form>
    </div>

    <div id="leaderboard">
      <div class="ribbon"></div>
      <table class="leaderboard-table">
        <thead>
          <tr>
            <th>Rank</th>
            <th>User Name</th>
            <th>Course</th>
            <th>Proficiency</th>

            <th>Total Score</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @php $rank = 1; @endphp
          @foreach ($rankings as $ranking)
          <tr class="leaderboard-row {{ $currentUserRanking && $ranking['user_id'] == $currentUserRanking['user_id'] ? 'userRank' : '' }}">
            <td class="number">{{ $rank++ }}</td>
            <td class="name">
    {{ $ranking['user_name'] }}
</td>
            <td class="name">{{ $ranking['course_id'] }}</td>
            <!-- ADDED THIS -->
            <td class="proficiency">
                {{ $ranking['proficiency'] ?? 'Unknown' }}
            </td>
            <td class="points">{{ $ranking['total_course_score'] }}</td>
            <td class="rank-icon">
              @if ($rank == 2)
              <img src="{{asset('images/gold.png')}}" alt="Top 1" class="medal-icon">
              @elseif ($rank == 3)
              <img src="{{asset('images/silver.png')}}" alt="Top 2" class="medal-icon">
              @elseif ($rank == 4)
              <img src="{{asset('images/bronze.png')}}" alt="Top 3" class="medal-icon">
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div id="current-rank">
    @if ($currentUserRanking && $userRank)
        <h2>Your Current Rank</h2>
        <table class="leaderboard-table">
            <thead>
                <tr></tr>
            </thead>
            <tbody>
                <tr class="leaderboard-row userRank">
                    <td class="number">{{ $userRank }}</td>
                    <td class="name">{{ $currentUserRanking['user_name'] }}</td>
                    <td class="name">{{ $currentUserRanking['course_id'] }}</td>
                    <td class="proficiency">
                        {{ $currentUserRanking['proficiency'] ?? 'Unknown' }}
                    </td>
                    <td class="points">{{ $currentUserRanking['total_course_score'] }}</td>
                    <td class="rank-icon"></td>
                </tr>
            </tbody>
        </table>

<!-- Added This if naa nay prof ang user -->
        @else
            @if (request('proficiency'))
                <p>You are not ranked in the <strong>{{ request('proficiency') }}</strong> level.</p>
            @else
                <p>Start learning to see your rank here!</p>
            @endif
        @endif
</div>

<style>
  .Big-box {
    width: 85%;
    margin: 0 auto;
    background: linear-gradient(145deg, #ffffff, #f0f0f0);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);

    position: relative;
  }


  .medal-icon {
    width: 4rem;
    height: auto;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    transition: transform 0.3s ease;
  }

  .medal-icon:hover {
    transform: scale(1.1) rotate(5deg);
  }



  .leaderboard-row td:last-child {
    text-align: center;
  }

  .number {
    height: 2rem;
    font-size: 2.2rem;
    font-weight: bold;
    text-align: left;
    background: linear-gradient(45deg, #141a39, #2b3666);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  #header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 2.5rem 2rem;
    position: relative;
    z-index: 1;
  }

  h1 {
    font-family: "Rubik", sans-serif;
    font-size: 2rem;
    background: linear-gradient(45deg, #141a39, #2b3666);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
  }

  #leaderboard {
    width: 100%;
    position: relative;
    max-height: 600px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #FFCA58 #f0f0f0;
  }

  #leaderboard::-webkit-scrollbar {
    width: 8px;
  }

  #leaderboard::-webkit-scrollbar-track {
    background: #f0f0f0;
    border-radius: 4px;
  }

  #leaderboard::-webkit-scrollbar-thumb {
    background: #FFCA58;
    border-radius: 4px;
  }

  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
    table-layout: fixed;
    color: #141a39;
  }

  tr {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 12px;
  }

  tr:not(:first-child):hover {
    background: linear-gradient(145deg, #ffffff, #f8f9fa);
    transform: scale(1.02) translateX(5px);
    box-shadow: 0 8px 20px rgba(32, 38, 72, 0.6);
  }

  tr:nth-child(even),
  tr:nth-child(odd) {
    background: linear-gradient(145deg, #f4f4f4, #e8e8e8);
    border-radius: 12px;
    margin: 8px 0;
  }

  .userRank {
    background: linear-gradient(145deg, #FFCA58, rgb(233, 229, 200)) !important;
    font-weight: bold !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(161, 181, 185, 0.3);
  }

  td {
    height: 3rem;
    font-family: "Rubik", sans-serif;
    font-size: 1.4rem;
    padding: 1.2rem 2rem;
    position: relative;
    border-radius: 12px;
  }

  thead tr {
    background: transparent !important;
    box-shadow: none !important;
  }

  thead th {
    font-weight: 600;
    color: #141a39;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-size: 1.2rem;
    padding: 1.2rem 2rem;
    border-bottom: 2px solid rgba(255, 202, 88, 0.3);
  }

  .name {
    text-align: left;
    font-size: 1.5rem;
    color: #2b3666;
  }

  .points {
    font-weight: bold;
    font-size: 2rem;
    text-align: right;
    vertical-align: middle;
    color: #141a39;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
  }

  .ribbon::before,
  .ribbon::after {
    content: "";
    height: 1.5rem;
    width: 1.5rem;
    bottom: -0.8rem;
    transform: rotate(45deg);
    background: #FFCA58;
    position: absolute;
    z-index: -1;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
  }

  .ribbon::before {
    left: 0.35rem;
  }

  .ribbon::after {
    right: 0.35rem;
  }

  #current-rank {
    width: 100%;
    margin: 2rem 0;
    padding: 0 1rem;

  }

  #current-rank h2 {
    font-family: "Rubik", sans-serif;
    font-size: 1.5rem;
    background: linear-gradient(45deg, #141a39, #2b3666);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1rem;
    padding-left: 2rem;
    letter-spacing: 1px;
  }

  @media (max-width: 740px) {
    * {
      font-size: 70%;
    }
  }

  @media (max-width: 500px) {
    * {
      font-size: 55%;
    }
  }

  @media (max-width: 390px) {
    * {
      font-size: 45%;
    }
  }
</style>
@endsection