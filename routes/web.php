<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\admin\QuestionController;
use App\Http\Controllers\admin\SubQuestionController;
use App\Http\Controllers\apiTestController;
use App\Http\Controllers\OpenAIController;


//auth route
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'index'])->name('login'); // Show login page
    Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); // Login users
    Route::get('registration', [AuthController::class, 'registration'])->name('register'); // Show registration page
    Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); // User registration
});


//public route
Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout'); // Logout user
    Route::middleware('public.user')->group(function () {
        Route::get('/', [OpenAIController::class, 'index'])->name('index'); // Show chat window
        Route::post('get-question', [OpenAIController::class, 'getQuestions'])->name('get.question'); // AJAX URL for getting already created data from the database
        Route::get('new-chat', [OpenAIController::class, 'newChatWindow'])->name('newChatWindow'); // Show chat window
    });


//admin route
    Route::middleware('is.admin')->prefix('admin')->group(function () {
        Route::get('/', [QuestionController::class, 'index'])->name('admin.index'); // Admin dashboard
        Route::resource('question', QuestionController::class);
        Route::post('first-question-status', [QuestionController::class, 'updateFirstQuestion'])->name('update.first.question');
        Route::post('last-question-status', [QuestionController::class, 'updateLastQuestion'])->name('update.last.question');
        Route::post('get-level-questions', [QuestionController::class, 'getLevelQuestions'])->name('get.level.question');
        Route::post('get-master-sub-questions', [QuestionController::class, 'getMasterSubQuestions'])->name('get.master.sub.questions');
        Route::post('master-questions-and-sub-questions', [QuestionController::class, 'masterQuestionsAndSubQuestions'])->name('master.questions.and.sub.questions');
        Route::resource('subquestion', SubQuestionController::class);
    });
});
