@extends('layouts.app')

@section('content')
<div class="main-container">

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - {{ $lesson->title }} - Quiz</h2>
            </div>

        </div>
    </div>


    <div class="row  justify-content-center mt-5">

        <div class="col-lg-8  ">

            <div class="row mb-3">
                <h2>Match the phrases</h2>
            </div>



            <form method="POST" action="{{ route('quiz.submit', [$course->id, $lesson->id]) }}">
                @csrf
                <div class="quiz-container">
                    @foreach($questions as $question)
                    <div class="quiz-item">
                        <p><strong>{{ $question->english }}</strong></p>

                        <select name="answers[{{ $question->id }}]" class="form-control mb-3">
                            <option value="" disabled selected>Choose the matching text</option>
                            @foreach($options as $option)
                            <option value="{{ $option->id }}">{{ $option->text }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-main mt-1">Submit Answers</button>
            </form>
        </div>

    </div>

</div>
@endsection