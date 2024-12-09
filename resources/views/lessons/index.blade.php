@extends('layouts.app')

@section('content')
<div class="main-container" style="overflow-y: auto;">
    <h2>All Lessons</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Lesson Title</th>
                <th>Lesson Image</th>
            </tr>
        </thead>
        <tbody>
            @if (count($lessons) > 0)
                @foreach ($lessons as $courseKey => $lesson)
                    @foreach ($lesson as $lessonId => $lessons)
                        <tr>
                            <td>{{ $lessons['title'] }}</td>
                            <td><img src="{{ $lessons['image'] ?? '' }}" alt="{{ $lessons['title'] }}" width="100"></td>
                        </tr>
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="3">No lessons found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection