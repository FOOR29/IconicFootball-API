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

