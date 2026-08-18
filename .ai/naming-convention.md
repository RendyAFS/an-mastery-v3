# Naming Conventions (naming-convention.md)

## Tujuan
Dokumen ini menjelaskan standardisasi penamaan seluruh berkas, kelas, rute, variabel, dan metode pada proyek ini. Standardisasi ini menjaga kerapian kode dan mencegah bentrokan referensi.

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
| **Model** | Singular, PascalCase | `Category`, `ProductCategory` |
| **Controller** | PascalCase, akhiran `Controller` | `CategoryController`, `ProductCategoryController` |
| **Repository** | PascalCase, akhiran `Repository` | `CategoryRepository`, `ProductCategoryRepository` |
| **Form Request** | PascalCase, awalan `Save`, akhiran `Request` | `SaveCategoryRequest`, `SaveProductCategoryRequest` |
| **API Resource** | PascalCase, akhiran `Resource` | `CategoryResource`, `ProductCategoryResource` |
| **Action Class** | PascalCase, awalan `Save`, akhiran `Action` | `SaveProductAction` |
| **Migration** | snake_case, jamak, deskriptif | `2026_07_23_create_categories_table` |
| **Rute URL** | kebab-case, jamak untuk resource | `/categories`, `/product-categories` |
| **Nama Rute (as)** | snake_case, jamak | `categories.index`, `product_categories.index` |
| **Folder View & JS**| kebab-case | `views/product-category/`, `js/pages/product-category/` |
| **Variabel PHP** | snake_case | `$category_id`, `$product_details` |
| **Variabel JS** | camelCase | `categoryId`, `productDetails` |
| **Database Column**| snake_case | `is_active`, `deleted_at`, `category_id` |

## Contoh implementasi
Pola penamaan dapat dilihat pada struktur pembuatan modul kustom:
- Perintah Artisan: `app/Console/Commands/MakeModuleCommand.php` yang memproses variabel `$studly = Str::studly($name)` dan `$kebab = Str::kebab($name)`.

## Contoh kode
Berikut adalah contoh implementasi penamaan kelas dan rute dalam satu modul:
```php
// File: app/Http/Controllers/ProductCategoryController.php
class ProductCategoryController extends Controller { ... }

// File: app/Repositories/ProductCategoryRepository.php
class ProductCategoryRepository { ... }

// File: routes/web.php
Route::resource('product-categories', ProductCategoryController::class)->names('product_categories');
// Menghasilkan rute bernama: product_categories.index, product_categories.store, dll.
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
- **Consisten Singular/Plural**: Gunakan nama tunggal (singular) untuk model/kelas PHP (misal `Category`), tetapi gunakan kata jamak (plural) untuk rute URL (misal `categories`) demi mematuhi RESTful API standard.
- **Konsistensi Variabel Kontrak**: Pastikan penamaan key properti JSON dari API Resource (`camelCase` atau `snake_case` kustom) sudah cocok dengan parsing key di dalam berkas Javascript halaman.

## Catatan penting
> [!WARNING]
> Jangan pernah mencampuradukkan penulisan underscore dan hyphen pada URL. `/product_categories` adalah kesalahan fatal. URL wajib menggunakan `/product-categories` dan nama rute wajib menggunakan `product_categories.*`.

