# Language & Localization Standards (lang.md)

## Tujuan
Dokumen ini menjelaskan standar pengelolaan lokalisasi dan internasionalisasi (i18n / language) di proyek **AN Mastery V3**. Panduan ini mengatur struktur berkas bahasa, pendaftaran lokalisasi di Blade, penggunaan terjemahan di frontend JavaScript, serta pemetaan nama model.

## Kapan digunakan
Rujuklah panduan ini setiap kali Anda membuat modul baru, menambahkan teks UI/form/validasi baru, mendaftarkan variabel lokalisasi untuk JavaScript di `lang.blade.php`, atau menggunakan teks terjemahan pada berkas Blade dan JS.

## Cara kerja
1. **Berkas Bahasa (PHP Arrays)**: Semua teks antarmuka disimpan di dalam folder `lang/en/` (Inggris) dan `lang/id/` (Indonesia) sebagai berkas array PHP yang mengembalikan pasangan key-value.
2. **Standardisasi Nama Model (`models.php`)**: Nama nama modul/model terdaftar di `lang/{locale}/models.php` (misal: `'Memo' => 'Memo'`, `'Supplier' => 'Konveksi'`) sehingga judul halaman/modal dapat dirender secara dinamis.
3. **Pemuatan Global di Blade (`lang.blade.php`)**: File `resources/views/layouts/lang.blade.php` mengonversi array terjemahan PHP ke objek JSON global JavaScript (`window.langModels`, `window.langCrud`, `window.langMemo`, dll).
4. **Konsumsi di Client-Side (JS)**: File JavaScript per halaman (`list.js`) mengakses objek global `window.langMemo`, `window.langModels`, `window.langCrud`, atau helper `trans(...)` untuk dialog konfirmasi, toast alert, dan judul modal.

## Struktur
### 1. Struktur Berkas Bahasa
```text
lang/
├── en/
│   ├── models.php       # Kamus nama model/modul (Singular PascalCase)
│   ├── crud.php         # Teks aksi umum (add_title, edit_title, created, updated, deleted)
│   ├── ui.php           # Teks tombol/status UI umum
│   └── {kebab-module}.php # Teks spesifik modul (misal: memo.php, supplier.php)
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
- Berkas Bahasa Memo (EN): [memo.php](file:///d:/laragon/www/an-mastery-v3/lang/en/memo.php)
- Berkas Bahasa Memo (ID): [memo.php](file:///d:/laragon/www/an-mastery-v3/lang/id/memo.php)
- Pemetaan Lokalisasi Layout: [lang.blade.php](file:///d:/laragon/www/an-mastery-v3/resources/views/layouts/lang.blade.php)

## Contoh kode
### 1. Di dalam Blade View:
```blade
<h1 class="text-3xl font-bold">{{ __('models.Memo') }}</h1>
<p class="text-sm text-(--color-dark-gray) mt-1">{{ __('memo.description') }}</p>

<x-select id="employee_id" name="employee_id"
    label="{{ __('memo.fields.employee') }}"
    placeholder="{{ __('memo.placeholders.employee') }}"
    searchPlaceholder="{{ __('memo.placeholders.search_employee') }}" />
```

### 2. Di dalam JavaScript (`list.js`):
```javascript
const modelName = window.langModels?.Memo ?? "Memo";

// Toast Notification
Toast.success(
    window.langCustomAlert?.success,
    trans("langCrud", "created", { model: modelName })
);

// Form Label & Text Access
const employeeLabel = window.langMemo?.fields?.employee ?? "Employee";
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
- **Gunakan Model Translation**: Jangan menulis string mentah model di JS/Blade, gunakan `__('models.Memo')` atau `window.langModels?.Memo`.

## Catatan penting
> [!IMPORTANT]
> Setiap kali menambahkan file `lang/{locale}/{module}.php` baru, pastikan untuk mendaftarkannya pada `resources/views/layouts/lang.blade.php` agar objek `window.lang{Module}` tersedia di browser tanpa menderita error `undefined`.
