# Feature Development Workflow (workflow.md)

## Tujuan
Dokumen ini mendefinisikan langkah-langkah kerja terstandardisasi (*workflow*) bagi AI Agent atau developer saat mengembangkan fitur atau modul baru di proyek **AN Mastery V3**.

## Kapan digunakan
Gunakan dokumen ini sebagai panduan praktis (*step-by-step*) setiap kali Anda menerima perintah untuk membuat modul baru dari nol atau menambahkan fitur CRUD baru.

## Cara kerja
Alur pengerjaan fitur baru berjalan dari penentuan model & migrasi database, pembuatan infrastruktur backend (rute, request, repository, resource, action, controller), hingga penyusunan frontend (blade views, javascript pages, css styling, dan inisialisasi interaksi UI).

## Struktur
Berikut adalah visualisasi alur pengerjaan fitur baru secara sekuensial:

```text
Database (Migration & Model)
↓
Artisan Command (make:module)
↓
Routing (routes/web.php)
↓
Form Request (Validation Rules)
↓
Repository (Data Queries)
↓
Action (Write / Transaction Logic)
↓
Resource (Data Transforming)
↓
Controller (Flow Coordinator)
↓
Blade View (HTML & Components)
↓
JavaScript Page (AJAX, Modals, AlpineJS)
↓
CSS / Styling Polish (Tailwind v4)
↓
Testing / Verification
```

## Contoh implementasi
### Skenario: Membuat Modul "Supplier" (Modal-based / Simple CRUD)
1. **Database**: Buat tabel `suppliers` menggunakan migrasi Laravel, definisikan model `Supplier.php`.
2. **Generator**: Jalankan perintah `php artisan make:module Supplier --simple --resource`.
3. **Rute**: Daftarkan rute di `routes/web.php` dalam grup middleware `auth`.
4. **Validasi**: Definisikan aturan validasi di `SaveSupplierRequest.php`.
5. **Kueri Data**: Tulis fungsi pembacaan data di `SupplierRepository.php`.
6. **Transformasi**: Definisikan field yang dikembalikan di `SupplierResource.php`.
7. **Controller**: Lengkapi method `index`, `store`, `show`, `update`, `destroy`, `restore`, `forceDelete` di `SupplierController.php`.
8. **UI (Blade)**: Desain layout tabel menggunakan komponen `<x-datatable>` di `resources/views/supplier/index.blade.php`.
9. **Logika JS**: Buat instansiasi Datatable dan bind event CRUD di `resources/js/pages/supplier/list.js`.

## Contoh kode
Berikut adalah panduan menjalankan command generator modul baru:
```bash
# 1. Jika modul menggunakan Full Page CRUD (default):
php artisan make:module Sablon --default

# 2. Jika modul menggunakan Modal-based CRUD (simple):
php artisan make:module Supplier --simple --resource
```

## Hubungan dengan file lain
- Panduan penamaan file/variabel saat membuat modul dapat dilihat di `naming-convention.md`.
- Detail implementasi backend dibahas di `backend.md`, `controller.md`, `request.md`, `repository.md`, dan `resource.md`.
- Detail implementasi frontend dibahas di `frontend.md`, `javascript.md`, `blade.md`, `css.md`, `datatable.md`, dan `cardgrid.md`.

## Checklist
- [ ] Apakah migrasi database sudah dijalankan dan model sudah memiliki `$fillable`?
- [ ] Apakah rute baru sudah didaftarkan di `routes/web.php` dan dilindungi middleware?
- [ ] Apakah otorisasi / Policy Permission (`$this->authorize(...)`) sudah diterapkan pada setiap method controller?
- [ ] Apakah response AJAX di Javascript sudah ditangani oleh wrapper `ApiProvider`?
- [ ] Apakah UI sudah di-inisialisasi ulang menggunakan helper `reinit-ui` setelah manipulasi DOM?

## Best Practice
- **Selalu Gunakan Generator**: Mulailah dengan menjalankan `make:module` kustom Artisan command untuk meminimalkan kesalahan struktur manual.
- **Terapkan Soft Deletes**: Pastikan fitur `restore` dan `forceDelete` didukung oleh model dan kueri repository.
- **Gunakan Transaksi Database**: Untuk data kompleks yang melibatkan beberapa tabel/relasi, pastikan logika di dalam Action dibungkus dalam `DB::transaction(...)`.

## Catatan penting
> [!WARNING]
> Jangan pernah melewatkan otorisasi policy di controller (misal: `$this->authorize('suppliers.create')`). Setiap aksi CRUD backend harus mencocokkan permission spatie yang telah ditentukan di seeder database.
