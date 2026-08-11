# Routing Standards (routing.md)

## Tujuan
Dokumen ini menjelaskan standar perutean (*routing*) yang digunakan di proyek ini, baik untuk rute web backend Laravel maupun rute API yang dikonsumsi oleh JavaScript client-side melalui pustaka Ziggy.

## Kapan digunakan
Gunakan panduan ini setiap kali Anda menambahkan endpoint baru, mendefinisikan rute modul, mengelompokkan middleware, atau melakukan rujukan rute di dalam file JavaScript.

## Cara kerja
1. **Pengelompokan Keamanan**: Semua rute yang membutuhkan otentikasi wajib dikelompokkan di dalam middleware `['auth', 'check.active']`.
2. **Standardisasi API & Web**: Rute web mengembalikan halaman Blade (melalui method `index`, `create`, `edit`), sedangkan request yang membutuhkan JSON dideteksi secara dinamis dalam controller menggunakan check `request()->expectsJson()` pada rute index yang sama.
3. **Integrasi Client-Side (Ziggy)**: Laravel menyertakan direktif `@routes` pada master layout. Dengan ini, berkas Javascript dapat memanggil rute bernama dengan memanggil fungsi global `route('nama_rute', params)`.

## Struktur
Berikut adalah aturan penamaan URL dan penamaan rute (*named routes*):
- **URL Multi-Kata**: Harus menggunakan format **kebab-case** (huruf kecil dipisahkan tanda hubung). Contoh: `/image-fabrics`, `/price-suppliers`.
- **Nama Rute (as)**: Harus menggunakan format **snake_case** (huruf kecil dipisahkan underscore). Contoh: `image_fabrics`, `price_suppliers`.
- **Pengelompokan Rute**: Aksi tambahan untuk resource (seperti `restore`, `force-delete`, `toggle-active`, `select`) wajib dimasukkan di dalam blok `Route::prefix('...')->as('...')->group(...)` sebelum pendaftaran `Route::resource('...')`.

## Contoh implementasi
Penerapan rute yang konsisten dalam file [web.php](file:///d:/laragon/www/an-mastery-v3/routes/web.php):
```php
// Rute Tambahan (dikelompokkan terlebih dahulu)
Route::prefix('{modules}')->as('{modules}.')-group(function () {
    Route::put('{model}/toggle-active', [{Module}Controller::class, 'toggleActive'])->name('toggle-active');
    Route::put('{model}/restore', [{Module}Controller::class, 'restore'])->name('restore');
    Route::delete('{model}/force-delete', [{Module}Controller::class, 'forceDelete'])->name('force-delete');
    Route::get('select', [{Module}Controller::class, 'select'])->name('select');
});
// Rute Resource utama
Route::resource('{modules}', {Module}Controller::class)->names('{modules}');
```

## Contoh kode
### 1. Pendaftaran rute di backend:
```php
Route::middleware(['auth', 'check.active'])->group(function () {
    // Contoh modul multi-kata (kebab-case URL, snake_case route name)
    Route::prefix('color-fabrics')->as('color_fabrics.')->group(function () {
        Route::put('{colorFabric}/restore', [App\Http\Controllers\ColorFabricController::class, 'restore'])->name('restore');
        Route::delete('{colorFabric}/force-delete', [App\Http\Controllers\ColorFabricController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('color-fabrics', App\Http\Controllers\ColorFabricController::class)->names('color_fabrics');
});
```

### 2. Penggunaan di Javascript (via Ziggy):
```javascript
// Memanggil endpoint asinkron dengan rute dinamis
const urlStore  = route("{modules}.store");
const urlUpdate = route("{modules}.update", id);
const urlDelete = route("{modules}.destroy", id);
```

## Hubungan dengan file lain
- Rute-rute ini didelegasikan langsung ke controller yang standarnya diatur di `controller.md`.
- Variabel parameter rute (seperti `{supplier}`) dicocokkan otomatis menggunakan model binding yang penamaannya disesuaikan di `naming-convention.md`.

## Checklist
- [ ] Apakah URL rute baru yang terdiri dari beberapa kata menggunakan format kebab-case (misal `bill-suppliers`)?
- [ ] Apakah nama rute kustom Anda menggunakan format snake_case (misal `bill_suppliers.restore`)?
- [ ] Apakah rute tambahan resource sudah ditulis di atas pendaftaran `Route::resource` agar tidak tertimpa?
- [ ] Apakah rute baru sudah dibungkus middleware `auth` dan `check.active`?

## Best Practice
- **Model Binding**: Selalu gunakan parameter singular pencocokan model (misal `{supplier}` untuk model `Supplier`) agar Laravel secara otomatis menyuntikkan model (*Route Model Binding*).
- **Hindari Hardcode URL**: Jangan menulis URL mentah seperti `/suppliers/store` di file Javascript. Selalu gunakan `route('suppliers.store')` untuk menjaga konsistensi rute.

## Catatan penting
> [!IMPORTANT]
> Jangan pernah mencampuradukkan penamaan nama rute menggunakan kebab-case. Perbedaan penulisan (URL kebab-case vs Nama rute snake_case) adalah aturan mutlak di proyek ini untuk kompatibilitas data binding.
