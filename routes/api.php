<?php

use App\Http\Controllers\CountdownStateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/countdown-state', CountdownStateController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
