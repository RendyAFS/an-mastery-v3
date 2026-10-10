<?php

use App\Http\Controllers\SablonController;
use Illuminate\Support\Facades\Route;

Route::prefix('sablons')->as('sablons.')->group(function () {
    Route::get('fabrics-by-supplier/{supplier}', [SablonController::class, 'fabricsBySupplier'])->name('fabrics-by-supplier');
    Route::get('fabrics/{fabric}/get-type-fabric', [SablonController::class, 'getTypeFabric'])->name('get-type-fabric');
    Route::put('bulk-status', [SablonController::class, 'bulkUpdateStatus'])->name('bulk-status');
    Route::put('{sablon}/status', [SablonController::class, 'updateStatus'])->name('update-status');
    Route::put('{sablon}/long-fabric', [SablonController::class, 'updateLongFabric'])->name('update-long-fabric');
    Route::put('{sablon}/restore', [SablonController::class, 'restore'])->name('restore');
    Route::delete('{sablon}/force-delete', [SablonController::class, 'forceDelete'])->name('force-delete');
});
Route::resource('sablons', SablonController::class)->names('sablons');
