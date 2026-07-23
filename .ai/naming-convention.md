# Naming Conventions (naming-convention.md)

## Tujuan
Dokumen ini menjelaskan standardisasi penamaan seluruh berkas, kelas, rute, variabel, dan metode pada proyek **AN Mastery V3**. Standardisasi ini menjaga kerapian kode dan mencegah bentrokan referensi.

## Kapan digunakan
Rujuklah panduan ini setiap kali Anda membuat berkas baru, mendefinisikan rute di `web.php`, menulis variabel baru di JS/PHP, membuat metode controller, atau menyusun elemen Blade baru.

## Cara kerja
Pola penamaan di proyek ini dikontrol secara ketat berdasarkan aturan casing tertentu:
1. **PascalCase** (StudlyCase): Untuk nama kelas PHP (Controllers, Repositories, Requests, Resources, Actions, Models).
2. **kebab-case**: Untuk URL rute, ID kontainer HTML, nama folder view, dan folder JS.
3. **snake_case**: Untuk nama rute Laravel (named routes), nama kolom tabel basis data, dan variabel PHP.
4. **camelCase**: Untuk nama variabel lokal JavaScript dan nama properti JSON response API.

## Struktur
Berikut adalah kamus standar konvensi penamaan proyek:

| Komponen | Pola Penamaan | Contoh |
| :--- | :--- | :--- |
| **Model** | Singular, PascalCase | `Supplier`, `ColorFabric` |
| **Controller** | PascalCase, akhiran `Controller` | `SupplierController`, `ColorFabricController` |
| **Repository** | PascalCase, akhiran `Repository` | `SupplierRepository`, `ColorFabricRepository` |
| **Form Request** | PascalCase, awalan `Save`, akhiran `Request` | `SaveSupplierRequest`, `SaveColorFabricRequest` |
| **API Resource** | PascalCase, akhiran `Resource` | `SupplierResource`, `ColorFabricResource` |
| **Action Class** | PascalCase, awalan `Save`, akhiran `Action` | `SaveSablonAction` |
| **Migration** | snake_case, jamak, deskriptif | `2026_07_23_create_suppliers_table` |
| **Rute URL** | kebab-case, jamak untuk resource | `/suppliers`, `/color-fabrics` |
| **Nama Rute (as)** | snake_case, jamak | `suppliers.index`, `color_fabrics.index` |
| **Folder View & JS**| kebab-case | `views/color-fabric/`, `js/pages/color-fabric/` |
| **Variabel PHP** | snake_case | `$supplier_id`, `$fabric_details` |
| **Variabel JS** | camelCase | `supplierId`, `fabricDetails` |
| **Database Column**| snake_case | `is_active`, `deleted_at`, `color_fabric_id` |

## Contoh implementasi
Pola penamaan dapat dilihat pada struktur pembuatan modul kustom:
- Perintah Artisan: [MakeModuleCommand.php](file:///d:/laragon/www/an-mastery-v3/app/Console/Commands/MakeModuleCommand.php) yang memproses variabel `$studly = Str::studly($name)` dan `$kebab = Str::kebab($name)`.

## Contoh kode
Berikut adalah contoh implementasi penamaan kelas dan rute dalam satu modul:
```php
// File: app/Http/Controllers/ColorFabricController.php
class ColorFabricController extends Controller { ... }

// File: app/Repositories/ColorFabricRepository.php
class ColorFabricRepository { ... }

// File: routes/web.php
Route::resource('color-fabrics', ColorFabricController::class)->names('color_fabrics');
// Menghasilkan rute bernama: color_fabrics.index, color_fabrics.store, dll.
```

## Hubungan dengan file lain
- Konvensi penamaan ini harus dipatuhi di seluruh pengerjaan file backend (`backend.md`) dan frontend (`frontend.md`).
- Pengaruh langsung terlihat pada file generator modul yang dijelaskan di `module-default.md` dan `module-simple.md`.

## Checklist
- [ ] Apakah nama berkas Controller Anda diakhiri kata `Controller.php`?
- [ ] Apakah URL rute menggunakan format kebab-case dan nama rutenya (`as`) menggunakan snake_case?
- [ ] Apakah folder view dan folder JS menggunakan nama yang sama dengan format kebab-case?
- [ ] Apakah nama kelas Form Request Anda menggunakan format `Save{ModuleName}Request`?

## Best Practice
- **Consisten Singular/Plural**: Gunakan nama tunggal (singular) untuk model/kelas PHP (misal `Fabric`), tetapi gunakan kata jamak (plural) untuk rute URL (misal `fabrics`) demi mematuhi RESTful API standard.
- **Konsistensi Variabel Kontrak**: Pastikan penamaan key properti JSON dari API Resource (`camelCase` atau `snake_case` kustom) sudah cocok dengan parsing key di dalam berkas Javascript halaman.

## Catatan penting
> [!WARNING]
> Jangan pernah mencampuradukkan penulisan underscore dan hyphen pada URL. `/color_fabrics` adalah kesalahan fatal. URL wajib menggunakan `/color-fabrics` dan nama rute wajib menggunakan `color_fabrics.*`.
