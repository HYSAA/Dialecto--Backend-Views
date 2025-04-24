@extends('layouts.app')

@section('content')


<div class="main-container">


    <div class="row">
        <div class="col-lg-6">
            <h2>Create item > {{$courseName}} > {{$lessonName}} </h2>
        </div>

    </div>


    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form action="{{ route('admin.quizzes.store', [$courseId, $lessonId]) }}" style="padding-top: 20px;" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row  justify-content-center">

            <div class="col-md-4">
                <div class="form-group">
                    <strong>Points: </strong>
                    <select name="points" class="form-control" required>
                        <option value="" disabled selected>Select points</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
            </div>

        </div>


        <div class="row  justify-content-center">

            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Question: </strong>

                    <select name="question" class="form-control" required>

                        <option value="" disabled selected>Choose your question</option>

                        @foreach ($contents as $key => $content)
                        <option value='@json($content)'>
                            {{ $content['english'] }}
                        </option>
                        @endforeach
                    </select>

                </div>
            </div>
        </div>



        <div class="row  justify-content-center ">

            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Choice A : </strong>


                    <select name="choiceARef" class="form-control" required>
                        <option value="" disabled selected>Choice A </option>

                        @foreach ($contents as $key => $content)
                        <option value='@json($content)'>
                            {{ $content['text'] }}
                        </option>
                        @endforeach
                    </select>

                </div>
            </div>

        </div>


        <div class="row  justify-content-center ">


            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Choice B : </strong>


                    <select name="choiceBRef" class="form-control" required>
                        <option value="" disabled selected>Choice B </option>
                        @foreach ($contents as $key => $content)
                        <option value='@json($content)'>
                            {{ $content['text'] }}
                        </option>
                        @endforeach
                    </select>



                </div>
            </div>

        </div>


        <div class="row  justify-content-center ">

            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Choice C : </strong>

                    <select name="choiceCRef" class="form-control" required>
                        <option value="" disabled selected>Choice C </option>
                        @foreach ($contents as $key => $content)
                        <option value='@json($content)'>
                            {{ $content['text'] }}
                        </option>
                        @endforeach
                    </select>

                </div>
            </div>

        </div>


        <div class="row justify-content-center" style="margin-top: 20px;">
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-danger" href="{{ route('admin.courses.index') }}">Back</a>
            </div>
        </div>


    </form>


</div>
@endsection