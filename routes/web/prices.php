<?php

use App\Http\Controllers\BonusController;
use App\Http\Controllers\PriceEmployeeController;
use App\Http\Controllers\PriceSupplierController;
use Illuminate\Support\Facades\Route;

// Price Suppliers
Route::prefix('price-suppliers')->as('price_suppliers.')->group(function () {
    Route::put('{priceSupplier}/restore', [PriceSupplierController::class, 'restore'])->name('restore');
    Route::delete('{priceSupplier}/force-delete', [PriceSupplierController::class, 'forceDelete'])->name('force-delete');
});
Route::resource('price-suppliers', PriceSupplierController::class)->names('price_suppliers');

// Price Employees
Route::prefix('price-employees')->as('price_employees.')->group(function () {
    Route::put('{priceEmployee}/restore', [PriceEmployeeController::class, 'restore'])->name('restore');
    Route::delete('{priceEmployee}/force-delete', [PriceEmployeeController::class, 'forceDelete'])->name('force-delete');
});
Route::resource('price-employees', PriceEmployeeController::class)->names('price_employees');

// Bonuses
Route::prefix('bonuses')->as('bonuses.')->group(function () {
    Route::put('{bonus}/restore', [BonusController::class, 'restore'])->name('restore');
    Route::delete('{bonus}/force-delete', [BonusController::class, 'forceDelete'])->name('force-delete');
});
Route::resource('bonuses', BonusController::class)->names('bonuses');
