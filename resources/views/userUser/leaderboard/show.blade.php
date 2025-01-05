@extends('layouts.app')

@section('content')
  


<div class="main-container" style="overflow:auto">

  <div class="Big-box">
    <div id="header">
        <h1>Leaderboard</h1>
      
    </div>

    <div id="leaderboard">
        <div class="ribbon"></div>
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>User ID</th>
                    <th>Course</th>
                    <th>Total Score</th>
                </tr>
            </thead>
            <tbody>
                @php $rank = 1; @endphp
                @foreach ($rankings as $ranking)
                    <tr class="leaderboard-row">
                        <td class="number">{{ $rank++ }}</td>
                        <td class="name">{{ $ranking['user_name'] }}</td>
                        <td class="name">{{ $ranking['course_id'] }}</td>
                        <td class="points">{{ $ranking['total_course_score'] }}</td>

                        <td class="rank-icon">
                @if($rank == 2)
                    <img src="https://img.icons8.com/?size=100&id=I4hsHPwm86-K&format=png&color=000000" alt="Top 1" class="medal-icon">
                    
                @elseif($rank == 3)
                    <img src="https://img.icons8.com/?size=100&id=YskxJ1NpCFQy&format=png&color=000000" alt="Top 2" class="medal-icon">
                @elseif($rank == 4)
                    <img src="https://img.icons8.com/?size=100&id=KL1txQ7JoYvd&format=png&color=000000" alt="Top 3" class="medal-icon">
                @endif
            </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
     
    </div>
    </div>
</div>

<style>
   
   

.Big-box{
  width: 85%; /* Adjusted width for a smaller box */
  margin: 0 auto;
  
}

.medal-icon {
    width: 5rem; /* Adjust the size of the icon */
    height: auto;
    position: relative;
 
    vertical-align: middle; /* Align vertically with text */
    margin-left: -3rem; /* Adjust spacing as needed */
  
}

.ribbon {
    width: 100%;
    height: 9rem;
    background-color: #FFCA58;
    position: absolute; /* Changed to relative for proper alignment */
    top: 0rem; /* Reset top position */
    -webkit-box-shadow: 0px 15px 11px -6px #7a7a7d;
    box-shadow: 0px 15px 11px -6pxrgb(6, 6, 8);
}
.leaderboard-row td:last-child {
    text-align: center; /* Center the icons in the last column */
}
.number {
 
 height: 2rem;
 font-size: 2.2rem;
 font-weight: bold;
 text-align: left;
 
}



#header {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 2.5rem 2rem;
}



h1 {
  font-family: "Rubik", sans-serif;
  font-size: 1.7rem;
  color: #141a39;
  text-transform: uppercase;
  cursor: default;
}

#leaderboard {
  width: 100%;
  position: relative;
  max-height: 600px; /* You can adjust the max height as needed */
  overflow-y: auto; /* Enable vertical scrolling */
}

table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
  color: #141a39;
  cursor: default;
}

tr {
  transition: all 0.2s ease-in-out;
  border-radius: 0.2rem;
}

tr:not(:first-child):hover {
  background-color: #fff;
  transform: scale(1.04);
  -webkit-box-shadow: 0px 5px 15px 8px #e4e7fb;
  box-shadow: 0px 5px 15px 8px #e4e7fb;
}

tr:nth-child(odd) {
  background-color: #f9f9f9;
}

tr:nth-child(1) {
  color: #fff;
}

td {
  height: 3rem;
  font-family: "Rubik", sans-serif;
  font-size: 1.4rem;
  padding: 1rem 2rem;
  position: relative;
}


.name {
  text-align: left;
  font-size: 1.5rem;
}

.points {
  font-weight: bold;
  font-size: 2rem;
  text-align: right; /* Align to the right for numerical consistency */
  vertical-align: middle; /* Align vertically with other cells */
  padding-right: 17rem; /* Add spacing to the right if needed */
}


.points:first-child {
  width: 20rem;
}

.gold-medal {
  height: 3rem;
  margin-left: 1.5rem;
}


.ribbon::before {
  content: "";
  height: 1.5rem;
  width: 1.5rem;
  bottom: -0.8rem;
  left: 0.35rem;
  transform: rotate(45deg);
  background-color: #FFCA58;
  position: absolute;
  z-index: -1;
}

.ribbon::after {
  content: "";
  height: 1.5rem;
  width: 1.5rem;
  bottom: -0.8rem;
  right: 0.35rem;
  transform: rotate(45deg);
  background-color: #FFCA58;
  position: absolute;
  z-index: -1;
}

#buttons {
  width: 100%;
  margin-top: 3rem;
  display: flex;
  justify-content: center;
  gap: 2rem;
}

.exit {
  width: 11rem;
  height: 3rem;
  font-family: "Rubik", sans-serif;
  font-size: 1.3rem;
  text-transform: uppercase;
  color: #7e7f86;
  border: 0;
  background-color: #fff;
  border-radius: 2rem;
  cursor: pointer;
}

.exit:hover {
  border: 0.1rem solid #5c5be5;
}

.continue {
  width: 11rem;
  height: 3rem;
  font-family: "Rubik", sans-serif;
  font-size: 1.3rem;
  color: #fff;
  text-transform: uppercase;
  background-color: #5c5be5;
  border: 0;
  border-bottom: 0.2rem solid #3838b8;
  border-radius: 2rem;
  cursor: pointer;
}

.continue:active {
  border-bottom: 0;
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