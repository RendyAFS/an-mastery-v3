# Project Best Practices (best-practice.md)

## Tujuan
Dokumen ini merangkum kumpulan aturan main, pola pemrograman wajib, hal yang dilarang keras, kesalahan yang sering terjadi (*common errors*), serta panduan penyelesaian masalah untuk proyek berbasis arsitektur Laravel modern.

## Kapan digunakan
Tinjau dokumen ini setiap kali Anda memulai pengerjaan fitur baru, sebelum mengajukan Pull Request / menyelesaikan task, atau saat melakukan debugging kegagalan validasi, query, dan integrasi UI.

## Cara kerja
Pedoman ini disusun berdasarkan standar mutu, performa, dan keamanan codebase. AI Agent dan developer harus mengikuti checklist sebelum membuat fitur dan menghindari larangan keras agar kualitas sistem tetap terjaga secara konsisten.

## 1. Standar Kode PHP & Laravel Core
- **Kurung Kurawal Eksplisit**: Selalu gunakan kurung kurawal `{}` untuk semua struktur kontrol (`if`, `foreach`, `while`, `else`), bahkan untuk blok satu baris.
- **Constructor Property Promotion**: Gunakan fitur PHP 8+ constructor property promotion (contoh: `public function __construct(public GitHubService $github) {}`). Jangan membuat method constructor kosong tanpa parameter kecuali jika constructor tersebut berstatus private.
- **Type Hinting & Return Types**: Wajib menyertakan tipe data eksplisit pada setiap parameter method dan nilai kembalian (*return type*):
  ```php
  public function isAccessible(User $user, ?string $path = null): bool
  ```
- **PHPDoc Blocks**: Utamakan PHPDoc block terstruktur untuk method kompleks, dan gunakan *array shape definitions* saat method menerima atau mengembalikan struktur array tertentu:
  ```php
  /**
   * @param array{name: string, price: int, is_active: bool} $payload
   */
  ```
- **Model Casts**: Pada Laravel modern, definisikan type casting menggunakan method `casts(): array` di model alih-alih properti `$casts`.
- **Modifikasi Kolom Migrasi Database**: Saat memodifikasi kolom tabel menggunakan `->change()`, seluruh atribut kolom yang sebelumnya ada (seperti `->nullable()`, `->default()`, `->unsigned()`) WAJIB didefinisikan ulang secara lengkap agar tidak terhapus oleh database driver.
- **Native Eager Loading Limit**: Manfaatkan fitur native limit pada query relasi jika didukung framework tanpa paket eksternal (contoh: `$query->latest()->limit(10)`).

## 2. Standar Tooling & Perintah Terminal
- **Pint Formatter**: Jika Anda memodifikasi file PHP, jalankan Pint **HANYA** pada file yang baru saja Anda edit (contoh: `vendor/bin/pint path/to/file.php`). Jangan jalankan Pint ke seluruh direktori proyek tanpa argumen file.
- **Artisan Non-Interaktif**: Selalu sertakan flag `--no-interaction` saat agent menjalankan perintah Artisan di terminal.
- **Eksekusi Tinker yang Aman**: Gunakan single-quotes untuk membungkus perintah dan double-quotes di dalam PHP:
  ```bash
  php artisan tinker --execute 'App\Models\User::where("is_active", true)->count();'
  ```
- **Automated Tests**: Jangan membuat skrip verifikasi sekali pakai jika fungsionalitas tersebut dapat dan harus dibuktikan dengan Feature Test / Unit Test.

## 3. Hal yang Wajib Dilakukan (Dos)
- **Otorisasi Eksplisit**: Tulis otorisasi Policy/Permission (contoh: `$this->authorize('permission_name')` atau Gate check) di awal setiap aksi method controller.
- **Pemisahan Read dan Write**: Kueri pembacaan data ditempatkan di Repository, sedangkan proses transaksi penyimpanan/update yang kompleks dibungkus dalam kelas Action dengan `DB::transaction(...)`.
- **Eager Loading**: Selalu muat relasi tabel di Repository (`$query->with(...)`) dan lindungi eksposur relasi di Resource (`$this->whenLoaded(...)`).
- **Filter Status Aktif (`is_active`)**: Selalu filter `where('is_active', true)` (atau scope `active()`) pada query operasional, presensi, kartu transaksi, dan select dropdown jika model memiliki flag aktif.
- **Format Tanggal ISO untuk Input**: Di API Resource, selalu format tanggal input sebagai `Y-m-d` (contoh: `'date' => $this->date?->format('Y-m-d')`), dan pisahkan format tampilan manusia ke `'date_formatted'`.
- **Pembersihan Mask Rupiah**: Panggil utilitas `normalizeFormInputs(form, payload)` sebelum mengirimkan payload data numerik rupiah ke backend.

## 4. Hal yang Dilarang Keras (Don'ts)
- **Jangan Format Seluruh Project**: Dilarang menjalankan Pint tanpa filter file yang menyebabkan modifikasi massal di file yang tidak relevan.
- **Jangan Hardcode URL Rute**: Jangan menulis string URL rute mentah di Javascript. Selalu gunakan helper `route('nama_rute')`.
- **Jangan Campur Logika Database di Controller**: Hindari menulis query `INSERT`, `UPDATE`, atau raw SQL kompleks langsung di Controller.
- **Jangan Gunakan Server-side Yajra**: Pada arsitektur client-side rendering, kelola datatable/grid sepenuhnya via JSON standard API Resource.
- **Jangan Kirim Format Teks Tanggal ke Flatpickr**: Dilarang mengirim teks tanggal berbahasa lokal (misal `"21 September 2026"`) pada properti `date` yang dikonsumsi Flatpickr form.

## 5. Masalah yang Sering Terjadi & Solusinya
- **Masalah: Error "Unable to locate file in Vite manifest" atau perubahan aset frontend tidak tampil.**
  - *Penyebab*: Bundle Vite belum dikompilasi atau file manifest belum diperbarui setelah perubahan CSS/JS.
  - *Solusi*: Jalankan `npm run build` untuk build aset produksi, atau jalankan `npm run dev` untuk live hot-module replacement.
- **Masalah: Nilai tanggal pada form edit tereset menjadi 1 Januari.**
  - *Penyebab*: API Resource mengembalikan `date` dalam format teks bulan lokal (misal `translatedFormat('d F Y')`). Parser Flatpickr dengan `dateFormat: 'Y-m-d'` gagal membaca token bulan sehingga fallback ke Januari tanggal 1.
  - *Solusi*: Di Resource, kembalikan `'date' => $this->date?->format('Y-m-d')` untuk input form, dan gunakan `'date_formatted' => $this->date?->translatedFormat('d F Y')` khusus untuk tampilan tabel/label.
- **Masalah: Model/relasi yang dinonaktifkan (`is_active = false`) tetap muncul di form dropdown.**
  - *Penyebab*: Query di Repository belum menyaring `where('is_active', true)`.
  - *Solusi*: Tambahkan `where('is_active', true)` pada query data select/aktif.
- **Masalah: Dropdown / Modal Preline UI tidak merespon setelah data tabel di-refresh via AJAX.**
  - *Penyebab*: Elemen DOM baru ditambahkan tetapi event handler Preline belum diinisialisasi ulang.
  - *Solusi*: Panggil helper `window.HSStaticMethods.autoInit()` atau `reinitUi()` di dalam callback `drawCallback` tabel atau setelah manipulasi DOM.
- **Masalah: Error "419 Page Expired" saat submit AJAX.**
  - *Penyebab*: CSRF token pada header HTTP request tidak terkirim atau meta tag kadaluarsa.
  - *Solusi*: Pastikan layout master memiliki `<meta name="csrf-token" content="{{ csrf_token() }}">` dan library HTTP client (Axios/ApiProvider) membaca token tersebut.

## Checklist Sebelum Menyelesaikan Task
- [ ] Apakah seluruh kurung kurawal, type hints, dan return types PHP sudah ditulis dengan eksplisit?
- [ ] Apakah file PHP yang disentuh sudah diformat dengan `vendor/bin/pint path/to/file.php`?
- [ ] Apakah perubahan logika sudah dilindungi oleh automated Feature/Unit Test?
- [ ] Apakah otorisasi policy/permission sudah terpasang pada controller?
- [ ] Apakah tidak ada error manifest Vite saat aplikasi dimuat?
