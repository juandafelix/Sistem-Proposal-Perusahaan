<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register',[AuthController::class, 'register'])->name('auth.register');
    Route::post('/login',[AuthController::class, 'login'])->name('auth.login');
});
Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/logout',[AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/me',[AuthController::class, 'me'])->name('auth.me');
    });

    Route::prefix('submissions')->group(function () {
        Route::get('/',[SubmissionController::class, 'index'])->name('submissions.index');
        Route::post('/',[SubmissionController::class, 'store'])->name('submissions.store');
        Route::get('/{submission}',[SubmissionController::class, 'show'])->name('submissions.show');
        Route::put('/{submission}',[SubmissionController::class, 'update'])->name('submissions.update');
        Route::put('/{submission}/approve',[SubmissionController::class, 'approve'])->name('submissions.approve');
    });
});
