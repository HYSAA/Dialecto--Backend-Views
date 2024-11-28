@extends('layouts.app')

@section('content')
<div class="main-container">
        <h2>Language Learning Survey</h2>
        <form action="{{ route('survey.submit') }}" method="POST">
            @csrf

            <!-- Example Question -->
            <div>
                <label>How familiar are you with this language?</label><br>
                <input type="radio" name="proficiency" value="1"> No experience<br>
                <input type="radio" name="proficiency" value="2"> Basic knowledge<br>
                <input type="radio" name="proficiency" value="3"> Conversational<br>
                <input type="radio" name="proficiency" value="4"> Fluent<br>
            </div>

            <button type="submit">Submit Survey</button>
        </form>
    </div>
@endsection