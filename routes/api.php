<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import the controllers
use App\Http\Controllers\Api\CourseController as ApiCourseController;
use App\Http\Controllers\Api\LessonController as ApiLessonController;
use App\Http\Controllers\Api\ContentController as ApiContentController;
use App\Http\Controllers\Api\QuestionController as ApiQuestionController;
use App\Http\Controllers\Api\AnswerController as ApiAnswerController;
use App\Http\Controllers\Api\HomeController as ApiHomeController;

// Define the API routes
// Route::middleware('auth:api')->group(function () {
//// API ROUTES FOR THE ADMIN
    Route::prefix('admin')->group(function () {
        // Route::get('dashboard', [ApiHomeController::class, 'index']);
        Route::resource('courses',ApiCourseController::class);
        Route::resource('courses.lessons', ApiLessonController::class);     
        Route::resource('courses.lessons.contents', ApiContentController::class);
        Route::resource('courses.lessons.contents.questions', ApiQuestionController::class);
        Route::resource('courses.lessons.contents.questions.answers', ApiAnswerController::class);  
    });

    //API ROUTES FOR THE USER TO GET REQUEST

   // User Routes (GET requests only)
Route::prefix('user')->group(function () {
    Route::get('courses', [ApiCourseController::class, 'index']);
    Route::get('courses/{course}', [ApiCourseController::class, 'show']);
    Route::get('courses/{course}/lessons', [ApiLessonController::class, 'index']);
    Route::get('courses/{course}/lessons/{lesson}', [ApiLessonController::class, 'show']);
    Route::get('courses/{course}/lessons/{lesson}/contents', [ApiContentController::class, 'index']);
    Route::get('courses/{course}/lessons/{lesson}/contents/{content}', [ApiContentController::class, 'show']);
    Route::get('courses/{course}/lessons/{lesson}/contents/{content}/questions', [ApiQuestionController::class, 'index']);
    Route::get('courses/{course}/lessons/{lesson}/contents/{content}/questions/{question}', [ApiQuestionController::class, 'show']);
    Route::get('courses/{course}/lessons/{lesson}/contents/{content}/questions/{question}/answers', [ApiAnswerController::class, 'index']);
    Route::get('courses/{course}/lessons/{lesson}/contents/{content}/questions/{question}/answers/{answer}', [ApiAnswerController::class, 'show']);
});




