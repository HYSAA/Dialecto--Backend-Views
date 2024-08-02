<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


//para ang admin ray maka access 
// Route::middleware(['auth', 'admin'])->prefix('api')->group(function () {
//     Route::get('admin/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
//     Route::resource('admin/courses', CourseController::class);
//     Route::resource('admin/lessons', LessonController::class);
//     Route::resource('courses', CourseController::class);
//     Route::resource('courses.lessons', LessonController::class);
//     Route::resource('lessons.contents', ContentController::class);
// });


//ADMIN ROUTES
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::resource('courses', CourseController::class);
    Route::resource('courses.lessons', LessonController::class);
    Route::resource('courses.lessons.contents', ContentController::class);
    Route::resource('courses.lessons.contents.questions', QuestionController::class);
    Route::resource('courses.lessons.contents.questions.answers', AnswerController::class);
    Route::get('/questions', function () {
        return view('questions');
    });
    
    //USER ROUTES
   
    
});

// Route::middleware(['auth', 'user'])->group(function () {
//     Route::get('courses', 'CourseController@index')->name('courses.index');
// });

 
