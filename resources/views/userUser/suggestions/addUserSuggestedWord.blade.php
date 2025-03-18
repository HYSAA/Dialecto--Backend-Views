@extends('layouts.app')

@section('content')

    <div class="main-container" style="padding: 15px;">
        <div class="pull-right" style="padding:15px;">
            <a class="btn btn-main" href="{{ route('user.selectUserCourseLesson') }}">Back</a>
        </div>

        <div class="row" style="overflow-y: auto; ">
            <div class="col-lg-12 margin-tb">

                <div class="row justify-content-center">

                    <!-- Display current content in the selected course  -->
                     <!-- Left Side -->
                    <div style="padding:15px; width: 50%; background: #ebe4cc; margin: 5px;border-radius: 10px;">
                        <div style="border-radius: 10px; padding:15px; height:100%; overflow-y: auto; background-color: #fff;">
                            <h4 class="mb-2">Current content in {{ $course['name'] }}</h4>

                            @if (isset($lesson['contents']) && count($lesson['contents']) > 0)
                                <table style="width: 100%; border-collapse: collapse;" class="table table-hover table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">English
                                            </th>
                                            <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">
                                                Surigaonon</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lesson['contents'] as $contentId => $content)
                                            <tr>
                                                <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $content['english'] }}
                                                </td>
                                                <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ $content['text'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>No contents found for this lesson.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Form for adding a new suggested word -->
                     <!-- right -->
                    <div style="padding:30px; margin: 5px;height: fit-content; background: #ebe4cc; border-radius: 10px;">
                        <form action="{{ route('user.submitWordSuggested', [$courseId, $lessonId]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Hidden fields for user, course, and lesson IDs -->
                            <input type="hidden" name="user_id" value="{{ auth()->user()->firebase_id }}">


                            <input type="hidden" name="course_id" value="{{ $courseId }}">
                            <input type="hidden" name="lesson_id" value="{{ $lessonId }}">

                            <!-- Video upload input -->
                            <div class="col-xs-12 col-sm-12 col-md-12" style="width:100%">
                                <div style="margin-bottom: 15px;">
                                    <label for="video"
                                        style="font-weight: bold; display: block; margin-bottom: 5px;">Video:</label>
                                    <input type="file" name="video" class="form-control form-control-lg" style="
                                    padding: 8px; 
                                    border: 1px solid #ccc; 
                                    border-radius: 8px; 
                                    background-color: #fff; 
                                    width: 100%; 
                                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                                " required>
                                </div>

                                <!-- Input for the course-specific word -->
                                <div style="padding-top:15px;">
                                    <strong for="text" class="form-group">{{ $course['name'] }} text:</strong><br>
                                    <input type="text" name="text" id="text" required class="form-control form-control-lg" style="border-radius: 5px;">
                                </div>

                                <!-- Input for the English translation -->
                                <div style="padding-top:15px;">
                                    <strong for="english">English text:</strong><br>
                                    <input type="text" name="english" id="english" required class="form-control form-control-lg" style="border-radius: 5px;">
                                </div>

                                <!-- Submit button -->
                                <div style="padding-top:15px;">
                                    <button class="btn btn-back-main" style="margin-left:35%;" type="submit">Submit</button>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection