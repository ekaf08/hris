<?php

use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**User Auth API*/
Route::name('auth.')->group(function () {
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('register', [UserController::class, 'register'])->name('register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('user', [UserController::class, 'fetch'])->name('user');
    });
});

/** Company API */
Route::name('company.')->middleware('auth:sanctum')->group(function () {
    Route::get('company', [CompanyController::class, 'fetch'])->name('fetch');
    Route::post('company', [CompanyController::class, 'create'])->name('create');
    Route::post('/company/update/{id}', [CompanyController::class, 'update'])->name('update');
});

/** Team API */
Route::name('team.')->middleware('auth:sanctum')->group(function () {
    Route::get('team', [TeamController::class, 'fetch'])->name('fetch');
});
