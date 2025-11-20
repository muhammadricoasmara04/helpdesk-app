<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\ApplicationProblemController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketReplyController;
use App\Http\Controllers\Api\TicketsControllers;
use App\Http\Controllers\Api\TicketsPriorityController;
use App\Http\Controllers\Api\TicketsProtityController;
use App\Http\Controllers\Api\TicketsStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;



Broadcast::routes(['middleware' => ['auth:sanctum']]);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/tickets', [TicketsControllers::class, 'myTickets']);
    Route::post('/tickets/{id}/assign', [TicketsControllers::class, 'assign']);
    Route::put('/tickets/{id}/close', [TicketReplyController::class, 'close']);
    Route::get('/staff', [AuthController::class, 'getStaff']);
    
    Route::apiResource('tickets', TicketsControllers::class);
    Route::apiResource('applications', ApplicationController::class);
    Route::apiResource('application-problems', ApplicationProblemController::class);
    Route::apiResource('ticket-status', TicketsStatusController::class);
    Route::apiResource('ticket-priority', TicketsPriorityController::class);
    Route::apiResource('ticket-replies', TicketReplyController::class);

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
