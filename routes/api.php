<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Address\CityController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Pet\RaceController;
use App\Http\Controllers\Pet\SpecieController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Financial\WalletController;
use App\Http\Controllers\Financial\WalletMovementController;
use App\Http\Controllers\Financial\ChartOfAccountController;
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
    Route::middleware('check.address')->group(function () {
        Route::get('/users/index', [UserController::class, 'getUsers']);
    });
    Route::post('/user/upload-photo', [UserController::class, 'uploadPhoto'])->name('user.uploadPhoto');
    Route::put('/user/update', [UserController::class, 'update']);
    Route::delete('/user/delete-photo', [UserController::class, 'delete']);
    Route::put('/user/{id}/inactive', [UserController::class, 'inactive']);
    Route::put('/user/{id}/active', [UserController::class, 'active']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);

    //cities
    Route::get('/city', [CityController::class, 'getCities']);

    //pets
    Route::get('/pets/index', [PetController::class, 'index']);
    Route::middleware('check.address')->group(function () {
        Route::post('pets/create', [PetController::class, 'store']);
        Route::put('pet/{id}', [PetController::class, 'update']);
        Route::post('pets/{petId}/upload-photo', [PetController::class, 'uploadPhoto']);
        Route::delete('pets/{petId}/delete-photo', [PetController::class, 'deletePhoto']);
        Route::post('pets/{petId}/adopt', [PetController::class, 'adopt']);
        Route::post('/races/store', [RaceController::class, 'store']);
        Route::post('/species/store', [SpecieController::class, 'store']);
    });
    Route::get('/races/index', [RaceController::class, 'index']);
    Route::get('/species/index', [SpecieController::class, 'index']);

    Route::get('/species/index', [SpecieController::class, 'index']);

    // Companies
    Route::get('/companies/index', [CompanyController::class, 'index']);
    Route::middleware('check.address')->group(function () {
        Route::get('/companies/{cnpj}', [CompanyController::class, 'getCompanyByCnpj']);
        Route::post('/companies/create', [CompanyController::class, 'store']);
    });

    // Wallet routes
    Route::get('/wallets/index', [WalletController::class, 'listAll']);
    Route::prefix('wallet')->group(function () {
        Route::post('/create', [WalletController::class, 'create']);
        Route::put('/update/{id}', [WalletController::class, 'update']);
        Route::get('/{walletId}/balance', [WalletController::class, 'getBalance']);
        Route::get('/movements/index', [WalletMovementController::class, 'listAll']); 
        Route::post('/movement', [WalletMovementController::class, 'create']);
        Route::get('/{walletId}/movements', [WalletMovementController::class, 'getMovements']);
    });

    // Chart of Accounts routes
    Route::prefix('chart-accounts')->group(function () {
        Route::get('/index', [ChartOfAccountController::class, 'index']);
        Route::get('/children/{id}', [ChartOfAccountController::class, 'getChildren']);
        Route::get('/{id}/parents', [ChartOfAccountController::class, 'getParents']);
        Route::get('/{id}', [ChartOfAccountController::class, 'show']);
        Route::post('/create', [ChartOfAccountController::class, 'create']);
        Route::put('/{id}', [ChartOfAccountController::class, 'update']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});