<?php

use App\Http\Controllers\AcceptAnswerController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionController;
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
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    /* Question resource routes */
    Route::get('/questions', [QuestionController::class, 'index']);
    Route::post('/questions', [QuestionController::class, 'store']);
    Route::get('/questions/{question:unique}/{slug?}', [QuestionController::class, 'show']);
    Route::get('/questions/{question:unique}/{slug?}/edit', [QuestionController::class, 'edit']);
    Route::put('/questions/{question:unique}/{slug?}', [QuestionController::class, 'update']);
    Route::delete('/questions/{question:unique}/{slug?}', [QuestionController::class, 'destroy']);
    Route::get('/questions/{keyword}', [QuestionController::class, 'search']);

    /* Answer resource routes */
    Route::post('/questions/{question:unique}/answers', [AnswerController::class, 'store']);
    Route::get('/questions/{question:unique}/answers/{answer:unique}/edit', [AnswerController::class, 'edit']);
    Route::put('/questions/{question:unique}/answers/{answer:unique}', [AnswerController::class, 'update']);
    Route::delete('/questions/{question:unique}/answers/{answer:unique}', [AnswerController::class, 'destroy']);
    Route::post('/answers/{answer:unique}/accept', AcceptAnswerController::class);
});
