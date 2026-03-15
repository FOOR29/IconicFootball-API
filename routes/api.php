<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['status' => 'ok'], 200);
});

Route::middleware('throttle:public')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('throttle:public')->group(function () {
    Route::get('/players', [PlayerController::class, 'index']);
    Route::get('/players/{id}', [PlayerController::class, 'show']);
});

Route::middleware('isUserAuth', 'throttle:auth')->group(function () {
    Route::get('/clubs', [ClubController::class, 'index']);
    Route::get('/countries', [CountriesController::class, 'index']);

    Route::get('/me', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['isUserAuth', 'isAdmin', 'throttle:admin'])->group(function () {
    Route::post('/players', [PlayerController::class, 'store']);
    Route::delete('/players/{id}', [PlayerController::class, 'destroy']);
    Route::put('/players/{id}', [PlayerController::class, 'update']);
    Route::patch('/players/{id}', [PlayerController::class, 'updatePartial']);

    Route::post('/clubs', [ClubController::class, 'store']);
    Route::delete('/clubs/{id}', [ClubController::class, 'destroy']);
    Route::put('/clubs/{id}', [ClubController::class, 'update']);

    Route::post('/countries', [CountriesController::class, 'store']);
    Route::delete('/countries/{id}', [CountriesController::class, 'destroy']);
    Route::put('/countries/{id}', [CountriesController::class, 'update']);
});
