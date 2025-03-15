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


    <form action="{{ route('admin.quizzes.postEdit', [$quizId, $courseId, $lessonId]) }}" style="padding-top: 20px;" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row  justify-content-center">

            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Question: </strong>
                    <input type="text" name="question" class="form-control" placeholder="Question" value="{{ $placeholder['question'] }}" required>

                </div>


            </div>


            <div class="col-md-4">
                <div class="form-group">
                    <strong>Points: </strong>
                    <select name="points" class="form-control" required>
                        <option value="{{ $placeholder['points'] }}">{{ $placeholder['points'] }}</option>
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


        <div class="row  justify-content-center ">
            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Answer: </strong>
                    <input type="text" name="answer" class="form-control" placeholder="Answer" value="{{ $placeholder['correct'] }}" required>
                </div>

            </div>

            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Answer audio reference: </strong>

                    <select name="answerRef" class="form-control">

                        <option value="{{ $placeholder['choices'][0]['audioRef'] }}" selected>{{ $placeholder['choices'][0]['text'] }}</option>


                        @foreach ($contents as $key => $content)
                        <option value="{{ $content['video'] }}">{{ $content['text'] }}</option>
                        @endforeach
                    </select>

                </div>
            </div>

        </div>

        <div class="row  justify-content-center ">
            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Choice A: </strong>
                    <input type="text" name="choiceA" class="form-control" placeholder="Choice A" value="{{ $placeholder['choices'][1]['text'] }}" required>
                </div>

            </div>

            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Choice A audio reference: </strong>

                    <select name="choiceARef" class="form-control">

                        <option value="{{ $placeholder['choices'][1]['audioRef'] }}" selected>{{ $placeholder['choices'][1]['text'] }}</option>

                        @foreach ($contents as $key => $content)
                        <option value="{{ $content['video'] }}">{{ $content['text'] }}</option>
                        @endforeach
                    </select>

                </div>
            </div>

        </div>


        <div class="row  justify-content-center ">
            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Choice B: </strong>
                    <input type="text" name="choiceB" class="form-control" placeholder="Choice B" value="{{ $placeholder['choices'][2]['text'] }}" required>
                </div>

            </div>

            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Choice B audio reference: </strong>


                    <select name="choiceBRef" class="form-control">

                        <option value="{{ $placeholder['choices'][2]['audioRef'] }}" selected>{{ $placeholder['choices'][2]['text'] }}</option>
                        @foreach ($contents as $key => $content)
                        <option value="{{ $content['video'] }}">{{ $content['text'] }}</option>
                        @endforeach
                    </select>



                </div>
            </div>

        </div>


        <div class="row  justify-content-center ">
            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Choice C: </strong>
                    <input type="text" name="choiceC" class="form-control" placeholder="Choice C" value="{{ $placeholder['choices'][3]['text'] }}" required>
                </div>

            </div>

            <div class="col-md-4  ">

                <div class="form-group ">
                    <strong>Choice C audio reference: </strong>

                    <select name="choiceCRef" class="form-control">

                        <option value="{{ $placeholder['choices'][3]['audioRef'] }}" selected>{{ $placeholder['choices'][3]['text'] }}</option>
                        @foreach ($contents as $key => $content)
                        <option value="{{ $content['video'] }}">{{ $content['text'] }}</option>
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