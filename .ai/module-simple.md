# Simple Module Development Guide (module-simple.md)

## Tujuan
Dokumen ini menjelaskan panduan langkah demi langkah untuk membuat **Modul Simple (Modal-based CRUD)** di proyek ini. Dokumen ini memastikan keseragaman interaksi modal pop-up dan reload data.

## Kapan digunakan
Gunakan dokumen ini setiap kali Anda membangun modul CRUD dengan input data sederhana yang tidak memiliki banyak relasi dinamis.

## Cara kerja
Modul Simple memusatkan semua aksi CRUD pada satu halaman indeks saja (`index.blade.php`). Proses penambahan dan pengeditan data memanfaatkan modal tunggal (`modal.blade.php`) secara pop-up. JavaScript halaman (`index.js`) menangani pembukaan modal, pengisian nilai form via AJAX request, submit data form, penutupan modal, dan reload dinamis DataTable tanpa memicu refresh halaman browser.

## Struktur
### 1. Struktur Folder & Berkas Modul Simple
Jika Anda membuat modul bernama `{Module}`, generator akan menghasilkan berkas-berkas berikut:
- **Backend**:
  - `app/Http/Controllers/{Module}Controller.php` (Mengontrol response index/API data)
  - `app/Repositories/{Module}Repository.php` (Menyediakan query data)
  - `app/Http/Requests/{Module}/Save{Module}Request.php` (Validasi kolom form)
  - `app/Http/Resources/{Module}Resource.php` (Format data JSON)
- **Views (Blade)**:
  - `resources/views/{module}/index.blade.php` (Halaman utama penampil tabel)
  - `resources/views/{module}/form.blade.php` (Berisi input field input)
  - `resources/views/{module}/modal.blade.php` (Kerangka modal pembungkus form)
- **Javascript**:
  - `resources/js/pages/{module}/index.js` (Script pengendali utama CRUD modal)

### 2. Perbandingan Modul Default vs Modul Simple
- **Modul Default (Full Page)**:
  - *Kelebihan*: Cocok untuk input data rumit, baris dinamis (AlpineJS), dan kalkulasi bertingkat.
  - *Kekurangan*: Membutuhkan pemuatan ulang halaman yang memperlambat alur kerja untuk data-data kecil.
- **Modul Simple (Modal-based)**:
  - *Kelebihan*: UX sangat cepat dan mulus karena tidak ada refresh halaman (single-page feel).
  - *Kekurangan*: Sulit dipelihara jika formulir memiliki input bertumpuk dan tabel relasi yang banyak.

## Contoh implementasi
Referensi modul simple standar:
- View halaman utama: `resources/views/category/index.blade.php`
- Modal pembungkus form: `resources/views/category/modal.blade.php`
- Script CRUD client-side: `resources/js/pages/category/index.js`

## Contoh kode
Berikut adalah logika penanganan pembukaan form edit pada modal asinkron di Javascript:
```javascript
const handleEdit = async (id) => {
    setModalTitle(trans("langCrud", "edit_title", { model: modelName }));
    setFormMode("edit", id); // Simpan status mode dan ID target di elemen form dataset

    try {
        // Ambil data detail terupdate dari server
        const response = await ApiProvider.get(route("{modules}.show", id));
        fillForm(response.data); // Isi value field form input
        openModal(); // Buka modal Preline
    } catch (error) {
        console.error("Fetch data error:", error);
        closeModal();
    }
};
```

## Hubungan dengan file lain
- Modul Simple dihasilkan secara instan menggunakan perintah generator kustom `php artisan make:module {ModuleName} --simple --resource`.
- Integrasi tabel dinamis berpedoman langsung pada `datatable.md`.

## Checklist
- [ ] Apakah form di dalam modal dibungkus tag `<form>` dengan atribut `data-mode="create"`?
- [ ] Apakah tombol Close modal memiliki event listener kustom untuk memicu reset form input?
- [ ] Apakah target elemen input menggunakan ID yang cocok dengan selector manipulasi di `index.js`?
- [ ] Apakah fungsi `reloadDatatable()` dipanggil pasca suksesnya submit POST/PUT?

## Best Practice
- **Reset Form pada Close**: Selalu bersihkan input form dan ubah kembali status mode ke `create` ketika modal ditutup (close modal event listener) agar data bekas edit tidak muncul saat user mengklik "Add New".
- **Gunakan API HSOverlay**: Gunakan fungsi global Preline `HSOverlay.open('#modal-id')` dan `HSOverlay.close('#modal-id')` untuk mengontrol visibilitas modal via JS.

## Catatan penting
> [!IMPORTANT]
> Jangan biarkan modal menyimpan data ID sebelumnya. Hapus dataset ID form (`delete form.dataset.id`) setiap kali status form dirubah kembali ke mode `create` untuk mencegah kesalahan rute URL pada proses penyimpanan.

