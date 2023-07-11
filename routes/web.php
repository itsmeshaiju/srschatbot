<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\OpenAIController_copy;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\MailController;
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
Route::get('login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register')->middleware('guest');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 
Route::get('dashboard', [AuthController::class, 'dashboard'])->middleware('auth');
Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
//Open ai
Route::get('/', [OpenAIController::class, 'index'])->name('index')->middleware('auth');
Route::post('chat-with-bot', [OpenAIController::class, 'botData'])->name('chat.with.bot')->middleware('auth');
Route::post('get-question', [OpenAIController::class, 'getQuestions'])->name('get.question')->middleware('auth');
// Route::get('/testchat', [OpenAIController_copy::class, 'index'])->name('index')->middleware('auth');

//test
// Route::get('testpdf', [PdfController::class, 'generatePDF'])->name('test.pdf')->middleware('auth');

