<?php

use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;

Route::prefix('galleries')->as('galleries.')->group(function () {
    Route::put('{gallery}/restore', [GalleryController::class, 'restore'])->name('restore');
    Route::delete('{gallery}/force-delete', [GalleryController::class, 'forceDelete'])->name('force-delete');
});
Route::resource('galleries', GalleryController::class)->names('galleries');
