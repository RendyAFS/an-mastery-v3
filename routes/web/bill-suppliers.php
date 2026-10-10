<?php

use App\Http\Controllers\BillSupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix('bill-suppliers')->as('bill_suppliers.')->group(function () {
    Route::get('by-supplier/{supplier}', [BillSupplierController::class, 'bySupplier'])->name('by-supplier');
    Route::get('available-sablons/{supplier}', [BillSupplierController::class, 'availableSablons'])->name('available-sablons');
    Route::post('calculate-bulk', [BillSupplierController::class, 'calculateBulk'])->name('calculate-bulk');

    Route::get('batch/{batch}/edit', [BillSupplierController::class, 'editBatch'])->name('batch.edit');
    Route::get('batch/{batch}/calculate', [BillSupplierController::class, 'calculateBatch'])->name('batch.calculate');
    Route::put('batch/{batch}', [BillSupplierController::class, 'updateBatch'])->name('batch.update');
    Route::delete('batch/{batch}', [BillSupplierController::class, 'destroyBatch'])->name('batch.destroy');
    Route::put('batch/{batch}/restore', [BillSupplierController::class, 'restoreBatch'])->name('batch.restore');
    Route::delete('batch/{batch}/force-delete', [BillSupplierController::class, 'forceDeleteBatch'])->name('batch.force-delete');
});
Route::resource('bill-suppliers', BillSupplierController::class)->only(['index', 'create', 'store', 'show'])->names('bill_suppliers');
