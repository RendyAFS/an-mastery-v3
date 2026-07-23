# JavaScript Standards (javascript.md)

## Tujuan
Dokumen ini menjelaskan standar penulisan kode JavaScript di proyek **AN Mastery V3** untuk memastikan kebersihan kode, keamanan ruang lingkup (*scope isolation*), standardisasi pemicuan AJAX, dan integrasi komponen interaktif UI.

## Kapan digunakan
Gunakan panduan ini setiap kali Anda membuat file JS baru di folder `resources/js/pages/`, menulis interaksi DOM, memicu AJAX, mengontrol inisialisasi Preline UI, atau memproses input rupiah.

## Cara kerja
1. **Scope Isolation**: JavaScript per halaman dibungkus dalam modul IIFE kustom (`PageScript`) agar tidak mencemari objek global browser (`window`).
2. **Pemicu Awal**: Modul dieksekusi di dalam blok pemicu jQuery `$(function() { PageScript.init(); })` setelah dokumen HTML selesai dimuat (*DOM Ready*).
3. **Event Delegation**: Untuk berinteraksi dengan baris tabel dinamis yang sering berubah, event binding dipasang menggunakan delegasi dokumen `$(document).on(...)`.
4. **AJAX Standard**: Semua request asinkron diproses menggunakan Axios client terstandar `ApiProvider` untuk otomatisasi loader layar dan penanganan error terpusat.
5. **AlpineJS Bindings**: Modul interaksi baris formulir kompleks menggunakan inisialisasi reaktivitas AlpineJS yang dihubungkan dengan daur hidup PageScript.

## Struktur
Setiap halaman JavaScript standar (`list.js` atau `form.js`) terstruktur sebagai berikut:
- **Bagian Import**: Memuat dependensi global kustom (misal: `ApiProvider`, `initDatatable`, `normalizeFormInputs`).
- **Deklarasi PageScript**: Fungsi modul IIFE utama.
  - Deklarasi state internal halaman (misal: variabel datatable, parameter filter).
  - Deklarasi helper rendering internal halaman (misal: `renderCard`, `reloadDatatable`).
  - Fungsi submit form (`submitForm`) dibungkus blok `try-catch-finally` untuk memastikan loading state berhenti.
  - Fungsi penanganan aksi (`handleCreate`, `handleEdit`, `handleDelete`).
  - Fungsi binding event DOM (`bindEvents`).
  - Pengembalian method publik (`return { init() { ... } }`).
- **DOM Ready Listener**: Memicu `PageScript.init()`.

## Contoh implementasi
Implementasi nyata Javascript untuk modul standard dan asinkron:
- Kode Halaman Supplier: [list.js](file:///d:/laragon/www/an-mastery-v3/resources/js/pages/supplier/list.js)
- Pustaka AJAX Provider: [api-provider.js](file:///d:/laragon/www/an-mastery-v3/resources/js/utils/api-provider.js)

## Contoh kode
Berikut adalah pola standar PageScript untuk aksi asinkron di halaman:
```javascript
import ApiProvider from "@/utils/api-provider";
import { startLoading, stopLoading } from "@/utils/button-loading";

const PageScript = (function () {
    let form;

    const handleDelete = async (id) => {
        const confirmed = await Confirm.delete("Hapus data ini?");
        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("suppliers.destroy", id));
            Toast.success("Success", "Data deleted successfully");
            // Pemicu reload datatable atau grid
        } catch (error) {
            console.error("Delete error:", error);
        }
    };

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", function () {
            handleDelete($(this).data("id"));
        });
    };

    return {
        init() {
            form = document.getElementById("my-form");
            bindEvents();
        }
    };
})();

$(function () {
    PageScript.init();
});
```

## Hubungan dengan file lain
- JavaScript ini dikaitkan ke dalam halaman Blade (`blade.md`) via direktif `@vite('path/to/script.js')`.
- JavaScript berkoordinasi erat dengan komponen pembantu dinamis `datatable.md` dan `cardgrid.md`.

## Checklist
- [ ] Apakah seluruh kode Javascript ditaruh di dalam struktur modular IIFE `PageScript`?
- [ ] Apakah event handler untuk aksi dalam tabel/grid menggunakan delegasi dokumen `$(document).on(...)`?
- [ ] Apakah fungsi `submitForm` Anda telah dibungkus blok `try-catch-finally` yang menghentikan loading tombol (`stopLoading`)?
- [ ] Apakah format rupiah pada formulir telah dinormalisasi menggunakan `normalizeFormInputs()` sebelum data dikirim ke API?

## Best Practice
- **Gunakan Global Toast/Confirm**: Manfaatkan API global yang sudah terintegrasi seperti `Toast.success(title, msg)`, `Toast.error(title, msg)`, `Confirm.show(msg, title)`, dan `Confirm.delete(msg)`.
- **Loading UI Indicator**: Selalu manfaatkan helper loading dinamis `startLoading(button)` dan `stopLoading(button)` pada tombol kirim form untuk mencegah double click submit.

## Catatan penting
> [!IMPORTANT]
> Jangan pernah menggunakan fungsi `alert()` bawaan browser atau pustaka modal luar. Semua dialog konfirmasi penghapusan wajib menggunakan promise `Confirm.delete()` atau `Confirm.show()` yang terintegrasi dengan desain sistem proyek ini.
