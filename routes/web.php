<?php

use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])->name('landing_page');

Route::middleware(['auth', 'check.active'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Filepond
    Route::post('/filepond/process', [App\Http\Controllers\FilepondController::class, 'process'])->name('filepond.process');
    Route::get('/filepond/load', [App\Http\Controllers\FilepondController::class, 'load'])->name('filepond.load');
    Route::delete('/filepond/revert', [App\Http\Controllers\FilepondController::class, 'revert'])->name('filepond.revert');

    // Profile
    Route::get('/profile', [App\Http\Controllers\MyProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\MyProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\MyProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Users
    Route::prefix('users')->as('users.')->group(function () {
        Route::put('{user}/toggle-active', [App\Http\Controllers\UserController::class, 'toggleActive'])->name('toggle-active');
        Route::put('{user}/restore', [App\Http\Controllers\UserController::class, 'restore'])->name('restore');
        Route::delete('{user}/force-delete', [App\Http\Controllers\UserController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('users', App\Http\Controllers\UserController::class)->names('users');

    // Roles
    Route::prefix('roles')->as('roles.')->group(function () {
        Route::put('{role}/restore', [App\Http\Controllers\RoleController::class, 'restore'])->name('restore');
        Route::delete('{role}/force-delete', [App\Http\Controllers\RoleController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('roles', App\Http\Controllers\RoleController::class)->names('roles');
});
