<?php

use App\Http\Controllers\AppVersionController;
use Illuminate\Support\Facades\Route;

Route::get('/app-version/status', [AppVersionController::class, 'status'])->name('app-version.status');
Route::prefix('app-version')->as('app_version.')->group(function () {
    Route::get('/', [AppVersionController::class, 'index'])->name('index');
    Route::post('/publish', [AppVersionController::class, 'publish'])->name('publish');
    Route::post('/sync-local', [AppVersionController::class, 'syncLocalVersion'])->name('sync-local');
});
