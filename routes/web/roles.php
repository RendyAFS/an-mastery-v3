<?php

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('roles')->as('roles.')->group(function () {
    Route::put('{role}/restore', [RoleController::class, 'restore'])->name('restore');
    Route::delete('{role}/force-delete', [RoleController::class, 'forceDelete'])->name('force-delete');
});
Route::resource('roles', RoleController::class)->names('roles');
