# CardGrid Integration Guide (cardgrid.md)

## Tujuan
Dokumen ini menjelaskan panduan lengkap penggunaan komponen grid kartu dinamis **CardGrid** di proyek **AN Mastery V3**. Panduan ini mencakup penggunaan Blade component, inisialisasi Javascript, konfigurasi opsi, dan format respons backend.

## Kapan digunakan
Gunakan komponen CardGrid setiap kali Anda merancang halaman daftar data kompleks yang menampilkan gambar, detail berjenjang, status berwarna, atau visualisasi yang lebih kaya daripada sekadar tabel biasa (misal: modul Sablon, Image Fabric).

## Cara kerja
1. **Struktur Blade (`x-cardgrid`)**: Merender container grid, search input, filter dropdown, loading overlay, template penomoran halaman, dan area kosong data (*empty state*).
2. **Pemicu Javascript (`initCardgrid`)**: Melakukan pengambilan data asinkron via `fetch()` API secara berkala ke URL backend berdasarkan halaman aktif dan filter.
3. **HTML Templating**: Javascript halaman menyediakan callback `renderCard(item)` yang mengembalikan string HTML kustom untuk dirender sebagai anak elemen grid.
4. **Loading & Empty State**: Mengotomatiskan transisi opasitas kontainer saat memuat data (`scale-[0.99] opacity-40`) dan mendeteksi ketiadaan baris data untuk memunculkan pesan "No data found".

## Struktur
### Parameter Komponen Blade (`<x-cardgrid>`)
- `id`: ID kontainer grid utama (default: `cardgrid`).
- `search`: Boolean untuk menampilkan input pencarian (default: `true`).
- `length`: Boolean untuk menampilkan opsi jumlah data (default: `true`).
- `filter`: Boolean untuk menampilkan dropdown status filter (default: `true`).
- `filterId`: ID kustom dropdown filter (default: `cg-filter`).
- `filterOptions`: Kumpulan opsi filter status.
- `defaultLength`: Jumlah kartu default per halaman (default: `12`).

### Parameter Inisialisasi Javascript (`initCardgrid()`)
- `containerId`: Selector ID kontainer kartu (contoh: `"#sablon-cardgrid"`).
- `filterSelector`: Selector filter status dropdown (contoh: `"#filter-sablon"`).
- `ajax`: Object rute backend. Memiliki properti `url` dan method `data()` untuk menyuplai parameter tambahan (seperti nomor minggu).
- `renderCard`: Fungsi callback yang mengembalikan template string HTML kartu.
- `pageLength`: Jumlah halaman default (contoh: `12`).
- `cardClickRoute`: Aksi rute navigasi halaman ketika kartu diklik.

## Contoh implementasi
Penerapan lengkap dapat dipelajari pada berkas:
- View Blade: [index.blade.php](file:///d:/laragon/www/an-mastery-v3/resources/views/sablon/index.blade.php)
- Javascript Halaman: [list.js](file:///d:/laragon/www/an-mastery-v3/resources/js/pages/sablon/list.js)
- Pembungkus Inisialisasi: [cardgrid.js](file:///d:/laragon/www/an-mastery-v3/resources/js/utils/cardgrid.js)
- Komponen Blade: [cardgrid.blade.php](file:///d:/laragon/www/an-mastery-v3/resources/views/components/cardgrid.blade.php)

## Contoh kode
### 1. Inisialisasi CardGrid di Javascript:
```javascript
import initCardgrid from "@/utils/cardgrid";

let cardgrid = initCardgrid({
    containerId: "#sablon-cardgrid",
    filterSelector: "#filter-sablon",
    ajax: {
        url: route("sablons.index"),
        data: function () {
            return { week_of: "2026-W30" }; // Parameter kustom
        },
    },
    renderCard: function (item) {
        return `
            <div class="p-4 bg-white rounded-xl shadow">
                <h3 class="font-bold">${item.supplier?.name}</h3>
                <p>Status: ${item.status}</p>
            </div>
        `;
    },
    pageLength: 12,
    cardClickRoute: (row) => route("sablons.edit", row.id)
});
```

### 2. Format JSON Respons Backend yang Diharapkan (Mendukung Pagination Standard):
```json
{
  "data": [
    { "id": 1, "status": "DONE", "supplier": { "name": "Supplier Utama" } }
  ],
  "meta": {
    "total": 1,
    "last_page": 1
  }
}
```

## Hubungan dengan file lain
- Data disuplai dari controller via `SablonResource::collection(...)` yang menyertakan eager-loading relasi (`resource.md`).
- Interaksi tombol di dalam kartu (seperti edit, hapus, restore) ditangkap di berkas Javascript (`javascript.md`) menggunakan delegasi dokumen.

## Checklist
- [ ] Apakah komponen Blade `<x-cardgrid>` memiliki ID yang sinkron dengan inisialisasi Javascript?
- [ ] Apakah fungsi `renderCard(item)` sudah menangani kemungkinan properti bernilai `null` agar tidak terjadi error javascript?
- [ ] Apakah target klik tombol di dalam kartu (misal tombol status/delete) dikecualikan agar tidak memicu navigasi `cardClickRoute`?
- [ ] Apakah respons dari backend sudah mengembalikan metadata pagination (`meta.total` atau `total`)?

## Best Practice
- **Isolasi Wrapper Tombol**: Gunakan `e.target.closest('button, a, .btn-delete')` di dalam helper klik `cardgrid.js` untuk mencegah bentrokan aksi navigasi kartu saat pengguna mengklik tombol di dalam kartu.
- **Dukungan Re-Inisialisasi**: Panggil helper `initLucide()` dan `window.HSStaticMethods.autoInit()` di akhir daur rendering kartu agar semua ikon Lucide dan tooltip Preline UI tetap aktif.

## Catatan penting
> [!IMPORTANT]
> Kontainer kartu yang dirender oleh `renderCard` akan dibungkus secara otomatis oleh `cardgrid.js` ke dalam sebuah div pembungkus berkelas `.cg-card-wrapper` dengan properti `data-id="${item.id}"`. Gunakan kelas pembungkus ini jika Anda ingin melakukan kustomisasi padding tambahan pada grid.
