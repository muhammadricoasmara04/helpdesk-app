<?php

use App\Http\Controllers\Web\ApplicationProblemController;
use App\Http\Controllers\Web\ApplicationController;
use App\Http\Controllers\Web\AuthPageController;
use App\Http\Controllers\Web\DashboardController;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthPageController::class, 'index'])->name('login');
Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

Route::get('/dashboard/application', [ApplicationController::class,'index'])->name('application');
Route::get('/dashboard/application/addapp', [ApplicationController::class,'store'])->name('application-store');
Route::get('/dashboard/application-problems', [ApplicationProblemController::class,'index'])->name('application-problems');
Route::get('/dashboard/application-problems/addapp', [ApplicationProblemController::class,'store'])->name('application-problems-store');

Route::get('/dashboard/ticket-status', [ApplicationController::class,'index'])->name('application');