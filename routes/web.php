<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\OpenAIController_copy;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\MailController;

use App\Http\Controllers\admin\QuestionController;
use App\Http\Controllers\apiTestController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//Auth

Route::get('login', [AuthController::class, 'index'])->name('login')->middleware('guest');//show login page
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');//login users
Route::get('registration', [AuthController::class, 'registration'])->name('register')->middleware('guest');//show registration page
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); //users registration
Route::get('login', [AuthController::class, 'index'])->name('login')->middleware('guest');//show login page
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');//login users
Route::get('registration', [AuthController::class, 'registration'])->name('register')->middleware('guest');//show registration page
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); //users registration 

Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');//logout user
//Open ai
Route::get('/', [OpenAIController::class, 'index'])->name('index')->middleware('auth');//show chat window



//Openai/ajax
Route::post('get-question', [OpenAIController::class, 'getQuestions'])->name('get.question')->middleware('auth');//ajax url for  getting already created data from database

//test
Route::get('test-api', [apiTestController::class, 'fetchChatCompletions'])->name('test.api')->middleware('auth');//created for testing functions
// Route::get('/testchat', [OpenAIController_copy::class, 'index'])->name('index')->middleware('auth');

//test
Route::get('test-api', [apiTestController::class, 'fetchChatCompletions'])->name('test.api')->middleware('auth');//created for testing functions
Route::get('new-chat', [OpenAIController::class, 'newChatWindow'])->name('newChatWindow')->middleware('auth');//show chat window

//admin
Route::get('admin/', [QuestionController::class,'index'])->name('admin.index');

