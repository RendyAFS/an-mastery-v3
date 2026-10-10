# AI Agent Boilerplate & Entry Point Guide (AGENTS.md)

## Tujuan
Dokumen ini berfungsi sebagai panduan utama, standar acuan teknis, dan gerbang masuk (*entry point*) bagi AI Agent dalam mengembangkan, merawat, atau memodifikasi aplikasi berbasis framework **Laravel**. Dokumen ini dirancang fleksibel sebagai boilerplate agen universal untuk berbagai proyek Laravel modern.

## Kapan Digunakan
AI Agent **wajib membaca dokumen ini di awal setiap sesi** sebelum menganalisis kode, menjalankan perintah, atau melakukan modifikasi apa pun pada codebase.

---

## 1. Aturan Dasar (*Foundation Rules*)

### Konfirmasi Versi & Lingkungan
- Jangan berasumsi versi PHP atau pustaka ekosistem. Selalu gunakan API dan sintaksis yang sesuai dengan versi yang terpasang:
  - **Paket PHP**: Jalankan `composer show --direct` untuk memeriksa daftar dependensi langsung beserta versinya, atau `composer show <vendor/package>` untuk satu paket spesifik.
  - **Paket JS/Frontend**: Periksa file `package.json` atau jalankan `npm list --depth=0`.

### Aktivasi Skill Domain
- Jika proyek menyediakan skill spesifik domain di folder `**/skills/**` (misal: `testing-best-practices`, `deploying-to-cloud`, `medialibrary-development`, dsb.), AI Agent **wajib mengaktifkan skill tersebut** saat bekerja di domain terkait.

### Konvensi & Struktur Kode
- **Patuhi Konvensi yang Ada**: Saat membuat atau mengedit file, selalu periksa file sejenis (*sibling files*) sebagai referensi struktur, pola, dan penamaan.
- **Penamaan Deskriptif**: Gunakan nama variabel dan method yang jelas dan deskriptif (contoh: `isRegisteredForDiscounts`, bukan `discount()`).
- **Gunakan Komponen yang Ada**: Cek keberadaan komponen reusable (Blade/JS/Helper) sebelum membuat komponen baru dari nol.
- **Struktur Folder**: Pertahankan struktur direktori standar proyek. Jangan membuat folder root baru tanpa persetujuan pengguna.
- **Dependensi**: Jangan menambahkan atau mengubah dependensi (`composer.json` / `package.json`) tanpa persetujuan pengguna.

### Verifikasi & Automated Tests
- Jangan membuat skrip verifikasi manual ad-hoc atau pengujian sementara via Tinker jika fungsionalitas tersebut dapat dan seharusnya dibuktikan melalui Unit/Feature Test. Automated tests memberikan jaminan regresi yang permanen.

### Frontend Bundling (Vite)
- Jika perubahan frontend (Blade, CSS, JS) tidak tampil di peramban atau muncul error `Unable to locate file in Vite manifest`:
  - Jalankan `npm run build` untuk mengompilasi aset ke direktori publik.
  - Atau minta pengguna menjalankan `npm run dev` / `composer run dev` jika berada dalam sesi live development.

### Pembuatan File Dokumentasi
- Buat file dokumentasi tambahan HANYA jika diminta secara eksplisit oleh pengguna.

---

## 2. Standar Bahasa & Sintaksis PHP (PHP 8+)

- **Kurung Kurawal Wajib**: Selalu gunakan kurung kurawal `{}` untuk semua struktur kendali (`if`, `else`, `foreach`, `while`), termasuk pada blok satu baris.
- **Constructor Property Promotion**: Gunakan fitur PHP 8 constructor property promotion:
  ```php
  public function __construct(
      public GitHubService $github,
      public UserRepository $userRepository
  ) {}
  ```
  Jangan membuat method `__construct()` kosong tanpa parameter kecuali jika method tersebut bersifat privat.
- **Type Hinting & Return Types Eksplisit**: Wajib mendeklarasikan tipe data parameter dan return type secara eksplisit pada setiap fungsi/method:
  ```php
  public function isAccessible(User $user, ?string $path = null): bool
  ```
- **PHPDoc & Array Shapes**: Utamakan blok PHPDoc terstruktur dengan spesifikasi bentuk array (*array shape*) untuk data kompleks:
  ```php
  /**
   * @param array{name: string, price: int, is_active: bool} $payload
   */
  ```
- **Konvensi Enum**: Gunakan penamaan Enum berbasis PascalCase dan manfaatkan Backed Enum PHP 8.1+ jika memerlukan nilai string/integer.

---

## 3. Standar Laravel Core & Eloquent

### Pembuatan File via Artisan
- Gunakan perintah `php artisan make:*` bawaan Laravel dengan menyertakan opsi `--no-interaction`:
  - Model, Migrasi, Factory, Seeder: `php artisan make:model Product -mfs --no-interaction`
  - Controller: `php artisan make:controller ProductController --no-interaction`
  - Form Request: `php artisan make:request SaveProductRequest --no-interaction`
  - API Resource: `php artisan make:resource ProductResource --no-interaction`
  - Generic Class / Action: `php artisan make:class Actions/Product/SaveProductAction --no-interaction`

### Model & Database
- **Model Casts**: Definisikan type casting pada model menggunakan method `casts(): array` (standar Laravel modern) alih-alih properti `$casts`:
  ```php
  protected function casts(): array
  {
      return [
          'is_active' => 'boolean',
          'published_at' => 'datetime',
      ];
  }
  ```
- **Modifikasi Kolom Migrasi**: Saat memodifikasi kolom tabel menggunakan `->change()`, seluruh atribut kolom sebelumnya (seperti `->nullable()`, `->default(...)`, `->unsigned()`) WAJIB didefinisikan ulang secara lengkap agar tidak terhapus.
- **Native Eager Loading Limit**: Manfaatkan fitur native limit relasi pada Laravel modern: `$query->latest()->limit(10);`.

### Routing & API
- **Named Routes**: Seluruh rute wajib memiliki nama (*named route*) dan dipanggil menggunakan helper `route('nama_rute')`. Hindari penulisan URL mentah (*hardcoded*).
- **Format URL vs Nama Rute**: Gunakan format **kebab-case** untuk URL (`/product-categories`) dan **snake_case** atau dot notation untuk nama rute (`product_categories.index`).
- **Organisasi Rute Modular**: Untuk proyek modular, pisahkan rute web per file di `routes/web/<module>.php` dan kelompokkan rute API di dalam prefix versi (`Route::prefix('v1')->as('api.v1.')->group(...)`).
- **API Resources**: Selalu transformasikan response JSON menggunakan Eloquent API Resources (`JsonResource`) alih-alih mengembalikan model mentah.

---

## 4. Standar Perkakas (*Tooling*) & Linter

### Laravel Pint (Code Formatter)
- **HANYA Format File yang Dimodifikasi**: Jika Anda membuat atau mengedit file PHP, jalankan Pint **HANYA** pada file spesifik yang disentuh:
  ```bash
  vendor/bin/pint app/Http/Controllers/ProductController.php app/Actions/Product/SaveProductAction.php
  ```
- **DILARANG KERAS** menjalankan `vendor/bin/pint` ke seluruh repositori tanpa argumen file atau menjalankan `vendor/bin/pint --test` yang memicu perubahan massal.

### Eksekusi Tinker
- Jalankan kode PHP cepat melalui Tinker dengan tanda kutip tunggal (`'`) sebagai pembungkus dan tanda kutip ganda (`"`) di dalam ekspresi PHP:
  ```bash
  php artisan tinker --execute 'App\Models\User::where("is_active", true)->count();'
  ```

### Tool MCP / Laravel Boost (Jika Tersedia)
Jika lingkungan terhubung dengan MCP Laravel Boost, prioritaskan tool berikut:
- `database-query`: Menjalankan kueri read-only langsung ke database.
- `database-schema`: Memeriksa struktur skema tabel sebelum menulis migrasi/model.
- `search-docs`: Mencari dokumentasi resmi paket/fitur Laravel terkini secara terarah.
- `get-absolute-url`: Mengambil URL domain aplikasi yang tepat.
- `browser-logs`: Membaca log error frontend terkini.

---

## 5. Standar Pengujian (*Automated Testing*)

- **Framework Pengujian**: Mendukung **Pest PHP** maupun **PHPUnit**.
  - Buat Feature Test Pest: `php artisan make:test {Name}Test --pest --no-interaction`
  - Buat Feature Test PHPUnit: `php artisan make:test {Name}Test --no-interaction`
- **Feature Test sebagai Prioritas**: Uji skenario menyeluruh (HTTP request, otorisasi, validasi status 422, status 200/201, transaksi DB).
- **Gunakan Factory**: Siapkan data uji selalu melalui Model Factory (`User::factory()->create()`).
- **Jalankan Tes Terfokus (Narrowest Set)**:
  ```bash
  php artisan test tests/Feature/ProductTest.php --compact
  # atau
  php artisan test --filter=test_can_create_product
  ```
- **Dilarang Menghapus Tes**: Jangan menghapus file tes yang sudah ada tanpa persetujuan tertulis dari pengguna.

---

## 6. Peta Knowledge Base Proyek (`.ai/`)

Berikut adalah daftar lengkap dokumentasi penunjang yang tersedia di dalam folder `.ai/`:

| Berkas Panduan | Deskripsi & Tanggung Jawab |
| :--- | :--- |
| **`AGENTS.md`** | Dokumen panduan utama AI (*Entry Point & Universal Rules*). |
| **`project-architecture.md`** | Gambaran arsitektur sistem, peta direktori, alur request/response, dan dependensi. |
| **`workflow.md`** | Siklus hidup dan langkah kerja pembuatan fitur dari awal hingga akhir. |
| **`backend.md`** | Arsitektur backend Laravel, tanggung jawab controller, service, action, dan repository. |
| **`frontend.md`** | Arsitektur frontend, asset bundling, integrasi JS/CSS, dan siklus rendering UI. |
| **`routing.md`** | Standar perutean web, rute modular, grup API v1, middleware, dan Ziggy. |
| **`controller.md`** | Pola koordinasi Controller dengan Repository dan Action. |
| **`request.md`** | Standardisasi validasi input form menggunakan Form Request. |
| **`repository.md`** | Aturan penulisan query pembacaan data, filter, dan eager loading. |
| **`resource.md`** | Standardisasi format JSON response menggunakan Eloquent API Resources. |
| **`javascript.md`** | Pola interaksi client-side menggunakan PageScript (IIFE), AlpineJS, dan utilitas AJAX. |
| **`blade.md`** | Aturan penyusunan layout, komponen UI reusable, dan rendering views. |
| **`css.md`** | Panduan sistem styling Tailwind CSS v4 dan variabel tema CSS :root. |
| **`datatable.md`** | Panduan integrasi tabel dinamis client-side berbasis komponen `<x-datatable>`. |
| **`cardgrid.md`** | Panduan integrasi grid kartu dinamis client-side berbasis komponen `<x-cardgrid>`. |
| **`module-default.md`** | Panduan pembuatan modul tipe Full Page CRUD. |
| **`module-simple.md`** | Panduan pembuatan modul tipe Modal CRUD. |
| **`naming-convention.md`** | Kamus standar penamaan file, class, method, route, dan variabel. |
| **`lang.md`** | Standardisasi lokalisasi (i18n), file bahasa (`lang/en` & `lang/id`), dan integrasi UI. |
| **`testing.md`** | Panduan pengujian otomatis (Pest & PHPUnit), factories, and regression safety. |
| **`tooling.md`** | Panduan operasional Artisan, Pint, Tinker, MCP Tools, dan Vite bundling. |
| **`best-practice.md`** | Checklist teknis komprehensif, larangan keras (*Don'ts*), dan troubleshooting. |

---

## 7. Alur Kerja Rekomendasi (*Step-by-Step*)

Ketika menerima permintaan pembuatan atau modifikasi fitur:
1. **Identifikasi & Baca Aturan**: Baca `AGENTS.md` dan file `.ai/*.md` yang relevan.
2. **Database & Model**: Siapkan migrasi, model dengan `casts()`, factory, dan seeder.
3. **Backend**:
   - Daftarkan Rute di `routes/web.php` atau `routes/api.php` dengan penamaan rute.
   - Buat Form Request untuk validasi input.
   - Buat Repository untuk kueri pembacaan data (`getAll`, `findById`).
   - Buat Action untuk eksekusi penulisan/transaksi database (`DB::transaction`).
   - Buat API Resource untuk standarisasi JSON response.
   - Lengkapi Controller dengan otorisasi eksplisit (`$this->authorize(...)`).
4. **Frontend** (Jika berlaku):
   - Susun Blade View dengan komponen reusable (`<x-datatable>`, modal, dsb.).
   - Tulis logika JavaScript interaktif (AJAX via ApiProvider, reinit UI Preline jika ada manipulasi DOM).
5. **Quality Assurance**:
   - Format file PHP yang dimodifikasi menggunakan `vendor/bin/pint path/to/file.php`.
   - Jalankan Feature/Unit Test terkait untuk memastikan seluruh assertion berstatus lulus.

---

## 8. Checklist Verifikasi AI Agent

- [ ] Apakah seluruh kurung kurawal, return type, dan type hints sudah ditulis eksplisit pada kode PHP?
- [ ] Apakah seluruh rute memiliki nama (*named routes*) dan dipanggil via `route()`?
- [ ] Apakah query pembacaan kompleks dipisahkan ke Repository dan penulisan kompleks ke Action?
- [ ] Apakah Pint hanya dijalankan pada file PHP yang baru saja dimodifikasi?
- [ ] Apakah automated tests sudah dibuat/dijalankan untuk memverifikasi perubahan logika?
- [ ] Apakah tidak ada error manifest Vite saat aplikasi dimuat?
