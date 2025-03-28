<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CIty\CityController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rotas autenticadas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/user/upload-photo', [UserController::class, 'uploadPhoto'])->name('user.uploadPhoto');
    Route::put('/user/update', [UserController::class, 'update']);
    Route::delete('/user/delete-photo', [UserController::class, 'delete']);
    
    
    Route::get('/city', [CityController::class, 'getCities']);


    Route::post('/logout', [AuthController::class, 'logout']);
});