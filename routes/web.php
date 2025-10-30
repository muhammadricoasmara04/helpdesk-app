<?php

use App\Http\Controllers\Web\ApplicationProblemController;
use App\Http\Controllers\Web\ApplicationController;
use App\Http\Controllers\Web\AuthPageController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\TicketPriorityController;
use App\Http\Controllers\Web\TicketReplyController;
use App\Http\Controllers\Web\TicketStatusController;
use App\Http\Controllers\Web\User\DashboardUserController;
use App\Http\Controllers\Web\User\TicketUserController;
use App\Http\Controllers\Web\User\TicketUserReplyController;
use Illuminate\Support\Facades\Broadcast;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::get('/', [AuthPageController::class, 'index'])->name('login');
Route::post('/', [AuthPageController::class, 'login']);
Route::post('/logout', [AuthPageController::class, 'logout'])->name('logout');
Route::middleware(['auth', 'role:admin'])
    ->prefix('dashboard')
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Applications
        Route::prefix('application')->group(function () {
            Route::get('/', [ApplicationController::class, 'index'])->name('application');
            Route::get('/addapp', [ApplicationController::class, 'store'])->name('application-store');
            Route::get('/{id}', [ApplicationController::class, 'show'])->name('application.show');
            Route::get('/{id}/edit', [ApplicationController::class, 'edit']);
            Route::put('/{id}', [ApplicationController::class, 'update']);
            Route::get('/updateapp', [ApplicationController::class, 'update'])->name('application-update');
        });

        // Application Problems
        Route::prefix('application-problems')->group(function () {
            Route::get('/', [ApplicationProblemController::class, 'index'])->name('application-problems');
            Route::get('/addapp', [ApplicationProblemController::class, 'store'])->name('application-problems-store');
            Route::get('/{id}', [ApplicationProblemController::class, 'show'])->name('application-problem.show');
            Route::get('/{id}/edit', [ApplicationProblemController::class, 'edit'])->name('application-problem.edit');
            Route::put('/{id}', [ApplicationProblemController::class, 'update'])->name('application-problem.update');
        });

        // Ticket Status
        Route::prefix('ticket-status')->group(function () {
            Route::get('/', [TicketStatusController::class, 'index'])->name('ticket-status');
            Route::get('/addticketstatus', [TicketStatusController::class, 'store'])->name('ticket-status-store');
        });

        // Ticket Priority
        Route::prefix('ticket-priority')->group(function () {
            Route::get('/', [TicketPriorityController::class, 'index'])->name('ticket-priority');
            Route::get('/addticketpriority', [TicketPriorityController::class, 'store'])->name('ticket-priority-store');
        });

        Route::prefix('ticket-reply-admin')->group(function () {
            Route::get('/{id}', [TicketReplyController::class, 'index'])->name('ticket-replied-admin');
        });
    });

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard/user', [DashboardUserController::class, 'index'])->name('dashboard.user');

    Route::prefix('user')->middleware(['auth'])->group(function () {
        Route::get('/ticket/create', [TicketUserController::class, 'index'])->name('ticket.index');
        Route::post('/ticket/store', [TicketUserController::class, 'store'])->name('ticket.store');
    });

    Route::prefix('ticket-reply')->group(function () {
        Route::get('/{id}', [TicketUserReplyController::class, 'index'])->name('ticket-replied');
    });
});

Broadcast::routes(['middleware' => ['auth:sanctum']]);
