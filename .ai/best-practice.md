# Project Best Practices (best-practice.md)

## Tujuan
Dokumen ini merangkum kumpulan aturan main, pola pemrograman wajib, hal yang dilarang keras, kesalahan yang sering terjadi (*common errors*), serta panduan penyelesaian masalah khusus di proyek berbasis arsitektur ini.

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
- AJAX Response Handler: `resources/js/utils/api-provider.js` pada switch case `422` yang otomatis memunculkan toast message untuk setiap kegagalan input.

## Contoh kode
Berikut adalah contoh penulisan transaksi database yang aman dan terstandar di proyek ini (kelas Action):
```php
// CONTOH YANG WAJIB DIIKUTI
namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SaveProductAction
{
    public function handle($request, ?Product $product = null): Product
    {
        $data = $request->validated();
        $details = $data['item_details'] ?? [];
        unset($data['item_details']);

        return DB::transaction(function () use ($data, $details, $product) {
            $product = $product 
                ? tap($product)->update($data)
                : Product::create($data);

            $product->productDetails()->delete();
            foreach ($details as $detail) {
                $product->productDetails()->create($detail);
            }

            return $product->load('productDetails');
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
- **Filter Status Aktif (`is_active`)**: Selalu filter `where('is_active', true)` (atau scope `active()`) pada query operasional, presensi, kartu tagihan, dan select dropdown jika model memiliki flag aktif.
- **Format Tanggal ISO untuk Input**: Di API Resource, selalu format tanggal input sebagai `Y-m-d` (contoh: `'date' => $this->date?->format('Y-m-d')`), dan pisahkan format manusia ke `'date_formatted'`.

### 2. Hal yang Dilarang Keras (Don'ts)
- **Jangan Gunakan Server-side Yajra**: Dilarang menulis pengolahan Yajra DataTable di backend. DataTable dikelola sepenuhnya secara client-side menggunakan JSON standard.
- **Jangan Hardcode Rute**: Jangan menulis string URL rute mentah di Javascript. Selalu gunakan helper `route('nama_rute')`.
- **Jangan Campur Logika Database**: Hindari menulis kueri `INSERT` atau `UPDATE` kompleks langsung di Controller. Gunakan kelas Action terisolasi.
- **Jangan Kirim Format Teks Tanggal ke Flatpickr**: Dilarang mengirim teks tanggal berbahasa Indonesia (seperti `"21 September 2026"`) pada properti `date` yang dikonsumsi oleh `setDate()` Flatpickr.

## Catatan penting
### Masalah yang Sering Terjadi & Solusinya:
- **Masalah: Nilai tanggal pada form edit tereset menjadi 1 Januari.**
  - *Penyebab*: API Resource mengembalikan `date` dalam format teks bulan lokal (misal `translatedFormat('d F Y')`). Parser Flatpickr yang disetel ke `dateFormat: 'Y-m-d'` gagal membaca token bulan dan hari sehingga fallback ke bulan 0 (Januari) tanggal 1.
  - *Solusi*: Di Resource, kembalikan `'date' => $this->date?->format('Y-m-d')` untuk input form/Flatpickr, dan gunakan `'date_formatted' => $this->date?->translatedFormat('d F Y')` untuk tampilan Datatable.
- **Masalah: Karyawan atau supplier yang dinonaktifkan (`is_active = false`) tetap muncul di presensi atau dropdown select.**
  - *Penyebab*: Query di Repository (seperti `getEmployeesWithPresenceForWeek` atau `getDataSelect`) belum menyaring `where('is_active', true)`.
  - *Solusi*: Tambahkan `where('is_active', true)` pada query operasional, dan bungkus kondisi `orWhere` dalam closure function.
- **Masalah: Elemen dropdown Preline tidak merespon/terbuka setelah tabel di-reload.**
  - *Penyebab*: DOM telah berubah, namun Preline belum mendeteksi ulang elemen baru.
  - *Solusi*: Panggil helper `ui-init` / `reinit-ui` (`initUi()` atau `reinitUi()`) atau `window.HSStaticMethods.autoInit()` di dalam callback `drawCallback()` tabel atau setelah memodifikasi DOM halaman via JS.
- **Masalah: Error "419 Page Expired" saat submit form via AJAX.**
  - *Penyebab*: CSRF token pada meta tag kadaluarsa atau tidak terpasang.
  - *Solusi*: Pastikan layout `main.blade.php` memuat `<meta name="csrf-token" content="{{ csrf_token() }}">` dan `ApiProvider.js` membaca meta tag tersebut.

