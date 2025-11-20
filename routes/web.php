<?php

use App\Http\Controllers\Web\ApplicationProblemController;
use App\Http\Controllers\Web\ApplicationController;
use App\Http\Controllers\Web\AuthPageController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\OrganizationController;
use App\Http\Controllers\Web\Staff\ApplicationProblemStaffController;
use App\Http\Controllers\Web\Staff\ApplicationStaffController;
use App\Http\Controllers\Web\Staff\DashboardStaffController;
use App\Http\Controllers\Web\Staff\OrganizationStaffController;
use App\Http\Controllers\Web\Staff\TicketPriorityStaffController;
use App\Http\Controllers\Web\Staff\TicketReplyStaffController;
use App\Http\Controllers\Web\Staff\TicketStaffController;
use App\Http\Controllers\Web\Staff\TicketStatusStaffController;
use App\Http\Controllers\Web\Staff\UserStaffController;
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

// login SSO
Route::get('/sso/login', [AuthPageController::class, 'ssoRedirect'])->name('sso.login');
Route::get('/sso/callback', [AuthPageController::class, 'ssoCallback'])->name('sso.callback');


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

Route::middleware(['auth', 'role:staff'])
    ->prefix('dashboard/staff')
    ->group(function () {
        // Dashboard Staff
        Route::get('/', [DashboardStaffController::class, 'index'])->name('dashboard.staff');
        // Ticket Staff
        Route::prefix('ticket')->group(function () {
            Route::get('/', [TicketStaffController::class, 'index'])->name('staff.ticket.index');

            Route::get('/search', [TicketStaffController::class, 'search'])->name('staff.tickets.search');
        });

        // application problems
        Route::prefix('application-problems')->group(function () {
            Route::get('/', [ApplicationProblemStaffController::class, 'index'])->name('staff.application-problems');
            Route::get('/{id}', [ApplicationProblemStaffController::class, 'show'])->name('application-problem.show');
        });

        Route::prefix('application')->group(function () {
            Route::get('/', [ApplicationStaffController::class, 'index'])->name('staff.application');
            Route::get('/{id}', [ApplicationStaffController::class, 'show'])->name('staff.application.show');
        });

        // 🔹 Organization Management
        Route::prefix('organization')->group(function () {
            Route::get('/', [OrganizationStaffController::class, 'index'])->name('staff.organization.index');
            Route::get('/{id}', [OrganizationStaffController::class, 'show'])->name('staff.organization.show');
        });

        // Ticket Status
        Route::prefix('ticket-status')->group(function () {
            Route::get('/', [TicketStatusStaffController::class, 'index'])->name('staff.ticket-status');
            Route::get('/{id}', [TicketStatusStaffController::class, 'show'])->name('staff.ticket-status.show');
        });

        // Ticket Priority
        Route::prefix('ticket-priority')->group(function () {
            Route::get('/', [TicketPriorityStaffController::class, 'index'])->name('staff.ticket-priority');
            Route::get('/{id}', [TicketPriorityStaffController::class, 'show'])->name('staff.ticket-priority.show');
        });

        Route::prefix('ticket-reply-admin')->group(function () {
            Route::get('/{id}', [TicketReplyController::class, 'index'])->name('ticket-replied-admin');
            Route::put('/tickets/{id}/priority', [TicketReplyController::class, 'update'])
                ->name('tickets.updatePriority');
        });

        Route::prefix('ticket-reply-staff')->group(function () {
            Route::get('/{id}', [TicketReplyStaffController::class, 'index'])->name('staff.ticket-replied-admin');
            Route::put('/tickets/{id}/priority', [TicketReplyStaffController::class, 'staff.update'])
                ->name('tickets.updatePriority');
        });

        Route::prefix('users')->group(function () {
            Route::get('/', [UserStaffController::class, 'index'])->name('staff.users.index');
            Route::get('/{id}', [UserStaffController::class, 'show'])->name('staff.users.show');
        });
    });

Route::middleware(['auth', 'role:user'])
    ->prefix('dashboard/user')
    ->group(function () {

        // Dashboard user utama
        Route::get('/', [DashboardUserController::class, 'index'])->name('dashboard.user');

        // Tiket User
        Route::prefix('ticket')->group(function () {
            Route::get('/', [TicketUserController::class, 'index'])->name('user.ticket.index');
            Route::get('/create', [TicketUserController::class, 'create'])->name('user.ticket.create');
            Route::post('/store', [TicketUserController::class, 'store'])->name('user.ticket.store');
        });

        // Chat / Balasan tiket user
        Route::prefix('ticket-reply')->group(function () {
            Route::get('/{id}', [TicketUserReplyController::class, 'index'])->name('ticket-replied');
            Route::post('/{id}/attachments', [TicketUserReplyController::class, 'uploadAttachment'])->name('ticket.uploadAttachment');
        });
    });


Broadcast::routes(['middleware' => ['auth:sanctum']]);
