# Frontend Architecture Guide (frontend.md)

## Tujuan
Dokumen ini menjelaskan struktur arsitektur, modul pemrosesan, dan standardisasi pengembangan frontend (Vite, CSS Tailwind v4, jQuery/Datatables, AlpineJS, Preline UI) pada proyek **AN Mastery V3**.

## Kapan digunakan
Gunakan panduan ini setiap kali Anda membangun antarmuka halaman baru, menulis kode JavaScript client-side, menghubungkan AJAX dengan backend, melakukan inisialisasi modul UI Preline, atau memodifikasi gaya CSS.

## Cara kerja
Frontend proyek ini dibangun secara hibrida di atas template Laravel Blade:
1. **Inisialisasi Halaman**: Menggunakan pola **PageScript** berupa IIFE (*Immediately Invoked Function Expression*) yang dibungkus di dalam jQuery `$(function() { PageScript.init(); })` untuk memastikan isolasi ruang lingkup (*scope*).
2. **Komunikasi AJAX**: Menggunakan wrapper Axios bernama `ApiProvider` untuk standardisasi *request headers*, *CSRF token*, global loader, dan penanganan kesalahan global secara otomatis (termasuk validasi error).
3. **Reaktivitas Form Dinamis**: Untuk modul form yang memiliki tabel dinamis, hitung otomatis, dan input bertingkat (seperti modul Sablon), proyek menggunakan **AlpineJS** yang diintegrasikan langsung dengan pembungkus PageScript.
4. **Komponen UI Interaktif**: Menggunakan **Preline UI** untuk komponen tab, modal (`HSOverlay`), select option kustom (`HSSelect`), dan dropdown.

## Struktur
### Folder Aset JS & CSS
- `resources/js/pages/[kebab-case]/`: Berisi berkas JS spesifik halaman (misal: `list.js`, `form.js`, `alpine-component.js`).
- `resources/js/utils/`: Berisi berkas utilitas global, antara lain:
  - `api-provider.js`: Klien AJAX Axios terstandar.
  - `datatable.js`: Pembungkus inisialisasi jQuery DataTables.
  - `cardgrid.js`: Pembungkus inisialisasi grid asinkron untuk daftar kartu (*cards*).
  - `custom-alert.js`: Inisialisasi global `Toast`, `Alert`, dan `Confirm` berbasis AlpineJS.
  - `reinit-ui.js`: Utilitas untuk memicu kembali deteksi elemen Preline UI setelah perubahan DOM secara dinamis.
  - `normalize-form.js`: Menghilangkan format rupiah/angka dari form payload sebelum dikirim ke backend.
  - `week.js`: Pustaka manipulasi tanggal dan filter nomor minggu (*Week Of*).

## Contoh implementasi
Inisialisasi dasar script halaman menggunakan module pattern:
- Berkas JS: [list.js](file:///d:/laragon/www/an-mastery-v3/resources/js/pages/supplier/list.js)
- Pemicu AJAX: [api-provider.js](file:///d:/laragon/www/an-mastery-v3/resources/js/utils/api-provider.js)

## Contoh kode
Berikut adalah contoh struktur inisialisasi UI di sisi client saat halaman dimuat:
```javascript
import ApiProvider from "@/utils/api-provider";
import reInitUi from "@/utils/reinit-ui";

const PageScript = (function () {
    const bindEvents = () => {
        $(document).on("click", ".btn-action", async function () {
            const id = $(this).data("id");
            // Eksekusi AJAX
            const res = await ApiProvider.get(route("suppliers.show", id));
            console.log(res.data);
            reInitUi(); // Re-init Preline UI dropdowns/overlays jika DOM berubah
        });
    };

    return {
        init() {
            bindEvents();
        }
    };
})();

$(function () {
    PageScript.init();
});
```

## Hubungan dengan file lain
- Panduan sintaks JS detail dibahas di `javascript.md`.
- Desain sistem styling dengan Tailwind v4 dijelaskan di `css.md`.
- Dokumentasi pemanfaatan tabel dinamis dibahas di `datatable.md` dan `cardgrid.md`.

## Checklist
- [ ] Apakah semua kode JS halaman Anda dibungkus menggunakan IIFE `PageScript`?
- [ ] Apakah semua pemicu AJAX menggunakan `ApiProvider` alih-alih `fetch` atau `axios` mentah secara langsung?
- [ ] Apakah Anda telah memanggil `reInitUi()` jika baru saja melakukan rendering elemen baru secara dinamis via JS?

## Best Practice
- **Isolasi State**: Jangan mencemari ruang lingkup global (*window scope*) kecuali untuk helper global yang sudah ditentukan seperti `Toast`, `Alert`, dan `Confirm`.
- **Event Delegation**: Selalu gunakan event delegation dengan jQuery `$(document).on('click', '.selector', ...)` untuk elemen dinamis yang dirender di dalam Datatable atau CardGrid.
- **Pembersihan Form**: Gunakan `normalizeFormInputs` untuk membersihkan payload dari format rupiah sebelum dikirim ke server.

## Catatan penting
> [!IMPORTANT]
> Proyek ini menggunakan **Preline UI v3**. Inisialisasi komponen drop-down dan select kustom ditangani menggunakan inisialisasi otomatis (`HSStaticMethods.autoInit()` atau instance kustom). Pastikan Anda memanggil inisialisasi ulang ini jika terjadi modifikasi DOM.
