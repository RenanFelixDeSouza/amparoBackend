<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CIty\CityController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Pet\RaceController;
use App\Http\Controllers\Pet\SpecieController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Company\CompanyController;
use Illuminate\Support\Facades\Route;

// Rotas públicas
//login
Route::post('/login', [AuthController::class, 'login']);
//register
Route::post('/register', [AuthController::class, 'register']);

// Rotas autenticadas
Route::middleware('auth:sanctum')->group(function () {
    //user
    Route::get('/user', [UserController::class, 'getUser']);
    Route::get('/users/index', [UserController::class, 'getUsers']);
    Route::post('/user/upload-photo', [UserController::class, 'uploadPhoto'])->name('user.uploadPhoto');
    Route::put('/user/update', [UserController::class, 'update']);
    Route::delete('/user/delete-photo', [UserController::class, 'delete']);

    //cities
    Route::get('/city', [CityController::class, 'getCities']);

    //pets
    Route::get('/pets/index', [PetController::class, 'index']);
    Route::post('pets/create', [PetController::class, 'store']);
    Route::post('pets/{petId}/upload-photo', [PetController::class, 'uploadPhoto']);
    Route::delete('pets/{petId}/delete-photo', [PetController::class, 'deletePhoto']);
    Route::post('pets/{petId}/adopt', [PetController::class, 'adopt']);
    Route::get('/races/index', [RaceController::class, 'index']);
    Route::post('/races/store', [RaceController::class, 'store']);
    Route::get('/species/index', [SpecieController::class, 'index']);
    Route::post('/species/store', [SpecieController::class, 'store']);
    
    Route::get('/species/index', [SpecieController::class, 'index']);
    
    // Companies
    Route::get('/companies/index', [CompanyController::class, 'index']);
    Route::get('/companies/{cnpj}', [CompanyController::class, 'getCompanyByCnpj']);
    Route::post('/companies/create', [CompanyController::class, 'store']);

    Route::post('/logout', [AuthController::class, 'logout']);
});