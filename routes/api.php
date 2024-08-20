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

    Route::prefix('user')->group(function () {
        // Route::get('dashboard', [ApiHomeController::class, 'index']);
        Route::resource('courses',ApiCourseController::class);
        Route::resource('courses.lessons', ApiLessonController::class);     
        Route::resource('courses.lessons.contents', ApiContentController::class);
        Route::resource('courses.lessons.contents.questions', ApiQuestionController::class);
        Route::resource('courses.lessons.contents.questions.answers', ApiAnswerController::class);  
    });




