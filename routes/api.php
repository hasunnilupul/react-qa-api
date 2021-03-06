<?php

use App\Http\Controllers\AcceptAnswerController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AnswerVoteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarksController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionVoteController;
use App\Http\Controllers\ShortenLinkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * API v1 group
 */
Route::prefix('v1')->group(function () {
    /* Authentication routes */
    Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    /* Question resource routes */
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/{question:unique}/{slug?}', [QuestionController::class, 'show'])->name('questions.show');
    Route::get('/questions/{question:unique}/{slug?}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{question:unique}/{slug?}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question:unique}/{slug?}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::get('/search/{keyword}', [QuestionController::class, 'search'])->name('questions.search');
    Route::post('/questions/{question:unique}/{slug?}/bookmarks', [BookmarksController::class, 'store'])->name('questions.bookmark');
    Route::delete('/questions/{question:unique}/{slug?}/bookmarks', [BookmarksController::class, 'destroy'])->name('questions.remove-bookmark');
    Route::post('/questions/{question:unique}/{slug?}/vote', QuestionVoteController::class)->name('questions.vote');

    /* Answer resource routes */
    Route::get('/answers/{question:unique}/{unique?}', [AnswerController::class, 'index'])->name('answers.index');
    Route::post('/questions/{question:unique}/answers', [AnswerController::class, 'store'])->name('answers.store');
    Route::get('/questions/{question:unique}/answers/{answer:unique}/edit', [AnswerController::class, 'edit'])->name('answers.edit');
    Route::put('/questions/{question:unique}/answers/{answer:unique}', [AnswerController::class, 'update'])->name('answers.update');
    Route::delete('/questions/{question:unique}/answers/{answer:unique}', [AnswerController::class, 'destroy'])->name('answers.destroy');
    Route::post('/answers/{answer:unique}/accept', AcceptAnswerController::class)->name('answers.accept');
    Route::post('/answers/{answer:unique}/vote', AnswerVoteController::class)->name('answers.vote');

    /* Shorten routes */
    Route::get('/q/{question:unique}', [ShortenLinkController::class, 'question_show'])->name('questions.shorten');
    Route::get('/a/{answer:unique}', [ShortenLinkController::class, 'answer_show'])->name('answers.shorten');
});
