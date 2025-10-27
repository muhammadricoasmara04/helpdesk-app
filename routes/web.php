<?php

use App\Http\Controllers\Web\ApplicationProblemController;
use App\Http\Controllers\Web\ApplicationController;
use App\Http\Controllers\Web\AuthPageController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\TicketPriorityController;
use App\Http\Controllers\Web\TicketStatusController;
use App\Http\Controllers\Web\User\DashboardUserController;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthPageController::class, 'index'])->name('login');
Route::post('/', [AuthPageController::class, 'login']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/user', [DashboardUserController::class, 'index'])->name('dashboard.user');

Route::get('/dashboard/application', [ApplicationController::class, 'index'])->name('application');
Route::get('/dashboard/application/addapp', [ApplicationController::class, 'store'])->name('application-store');
Route::get('/dashboard/application/{id}', [ApplicationController::class, 'show'])->name('application.show');
Route::get('/dashboard/application/{id}/edit', [ApplicationController::class, 'edit']);
Route::put('/dashboard/application/{id}', [ApplicationController::class, 'update']);
Route::get('/dashboard/application/updateapp', [ApplicationController::class, 'update'])->name('application-update');

Route::get('/dashboard/application-problems', [ApplicationProblemController::class, 'index'])->name('application-problems');
Route::get('/dashboard/application-problems/addapp', [ApplicationProblemController::class, 'store'])->name('application-problems-store');
Route::get('/dashboard/application-problem/{id}', [ApplicationProblemController::class, 'show'])->name('application-problem.show');
Route::get('/dashboard/application-problem/{id}/edit', [ApplicationProblemController::class, 'edit'])->name('application-problem.edit');
Route::put('/dashboard/application-problem/{id}', [ApplicationProblemController::class, 'update'])->name('application-problem.update');

Route::get('/dashboard/ticket-status', [TicketStatusController::class, 'index'])->name('ticket-status');
Route::get('/dashboard/ticket-status/addticketstatus', [TicketStatusController::class, 'store'])->name('ticket-status-store');

Route::get('/dashboard/ticket-priority', [TicketPriorityController::class, 'index'])->name('ticket-priority');
Route::get('/dashboard/ticket-status/addticketpriority', [TicketPriorityController::class, 'store'])->name('ticket-priority-store');
