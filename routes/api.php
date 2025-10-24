<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ApplicationProblemController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketsControllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware(['web'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tickets', TicketsControllers::class);
    Route::apiResource('applications', ApplicationController::class);
    Route::apiResource('application-problems', ApplicationProblemController::class);

    Route::post('/logout', [AuthController::class, 'logout']);
});
