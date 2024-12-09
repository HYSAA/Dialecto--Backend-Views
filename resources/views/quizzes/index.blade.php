@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-4">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Quiz bank > {{$courseName}} > {{$lessonName}} </h2>
            </div>
            <div class="pull-right ">
                <a class="btn btn-main" href="{{ route('admin.quizzes.create' , [$courseId, $lessonId]) }}"> Create Item</a>


            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        </div>
    </div>

    @endif


    <div class="row" style="overflow-y: auto;">

        <div class="col-lg-12 margin-tb">
            @if($quizzes !== null)
            <table class="table table-bordered">
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Choice A</th>
                    <th>Choice B</th>
                    <th>Choice C</th>
                    <th>Choice D</th>
                    <th>Points</th>
                    <th width="280px">Action</th>
                </tr>

                @foreach ($quizzes as $quizId => $quiz)
                <tr>
                    <td>{{ $quiz['question'] }}</td>
                    <td>{{ $quiz['correct'] }}</td>


                    @foreach ($quiz['choices'] as $index => $choice)
                    <td>{{ $choice['text'] }}</td> <!-- Assuming 'text' holds the displayable choice value -->
                    @endforeach




                    <td>{{ $quiz['points'] }}</td>
                    <td>
                        <form action="{{ route('admin.courses.destroy', $quizId) }}" method="POST">
                            <a href="{{ route('admin.courses.edit', $quizId) }}" class="btn btn-success">Edit</a>
                            <a href="{{ route('admin.courses.show', $quizId) }}" class="btn btn-primary">View</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach


            </table>

        </div>
        @else
        <p>No quizzes available.</p>
        @endif
    </div>
</div>

@endsection