<?php

// admin

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController; //first
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\UserController;

// user 

use App\Http\Controllers\User\CourseController as UserCourseController;
use App\Http\Controllers\User\LessonController as UserLessonController;
use App\Http\Controllers\User\ContentController as UserContentController;






use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('auth.login');
});
// Route::get('/',[AdminController::class,'manageUsers']);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

//PARA NI LAHOS SA COURSES ANG USER IG OPEN 
// Route::get('/dashboard', [CourseController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


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
    //MAO NING CODE PARA MO LAHOS DIRECTLY SA COURSES IG DASHBOARD SA ADMIN
    Route::get('/dashboard', [CourseController::class, 'index'])->name('dashboard'); //bag o ni 

    // Route::get('dashboard', [HomeController::class, 'index'])->name('admin.dashboard');


    Route::resource('courses', CourseController::class)->names([
        'index' => 'admin.courses.index',
        'create' => 'admin.courses.create',
        'store' => 'admin.courses.store',
        'show' => 'admin.courses.show',
        'edit' => 'admin.courses.edit',
        'update' => 'admin.courses.update',
        'destroy' => 'admin.courses.destroy'
    ]);

    Route::resource('courses.lessons', LessonController::class);
    Route::get('lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::resource('courses.lessons.contents', ContentController::class);
    Route::resource('courses.lessons.contents.questions', QuestionController::class);
    Route::resource('courses.lessons.contents.questions.answers', AnswerController::class);
    Route::get('/questions', function () {
        return view('questions');
    });

    // route for viewing user 

    Route::resource('users', UserController::class);


    // Route::middleware(['auth', 'user'])->prefix('user')->group(function () {
    //     Route::resource('courses', CourseController::class);
    //     Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    // });

    //USER ROUTES


});









Route::middleware(['auth', 'user'])->prefix('user')->group(function () {

    // Dashboard route
    Route::get('/dashboard', [UserCourseController::class, 'index'])->name('user.dashboard');

    Route::resource('courses', UserCourseController::class)->names([
        'index' => 'user.courses.index',
        'create' => 'user.courses.create',
        'store' => 'user.courses.store',
        'show' => 'user.courses.show',
        'edit' => 'user.courses.edit',
        'update' => 'user.courses.update',
        'destroy' => 'user.courses.destroy'
    ]);

    Route::resource('courses.lessons', UserLessonController::class)->names([
        'index' => 'user.lessons.index',
        'create' => 'user.lessons.create',
        'store' => 'user.lessons.store',
        'show' => 'user.lessons.show',
        'edit' => 'user.lessons.edit',
        'update' => 'user.lessons.update',
        'destroy' => 'user.lessons.destroy'
    ]);

    Route::resource('courses.lessons.contents', UserContentController::class)->names([
        'index' => 'user.contents.index',
        'create' => 'user.contents.create',
        'store' => 'user.contents.store',
        'show' => 'user.contents.show',
        'edit' => 'user.contents.edit',
        'update' => 'user.contents.update',
        'destroy' => 'user.contents.destroy',
    ]);












    // Route::resource('courses.lessons.contents', ContentController::class);
    // Route::resource('courses.lessons.contents.questions', QuestionController::class);
    // Route::resource('courses.lessons.contents.questions.answers', AnswerController::class);

    // Additional lesson and question routes
    // Route::get('lessons', [LessonController::class, 'index'])->name('lessons.index');
    // Route::get('/questions', function () {
    //     return view('questions');
    // });

    // User resource
    // Route::resource('users', UserController::class);
});
