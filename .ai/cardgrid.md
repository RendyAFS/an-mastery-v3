# CardGrid Integration Guide (cardgrid.md)

## Tujuan
Dokumen ini menjelaskan panduan lengkap penggunaan komponen grid kartu dinamis **CardGrid** di proyek ini. Panduan ini mencakup penggunaan Blade component, inisialisasi Javascript, skeleton loading, konfigurasi opsi, dan format respons backend.

## Kapan digunakan
Gunakan komponen CardGrid setiap kali Anda merancang halaman daftar data kompleks yang menampilkan gambar, detail berjenjang, status berwarna, atau visualisasi yang lebih kaya daripada sekadar tabel biasa.

## Cara kerja
1. **Struktur Blade (`x-cardgrid`)**: Merender container grid, search input, filter dropdown, **skeleton loading grid** (tampil saat first load), loading overlay (untuk reload berikutnya), template penomoran halaman, dan area kosong data (*empty state*).
2. **Skeleton Loading (First Load)**: Saat halaman pertama kali dibuka, skeleton `animate-pulse` langsung tampil sebelum data datang. Setelah data pertama berhasil dirender, skeleton disembunyikan dan grid kartu dimunculkan.
3. **Loading Overlay (Subsequent Loads)**: Untuk request berikutnya (filter, pagination, search), skeleton tidak muncul lagi — melainkan overlay transparan dengan spinner kecil yang ditampilkan di atas kartu-kartu yang sudah ada.
4. **Pemicu Javascript (`initCardGrid`)**: Melakukan pengambilan data asinkron via `fetch()` API secara berkala ke URL backend berdasarkan halaman aktif dan filter.
5. **HTML Templating**: Javascript halaman menyediakan callback `renderCard(item)` yang mengembalikan string HTML kustom untuk dirender sebagai anak elemen grid.
6. **Pemuatan Control Locking**: Selama data loading, seluruh kontrol (search, filter, length, pagination, button group di `data-cg-controls`) dikunci (`pointer-events-none opacity-50`). Filter di luar komponen cukup diberi atribut `data-cg-page-filters`.

## Struktur
### Parameter Komponen Blade (`<x-cardgrid>`)
- `id`: ID kontainer grid utama (default: `cardgrid`).
- `search`: Boolean untuk menampilkan input pencarian (default: `true`).
- `length`: Boolean untuk menampilkan opsi jumlah data (default: `true`).
- `filter`: Boolean untuk menampilkan dropdown status filter (default: `true`).
- `filterId`: ID kustom dropdown filter (default: `cg-filter`).
- `filterOptions`: Kumpulan opsi filter status.
- `filterDefault`: Nilai filter default (default: `active`).
- `defaultLength`: Jumlah kartu default per halaman (default: `12`). Juga menentukan jumlah kartu skeleton.
- `filtersInline`: Boolean, jika `true` slot `filters` ditampilkan sebaris di baris pertama (default: `false` = baris terpisah).
- `<x-slot:filters>`: Slot opsional untuk filter kustom tambahan (seperti `<x-button-group>`).

### Elemen DOM yang digenerate komponen
- `#{id}-skeleton`: Div skeleton loading — tampil saat first load, disembunyikan setelah data pertama datang.
- `#{id}-loading`: Div overlay spinner — untuk loading berikutnya setelah kartu sudah ada.
- `#{id}`: Grid container utama — tempat kartu dirender.
- `#{id}-empty`: Div empty state — tampil jika hasil 0.
- `[data-cg-controls="{id}"]`: Wrapper kontrol internal — dikunci saat loading.

### Parameter Inisialisasi Javascript (`initCardGrid()`)
- `containerId`: Selector ID kontainer kartu (contoh: `"#product-cardgrid"`).
- `filterSelector`: Selector filter status dropdown (contoh: `"#filter-product"`).
- `ajax`: Object rute backend. Memiliki properti `url` dan method `data()` untuk menyuplai parameter tambahan.
- `renderCard`: Fungsi callback yang mengembalikan template string HTML kartu.
- `pageLength`: Jumlah halaman default (contoh: `12`).
- `cardClickRoute`: Aksi rute navigasi halaman ketika kartu diklik.

## Contoh implementasi
Penerapan lengkap dapat dipelajari pada berkas:
- View Blade: `resources/views/{module}/index.blade.php`
- Javascript Halaman: `resources/js/pages/{module}/index.js`
- Pembungkus Inisialisasi: `resources/js/utils/cardgrid.js`
- Komponen Blade: `resources/views/components/cardgrid.blade.php`

## Contoh kode
### 1. Inisialisasi CardGrid di Javascript:
```javascript
import initCardGrid from "@/utils/cardgrid";

let cardgrid = initCardGrid({
    containerId: "#product-cardgrid",
    filterSelector: "#filter-product",
    ajax: {
        url: route("products.index"),
        data: function () {
            return { category_id: 1 }; // Parameter kustom
        },
    },
    renderCard: function (item) {
        return `
            <div class="p-4 bg-white rounded-xl shadow">
                <h3 class="font-bold">${item.name}</h3>
                <p>Status: ${item.status}</p>
            </div>
        `;
    },
    pageLength: 12,
    cardClickRoute: (row) => route("products.edit", row.id)
});
```

### 2. Penggunaan Blade dengan Filter Inline:
```blade
<x-cardgrid id="product-cardgrid" filterId="filter-product" :defaultLength="48"
    :lengthOptions="[12, 24, 48]" :filtersInline="false">
    <x-slot:filters>
        <x-button-group id="filter-category" name="category_id"
            :options="$categories" :all-label="__('product.filter.all_categories')"
            :multiple="true" />
    </x-slot:filters>
</x-cardgrid>
```

### 3. Filter di luar komponen (`data-cg-page-filters`):
Jika ada filter tambahan di luar tag `<x-cardgrid>` (misalnya datepicker atau tombol reset di header halaman), tambahkan atribut `data-cg-page-filters="<gridId>"` ke wrapper filter tersebut. Filter tersebut akan dikunci otomatis saat loading — **tanpa perubahan JS apapun**.

```blade
{{-- Di luar <x-cardgrid>, misalnya di header halaman --}}
<div class="flex flex-wrap items-end gap-3" data-cg-page-filters="{gridId}">
    <div>
        <label for="filter-date-range">{{ __('module.filter.date_range') }}</label>
        <x-datepicker id="filter-date-range" name="date_range" mode="range" />
    </div>
    <x-button-loading type="button" id="filter-reset" icon="rotate-ccw" ... />
</div>

<x-cardgrid id="{gridId}" ...>
    ...
</x-cardgrid>
```

Nilai `{gridId}` harus **sama persis** dengan `id` yang diberikan pada `<x-cardgrid>`. Pola ini dapat diterapkan ke modul manapun yang memiliki filter di luar komponen.

### 4. Format JSON Respons Backend yang Diharapkan (Mendukung Pagination Standard):
```json
{
  "data": [
    { "id": 1, "name": "Product A", "status": "ACTIVE" }
  ],
  "meta": {
    "total": 1,
    "last_page": 1
  }
}
```

## Hubungan dengan file lain
- Data disuplai dari controller via `ProductResource::collection(...)` yang menyertakan eager-loading relasi (`resource.md`).
- Interaksi tombol di dalam kartu (seperti edit, hapus, restore) ditangkap di berkas Javascript (`javascript.md`) menggunakan delegasi dokumen (event delegation via `document.addEventListener`).

## Checklist
- [ ] Apakah komponen Blade `<x-cardgrid>` memiliki ID yang sinkron dengan `containerId` di inisialisasi Javascript?
- [ ] Apakah fungsi `renderCard(item)` sudah menangani kemungkinan properti bernilai `null` agar tidak terjadi error javascript?
- [ ] Apakah target klik tombol di dalam kartu (misal tombol status/delete) dikecualikan agar tidak memicu navigasi `cardClickRoute`?
- [ ] Apakah respons dari backend sudah mengembalikan metadata pagination (`meta.total` atau `total`)?
- [ ] Jika ada filter di luar komponen (datepicker, tombol reset), apakah wrapper-nya sudah diberi atribut `data-cg-page-filters="<gridId>"`?

## Best Practice
- **Isolasi Wrapper Tombol**: Gunakan `e.target.closest('button, a, .btn-delete')` di dalam helper klik `cardgrid.js` untuk mencegah bentrokan aksi navigasi kartu saat pengguna mengklik tombol di dalam kartu.
- **Dukungan Re-Inisialisasi**: Panggil helper `initLucide()` dan `window.HSStaticMethods.autoInit()` di akhir daur rendering kartu agar semua ikon Lucide dan tooltip Preline UI tetap aktif.
- **Filter Luar Komponen**: Tandai wrapper filter luar dengan `data-cg-page-filters="<gridId>"` — bukan dengan memodifikasi `cardgrid.js` atau `filterSelector`. `filterSelector` hanya untuk dropdown filter status yang sudah ada di dalam komponen.

## Catatan penting
> [!IMPORTANT]
> Kontainer kartu yang dirender oleh `renderCard` akan dibungkus secara otomatis oleh `cardgrid.js` ke dalam sebuah div pembungkus berkelas `.cg-card-wrapper` dengan properti `data-id="${item.id}"`. Gunakan kelas pembungkus ini jika Anda ingin melakukan kustomisasi padding tambahan pada grid.

> [!IMPORTANT]
> Skeleton loading otomatis menyesuaikan jumlah kartu dengan nilai `defaultLength`. Jika `defaultLength` diset ke nilai besar (misalnya 48), skeleton akan menampilkan maksimal 8 kartu placeholder agar tidak memenuhi layar.
