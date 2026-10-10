# Developer & Agent Tooling Guidelines (tooling.md)

## Tujuan
Dokumen ini menjelaskan standar penggunaan alat bantu (*tooling*) pengembangan, perintah CLI, pembacaan konfigurasi, pemformatan kode (*linter/formatter*), eksekusi Tinker, dan pemanfaatan alat MCP (jika tersedia) untuk proyek Laravel.

## Kapan digunakan
Gunakan dokumen ini saat menjalankan perintah CLI (Artisan, Composer, NPM, Pint, Pest), melakukan debugging interaktif via Tinker, atau menyelesaikan kendala bundling frontend.

## 1. Verifikasi Versi Dependensi
Sebelum mengasumsikan API dari pustaka tertentu, selalu konfirmasikan versi yang terinstal:
- **Paket PHP**: Jalankan `composer show --direct` untuk melihat dependensi langsung beserta versinya, atau `composer show <vendor/package>` untuk satu paket spesifik.
- **Paket JS**: Periksa file `package.json` atau jalankan `npm list --depth=0`.

## 2. Standar Perintah Artisan
- **Mode Non-Interaktif**: Selalu tambahkan flag `--no-interaction` pada perintah Artisan yang dijalankan oleh agent agar tidak berhenti menunggu input terminal.
- **Eksplorasi Perintah**: Gunakan `php artisan list` untuk menemukan perintah yang tersedia, dan `php artisan [command] --help` untuk melihat opsi dan argumen yang didukung.
- **Pembuatan File Standar**:
  - Model, migrasi, factory, seeder: `php artisan make:model Product -mfs --no-interaction`
  - Controller: `php artisan make:controller ProductController --no-interaction`
  - Form Request: `php artisan make:request SaveProductRequest --no-interaction`
  - API Resource: `php artisan make:resource ProductResource --no-interaction`
  - Action / Generic Class: `php artisan make:class Actions/Product/SaveProductAction --no-interaction`
- **Inspeksi Rute**:
  - Gunakan `php artisan route:list`.
  - Filter yang direkomendasikan: `--method=GET`, `--name=products`, `--path=api`, `--except-vendor`, `--only-vendor`.
- **Membaca Konfigurasi**:
  - Baca nilai konfigurasi menggunakan dot notation: `php artisan config:show app.name` atau `php artisan config:show database.default`.
  - Atau baca langsung dari file di folder `config/`.

## 3. Eksekusi Tinker (Debugging PHP)
Gunakan Tinker untuk mengeksekusi kode PHP cepat dalam konteks aplikasi.
- **Aturan Shell Escaping**: Selalu gunakan tanda kutip tunggal (`'`) untuk membungkus perintah keseluruhan, dan tanda kutip ganda (`"`) untuk string PHP di dalamnya agar terhindar dari *shell expansion*:
  ```bash
  # Benar:
  php artisan tinker --execute 'App\Models\User::where("is_active", true)->count();'
  
  # Contoh query relasi:
  php artisan tinker --execute 'echo App\Models\Product::with("category")->first()?->toJson();'
  ```
- **Batasan**: Jangan membuat atau memanipulasi data produksi secara permanen lewat Tinker tanpa persetujuan user. Lebih utamakan automated tests dengan factories.

## 4. Format Kode dengan Laravel Pint
- **HANYA File yang Dimodifikasi**: Jika Anda memodifikasi file PHP, jalankan Pint **hanya** pada file-file spesifik yang baru saja Anda edit.
  ```bash
  # Format file spesifik:
  vendor/bin/pint app/Http/Controllers/ProductController.php app/Actions/Product/SaveProductAction.php
  ```
- **Larangan Keras**: **Dilarang** menjalankan Pint ke seluruh direktori proyek tanpa argumen file atau menjalankan `vendor/bin/pint --test` yang memformat ribuan file lain yang tidak terkait dengan tugas Anda.

## 5. Frontend Bundling & Vite
- Jika perubahan file frontend (Blade, JS, CSS) tidak muncul di browser atau muncul error `Unable to locate file in Vite manifest`:
  1. Jalankan `npm run build` untuk mengompilasi aset produksi ke folder `public/build`.
  2. Atau sarankan user menjalankan `npm run dev` / `composer run dev` jika dalam mode live development.

## 6. MCP & Boost Tools (Jika Tersedia)
Jika lingkungan kerja Anda terhubung dengan MCP server Laravel Boost, utamakan penggunaan tool Boost berikut:
- **`database-query`**: Menjalankan kueri read-only langsung tanpa perlu membuka tinker.
- **`database-schema`**: Memeriksa skema dan tipe data kolom tabel sebelum menyusun migrasi atau model.
- **`search-docs`**: Mencari dokumentasi resmi framework/paket Laravel terbaru berdasarkan kata kunci terarah.
- **`get-absolute-url`**: Memperoleh domain, scheme, dan port URL aplikasi yang akurat.
- **`browser-logs`**: Membaca log error frontend/browser terkini.

## Checklist
- [ ] Apakah perintah Artisan menyertakan `--no-interaction`?
- [ ] Apakah Pint hanya dijalankan pada file PHP yang baru saja dimodifikasi?
- [ ] Apakah eksekusi Tinker menggunakan tanda kutip tunggal pembungkus yang aman?
- [ ] Apakah aset frontend sudah di-build ulang (`npm run build`) jika terjadi error manifest?
