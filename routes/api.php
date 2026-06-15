<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DailyRecordController;
use App\Http\Controllers\Api\EntertainmentController;
use App\Http\Controllers\Api\HistoriController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {

    Route::apiResource('/tasks', TaskController::class);
    Route::post('/tasks/{id}/check', [TaskController::class, 'checkTask']);

    Route::get('/apps', [EntertainmentController::class, 'index']);
    Route::post('/apps/{id}/purchase', [EntertainmentController::class, 'purchase']);
    Route::post('/apps/complete', [EntertainmentController::class, 'completeSession']);

    Route::post('/relax/session/start', [EntertainmentController::class, 'sessionStart']);
    Route::post('/relax/session/end', [EntertainmentController::class, 'sessionEnd']);
    Route::get('/relax/session/active', [EntertainmentController::class, 'activeSession']);
    Route::get('/relax/session/history', [EntertainmentController::class, 'sessionHistory']);

    Route::get('/coin-histori', [HistoriController::class, 'index']);

    Route::get('/daily-record', [DailyRecordController::class, 'show']);
    Route::post('/daily-record/mood', [DailyRecordController::class, 'storeMood']);
    Route::post('/daily-record/rest-day', [DailyRecordController::class, 'useRestDay']);

    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::put('/user/update', [UserController::class, 'updateProfile']);
    Route::post('/user/change-email', [UserController::class, 'changeEmail']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
    Route::post('/user/settings', [UserController::class, 'updateSettings']);

    Route::get('/streak', [UserController::class, 'streak']);
    Route::post('/streak/increment', [UserController::class, 'incrementStreak']);

    Route::post('/mood', [DailyRecordController::class, 'storeMood']);
    Route::get('/mood/history', [DailyRecordController::class, 'moodHistory']);

    Route::get('/coins/history', [HistoriController::class, 'index']);
    Route::post('/coins/earn', [HistoriController::class, 'earnCoins']);
    Route::post('/coins/spend', [HistoriController::class, 'spendCoins']);

    Route::get('/puzzles', [DailyRecordController::class, 'puzzles']);
    Route::post('/puzzles/unlock', [DailyRecordController::class, 'unlockPuzzle']);

    Route::apiResource('/notes', NoteController::class);
});
