# Routing Standards (routing.md)

## Tujuan
Dokumen ini menjelaskan standar perutean (*routing*) yang digunakan di proyek Laravel, baik untuk rute web backend maupun rute API yang dikonsumsi oleh JavaScript client-side melalui pustaka seperti Ziggy atau Axios client.

## Kapan digunakan
Gunakan panduan ini setiap kali Anda menambahkan endpoint baru, mendefinisikan rute modul, mengelompokkan middleware, memisahkan berkas rute modular, atau melakukan pemanggilan rute di dalam file JavaScript.

## Cara kerja
1. **Organisasi File Rute**:
   - Rute web utama didaftarkan di `routes/web.php` atau dipecah per modul di dalam direktori `routes/web/<module>.php` untuk menjaga keterbacaan pada proyek besar.
   - Rute API murni dikelompokkan di dalam prefix versi (misal: `Route::prefix('v1')->as('api.v1.')->group(...)` di `routes/api.php` atau `routes/api/v1/*.php`).
2. **Pengelompokan Keamanan & Middleware**:
   - Semua rute yang membutuhkan otentikasi wajib dikelompokkan di dalam middleware otentikasi (misal: `['auth', 'check.active']` atau `['auth:sanctum']`).
3. **Standardisasi API & Web**:
   - Pada pola hibrida, rute web mengembalikan halaman Blade (melalui method `index`, `create`, `edit`), sedangkan request yang membutuhkan JSON dideteksi secara dinamis dalam controller menggunakan `request()->expectsJson()`.
   - Pada pola terpisah (SPA/REST API), controller API mengembalikan API Resource JSON secara murni.
4. **Integrasi Client-Side (Ziggy / Named Routes)**:
   - Setiap rute WAJIB memiliki nama (*named route*).
   - Di Blade/JS, buat link dan panggil URL selalu menggunakan helper `route('nama_rute', params)` dan jangan pernah menulis URL mentah (*hardcoded string*).

## Struktur & Konvensi Penamaan
- **URL Multi-Kata**: Harus menggunakan format **kebab-case** (huruf kecil dipisahkan tanda hubung). Contoh: `/product-categories`, `/user-roles`.
- **Nama Rute (as / name)**: Harus menggunakan format **snake_case** atau dot notation terstandar. Contoh: `product_categories.index`, `api.v1.products.store`.
- **Pengelompokan Rute Tambahan**: Aksi tambahan untuk resource (seperti `restore`, `force-delete`, `toggle-active`, `select`) wajib dimasukkan di dalam blok `Route::prefix('...')->as('...')->group(...)` sebelum pendaftaran `Route::resource('...')`.

## Contoh implementasi

### 1. Pendaftaran Rute Web (routes/web.php atau routes/web/product.php)
```php
Route::middleware(['auth', 'check.active'])->group(function () {
    // Rute Tambahan (dikelompokkan terlebih dahulu sebelum resource)
    Route::prefix('product-categories')->as('product_categories.')->group(function () {
        Route::put('{productCategory}/restore', [ProductCategoryController::class, 'restore'])->name('restore');
        Route::delete('{productCategory}/force-delete', [ProductCategoryController::class, 'forceDelete'])->name('force-delete');
        Route::put('{productCategory}/toggle-active', [ProductCategoryController::class, 'toggleActive'])->name('toggle-active');
        Route::get('select', [ProductCategoryController::class, 'select'])->name('select');
    });

    // Rute Resource utama
    Route::resource('product-categories', ProductCategoryController::class)->names('product_categories');
});
```

### 2. Pendaftaran Rute API Terstruktur (routes/api.php)
```php
Route::prefix('v1')->as('api.v1.')->middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('products', Api\ProductApiController::class);
    Route::apiResource('categories', Api\CategoryApiController::class);
});
```

### 3. Penggunaan di Frontend / JavaScript (via Ziggy)
```javascript
// Memanggil endpoint asinkron dengan rute bernama dinamis
const urlIndex  = route("product_categories.index");
const urlStore  = route("product_categories.store");
const urlUpdate = route("product_categories.update", id);
const urlDelete = route("product_categories.destroy", id);
```

## Hubungan dengan file lain
- Rute-rute ini didelegasikan langsung ke controller yang standarnya diatur di `controller.md`.
- Variabel parameter rute (seperti `{productCategory}`) dicocokkan otomatis menggunakan route model binding yang penamaannya disesuaikan di `naming-convention.md`.

## Checklist
- [ ] Apakah URL rute multi-kata menggunakan format kebab-case (misal `/product-categories`)?
- [ ] Apakah setiap rute sudah diberi nama (*named route*) dan menggunakan snake_case/dot-notation?
- [ ] Apakah rute tambahan resource sudah ditulis di atas `Route::resource` agar tidak tertimpa oleh parameter wildcard `{id}`?
- [ ] Apakah rute baru sudah dibungkus dengan middleware otentikasi yang tepat?
- [ ] Apakah tidak ada URL mentah/hardcoded string di file JavaScript?

## Best Practice
- **Route Model Binding**: Selalu gunakan parameter singular pencocokan model (misal `{productCategory}` untuk model `ProductCategory`) agar Laravel secara otomatis menyuntikkan model (*Route Model Binding*).
- **Gunakan Helper route()**: Hindari menulis string URL mentah seperti `/categories/store`. Selalu gunakan `route('product_categories.store')` untuk fleksibilitas jika prefix URL berubah.
- **Modularisasi**: Jika file `routes/web.php` sudah terlalu panjang, pisahkan rute per modul ke folder `routes/web/` dan muat menggunakan `Route::prefix(...)` atau require di service provider.
