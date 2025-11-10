<?php

use App\Http\Controllers\Web\ApplicationProblemController;
use App\Http\Controllers\Web\ApplicationController;
use App\Http\Controllers\Web\AuthPageController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\OrganizationController;
use App\Http\Controllers\Web\TicketController;
use App\Http\Controllers\Web\TicketPriorityController;
use App\Http\Controllers\Web\TicketReplyController;
use App\Http\Controllers\Web\TicketStatusController;
use App\Http\Controllers\Web\User\DashboardUserController;
use App\Http\Controllers\Web\User\TicketUserController;
use App\Http\Controllers\Web\User\TicketUserReplyController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Broadcast;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

// Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [AuthPageController::class, 'index'])->name('login');
Route::post('/login', [AuthPageController::class, 'login'])->name('login.post');
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
            Route::delete('/{id}', [ApplicationController::class, 'destroy'])
                ->name('application.destroy');
        });

        // Application Problems
        Route::prefix('application-problems')->group(function () {
            Route::get('/', [ApplicationProblemController::class, 'index'])->name('application-problems');
            Route::get('/addapp', [ApplicationProblemController::class, 'store'])->name('application-problems-store');
            Route::get('/{id}', [ApplicationProblemController::class, 'show'])->name('application-problem.show');
            Route::get('/{id}/edit', [ApplicationProblemController::class, 'edit'])->name('application-problem.edit');
            Route::put('/{id}', [ApplicationProblemController::class, 'update'])->name('application-problem.update');
        });

        //Ticket 
        Route::prefix('ticket')->group(function () {
            Route::get('/', [TicketController::class, 'index'])->name('ticket.index');
    
            Route::get('/search', [TicketController::class, 'search'])->name('tickets.search');
        });

        // Ticket Status
        Route::prefix('ticket-status')->group(function () {
            Route::get('/', [TicketStatusController::class, 'index'])->name('ticket-status');
            Route::get('/addticketstatus', [TicketStatusController::class, 'store'])->name('ticket-status-store');
            Route::get('/{id}/edit', [TicketStatusController::class, 'edit'])->name('ticket-status.edit');
            Route::get('/{id}', [TicketStatusController::class, 'show'])->name('ticket-status.show');
            Route::put('/{id}', [TicketStatusController::class, 'update'])->name('ticket-status.update');
        });

        // Ticket Priority
        Route::prefix('ticket-priority')->group(function () {
            Route::get('/', [TicketPriorityController::class, 'index'])->name('ticket-priority');
            Route::get('/addticketpriority', [TicketPriorityController::class, 'store'])->name('ticket-priority-store');
            Route::get('/{id}', [TicketPriorityController::class, 'show'])->name('ticket-priority.show');
            Route::get('/{id}/edit', [TicketPriorityController::class, 'edit'])->name('ticket-priority.edit');
            Route::put('/{id}', [TicketPriorityController::class, 'update'])->name('ticket-priority.update');
        });

        Route::prefix('ticket-reply-admin')->group(function () {
            Route::get('/{id}', [TicketReplyController::class, 'index'])->name('ticket-replied-admin');
            Route::put('/tickets/{id}/priority', [TicketReplyController::class, 'update'])
                ->name('tickets.updatePriority');
        });

        // Users Management
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/addusers', [UserController::class, 'create'])->name('users.create');
            Route::post('/', [UserController::class, 'store'])->name('users.store');
            Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        // 🔹 Organization Management
        Route::prefix('organization')->group(function () {
            Route::get('/', [OrganizationController::class, 'index'])->name('organization.index');
            Route::get('/create', [OrganizationController::class, 'create'])->name('organization.create');
            Route::post('/', [OrganizationController::class, 'store'])->name('organization.store');
            Route::get('/{id}', [OrganizationController::class, 'show'])->name('organization.show');
            Route::get('/{id}/edit', [OrganizationController::class, 'edit'])->name('organization.edit');
            Route::put('/{id}', [OrganizationController::class, 'update'])->name('organization.update');
            Route::delete('/{id}', [OrganizationController::class, 'destroy'])->name('organization.destroy');
        });
    });

Route::middleware(['auth', 'role:user'])
    ->prefix('dashboard/user')
    ->group(function () {

        // Dashboard user utama
        Route::get('/', [DashboardUserController::class, 'index'])->name('dashboard.user');

        // Tiket User
        Route::prefix('ticket')->group(function () {
            Route::get('/create', [TicketUserController::class, 'index'])->name('ticket.index');
            Route::post('/store', [TicketUserController::class, 'store'])->name('ticket.store');
        });

        // Chat / Balasan tiket user
        Route::prefix('ticket-reply')->group(function () {
            Route::get('/{id}', [TicketUserReplyController::class, 'index'])->name('ticket-replied');
        });
    });


Broadcast::routes(['middleware' => ['auth:sanctum']]);
