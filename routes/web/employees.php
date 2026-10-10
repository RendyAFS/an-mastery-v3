<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('employees')->as('employees.')->group(function () {
    Route::put('{employee}/toggle-active', [EmployeeController::class, 'toggleActive'])->name('toggle-active');
    Route::put('{employee}/restore', [EmployeeController::class, 'restore'])->name('restore');
    Route::delete('{employee}/force-delete', [EmployeeController::class, 'forceDelete'])->name('force-delete');
    Route::get('select/employees', [EmployeeController::class, 'select'])->name('select');
});
Route::resource('employees', EmployeeController::class)->names('employees');
