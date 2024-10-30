@extends('layouts.app')

@section('content')

<div class="main-container" style="padding: 15px;">
    <div class="pull-right " style="padding:15px;">
        <a class="btn btn-back-main" href="{{ route('user.wordSuggested') }}">Back</a>
    </div>
    <div class="col-lg-12 margin-tb">
        <div class="row justify-content-center" style="width: 100%;">

            <form action="{{ route('user.updateSelected', [$suggestedWordId]) }}" method="POST" enctype="multipart/form-data">

                @csrf
                <input type="hidden" name="suggestedWordId" value="{{ $suggestedWordId }}">

                <div class="card mb-2 mr-2" style="padding:15px;">
                    <div style="padding-top:15px;">
                        <strong for="text" class="form-group">Word:</strong><br>
                        <input type="text" name="text" id="text" required class="form-control form-control-lg"
                            value="{{ $suggestedWord['text'] ?? '' }}">
                    </div>
                    <div style="padding-top:15px;">
                        <strong for="english">English Word:</strong><br>
                        <input type="text" name="english" id="english" required class="form-control form-control-lg"
                            value="{{ $suggestedWord['english'] ?? '' }}">
                    </div>
                    <div>
                        <strong for="video">Video:</strong><br>
                        <input type="file" name="video" class="form-control form-control-lg">
                    </div>
                    <div style="padding-top:15px;">
                        <button class="btn btn-back-main" style="margin-left:35%;" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .table td,
    .table th {
        text-align: left;
    }

    .table td,
    .table th {
        width: 50%;
    }

    .table td {
        padding: 10px;
    }
</style>

@endsection