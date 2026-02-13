<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\CountriesController;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | AUTH
 * |--------------------------------------------------------------------------
 */
Route::middleware('throttle:public')->group(function () {
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
});

/*
 * |--------------------------------------------------------------------------
 * | PUBLIC ROUTES
 * |--------------------------------------------------------------------------
 */

Route::middleware('throttle:public')->group(function () {
    Route::get('/players', [App\Http\Controllers\PlayerController::class, 'index']);
    Route::get('/players/{id}', [App\Http\Controllers\PlayerController::class, 'show']);
});

/*
 * |--------------------------------------------------------------------------
 * | AUTHENTICATED USERS
 * |--------------------------------------------------------------------------
 */

Route::middleware('isUserAuth', 'throttle:auth')->group(function () {
    Route::get('/clubs', [App\Http\Controllers\ClubController::class, 'index']);
    Route::get('/countries', [App\Http\Controllers\CountriesController::class, 'index']);

    Route::get('/me', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

/*
 * |--------------------------------------------------------------------------
 * | ADMIN ROUTES
 * |--------------------------------------------------------------------------
 */

Route::middleware(['isUserAuth', 'isAdmin', 'throttle:admin'])->group(function () {
    Route::post('/players', [App\Http\Controllers\PlayerController::class, 'store']);
    Route::delete('/players/{id}', [App\Http\Controllers\PlayerController::class, 'destroy']);
    Route::put('/players/{id}', [App\Http\Controllers\PlayerController::class, 'update']);
    Route::patch('/players/{id}', [App\Http\Controllers\PlayerController::class, 'updatePartial']);

    Route::post('/clubs', [App\Http\Controllers\ClubController::class, 'store']);
    Route::delete('/clubs/{id}', [App\Http\Controllers\ClubController::class, 'destroy']);
    Route::put('/clubs/{id}', [App\Http\Controllers\ClubController::class, 'update']);

    Route::post('/countries', [App\Http\Controllers\CountriesController::class, 'store']);
    Route::delete('/countries/{id}', [App\Http\Controllers\CountriesController::class, 'destroy']);
    Route::put('/countries/{id}', [App\Http\Controllers\CountriesController::class, 'update']);
});
