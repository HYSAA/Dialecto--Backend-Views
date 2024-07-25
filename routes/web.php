<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ContentController;

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
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::resource('courses', CourseController::class);
    Route::resource('courses.lessons', LessonController::class);
    
    // Route::resource('lessons.contents', ContentController::class);
    
    // Route::resource('courses.lessons.contents.index',ContentController::class);
    Route::resource('courses.lessons.contents', ContentController::class);
    
  
    
});


 
//API GATEWAY
// Route::prefix('api')->group(function () {
//     Route::resource('admin/courses', CourseController::class);
//     Route::resource('admin/lessons', LessonController::class);
// });