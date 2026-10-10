<?php

use App\Http\Controllers\WorkshopController;
use Illuminate\Support\Facades\Route;

Route::prefix('workshops')->as('workshops.')->group(function () {
    Route::get('active-list', [WorkshopController::class, 'activeList'])->name('active-list');
    Route::post('switch', [WorkshopController::class, 'switchWorkshop'])->name('switch');
    Route::put('{workshop}/restore', [WorkshopController::class, 'restore'])->name('restore');
    Route::delete('{workshop}/force-delete', [WorkshopController::class, 'forceDelete'])->name('force-delete');
    Route::get('select/workshops', [WorkshopController::class, 'select'])->name('select');
});
Route::resource('workshops', WorkshopController::class)->names('workshops');
