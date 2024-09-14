@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-2">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">

                <h2>{{$course->name}} Word Bank</h2>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="row mb-2">

        <div class="col-lg-12 margin-tb">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    </div>
    @endif
    @if (session('fail'))
    <div class="row mb-2">

        <div class="col-lg-12 margin-tb">
            <div class="alert alert-danger">
                {{ session('fail') }}
            </div>
        </div>
    </div>
    @endif



    <div class="row" style="overflow-y: auto;">
        <div class="col-lg-12 margin-tb">

            <table class="table table-striped table-bordered">

                <tbody>
                    <tr>
                        <th>Lesson</th>
                        <th>Translation Text</th>
                        <th>English Text</th>
                        <th>Video</th>
                        <th width=" 280px">Action</th>
                    </tr>


                    @foreach ($suggestions as $word)
                    <tr>
                        <td>{{ $word->lesson->title }}</td>

                        <td>{{ $word->text }}</td>
                        <td>{{ $word->english }}</td>
                        <!-- <td>{{ $word->status }}</td> -->


                        <td>    @if ($word->video)
                            <video width="200px" controls>
                                <source src="{{ $word->video }}" type="video/mp4">
                            </video>
                            @else
                            No video available
                            @endif</td>


                        <td>

                            <!-- <a class="btn btn-success" href="{{ route('admin.addWordToLesson', ['courseid' => $course->id, 'wordid' => $word->id]) }}">Add Word</a> -->

                            <a class="btn btn-success {{ $word->usedID ? 'disabled' : '' }}"
                                href="{{ $word->usedID ? 'javascript:void(0);' : route('admin.addWordToLesson', ['courseid' => $course->id, 'wordid' => $word->id]) }}"
                                tabindex="-1"
                                aria-disabled="{{ $word->usedID ? 'true' : 'false' }}">
                                Add Word
                            </a>



                            <a class="btn btn-danger {{ $word->usedID ? '' : 'disabled' }}"
                                href="{{ $word->usedID ? route('admin.removeWord', ['courseid' => $course->id, 'wordid' => $word->id]) : 'javascript:void(0);' }}"
                                tabindex="-1"
                                aria-disabled="{{ $word->usedID ? 'false' : 'true' }}">
                                Remove
                            </a>

                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>









</div>

@endsection