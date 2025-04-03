<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CIty\CityController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Pet\RaceController;
use App\Http\Controllers\Pet\SpecieController;
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

    //pets
    Route::post('pets/create', [PetController::class, 'store']);
    Route::post('pets/{petId}/upload-photo', [PetController::class, 'uploadPhoto']);
    Route::get('/races/index', [RaceController::class, 'index']);
    Route::get('/species/index', [SpecieController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout']);
});