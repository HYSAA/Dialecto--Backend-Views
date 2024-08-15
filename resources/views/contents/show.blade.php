@extends('layouts.app')

@section('content')
<div class="main-container">
    <br><br>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{ $course->name }} - {{ $lesson->title }} - Contents</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{  route('courses.lessons.show', [$course->id,$lesson->id]) }}"> Back To Lesson</a>
                <!-- Fixed IT  -->
                <a class="btn btn-primary" type="button" href="{{  route('courses.lessons.contents.show', [$course->id, $lesson->id, $content->id - 1]) }}">Back</a>

            </div>
        </div>
    </div>

    <div class="row">
        <!-- <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Text:</strong>
                {{ $content->text }}
                @if ($content->image)
                                    <img src="{{ $content->image }}" width="150px" alt="Content Image">
                                @endif
            </div>
        </div> -->
    </div>
    <!-- Dialect -->
    <div class="row justify-content-center">
                
            <b class="fs-1" style="font-size: 50px">{{ $content->text }}</b>
        
    </div>
    <!-- English Equivalent -->
    <div class="row justify-content-center">
               <p>English Equivalent</p> 
            <i class="fs-1" style="font-size: 40px">{{ $content->english }}</i>
        
    </div>
    <div class="row justify-content-center">
    @if ($content->video)
                                    <video width="500px" controls>
                                        <source src="{{ $content->video }}" type="video/mp4">
                                    </video>
                             @endif
    </div>


<!-- KANI RA AJ AKAOH GE USOB GE PLUS 1 ang ID pero ka sunod ani is sa controller para maka back sa before na content ug forward  -->
    <div class="row justify-content-center">
        <a class="btn btn-primary" type="button" href="{{  route('courses.lessons.contents.show', [$course->id, $lesson->id, $content->id + 1]) }}">proceed</a>
    </div>
</div>
@endsection