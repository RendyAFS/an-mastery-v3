<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & System Core Routes
|--------------------------------------------------------------------------
*/

// Dynamic Service Worker (PWA)
Route::get('/sw.js', function () {
    return response()->view('sw')->header('Content-Type', 'application/javascript');
});

// Landing Page & Localization
Route::get('/', [LandingPageController::class, 'index'])->name('landing_page');
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// mkcert Root CA Download (public — no auth required)
Route::get('/cert/download', function () {
    $path = storage_path('app/cert/rootCA.pem');

    if (! file_exists($path)) {
        abort(404, 'Certificate file not found. Please copy your mkcert rootCA.pem to storage/app/cert/rootCA.pem');
    }

    return response()->download($path, 'rootCA.pem', [
        'Content-Type' => 'application/x-pem-file',
        'Content-Disposition' => 'attachment; filename="rootCA.pem"',
    ]);
})->name('cert.download');

/*
|--------------------------------------------------------------------------
| Authenticated & Active User Routes (Modular)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.active'])->group(function () {
    // Dashboard, Profile & Filepond
    require __DIR__.'/web/dashboard.php';

    // Access Control
    require __DIR__.'/web/users.php';
    require __DIR__.'/web/roles.php';

    // Master Data (Employees & Suppliers)
    require __DIR__.'/web/suppliers.php';
    require __DIR__.'/web/employees.php';

    // Master Data (Fabrics & Materials)
    require __DIR__.'/web/fabrics.php';

    // Pricing & Bonuses
    require __DIR__.'/web/prices.php';

    // Production & Operations
    require __DIR__.'/web/presences.php';
    require __DIR__.'/web/sablons.php';
    require __DIR__.'/web/bill-suppliers.php';
    require __DIR__.'/web/salary-employees.php';

    // Workshops, Memos & Galleries
    require __DIR__.'/web/memos.php';
    require __DIR__.'/web/galleries.php';
    require __DIR__.'/web/workshops.php';

    // System Settings & App Version
    require __DIR__.'/web/app-version.php';
});

/*
|--------------------------------------------------------------------------
| Local Environment Testing Routes
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::prefix('test-errors')->as('test-errors.')->group(function () {
        Route::get('401', fn () => abort(401))->name('401');
        Route::get('403', fn () => abort(403))->name('403');
        Route::get('404', fn () => abort(404))->name('404');
        Route::get('419', fn () => abort(419))->name('419');
        Route::get('429', fn () => abort(429))->name('429');
        Route::get('500', fn () => abort(500))->name('500');
        Route::get('503', fn () => abort(503))->name('503');

        Route::get('/', function () {
            $codes = ['401', '403', '404', '419', '429', '500', '503'];

            $links = collect($codes)
                ->map(fn ($code) => '<li><a href="'.route('test-errors.'.$code).'" style="color:#6d9886">'.$code.'</a></li>')
                ->implode('');

            return '<div style="font-family:sans-serif;padding:2rem"><h1>Test Error Pages</h1><ul>'.$links.'</ul></div>';
        })->name('index');
    });
}
