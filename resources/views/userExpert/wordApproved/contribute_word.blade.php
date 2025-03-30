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
                    <div class="col-lg-3 " style="padding-left: 0;">
                        <div class="card shadow p-3">
                            <div class=" mb-3">
                                <label for="lesson" class="form-label">Select Lesson</label>

                                <select name="lesson_id" id="lesson" class="form-select w-100"
                                    style="border-radius: 8px; border: 2px solid #007bff; background-color: white; 
                           padding: 10px; font-size: 16px; transition: 0.3s ease-in-out;"
                                    onfocus="this.style.borderColor='#0056b3'; this.style.boxShadow='0 0 5px rgba(0, 91, 187, 0.5)';"
                                    onblur="this.style.borderColor='#007bff'; this.style.boxShadow='none';">

                                    @foreach($thisLessons as $key => $lesson)
                                    <option value="{{ $key }}">{{ $lesson['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="text" class="form-label">Text</label>
                                <input type="text" name="text" id="text" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="english" class="form-label">English</label>
                                <input type="text" name="english" id="english" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="video" class="form-label">Upload Video</label>
                                <input type="file" name="video" id="video" class="form-control" accept="video/*" required>
                            </div>

                            <div class="mb-2">
                                <button class="btn btn-main w-100" type="submit">Submit</button>
                            </div>





                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>


</div>
@endsection