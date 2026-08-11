# Controller Standards (controller.md)

## Tujuan
Dokumen ini menjelaskan standar implementasi HTTP Controller di proyek ini. Standar ini mencakup struktur dasar, penyuntikan dependensi, pemeriksaan otorisasi, penanganan AJAX response, dan pendelegasian proses penulisan data.

## Kapan digunakan
Gunakan dokumen ini setiap kali Anda membuat controller baru atau menambahkan method aksi pada controller yang sudah ada untuk memastikan keselarasan arsitektur.

## Cara kerja
Controller bertindak sebagai pengontrol alur (*flow coordinator*):
1. **Suntikan Dependensi**: Repository disuntikkan secara otomatis melalui constructor controller.
2. **Otorisasi**: Izin akses diverifikasi menggunakan `$this->authorize('permission_name')` di awal setiap aksi method.
3. **Pemberian Respon Ganda**:
   - Jika request meminta JSON (`expectsJson()`), controller mengembalikan `JsonResource` untuk dikonsumsi AJAX client-side.
   - Jika rute diakses langsung dari browser, controller mengembalikan view Blade.
4. **Pembagian Proses Penulisan**:
   - Operasi penulisan sederhana langsung menggunakan kueri Eloquent (misal: `Supplier::create(...)`).
   - Operasi penulisan kompleks didelegasikan ke kelas Action (misal: `SaveSablonAction`).

## Struktur
Berikut adalah susunan method yang terstandarisasi dalam controller resource:
- `__construct()`: Menyuntikkan kelas Repository.
- `index()`: Mengembalikan View utama atau data JSON Resource (jika `expectsJson()`).
- `create()`: Mengembalikan View halaman pembuatan (jika menggunakan modul tipe Default).
- `store()`: Menerima request validasi, memproses penyimpanan, dan mengembalikan JSON Resource data baru.
- `show()`: Mengembalikan detail satu data spesifik dibungkus JSON Resource.
- `edit()`: Mengembalikan View halaman edit yang diisi data pendukung dari repository.
- `update()`: Menerima data baru, memproses pembaruan, dan mengembalikan JSON Resource data terupdate.
- `destroy()`: Menghapus data secara soft-delete, mengembalikan `response()->noContent()`.
- `restore()`: Memulihkan data yang terhapus secara soft-delete.
- `forceDelete()`: Menghapus data secara permanen dari database.

## Contoh implementasi
Referensi controller yang sudah ada di proyek ini:
- Controller Simple CRUD (modal): [SupplierController.php](file:///d:/laragon/www/an-mastery-v3/app/Http/Controllers/SupplierController.php)
- Controller Full Page CRUD: [SablonController.php](file:///d:/laragon/www/an-mastery-v3/app/Http/Controllers/SablonController.php)

## Contoh kode
Berikut adalah contoh struktur penulisan controller yang direkomendasikan (ganti `{Module}` dengan nama modul Anda):
```php
namespace App\Http\Controllers;

use App\Repositories\{Module}Repository;
use App\Http\Requests\{Module}\Save{Module}Request;
use App\Http\Resources\{Module}Resource;
use App\Models\{Module};

class {Module}Controller extends Controller
{
    public function __construct(
        private {Module}Repository ${module}Repository
    ) {}

    public function index()
    {
        $this->authorize('{modules}.view');

        if (request()->expectsJson()) {
            $filter = request('filter', 'active');
            ${modules} = $this->{module}Repository->getAll($filter);
            return {Module}Resource::collection(${modules});
        }

        return view('{module}.index');
    }

    public function store(Save{Module}Request $request)
    {
        $this->authorize('{modules}.create');
        ${module} = {Module}::create($request->validated());
        return new {Module}Resource(${module});
    }

    public function show({Module} ${module})
    {
        $this->authorize('{modules}.view');
        return new {Module}Resource(${module});
    }

    public function update(Save{Module}Request $request, {Module} ${module})
    {
        $this->authorize('{modules}.edit');
        ${module}->update($request->validated());
        return new {Module}Resource(${module});
    }

    public function destroy({Module} ${module})
    {
        $this->authorize('{modules}.delete');
        ${module}->delete();
        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('{modules}.delete');
        ${module} = {Module}::onlyTrashed()->findOrFail($id);
        ${module}->restore();
        return new {Module}Resource(${module});
    }

    public function forceDelete(int $id)
    {
        $this->authorize('{modules}.delete');
        ${module} = {Module}::onlyTrashed()->findOrFail($id);
        ${module}->forceDelete();
        return response()->noContent();
    }
}
```

## Hubungan dengan file lain
- Data dibaca melalui Repository yang diatur di `repository.md`.
- Validasi data dijamin oleh Form Request yang diatur di `request.md`.
- Data respons dibungkus oleh API Resource yang diatur di `resource.md`.
- Rute-rute controller diatur di `routing.md`.

## Checklist
- [ ] Apakah kelas Repository telah disuntikkan secara aman melalui constructor?
- [ ] Apakah verifikasi permission (`$this->authorize(...)`) sudah terpasang di setiap method?
- [ ] Apakah rute `index` sudah mendukung pengecekan respons dinamis (`expectsJson()`)?
- [ ] Apakah respons penghapusan (`destroy()`) mengembalikan `response()->noContent()`?

## Best Practice
- **Keep Code Lean**: Jangan pernah meletakkan manipulasi kueri database mentah atau transaksi SQL di dalam controller.
- **Kembalikan Resource**: Selalu bungkus data Eloquent yang dikembalikan ke Javascript menggunakan API Resource.

## Catatan penting
> [!IMPORTANT]
> Aksi pemulihan (`restore`) dan penghapusan permanen (`forceDelete`) harus mencari data menggunakan `onlyTrashed()` agar data yang berstatus terhapus secara soft-delete dapat terdeteksi: `{Module}::onlyTrashed()->findOrFail($id)`.
