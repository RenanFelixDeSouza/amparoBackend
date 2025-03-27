<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rotas autenticadas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/user/upload-photo', [UserController::class, 'uploadPhoto'])->name('user.uploadPhoto');
    Route::put('/user/update', [UserController::class, 'update']);

    Route::post('/logout', [AuthController::class, 'logout']);
});