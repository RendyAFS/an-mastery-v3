# Datatable Integration Guide (datatable.md)

## Tujuan
Dokumen ini menjelaskan panduan lengkap penggunaan komponen tabel dinamis **Datatable** di proyek ini. Panduan ini mencakup penggunaan Blade component, inisialisasi Javascript, konfigurasi opsi, filter slot kustom, dan format respons backend.

## Kapan digunakan
Gunakan komponen DataTable setiap kali Anda ingin menyusun halaman list data berbasis tabel yang membutuhkan pencarian client-side, pengubahan jumlah halaman, pagination tersemat, filter status, filter tambahan kustom, dan penanganan aksi CRUD.

## Cara kerja
1. **Struktur Blade (`x-datatable`)**: Merender container tabel, search bar, dropdown length, filter select kustom, slot filter kustom (`<x-slot:filters>`), dan elemen template pagination.
2. **Pemuatan Control Locking**: Saat request fetching/loading berjalan, seluruh input pencarian, tombol clear, dropdown filter, button group filter tambahan (`data-dt-controls`), dan tombol pagination akan otomatis di-disable (`pointer-events-none opacity-50`) agar tidak menggangu kueri.
3. **Pemuatan Client-Side**: Halaman JS memformat baris kolom menggunakan fungsi `render(data, type, row)`. Ini mengeliminasi kebutuhan rendering server-side Yajra.
4. **Custom Pagination**: Datatable menangkap event `draw` dan merender ulang tombol-tombol halaman berdasarkan template HTML (`#dt-pagination-btn-template`, dll) untuk menjaga konsistensi visual Tailwind CSS v4.

## Struktur
### Parameter Komponen Blade (`<x-datatable>`)
- `id`: ID unik untuk element `<table>` (default: `datatable`).
- `search`: Boolean untuk menampilkan search bar (default: `true`).
- `length`: Boolean untuk menampilkan dropdown jumlah baris data (default: `true`).
- `filter`: Boolean untuk menampilkan dropdown filter status (default: `true`).
- `filterId`: ID unik dropdown filter (default: `dt-filter`).
- `filterOptions`: Opsi filter (default: `all`, `active`, `deleted`).
- `defaultLength`: Jumlah baris default (default: `10`).
- `<x-slot:filters>`: Slot opsional untuk menyisipkan filter kustom tambahan (seperti `<x-button-group>`).

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

### 3. Format JSON Respons Backend yang Diharapkan:
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

## Best Practice
- **Batasi Kolom Aksi**: Jangan biarkan baris tabel langsung memicu link navigasi jika user mengklik tombol aksi/dropdown. Pastikan baris klik memverifikasi `e.target.closest('button, a, .hs-dropdown')` (sudah tertangani bawaan di `datatable.js`).
- **Reload Mulus**: Untuk me-refresh data tabel setelah simpan/hapus, gunakan method bawaan `datatable.ajax.reload(null, false)` agar pagination posisi aktif tidak bergeser.
- **Proteksi Loading**: Seluruh kontrol filter di dalam kontainer `data-dt-controls` akan dikunci secara otomatis saat memuat data.

## Catatan penting
> [!IMPORTANT]
> Jangan pernah menggunakan plugin inisialisasi DataTable mentah milik jQuery (`$(...).DataTable()`). Selalu gunakan wrapper `initDatatable(...)` dari `resources/js/utils/datatable.js` agar gaya pagination Tailwind CSS v4, input search clear, lock control otomatis saat loading, dan ikon Lucide tetap ter-render secara konsisten.
