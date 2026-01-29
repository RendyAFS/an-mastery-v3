<?php

use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])->name('landing_page');

Route::middleware(['auth', 'check.active'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [App\Http\Controllers\MyProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\MyProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\MyProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Users
    Route::prefix('users')->as('users.')->group(function () {
        Route::put('{user}/toggle-active', [App\Http\Controllers\UserController::class, 'toggleActive'])->name('toggle-active');
    });
    Route::resource('users', App\Http\Controllers\UserController::class)->names('users');

    // Roles
    Route::resource('roles', App\Http\Controllers\RoleController::class)->names('roles');
});
