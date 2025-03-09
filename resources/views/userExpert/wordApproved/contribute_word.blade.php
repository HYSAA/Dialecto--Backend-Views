@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-2">

        <div class="col-lg-12 margin-tb">
            <div class="pull-left mb-2">
                <h2 id="title">Contribute word for the language <strong> {{$language}}</strong></h2>

            </div>

        </div>

    </div>






    <div class="row" id="wordsFromUser" style="overflow-y: auto;">

        <div class="col-lg-12 margin-tb">

            <form action="{{ route('expert.submitContributeWord') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="course_id" value="{{ $courseId }}">



                <div class="row">
                    <label for="lesson">Select a lesson you want to contribute to:</label>
                </div>


                <div class="row ">

                    <div class="col-lg-3">



                        <div class="row mb-2">

                            <select name="lesson_id" id="lesson" style="width: 100%;">
                                @foreach($thisLessons as $key => $lesson)
                                <option value="{{ $key }}">{{ $lesson['title'] }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="row mb-2">

                            <label for="text">Text: </label>
                        </div>

                        <div class="row mb-2">

                            <input type="text" name="text" id="text" style="width: 100%;" required>
                        </div>

                        <div class="row mb-2">

                            <label for="english">English:</label>
                        </div>

                        <div class="row mb-4">

                            <input type="text" name="english" id="english" style="width: 100%;" required>
                        </div>


                        <div class="row mb-2">
                            <button class="btn btn-main" type="submit">Submit</button>
                        </div>


                    </div>

                    <div class="col-lg-4  ">
                        <div class=" row mb-2">


                            <label for="video">Video:</label>

                        </div>

                        <div class="row mb-2">

                            <input type="file" name="video" id="video" accept="video/*">
                        </div>


                    </div>
                </div>
            </form>
        </div>
    </div>


</div>
@endsection