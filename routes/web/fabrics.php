<?php

use App\Http\Controllers\ColorFabricController;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\ImageFabricController;
use App\Http\Controllers\TypeColorController;
use App\Http\Controllers\TypeFabricController;
use Illuminate\Support\Facades\Route;

// Fabrics
Route::prefix('fabrics')->as('fabrics.')->group(function () {
    Route::put('{fabric}/restore', [FabricController::class, 'restore'])->name('restore');
    Route::delete('{fabric}/force-delete', [FabricController::class, 'forceDelete'])->name('force-delete');
    Route::get('select/fabrics', [FabricController::class, 'select'])->name('select');
});
Route::resource('fabrics', FabricController::class)->names('fabrics');

// Image Fabrics
Route::prefix('image-fabrics')->as('image_fabrics.')->group(function () {
    Route::put('{imageFabric}/restore', [ImageFabricController::class, 'restore'])->name('restore');
    Route::delete('{imageFabric}/force-delete', [ImageFabricController::class, 'forceDelete'])->name('force-delete');
});
Route::resource('image-fabrics', ImageFabricController::class)->names('image_fabrics');

// Color Fabrics
Route::prefix('color-fabrics')->as('color_fabrics.')->group(function () {
    Route::put('{colorFabric}/restore', [ColorFabricController::class, 'restore'])->name('restore');
    Route::delete('{colorFabric}/force-delete', [ColorFabricController::class, 'forceDelete'])->name('force-delete');
});
Route::resource('color-fabrics', ColorFabricController::class)->names('color_fabrics');

// Type Fabrics
Route::prefix('type-fabrics')->as('type_fabrics.')->group(function () {
    Route::put('{typeFabric}/restore', [TypeFabricController::class, 'restore'])->name('restore');
    Route::delete('{typeFabric}/force-delete', [TypeFabricController::class, 'forceDelete'])->name('force-delete');
    Route::get('select/type-fabrics', [TypeFabricController::class, 'select'])->name('select');
});
Route::resource('type-fabrics', TypeFabricController::class)->names('type_fabrics');

// Type Colors
Route::prefix('type-colors')->as('type_colors.')->group(function () {
    Route::put('{typeColor}/restore', [TypeColorController::class, 'restore'])->name('restore');
    Route::delete('{typeColor}/force-delete', [TypeColorController::class, 'forceDelete'])->name('force-delete');
    Route::get('select/type-colors', [TypeColorController::class, 'select'])->name('select');
});
Route::resource('type-colors', TypeColorController::class)->names('type_colors');
