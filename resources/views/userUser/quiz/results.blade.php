@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - {{ $lesson->title }} - Results</h2>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10 margin-tb text-center" style="padding-top: 70px;">
            <h1>Finished quiz on</h1>
            <h2 class="mb-2">{{ $lesson->title }}</h2>
            <h4 style="color: #90949C;"> You answered {{ $score }} questions correctly out of {{ $items }}</h4>

            <div class="row mt-4 mb-2 ">
                <div class="col-lg-6 addborder " style="display: flex; justify-content:center; ">

                    <!-- <div class="container mt-5 addborder" style="display: flex; justify-content: center; align-items: center;"> -->
                    <div id="canvas-holder " class="addborder" style="width: 500px;">
                        <canvas id="chart"></canvas>
                        <div class="row justify-content-center">

                        </div>
                    </div>
                    <!-- </div> -->
                </div>

                <div class="col-lg-6">
                    BAdge goes here
                </div>


            </div>

            <div class="row">
                <div class="col-lg-12 ">

                    <a class="btn btn-main" href="{{ route('user.quiz.show', [$course->id, $lesson->id]) }}">Retake Quiz</a>
                    <a class="btn btn-back-main " href="{{ route('user.courses.index') }}">Go back to Courses</a>
                </div>
            </div>






        </div>
    </div>

</div>

<script src="https://unpkg.com/chart.js@2.8.0/dist/Chart.bundle.js"></script>
<script src="https://unpkg.com/chartjs-gauge@0.3.0/dist/chartjs-gauge.js"></script>

<script>
    var score = @json($score);
    var label;
    var needleValue;

    if (score <= 0) {
        label = "Fail";
        needleValue = 0.5; // Center of the first segment

    } else if (score == 2) {
        label = "Nice Try";
        needleValue = 1.5; // Center of the first segment

    } else if (score == 3) {
        label = "Good Job";
        needleValue = 2.5; // Center of the second segment

    } else if (score <= 4) {
        label = "Excellent";
        needleValue = 3; // Center or end of the fourth segment

    }

    var config = {
        type: 'gauge',
        data: {
            datasets: [{
                data: [1, 2, 3, 4], // Define segments for the gauge
                value: needleValue, // Display the user's score
                backgroundColor: ['red', 'orange', 'yellow', 'green'], // Colors corresponding to score ranges
                borderWidth: 1

            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: `Your quiz score is ${score}`,
                fontSize: 16
            },
            layout: {
                padding: {
                    bottom: 30
                }
            },
            needle: {
                radiusPercentage: 5,
                widthPercentage: 3.2,
                lengthPercentage: 80,
                color: 'rgba(0, 0, 0, 1)'
            },
            valueLabel: {
                formatter: Math.round
            },
            plugins: {
                beforeDraw: function(chart) {
                    var ctx = chart.chart.ctx;
                    ctx.save();
                    var width = chart.chart.width,
                        height = chart.chart.height;
                    ctx.font = '20px Arial';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#000';
                    ctx.fillText(label, width / 2, height / 2 + 20); // Adjust the position of the text here
                    ctx.restore();
                }
            }
        }
    };

    window.onload = function() {
        var ctx = document.getElementById('chart').getContext('2d');
        window.myGauge = new Chart(ctx, config);
    };
</script>
@endsection