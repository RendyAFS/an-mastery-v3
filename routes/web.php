<?php

use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])->name('landing_page');

Route::get('/locale/{locale}', [App\Http\Controllers\LocaleController::class, 'switch'])->name('locale.switch');

Route::middleware(['auth', 'check.active'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/fabrics', [App\Http\Controllers\DashboardController::class, 'fabrics'])->name('dashboard.fabrics');
    Route::get('/dashboard/latest-sablons', [App\Http\Controllers\DashboardController::class, 'latestSablons'])->name('dashboard.latest-sablons');

    // Filepond
    Route::post('/filepond/process', [App\Http\Controllers\FilepondController::class, 'process'])->name('filepond.process');
    Route::get('/filepond/load', [App\Http\Controllers\FilepondController::class, 'load'])->name('filepond.load');
    Route::delete('/filepond/revert', [App\Http\Controllers\FilepondController::class, 'revert'])->name('filepond.revert');

    // Profile
    Route::get('/profile', [App\Http\Controllers\MyProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\MyProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\MyProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Users
    Route::prefix('users')->as('users.')->group(function () {
        Route::put('{user}/toggle-active', [App\Http\Controllers\UserController::class, 'toggleActive'])->name('toggle-active');
        Route::put('{user}/restore', [App\Http\Controllers\UserController::class, 'restore'])->name('restore');
        Route::delete('{user}/force-delete', [App\Http\Controllers\UserController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('users', App\Http\Controllers\UserController::class)->names('users');

    // Roles
    Route::prefix('roles')->as('roles.')->group(function () {
        Route::put('{role}/restore', [App\Http\Controllers\RoleController::class, 'restore'])->name('restore');
        Route::delete('{role}/force-delete', [App\Http\Controllers\RoleController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('roles', App\Http\Controllers\RoleController::class)->names('roles');

    // Suppliers
    Route::prefix('suppliers')->as('suppliers.')->group(function () {
        Route::put('{supplier}/toggle-active', [App\Http\Controllers\SupplierController::class, 'toggleActive'])->name('toggle-active');
        Route::put('{supplier}/restore', [App\Http\Controllers\SupplierController::class, 'restore'])->name('restore');
        Route::delete('{supplier}/force-delete', [App\Http\Controllers\SupplierController::class, 'forceDelete'])->name('force-delete');
        Route::get('select/suppliers', [App\Http\Controllers\SupplierController::class, 'select'])->name('select');
    });
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class)->names('suppliers');

    // Employees
    Route::prefix('employees')->as('employees.')->group(function () {
        Route::put('{employee}/toggle-active', [App\Http\Controllers\EmployeeController::class, 'toggleActive'])->name('toggle-active');
        Route::put('{employee}/restore', [App\Http\Controllers\EmployeeController::class, 'restore'])->name('restore');
        Route::delete('{employee}/force-delete', [App\Http\Controllers\EmployeeController::class, 'forceDelete'])->name('force-delete');
        Route::get('select/employees', [App\Http\Controllers\EmployeeController::class, 'select'])->name('select');
    });
    Route::resource('employees', App\Http\Controllers\EmployeeController::class)->names('employees');

    // Image Fabric
    Route::prefix('image-fabrics')->as('image_fabrics.')->group(function () {
        Route::put('{imageFabric}/restore', [App\Http\Controllers\ImageFabricController::class, 'restore'])->name('restore');
        Route::delete('{imageFabric}/force-delete', [App\Http\Controllers\ImageFabricController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('image-fabrics', App\Http\Controllers\ImageFabricController::class)->names('image_fabrics');

    // Color Fabric
    Route::prefix('color-fabrics')->as('color_fabrics.')->group(function () {
        Route::put('{colorFabric}/restore', [App\Http\Controllers\ColorFabricController::class, 'restore'])->name('restore');
        Route::delete('{colorFabric}/force-delete', [App\Http\Controllers\ColorFabricController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('color-fabrics', App\Http\Controllers\ColorFabricController::class)->names('color_fabrics');

    // Type Fabric
    Route::prefix('type-fabrics')->as('type_fabrics.')->group(function () {
        Route::put('{typeFabric}/restore', [App\Http\Controllers\TypeFabricController::class, 'restore'])->name('restore');
        Route::delete('{typeFabric}/force-delete', [App\Http\Controllers\TypeFabricController::class, 'forceDelete'])->name('force-delete');
        Route::get('select/type-fabrics', [App\Http\Controllers\TypeFabricController::class, 'select'])->name('select');
    });
    Route::resource('type-fabrics', App\Http\Controllers\TypeFabricController::class)->names('type_fabrics');

    // Type Colors
    Route::prefix('type-colors')->as('type_colors.')->group(function () {
        Route::put('{typeColor}/restore', [App\Http\Controllers\TypeColorController::class, 'restore'])->name('restore');
        Route::delete('{typeColor}/force-delete', [App\Http\Controllers\TypeColorController::class, 'forceDelete'])->name('force-delete');
        Route::get('select/type-colors', [App\Http\Controllers\TypeColorController::class, 'select'])->name('select');
    });
    Route::resource('type-colors', App\Http\Controllers\TypeColorController::class)->names('type_colors');

    // Price Supplier
    Route::prefix('price-suppliers')->as('price_suppliers.')->group(function () {
        Route::put('{priceSupplier}/restore', [App\Http\Controllers\PriceSupplierController::class, 'restore'])->name('restore');
        Route::delete('{priceSupplier}/force-delete', [App\Http\Controllers\PriceSupplierController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('price-suppliers', App\Http\Controllers\PriceSupplierController::class)->names('price_suppliers');

    // Price Employee
    Route::prefix('price-employees')->as('price_employees.')->group(function () {
        Route::put('{priceEmployee}/restore', [App\Http\Controllers\PriceEmployeeController::class, 'restore'])->name('restore');
        Route::delete('{priceEmployee}/force-delete', [App\Http\Controllers\PriceEmployeeController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('price-employees', App\Http\Controllers\PriceEmployeeController::class)->names('price_employees');

    // Presences
    Route::prefix('presences')->as('presences.')->group(function () {
        Route::get('/', [App\Http\Controllers\PresenceController::class, 'index'])->name('index');
        Route::get('data', [App\Http\Controllers\PresenceController::class, 'data'])->name('data');
        Route::get('employees', [App\Http\Controllers\PresenceController::class, 'employees'])->name('employees');
        Route::post('bulk-generate', [App\Http\Controllers\PresenceController::class, 'bulkGenerate'])->name('bulkGenerate');
        Route::get('{employee}/show', [App\Http\Controllers\PresenceController::class, 'show'])->name('show');
        Route::put('{employee}/update', [App\Http\Controllers\PresenceController::class, 'update'])->name('update');
    });

    // Fabric
    Route::prefix('fabrics')->as('fabrics.')->group(function () {
        Route::put('{fabric}/restore', [App\Http\Controllers\FabricController::class, 'restore'])->name('restore');
        Route::delete('{fabric}/force-delete', [App\Http\Controllers\FabricController::class, 'forceDelete'])->name('force-delete');
        Route::get('select/fabrics', [App\Http\Controllers\FabricController::class, 'select'])->name('select');
    });
    Route::resource('fabrics', App\Http\Controllers\FabricController::class)->names('fabrics');

    // Sablon
    Route::prefix('sablons')->as('sablons.')->group(function () {
        Route::get('fabrics-by-supplier/{supplier}', [App\Http\Controllers\SablonController::class, 'fabricsBySupplier'])->name('fabrics-by-supplier');
        Route::get('fabrics/{fabric}/get-type-fabric', [App\Http\Controllers\SablonController::class, 'getTypeFabric'])->name('get-type-fabric');
        Route::put('{sablon}/status', [App\Http\Controllers\SablonController::class, 'updateStatus'])->name('update-status');
        Route::put('{sablon}/restore', [App\Http\Controllers\SablonController::class, 'restore'])->name('restore');
        Route::delete('{sablon}/force-delete', [App\Http\Controllers\SablonController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('sablons', App\Http\Controllers\SablonController::class)->names('sablons');

    // Bill Supplier
    Route::prefix('bill-suppliers')->as('bill_suppliers.')->group(function () {
        Route::get('by-supplier/{supplier}', [App\Http\Controllers\BillSupplierController::class, 'bySupplier'])->name('by-supplier');
        Route::get('available-sablons/{supplier}', [App\Http\Controllers\BillSupplierController::class, 'availableSablons'])->name('available-sablons');
        Route::post('calculate-bulk', [App\Http\Controllers\BillSupplierController::class, 'calculateBulk'])->name('calculate-bulk');

        Route::get('batch/{batch}/edit', [App\Http\Controllers\BillSupplierController::class, 'editBatch'])->name('batch.edit');
        Route::get('batch/{batch}/calculate', [App\Http\Controllers\BillSupplierController::class, 'calculateBatch'])->name('batch.calculate');
        Route::put('batch/{batch}', [App\Http\Controllers\BillSupplierController::class, 'updateBatch'])->name('batch.update');
        Route::delete('batch/{batch}', [App\Http\Controllers\BillSupplierController::class, 'destroyBatch'])->name('batch.destroy');
        Route::put('batch/{batch}/restore', [App\Http\Controllers\BillSupplierController::class, 'restoreBatch'])->name('batch.restore');
        Route::delete('batch/{batch}/force-delete', [App\Http\Controllers\BillSupplierController::class, 'forceDeleteBatch'])->name('batch.force-delete');
    });
    Route::resource('bill-suppliers', App\Http\Controllers\BillSupplierController::class)->only(['index', 'create', 'store', 'show'])->names('bill_suppliers');

    // Supplier Cover Style
    Route::prefix('suppliers/{supplier}/cover-style')->as('suppliers.cover-style.')->group(function () {
        Route::get('/', [App\Http\Controllers\SupplierCoverStyleController::class, 'edit'])->name('edit');
        Route::put('/', [App\Http\Controllers\SupplierCoverStyleController::class, 'update'])->name('update');
    });

    // Salary Employee
    Route::prefix('salary-employees')->as('salary_employees.')->group(function () {
        Route::get('{employee}/additional-fee', [App\Http\Controllers\SalaryEmployeeController::class, 'additionalFee'])->name('additional-fee');
        Route::put('sync', [App\Http\Controllers\SalaryEmployeeController::class, 'sync'])->name('sync');
        Route::put('{employee}', [App\Http\Controllers\SalaryEmployeeController::class, 'update'])->name('update');
    });
    Route::resource('salary-employees', App\Http\Controllers\SalaryEmployeeController::class)->only(['index'])->names('salary_employees');

    // Memo
    Route::prefix('memos')->as('memos.')->group(function () {
        Route::put('{id}/restore', [App\Http\Controllers\MemoController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [App\Http\Controllers\MemoController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('memos', App\Http\Controllers\MemoController::class)->names('memos');

    // Gallery
    Route::prefix('galleries')->as('galleries.')->group(function () {
        Route::put('{gallery}/restore', [App\Http\Controllers\GalleryController::class, 'restore'])->name('restore');
        Route::delete('{gallery}/force-delete', [App\Http\Controllers\GalleryController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('galleries', App\Http\Controllers\GalleryController::class)->names('galleries');
});


if (app()->environment('local')) {
    Route::prefix('test-errors')->as('test-errors.')->group(function () {
        Route::get('401', fn() => abort(401))->name('401');
        Route::get('403', fn() => abort(403))->name('403');
        Route::get('404', fn() => abort(404))->name('404');
        Route::get('419', fn() => abort(419))->name('419');
        Route::get('429', fn() => abort(429))->name('429');
        Route::get('500', fn() => abort(500))->name('500');
        Route::get('503', fn() => abort(503))->name('503');

        // index kecil biar gampang klik satu-satu
        Route::get('/', function () {
            $codes = ['401', '403', '404', '419', '429', '500', '503'];

            $links = collect($codes)
                ->map(fn($code) => '<li><a href="' . route('test-errors.' . $code) . '" style="color:#6d9886">' . $code . '</a></li>')
                ->implode('');

            return '<div style="font-family:sans-serif;padding:2rem"><h1>Test Error Pages</h1><ul>' . $links . '</ul></div>';
        })->name('index');
    });
}
