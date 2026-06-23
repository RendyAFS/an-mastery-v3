<?php

use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [App\Http\Controllers\LandingPageController::class, 'index'])->name('landing_page');

Route::middleware(['auth', 'check.active'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

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
        Route::put('{supplier}/restore', [App\Http\Controllers\SupplierController::class, 'restore'])->name('restore');
        Route::delete('{supplier}/force-delete', [App\Http\Controllers\SupplierController::class, 'forceDelete'])->name('force-delete');
        Route::get('select/suppliers', [App\Http\Controllers\SupplierController::class, 'select'])->name('select');
    });
    Route::resource('suppliers', App\Http\Controllers\SupplierController::class)->names('suppliers');

    // Employees
    Route::prefix('employees')->as('employees.')->group(function () {
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
        Route::get('{employee}/show', [App\Http\Controllers\PresenceController::class, 'show'])->name('show');
        Route::put('{employee}/update', [App\Http\Controllers\PresenceController::class, 'update'])->name('update');
    });
    Route::resource('presences', App\Http\Controllers\PresenceController::class)->names('presences');

    // Fabric
    Route::prefix('fabrics')->as('fabrics.')->group(function () {
        Route::put('{fabric}/restore', [App\Http\Controllers\FabricController::class, 'restore'])->name('restore');
        Route::delete('{fabric}/force-delete', [App\Http\Controllers\FabricController::class, 'forceDelete'])->name('force-delete');
        Route::get('select/fabrics', [App\Http\Controllers\FabricController::class, 'select'])->name('select');
    });
    Route::resource('fabrics', App\Http\Controllers\FabricController::class)->names('fabrics');
});
