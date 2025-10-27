<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ApplicationProblemController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketsControllers;
use App\Http\Controllers\Api\TicketsPriorityController;
use App\Http\Controllers\Api\TicketsProtityController;
use App\Http\Controllers\Api\TicketsStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tickets', TicketsControllers::class);
    Route::apiResource('applications', ApplicationController::class);
    Route::apiResource('application-problems', ApplicationProblemController::class);
    Route::apiResource('ticket-status', TicketsStatusController::class);
    Route::apiResource('ticket-priority', TicketsPriorityController::class);
    
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
