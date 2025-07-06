<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProposalController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/proposals/{proposal}', [ProposalController::class, 'show'])->name('proposals.show');

Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('proposals', ProposalController::class)->except(['show']);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
