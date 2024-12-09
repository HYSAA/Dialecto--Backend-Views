<?php

// admin
use App\Http\Controllers\WordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController; //first
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WordBankController;

// user 

use App\Http\Controllers\User\CourseController as UserCourseController;
use App\Http\Controllers\User\LessonController as UserLessonController;
use App\Http\Controllers\User\ContentController as UserContentController;
use App\Http\Controllers\User\QuizController;
use App\Http\Controllers\ControllerProfile as  UserControllerProfile;
use App\Http\Controllers\User\UserProgressController;


//expert
use App\Http\Controllers\Expert\CourseController as  ExpertCourseController;
use App\Http\Controllers\Expert\LessonController as  ExpertLessonController;
use App\Http\Controllers\Expert\ContentController as  ExpertContentController;
use App\Http\Controllers\Expert\QuizController as ExpertQuizController;
use App\Http\Controllers\Expert\ControllerProfile as  ExpertControllerProfile;
use App\Http\Controllers\Expert\ExpertController;
use App\Http\Controllers\Expert\ExpertProgresscontroller;
use App\Http\Controllers\Expert\ExpertDictionary;


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
    Route::get('lessons', [LessonController::class, 'index'])->name('lessons.index');

    // Route::get('dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
    // Route::get('users.show', [LessonController::class, 'index'])->name('lessons.index');

    Route::resource('courses', CourseController::class)->names([
        'index' => 'admin.courses.index',
        'create' => 'admin.courses.create',
        'store' => 'admin.courses.store',
        'show' => 'admin.courses.show',
        'edit' => 'admin.courses.edit',
        'update' => 'admin.courses.update',
        'destroy' => 'admin.courses.destroy'
    ]);

    // Route::resource('courses.lessons', LessonController::class);

    Route::resource('courses.lessons', LessonController::class)->names([
        'index' => 'admin.lessons.index',
        'create' => 'admin.lessons.create',
        'store' => 'admin.lessons.store',
        'show' => 'admin.lessons.show',
        'edit' => 'admin.lessons.edit',
        'update' => 'admin.lessons.update',
        'destroy' => 'admin.lessons.destroy'
    ]);

    // Route::resource('courses.lessons.contents', ContentController::class);

    Route::resource('courses.lessons.contents', ContentController::class)->names([
        'index' => 'admin.contents.index',
        'create' => 'admin.contents.create',
        'store' => 'admin.contents.store',
        'show' => 'admin.contents.show',
        'edit' => 'admin.contents.edit',
        'update' => 'admin.contents.update',
        'destroy' => 'admin.contents.destroy',
    ]);

    Route::resource('courses.lessons.questions', QuestionController::class)->names([
        'index' => 'admin.questions.index',
        'create' => 'admin.questions.create',
        'store' => 'admin.questions.store',
        'show' => 'admin.questions.show',
        'edit' => 'admin.questions.edit',
        'update' => 'admin.questions.update',
        'destroy' => 'admin.questions.destroy',
    ]);

    Route::resource('courses.lessons.questions', QuestionController::class);
    Route::resource('courses.lessons.questions.answers', AnswerController::class);
    Route::get('/questions', function () {
        return view('questions');
    });


    Route::resource('users', UserController::class);


    Route::get('/pending-expert', [UserController::class, 'showPendingExpert'])->name('admin.showPendingExpert');

    Route::get('/post-verify/{id}', [UserController::class, 'postVerify'])->name('admin.postVerify');
    Route::get('/post-postDeny/{id}', [UserController::class, 'postDeny'])->name('admin.postDeny');



    Route::get('/word-bank', [WordBankController::class, 'showWordBank'])->name('admin.showWordBank');

    Route::get('/word-bank/{id}', [WordBankController::class, 'wordBankCourse'])->name('admin.wordBankCourse');

    Route::get('/word-bank/{courseid}/addWordToLesson/{wordid}', [WordBankController::class, 'addWordToLesson'])->name('admin.addWordToLesson');
    Route::get('/word-bank/{courseid}/removeWord/{wordid}', [WordBankController::class, 'removeWord'])->name('admin.removeWord');
});




Route::middleware(['auth', 'expert'])->prefix('expert')->group(function () {

    // Dashboard route
    Route::get('/dashboard', [ExpertCourseController::class, 'index'])->name('expert.dashboard');

    Route::resource('courses', ExpertCourseController::class)->names([
        'index' => 'expert.courses.index',
        'create' => 'expert.courses.create',
        'store' => 'expert.courses.store',
        'show' => 'expert.courses.show',
        'edit' => 'expert.courses.edit',
        'update' => 'expert.courses.update',
        'destroy' => 'expert.courses.destroy'
    ]);

    Route::resource('courses.lessons', ExpertLessonController::class)->names([
        'index' => 'expert.lessons.index',
        'create' => 'expert.lessons.create',
        'store' => 'expert.lessons.store',
        'show' => 'expert.lessons.show',
        'edit' => 'expert.lessons.edit',
        'update' => 'expert.lessons.update',
        'destroy' => 'expert.lessons.destroy'
    ]);

    Route::resource('courses.lessons.contents', ExpertContentController::class)->names([
        'index' => 'expert.contents.index',
        'create' => 'expert.contents.create',
        'store' => 'expert.contents.store',
        'show' => 'expert.contents.show',
        'edit' => 'expert.contents.edit',
        'update' => 'expert.contents.update',
        'destroy' => 'expert.contents.destroy',
    ]);


    Route::get('/contribute-word', [ExpertController::class, 'contributeWord'])->name('expert.contributeWord');

    Route::post('/expert/submit-contribute-word', [ExpertController::class, 'submitContributeWord'])->name('expert.submitContributeWord');

    Route::get('/pending-words', [ExpertController::class, 'index'])->name('expert.pendingWords');

    Route::post('/expert/approve-word/{id}', [ExpertController::class, 'approveWord'])->name('expert.approveWord');
    Route::post('/expert/disapprove-word/{id}', [ExpertController::class, 'disapproveWord'])->name('expert.disapproveWord');

    //Progress
    Route::get('/expert/progress/{id}', [ExpertProgresscontroller::class, 'expertprogress'])->name('expert.progress');

    Route::get('/expert/dictionary/{id}',[ExpertDictionary::class,'expertdictionary'])->name('expert.dictionary');



    // Quiz routes
    Route::get('/quiz', [ExpertQuizController::class, 'show'])->name('expert.quiz');

    Route::get('/courses/{courseId}/lessons/{lessonId}/quiz', [ExpertQuizController::class, 'showQuiz'])->name('expert.quiz.show');
    Route::post('/courses/{courseId}/lessons/{lessonId}/quiz', [ExpertQuizController::class, 'submitQuiz'])->name('expert.quiz.submit');
    Route::get('/courses/{courseId}/lessons/{lessonId}/quiz/result', [ExpertQuizController::class, 'showResult'])->name('expert.quiz.result');

    // Multiple choice quiz
    Route::get('/courses/{courseId}/lessons/{lessonId}/multipleChoice', [ExpertQuizController::class, 'multipleChoice'])->name('expert.multipleChoice.show');

    // Profile routes
    Route::get('/profile', [ExpertControllerProfile::class, 'show'])->name('expert.profile.show');
    Route::get('/profile/edit', [ExpertControllerProfile::class, 'edit'])->name('expert.profile.edit');
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


   

    Route::get('/wordSuggested/{id}/viewUpdateSelected', [UserController::class, 'viewUpdateSelected'])->name('user.viewUpdateSelected');

    Route::get('/wordSuggested/{id}/deleteSelectedWord', [UserController::class, 'deleteSelectedWord'])->name('user.deleteSelectedWord');

    Route::post('/wordSuggested/{id}/updateSelected', [UserController::class, 'updateSelected'])->name('user.updateSelected');

    Route::get('/wordSuggested', [UserController::class, 'wordSuggested'])->name('user.wordSuggested');




    Route::get('/selectUserCourseLesson', [UserController::class, 'selectUserCourseLesson'])->name('user.selectUserCourseLesson');
    Route::get('/selectUserCourseLesson/courses/{courseId}/lessons/{lessonId}/addUserSuggestedWord', [UserController::class, 'addUserSuggestedWord'])->name('user.addUserSuggestedWord');

    Route::post('/selectUserCourseLesson/courses/{courseId}/lessons/{lessonId}/submitwordSuggested', [UserController::class, 'submitWordSuggested'])->name('user.submitWordSuggested');
    // Route::get('/get-lessons/{course}', [UserController::class, 'getLessons']);


    Route::get('/quiz', [UserController::class, 'show'])->name('user.quiz');

    Route::get('/courses/{courseId}/lessons/{lessonId}/quiz', [QuizController::class, 'showQuiz'])->name('user.quiz.show');
    Route::post('/courses/{courseId}/lessons/{lessonId}/quiz', [QuizController::class, 'submitQuiz'])->name('quiz.submit');
    Route::get('/courses/{courseId}/lessons/{lessonId}/quiz/result', [QuizController::class, 'showResult'])->name('quiz.result');






    Route::get('/courses/{courseId}/lessons/{lessonId}/multipleChoice', [QuizController::class, 'multipleChoice'])->name('user.multipleChoice.show');

    Route::post('/courses/{courseId}/lessons/{lessonId}/quiz', [QuizController::class, 'submitQuiz'])->name('quiz.submit');
    Route::get('/courses/{courseId}/lessons/{lessonId}/quiz/result', [QuizController::class, 'showResult'])->name('quiz.result');


    Route::get('/survey', [SurveyController::class, 'showSurvey'])->name('survey.show');
    Route::post('/survey', [SurveyController::class, 'submitSurvey'])->name('survey.submit');
    Route::get('/courses/{course}/completed-lessons', [SurveyController::class, 'countCompletedLessons'])->name('course.completed.lessons');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [SurveyController::class, 'completeLesson'])->name('lesson.complete');





    Route::get('/profile/{id}', [UserControllerProfile::class, 'show'])->name('user.profile.show');
    Route::get('/profile/edit', [UserControllerProfile::class, 'edit'])->name('user.profile.edit');
    Route::get('/profile/{id}/apply-expert', [UserControllerProfile::class, 'applyExpert'])->name('user.profile.applyExpert');


    Route::get('/user/progress/{id}', [UserProgressController::class, 'userprogress'])->name('user.progress');

    Route::get('/user/dictionary/{id}',[UserDictionary::class,'userdictionary'])->name('user.dictionary');


    Route::post('/profile/posting-credentials', [UserControllerProfile::class, 'postCredentials'])->name('user.profile.postCredentials');

    Route::get('/profile/submitted-creds/{name}', [UserControllerProfile::class, 'submittedCredentials'])->name('user.profile.submittedCredentials');
});
