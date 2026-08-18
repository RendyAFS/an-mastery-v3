# Language & Localization Standards (lang.md)

## Tujuan
Dokumen ini menjelaskan standar pengelolaan lokalisasi dan internasionalisasi (i18n / language) di proyek ini. Panduan ini mengatur struktur berkas bahasa, pendaftaran lokalisasi di Blade, penggunaan terjemahan di frontend JavaScript, serta pemetaan nama model.

## Kapan digunakan
Rujuklah panduan ini setiap kali Anda membuat modul baru, menambahkan teks UI/form/validasi baru, mendaftarkan variabel lokalisasi untuk JavaScript di `lang.blade.php`, atau menggunakan teks terjemahan pada berkas Blade dan JS.

## Cara kerja
1. **Berkas Bahasa (PHP Arrays)**: Semua teks antarmuka disimpan di dalam folder `lang/en/` (Inggris) dan `lang/id/` (Indonesia) sebagai berkas array PHP yang mengembalikan pasangan key-value.
2. **Standardisasi Nama Model (`models.php`)**: Nama nama modul/model terdaftar di `lang/{locale}/models.php` (misal: `'Product' => 'Product'`, `'Category' => 'Kategori'`) sehingga judul halaman/modal dapat dirender secara dinamis.
3. **Pemuatan Global di Blade (`lang.blade.php`)**: File `resources/views/layouts/lang.blade.php` mengonversi array terjemahan PHP ke objek JSON global JavaScript (`window.langModels`, `window.langCrud`, `window.langModule`, dll).
4. **Konsumsi di Client-Side (JS)**: File JavaScript per halaman (`index.js`) mengakses objek global `window.langModule`, `window.langModels`, `window.langCrud`, atau helper `trans(...)` untuk dialog konfirmasi, toast alert, dan judul modal.

## Struktur
### 1. Struktur Berkas Bahasa
```text
lang/
├── en/
│   ├── models.php       # Kamus nama model/modul (Singular PascalCase)
│   ├── crud.php         # Teks aksi umum (add_title, edit_title, created, updated, deleted)
│   ├── ui.php           # Teks tombol/status UI umum
│   └── {kebab-module}.php # Teks spesifik modul (misal: category.php, product.php)
└── id/
    ├── models.php
    ├── crud.php
    ├── ui.php
    └── {kebab-module}.php
```

### 2. Registrasi Variabel JS di `resources/views/layouts/lang.blade.php`
- Tambahkan pengumpulan data array: `$langModuleName = collect(trans('kebab-module'))->toArray();`
- Ekspos ke window object: `window.langModuleName = @json($langModuleName);`

## Contoh implementasi
Pola lokalisasi dapat dipelajari pada berkas:
- Berkas Bahasa Modul (EN): `lang/en/{kebab-module}.php`
- Berkas Bahasa Modul (ID): `lang/id/{kebab-module}.php`
- Pemetaan Lokalisasi Layout: `resources/views/layouts/lang.blade.php`

## Contoh kode
### 1. Di dalam Blade View:
```blade
<h1 class="text-3xl font-bold">{{ __('models.Category') }}</h1>
<p class="text-sm text-(--color-dark-gray) mt-1">{{ __('category.description') }}</p>

<x-select id="category_id" name="category_id"
    label="{{ __('category.fields.name') }}"
    placeholder="{{ __('category.placeholders.name') }}"
    searchPlaceholder="{{ __('category.placeholders.search') }}" />
```

### 2. Di dalam JavaScript (`index.js`):
```javascript
const modelName = window.langModels?.Category ?? "Category";

// Toast Notification
Toast.success(
    window.langCustomAlert?.success,
    trans("langCrud", "created", { model: modelName })
);

// Form Label & Text Access
const categoryLabel = window.langCategory?.fields?.name ?? "Name";
```

## Hubungan dengan file lain
- Berkas bahasa dikaitkan ke layout melalui `resources/views/layouts/lang.blade.php`.
- Penggunaan terjemahan UI di Blade diatur pada `blade.md`.
- Integrasi variabel terjemahan di client-side diatur pada `javascript.md`.

## Checklist
- [ ] Apakah file `lang/en/{kebab-module}.php` dan `lang/id/{kebab-module}.php` sudah dibuat?
- [ ] Apakah nama model sudah didaftarkan di `lang/en/models.php` dan `lang/id/models.php`?
- [ ] Apakah `$langModuleName` dan `window.langModuleName` sudah didaftarkan di `lang.blade.php`?
- [ ] Apakah tampilan Blade sudah menggunakan helper `__('kebab-module.key')` dan `__('models.ModuleName')`?
- [ ] Apakah script JavaScript sudah membaca dari `window.langModels` dan `window.lang{ModuleName}`?

## Best Practice
- **Gunakan Key Berstruktur**: Kelompokkan key di file bahasa ke dalam sub-array seperti `fields`, `placeholders`, `status`, `filter`, dan `validation`.
- **Gunakan Model Translation**: Jangan menulis string mentah model di JS/Blade, gunakan `__('models.Category')` atau `window.langModels?.Category`.

## Catatan penting
> [!IMPORTANT]
> Setiap kali menambahkan file `lang/{locale}/{module}.php` baru, pastikan untuk mendaftarkannya pada `resources/views/layouts/lang.blade.php` agar objek `window.lang{Module}` tersedia di browser tanpa menderita error `undefined`.

