<?php

use App\Http\Controllers\SalaryEmployeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('salary-employees')->as('salary_employees.')->group(function () {
    Route::get('{employee}/additional-fee', [SalaryEmployeeController::class, 'additionalFee'])->name('additional-fee');
    Route::put('sync', [SalaryEmployeeController::class, 'sync'])->name('sync');
    Route::put('{employee}', [SalaryEmployeeController::class, 'update'])->name('update');
});
Route::resource('salary-employees', SalaryEmployeeController::class)->only(['index'])->names('salary_employees');
