<?php

use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])->name('landing_page');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', App\Http\Controllers\UserController::class)->names('users');

    // Roles
    Route::resource('roles', App\Http\Controllers\RoleController::class)->names('roles');
});
