# Repository Pattern Standards (repository.md)

## Tujuan
Dokumen ini menjelaskan standar implementasi **Repository Pattern** di backend proyek ini. Pola ini mengisolasi logika query basis data agar tidak tersebar di controller maupun model.

## Kapan digunakan
Gunakan panduan ini setiap kali Anda menulis query pembacaan data, manipulasi pengambilan data bertingkat (`with` relationships), filter pencarian, pagination, atau seleksi data opsi drop-down.

## Cara kerja
1. **Isolasi Logika Query**: Repository hanya bertugas memproses pembacaan data (`SELECT`) dan pencarian data dari database. Logika bisnis penyimpanan (`INSERT`/`UPDATE`) dilarang dimasukkan di sini.
2. **Pencarian & Filter**: Semua query pencarian kata kunci dan filter dinamis (seperti status aktif, status terhapus, filter rentang waktu minggu) dideklarasikan di dalam kelas Repository.
3. **Pemuatan Eager**: Relasi tabel kueri harus dimuat langsung menggunakan eager loading (`with([...])`) di level Repository untuk mencegah kendala N+1 Query.

## Struktur
Setiap kelas Repository terletak di bawah folder `app/Repositories/` dan dinamai dengan format `{ModuleName}Repository.php`. Method standard yang biasa disediakan meliputi:
- `getAll(string $filter, ?string $search, int $perPage, ...)`: Mengembalikan data listing dinamis lengkap beserta filter status (active, deleted, all).
- `getDataSelect(?string $search, ?int $id, int $limit, int $page)`: Mengolah pagination data ringan untuk keperluan pencarian AJAX drop-down pilihan select.
- `findWithDetails(Model $model)`: Memuat kembali instansi model beserta seluruh eager-loaded relasinya untuk halaman detail atau form edit.

## Contoh implementasi
Referensi repository yang sudah ada di proyek ini:
- Repository Simple (tanpa relasi): [SupplierRepository.php](file:///d:/laragon/www/an-mastery-v3/app/Repositories/SupplierRepository.php)
- Repository Kompleks (dengan relasi): [SablonRepository.php](file:///d:/laragon/www/an-mastery-v3/app/Repositories/SablonRepository.php)

## Contoh kode
Berikut adalah contoh implementasi pencarian dan data select dinamis di Repository (ganti `{Module}` dengan nama modul Anda):
```php
namespace App\Repositories;

use App\Models\{Module};

class {Module}Repository
{
    public function getAll($filter = 'active')
    {
        $query = {Module}::query()->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }

        return $query->get();
    }

    public function getDataSelect(?string $search = null, ?int $id = null, int $limit = 10, int $page = 1)
    {
        return {Module}::query()
            ->select('id', 'name')
            ->when($id, fn($query) => $query->whereKey($id))
            ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate($limit, ['*'], 'page', $page);
    }

    public function findWithDetails({Module} $model): {Module}
    {
        return $model->loadMissing(['relation1', 'relation2']);
    }
}
```

## Hubungan dengan file lain
- Repository di-instansiasi di Controller (`controller.md`) via Constructor Injection.
- Model data yang dimuat oleh Repository dipetakan ke format JSON menggunakan API Resource (`resource.md`).

## Checklist
- [ ] Apakah kueri pembacaan data Anda sudah dipindahkan ke kelas Repository baru?
- [ ] Apakah kueri relational tabel Anda sudah ditangani menggunakan `with(...)` (Eager Loading)?
- [ ] Apakah filter `active`, `deleted` (soft-deleted), dan `all` sudah ditangani di method `getAll`?
- [ ] Apakah nama berkas menggunakan akhiran `Repository.php` (misal: `FabricRepository.php`)?

## Best Practice
- **Separation of Concerns**: Jangan menulis logika penyimpanan (`save`, `update`, `delete`, `DB::transaction`) di dalam Repository. Logika tersebut ditangani oleh Controller (jika sederhana) atau Action Class (jika kompleks).
- **Return Query/Eloquent Builder**: Untuk konsistensi, kembalikan instansi Eloquent Collection, Paginated Collection, atau instansi Eloquent Model dari method Repository Anda.

## Catatan penting
> [!IMPORTANT]
> Proyek ini menerapkan fitur **Soft Deletes**. Pastikan Anda selalu menangani filter penanganan data yang terhapus dengan memanfaatkan method Eloquent `onlyTrashed()` dan `withTrashed()` di dalam method query `getAll()`.
