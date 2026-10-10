<?php

use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierCoverStyleController;
use Illuminate\Support\Facades\Route;

// Suppliers
Route::prefix('suppliers')->as('suppliers.')->group(function () {
    Route::put('{supplier}/toggle-active', [SupplierController::class, 'toggleActive'])->name('toggle-active');
    Route::put('{supplier}/restore', [SupplierController::class, 'restore'])->name('restore');
    Route::delete('{supplier}/force-delete', [SupplierController::class, 'forceDelete'])->name('force-delete');
    Route::get('select/suppliers', [SupplierController::class, 'select'])->name('select');
});
Route::resource('suppliers', SupplierController::class)->names('suppliers');

// Supplier Cover Style
Route::prefix('suppliers/{supplier}/cover-style')->as('suppliers.cover-style.')->group(function () {
    Route::get('/', [SupplierCoverStyleController::class, 'edit'])->name('edit');
    Route::put('/', [SupplierCoverStyleController::class, 'update'])->name('update');
});
