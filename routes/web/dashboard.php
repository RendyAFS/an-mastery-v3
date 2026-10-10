<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FilepondController;
use App\Http\Controllers\MyProfileController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/fabrics', [DashboardController::class, 'fabrics'])->name('dashboard.fabrics');
Route::get('/dashboard/latest-sablons', [DashboardController::class, 'latestSablons'])->name('dashboard.latest-sablons');

// Filepond Upload Handlers
Route::post('/filepond/process', [FilepondController::class, 'process'])->name('filepond.process');
Route::get('/filepond/load', [FilepondController::class, 'load'])->name('filepond.load');
Route::delete('/filepond/revert', [FilepondController::class, 'revert'])->name('filepond.revert');

// User Profile
Route::get('/profile', [MyProfileController::class, 'index'])->name('profile.index');
Route::put('/profile', [MyProfileController::class, 'update'])->name('profile.update');
Route::put('/profile/password', [MyProfileController::class, 'updatePassword'])->name('profile.update-password');
