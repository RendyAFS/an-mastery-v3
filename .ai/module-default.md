# Default Module Development Guide (module-default.md)

## Tujuan
Dokumen ini menjelaskan panduan langkah demi langkah untuk membuat **Modul Default (Full Page CRUD)** di proyek **AN Mastery V3**. Dokumen ini memastikan keseragaman alur data dan struktur halaman saat mengembangkan modul berhalaman penuh.

## Kapan digunakan
Gunakan dokumen ini setiap kali Anda membuat modul CRUD besar yang memerlukan form pembuatan dan pengeditan di halaman terpisah (bukan di dalam modal pop-up), seperti modul Sablon atau Fabric.

## Cara kerja
Modul Default memisahkan antarmuka pengguna ke beberapa halaman Blade statis yang dilayani langsung oleh perutean resource Laravel. Interaksi data berjalan melalui pemuatan halaman formulir baru, penyerahan data berbasis asinkron (AJAX) di halaman tersebut, dan pemindahan arah lokasi kembali ke halaman list utama.

## Struktur
### 1. Struktur Folder & Berkas Modul Default
Jika Anda membuat modul bernama `Sablon`, generator akan menghasilkan berkas-berkas berikut:
- **Backend**:
  - `app/Http/Controllers/SablonController.php` (Mengontrol rendering rute & respon API)
  - `app/Repositories/SablonRepository.php` (Mengolah data kueri)
  - `app/Http/Requests/Sablon/SaveSablonRequest.php` (Validasi skema form)
  - `app/Http/Resources/SablonResource.php` (Standardisasi kembalian JSON)
- **Views (Blade)**:
  - `resources/views/sablon/index.blade.php` (Halaman daftar data utama)
  - `resources/views/sablon/form.blade.php` (Kerangka kolom input form)
  - `resources/views/sablon/create.blade.php` (Halaman pembungkus form tambah)
  - `resources/views/sablon/edit.blade.php` (Halaman pembungkus form edit)
- **Javascript**:
  - `resources/js/pages/sablon/index.js` (Mengontrol tabel/grid di halaman index)
  - `resources/js/pages/sablon/form.js` (Mengontrol interaksi/validasi form)
  - `resources/js/pages/sablon/alpine-component.js` (Mengolah reaktivitas tabel dinamis - opsional)

### 2. Alur Pengoperasian CRUD Modul Default
- **Halaman Index**: Menampilkan data menggunakan komponen `<x-cardgrid>` atau `<x-datatable>`.
- **Aksi Create**: Mengarahkan user ke URL `/sablons/create`, memuat berkas `create.blade.php`.
- **Aksi Store**: Mengirim data input via `ApiProvider.post(route('sablons.store'), payload)`, menampilkan toast success, lalu mengalihkan halaman ke index via `window.location.href`.
- **Aksi Edit**: Mengarahkan user ke URL `/sablons/{id}/edit`, memuat berkas `edit.blade.php` beserta data awal.
- **Aksi Update**: Mengirim data terupdate via `ApiProvider.put(...)` dan mengalihkan halaman kembali ke index.

## Contoh implementasi
Implementasi modul ini dapat dipelajari pada berkas:
- Kontroler Utama: [SablonController.php](file:///d:/laragon/www/an-mastery-v3/app/Http/Controllers/SablonController.php)
- View Halaman Tambah: [create.blade.php](file:///d:/laragon/www/an-mastery-v3/resources/views/sablon/create.blade.php)
- Javascript Controller Form: [page-script.js](file:///d:/laragon/www/an-mastery-v3/resources/js/pages/sablon/page-script.js)

## Contoh kode
Berikut adalah kerangka penulisan method controller untuk melayani pendaftaran halaman modul default:
```php
// app/Http/Controllers/SablonController.php
public function create()
{
    $this->authorize('sablons.create');
    // Memuat data relasi pendukung untuk pilihan select
    return view('sablon.create', $this->sablonRepository->getFormData());
}

public function edit(Sablon $sablon)
{
    $this->authorize('sablons.edit');
    $sablon = $this->sablonRepository->findWithDetails($sablon);
    $formData = $this->sablonRepository->getFormData();
    
    return view('sablon.edit', array_merge(['sablon' => $sablon], $formData));
}
```

## Hubungan dengan file lain
- Modul Default dibuat secara otomatis dengan menjalankan generator kustom `app/Console/Commands/MakeModuleCommand.php`.
- Standardisasi styling form berpedoman pada `css.md` dan `blade.md`.

## Checklist
- [ ] Apakah rute resource untuk modul default sudah didaftarkan di `web.php`?
- [ ] Apakah berkas `form.blade.php` sudah di-include di dalam `create.blade.php` dan `edit.blade.php`?
- [ ] Apakah form submit di Javascript sudah menangani pengalihan halaman kembali ke index (`window.location.href = route('...')`) setelah proses simpan berhasil?
- [ ] Apakah file JavaScript untuk halaman list (`index.js`) dan halaman form (`form.js`) terpisah secara modular?

## Best Practice
- **Pisahkan Logika Form**: Simpan kode baris dinamis (seperti perhitungan nominal otomatis) di dalam berkas AlpineJS kustom (`alpine-component.js`) dan biarkan `page-script.js` fokus mengontrol pengiriman request AJAX dan penanganan response.
- **Gunakan Eager Loading untuk Form Edit**: Pastikan model dimuat beserta relasi detailnya di method `edit()` agar baris detail dinamis dapat ter-render kembali saat pengeditan.

## Catatan penting
> [!IMPORTANT]
> Modul Default wajib menyediakan tombol simpan alternatif "Save and Create Another" pada halaman tambah data (Create Page). Ini mempermudah pekerja menginput banyak transaksi berturut-turut tanpa harus kembali ke halaman list utama.
