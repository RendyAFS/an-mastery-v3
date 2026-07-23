# Project Best Practices (best-practice.md)

## Tujuan
Dokumen ini merangkum kumpulan aturan main, pola pemrograman wajib, hal yang dilarang keras, kesalahan yang sering terjadi (*common errors*), serta panduan penyelesaian masalah khusus di proyek **AN Mastery V3**.

## Kapan digunakan
Tinjau dokumen ini setiap kali Anda memulai pengerjaan fitur baru, sebelum mengajukan Pull Request, atau saat melakukan debugging kegagalan validasi / manipulasi DOM.

## Cara kerja
Pedoman ini disusun berdasarkan pola nyata codebase proyek. AI Agent dan developer harus mengikuti checklist sebelum membuat fitur dan menghindari larangan keras agar kualitas serta keamanan sistem tetap terjaga secara konsisten.

## Struktur
Dokumen ini mencakup:
- **Hal yang Wajib Dilakukan (Dos)**: Pemisahan data read-write, soft delete, loader UI.
- **Hal yang Dilarang Keras (Don'ts)**: Pemuatan server-side Yajra, input raw di DB.
- **Daftar Kesalahan yang Sering Terjadi**: Lupa reset modal state, N+1 Query.
- **Checklist Sebelum Membuat Fitur**: Daftar verifikasi kesiapan teknis.

## Contoh implementasi
Pola best practice penanganan error validasi asinkron dapat dipelajari di:
- AJAX Response Handler: [api-provider.js](file:///d:/laragon/www/an-mastery-v3/resources/js/utils/api-provider.js) pada switch case `422` yang otomatis memunculkan toast message untuk setiap kegagalan input.

## Contoh kode
Berikut adalah contoh penulisan transaksi database yang aman dan terstandar di proyek ini (kelas Action):
```php
// CONTOH YANG WAJIB DIIKUTI
namespace App\Actions\Sablon;

use App\Models\Sablon;
use Illuminate\Support\Facades\DB;

class SaveSablonAction
{
    public function handle($request, ?Sablon $sablon = null): Sablon
    {
        $data = $request->validated();
        $details = $data['fabric_details'] ?? [];
        unset($data['fabric_details']);

        return DB::transaction(function () use ($data, $details, $sablon) {
            $sablon = $sablon 
                ? tap($sablon)->update($data)
                : Sablon::create($data);

            $sablon->sablonDetails()->delete();
            foreach ($details as $detail) {
                $sablon->sablonDetails()->create($detail);
            }

            return $sablon->load('sablonDetails');
        });
    }
}
```

## Hubungan dengan file lain
- Dokumen ini melengkapi panduan arsitektur di `project-architecture.md`.
- Checklist sebelum membuat fitur terhubung langsung dengan langkah kerja di `workflow.md`.

## Checklist
### Sebelum Memulai Pembuatan Fitur:
- [ ] Apakah model database yang digunakan sudah menerapkan trait `SoftDeletes` dan package `Userstamps`?
- [ ] Apakah Permission kustom Spatie sudah didaftarkan di `DatabaseSeeder` atau `MenuPermissionSeeder`?
- [ ] Apakah Anda telah menyiapkan controller dengan constructor injection kelas Repository?
- [ ] Apakah tombol submit form Anda menggunakan komponen Blade `<x-button-loading>`?

## Best Practice
### 1. Hal yang Wajib Dilakukan (Dos)
- **Otorisasi Eksplisit**: Tulis `$this->authorize('permission_name')` di awal setiap aksi method controller.
- **Pembersihan Mask Rupiah**: Panggil utilitas `normalizeFormInputs(form, payload)` sebelum mengirimkan data numerik rupiah ke backend.
- **Gunakan Eager Loading**: Selalu muat relasi tabel di Repository (`$query->with(...)`) dan lindungi eksposur relasi di Resource (`$this->whenLoaded(...)`).

### 2. Hal yang Dilarang Keras (Don'ts)
- **Jangan Gunakan Server-side Yajra**: Dilarang menulis pengolahan Yajra DataTable di backend. DataTable dikelola sepenuhnya secara client-side menggunakan JSON standard.
- **Jangan Hardcode Rute**: Jangan menulis string URL rute mentah di Javascript. Selalu gunakan helper `route('nama_rute')`.
- **Jangan Campur Logika Database**: Hindari menulis kueri `INSERT` atau `UPDATE` kompleks langsung di Controller. Gunakan kelas Action terisolasi.

## Catatan penting
### Masalah yang Sering Terjadi & Solusinya:
- **Masalah: Elemen dropdown Preline tidak merespon/terbuka setelah tabel di-reload.**
  - *Penyebab*: DOM telah berubah, namun Preline belum mendeteksi ulang elemen baru.
  - *Solusi*: Panggil helper `reInitUi()` atau `window.HSStaticMethods.autoInit()` di dalam callback `drawCallback()` tabel atau setelah memodifikasi DOM halaman via JS.
- **Masalah: Error "419 Page Expired" saat submit form via AJAX.**
  - *Penyebab*: CSRF token pada meta tag kadaluarsa atau tidak terpasang.
  - *Solusi*: Pastikan layout `main.blade.php` memuat `<meta name="csrf-token" content="{{ csrf_token() }}">` dan `ApiProvider.js` membaca meta tag tersebut.
