<?php

use App\Http\Controllers\PresenceController;
use Illuminate\Support\Facades\Route;

Route::prefix('presences')->as('presences.')->group(function () {
    Route::get('/', [PresenceController::class, 'index'])->name('index');
    Route::get('data', [PresenceController::class, 'data'])->name('data');
    Route::get('employees', [PresenceController::class, 'employees'])->name('employees');
    Route::post('bulk-generate', [PresenceController::class, 'bulkGenerate'])->name('bulkGenerate');
    Route::get('{employee}/show', [PresenceController::class, 'show'])->name('show');
    Route::put('{employee}/update', [PresenceController::class, 'update'])->name('update');
});
