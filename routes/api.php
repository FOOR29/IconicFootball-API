<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/players', [App\Http\Controllers\PlayerController::class, 'index']);
Route::get('/players/{id}', [App\Http\Controllers\PlayerController::class, 'show']);
Route::post('/players', [App\Http\Controllers\PlayerController::class, 'store']);
Route::delete('/players/{id}', [App\Http\Controllers\PlayerController::class, 'destroy']);
Route::put('/players/{id}', [App\Http\Controllers\PlayerController::class, 'update']);
Route::patch('/players/{id}', [App\Http\Controllers\PlayerController::class, 'updatePartial']);

//clubs y counties
Route::get('/clubs', [App\Http\Controllers\ClubController::class, 'index']);
Route::post('/clubs', [App\Http\Controllers\ClubController::class, 'store']);
Route::delete('/clubs/{id}', [App\Http\Controllers\ClubController::class, 'destroy']);
Route::put('/clubs/{id}', [App\Http\Controllers\ClubController::class, 'update']);

//countries
Route::get('/countries', [App\Http\Controllers\CountriesController::class, 'index']);
Route::post('/countries', [App\Http\Controllers\CountriesController::class, 'store']);
Route::delete('/countries/{id}', [App\Http\Controllers\CountriesController::class, 'destroy']);
Route::put('/countries/{id}', [App\Http\Controllers\CountriesController::class, 'update']);
