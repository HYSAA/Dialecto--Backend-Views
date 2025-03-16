@extends('layouts.app')

@section('content')

<div class="main-container">

    <div class="row mb-2">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course['name'] }} Word Bank</h2>
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
                        <th width="280px">Action</th>
                    </tr>

                    @foreach ($filteredByCourse as $wordId => $word)
                    <tr>
                        <td>{{ $word['lesson_name'] ?? 'No title available' }}</td>
                        <td>{{ $word['text'] }}</td>
                        <td>{{ $word['english'] }}</td>

                        <td>
                            @if ($word['video'])
                            <video width="200px" controls>
                                <source src="{{ $word['video'] }}" type="video/mp4">
                            </video>
                            @else
                            No video available
                            @endif
                        </td>

                        <td>


                            <a class="btn btn-success {{ $word['used_id'] === true ? 'disabled' : '' }}"
                                href="{{ $word['used_id'] === true ? 'javascript:void(0);' : route('admin.addWordToLesson', ['courseId' => $courseId, 'wordId' => $wordId]) }}"
                                tabindex="-1"
                                aria-disabled="{{ $word['used_id'] === true ? 'true' : 'false' }}">
                                Add Word
                            </a>

                            <form action="{{ route('admin.removeFromLesson', ['courseId' => $courseId, 'wordId' => $wordId]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" {{ $word['used_id'] === false ? 'disabled' : '' }}>
                                    Remove
                                </button>
                            </form>




                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection