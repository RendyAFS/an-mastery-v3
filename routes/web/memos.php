<?php

use App\Http\Controllers\MemoController;
use Illuminate\Support\Facades\Route;

Route::prefix('memos')->as('memos.')->group(function () {
    Route::put('{id}/restore', [MemoController::class, 'restore'])->name('restore');
    Route::delete('{id}/force-delete', [MemoController::class, 'forceDelete'])->name('force-delete');
});
Route::resource('memos', MemoController::class)->names('memos');
