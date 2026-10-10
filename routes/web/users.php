<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->as('users.')->group(function () {
    Route::put('{user}/toggle-active', [UserController::class, 'toggleActive'])->name('toggle-active');
    Route::put('{user}/restore', [UserController::class, 'restore'])->name('restore');
    Route::delete('{user}/force-delete', [UserController::class, 'forceDelete'])->name('force-delete');
});
Route::resource('users', UserController::class)->names('users');
