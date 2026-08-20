# Datatable Integration Guide (datatable.md)

## Tujuan
Dokumen ini menjelaskan panduan lengkap penggunaan komponen tabel dinamis **Datatable** di proyek ini. Panduan ini mencakup penggunaan Blade component, inisialisasi Javascript, konfigurasi opsi, filter slot kustom, skeleton loading, dan format respons backend.

## Kapan digunakan
Gunakan komponen DataTable setiap kali Anda ingin menyusun halaman list data berbasis tabel yang membutuhkan pencarian client-side, pengubahan jumlah halaman, pagination tersemat, filter status, filter tambahan kustom, dan penanganan aksi CRUD.

## Cara kerja
1. **Struktur Blade (`x-datatable`)**: Merender skeleton loading (tampil by default), container tabel (hidden by default), search bar, dropdown length, filter select kustom, slot filter kustom (`<x-slot:filters>`), dan elemen template pagination.
2. **Skeleton Loading (First Load)**: Saat halaman pertama kali dimuat, skeleton animate-pulse langsung tampil menggantikan tabel kosong. Begitu data pertama kali berhasil di-draw, skeleton disembunyikan dan tabel ditampilkan.
3. **Pemuatan Control Locking**: Saat halaman pertama dimuat (`firstDraw === true`), seluruh kontrol — search bar, clear button, dropdown filter, button group (`data-dt-controls`), tombol pagination, dan filter luar (`data-dt-page-filters`) — dikunci (`pointer-events-none opacity-50`) secara otomatis. Setelah draw pertama selesai, semua kontrol aktif kembali.
4. **Pemuatan Client-Side**: Halaman JS memformat baris kolom menggunakan fungsi `render(data, type, row)`. Ini mengeliminasi kebutuhan rendering server-side Yajra.
5. **Custom Pagination**: Datatable menangkap event `draw` dan merender ulang tombol-tombol halaman berdasarkan template HTML (`#dt-pagination-btn-template`, dll) untuk menjaga konsistensi visual Tailwind CSS v4.

## Struktur
### Parameter Komponen Blade (`<x-datatable>`)
- `id`: ID unik untuk element `<table>` (default: `datatable`).
- `search`: Boolean untuk menampilkan search bar (default: `true`).
- `length`: Boolean untuk menampilkan dropdown jumlah baris data (default: `true`).
- `filter`: Boolean untuk menampilkan dropdown filter status (default: `true`).
- `filterId`: ID unik dropdown filter (default: `dt-filter`).
- `filterOptions`: Opsi filter (default: `all`, `active`, `deleted`).
- `defaultLength`: Jumlah baris default (default: `10`). Juga menentukan jumlah baris skeleton yang dirender.
- `<x-slot:filters>`: Slot opsional untuk menyisipkan filter kustom tambahan (seperti `<x-button-group>`).

### Elemen DOM yang digenerate komponen
- `#dt-skeleton-{id}`: Div skeleton loading — tampil by default, disembunyikan setelah draw pertama.
- `#dt-wrapper-{id}`: Wrapper tabel asli — hidden by default, ditampilkan setelah draw pertama.
- `[data-dt-controls="{id}"]`: Wrapper kontrol internal (search, filter, length) — dikunci saat loading.

### Parameter Inisialisasi Javascript (`initDatatable()`)
- `table`: CSS Selector tabel (contoh: `"#items-datatable"`).
- `filterSelector`: CSS Selector dropdown filter (contoh: `"#filter-items"`).
- `onRowClick`: Callback function saat baris diklik (menerima object `row` dan element `this`).
- `rowClickRoute`: Callback function penentu navigasi URL halaman saat baris diklik.
- `ajax`: Object konfigurasi request (`url`, `method`, `data`).
- `columns`: Array berisi mapping properti data dan renderer kolom.

## Contoh implementasi
Penerapan lengkap dapat dipelajari pada berkas:
- View Blade: `resources/views/{module}/index.blade.php`
- Javascript Halaman: `resources/js/pages/{module}/index.js`
- Pembungkus Inisialisasi: `resources/js/utils/datatable.js`
- Komponen Blade: `resources/views/components/datatable.blade.php`

## Contoh kode
### 1. Penggunaan Komponen Blade dengan Slot Filter Kustom:
```blade
<x-datatable id="product-datatable" filterId="filter-product">
    <x-slot:filters>
        <x-button-group id="filter-category" name="category_id" :options="$categories" :all-label="__('product.filter.all_categories')" :multiple="true" />
    </x-slot:filters>

    <thead>
        <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Action</th>
        </tr>
    </thead>
</x-datatable>
```

### 2. Inisialisasi DataTable di Javascript:
```javascript
import initDatatable from "@/utils/datatable";

let datatable = initDatatable({
    table: "#product-datatable",
    filterSelector: "#filter-product",
    onRowClick: (row) => handleEdit(row.id),
    ajax: {
        url: route("products.index"),
        method: "GET",
        dataSrc: "data",
        data: function (d) {
            d.filter = $("#filter-product").val();
            d.category_id = $("#filter-category").val();
        },
    },
    columns: [
        { data: "name", width: "30%" },
        { data: "code", width: "30%" },
        {
            data: "id",
            width: "10%",
            orderable: false,
            render(id) {
                return `<button type="button" class="btn-edit" data-id="${id}">Edit</button>`;
            }
        }
    ]
});
```

### 3. Filter di luar komponen (`data-dt-page-filters`):
Jika ada filter tambahan di luar tag `<x-datatable>` (misalnya datepicker atau tombol reset di header halaman), tambahkan atribut `data-dt-page-filters="<tableId>"` ke wrapper filter tersebut. Filter tersebut akan dikunci otomatis saat loading dan aktif kembali setelah data pertama tampil — **tanpa perubahan JS apapun**.

```blade
{{-- Di luar <x-datatable>, misalnya di header halaman --}}
<div class="flex flex-wrap items-end gap-3" data-dt-page-filters="{tableId}">
    <div>
        <label for="filter-date-range">{{ __('module.filter.date_range') }}</label>
        <x-datepicker id="filter-date-range" name="date_range" mode="range" />
    </div>
    <x-button-loading type="button" id="filter-reset" icon="rotate-ccw" ... />
</div>

<x-datatable id="{tableId}" ...>
    ...
</x-datatable>
```

Nilai `{tableId}` harus **sama persis** dengan `id` yang diberikan pada `<x-datatable>`. Pola ini dapat diterapkan ke modul manapun yang memiliki filter di luar komponen.

### 4. Format JSON Respons Backend yang Diharapkan:
```json
{
  "data": [
    {
      "id": 1,
      "name": "Category A",
      "code": "CAT-01",
      "is_active": true,
      "deleted_at": null
    }
  ]
}
```

## Hubungan dengan file lain
- Data JSON disuplai oleh method `index` di Controller (`controller.md`) dibungkus API Resource (`resource.md`).
- Aksi di dalam tabel (seperti tombol edit/delete) ditangkap di berkas Javascript (`javascript.md`) menggunakan event delegation.

## Checklist
- [ ] Apakah tabel Blade menggunakan komponen `<x-datatable>` dengan ID unik?
- [ ] Apakah fungsi `initDatatable` sudah memuat array `columns` yang cocok dengan properti JSON backend?
- [ ] Apakah properti `onRowClick` atau `rowClickRoute` sudah dideklarasikan dengan aman (mengecualikan button/dropdown klik)?
- [ ] Apakah dropdown filter status & filter tambahan sudah disinkronkan dengan payload AJAX `data: function(d)`?
- [ ] Jika ada filter di luar komponen (datepicker, tombol reset), apakah wrapper-nya sudah diberi atribut `data-dt-page-filters="<tableId>"`?

## Best Practice
- **Batasi Kolom Aksi**: Jangan biarkan baris tabel langsung memicu link navigasi jika user mengklik tombol aksi/dropdown. Pastikan baris klik memverifikasi `e.target.closest('button, a, .hs-dropdown')` (sudah tertangani bawaan di `datatable.js`).
- **Reload Mulus**: Untuk me-refresh data tabel setelah simpan/hapus, gunakan method bawaan `datatable.ajax.reload(null, false)` agar pagination posisi aktif tidak bergeser.
- **Proteksi Loading**: Seluruh kontrol filter di dalam kontainer `data-dt-controls` akan dikunci secara otomatis saat memuat data pertama kali. Filter luar cukup diberi `data-dt-page-filters`.
- **Jangan tambahkan `processing: true`**: Spinner bawaan DataTables sudah dihapus karena digantikan oleh Skeleton Loading. Jangan aktifkan kembali tanpa mematikan skeleton.

## Catatan penting
> [!IMPORTANT]
> Jangan pernah menggunakan plugin inisialisasi DataTable mentah milik jQuery (`$(...).DataTable()`). Selalu gunakan wrapper `initDatatable(...)` dari `resources/js/utils/datatable.js` agar skeleton loading, gaya pagination Tailwind CSS v4, input search clear, lock control otomatis saat loading, dan ikon Lucide tetap ter-render secara konsisten.

> [!WARNING]
> Skeleton loading bekerja dengan memanfaatkan `firstDraw` flag internal. Jangan memanggil `datatable.draw()` secara manual di luar konteks reload AJAX, karena skeleton tidak akan muncul kembali setelah draw pertama selesai.
