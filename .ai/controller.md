# Controller Standards (controller.md)

## Tujuan
Dokumen ini menjelaskan standar implementasi HTTP Controller di proyek **AN Mastery V3**. Standar ini mencakup struktur dasar, penyuntikan dependensi, pemeriksaan otorisasi, penanganan AJAX response, dan pendelegasian proses penulisan data.

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
Pola controller standard dapat dipelajari pada berkas:
- Kontroler Supplier: [SupplierController.php](file:///d:/laragon/www/an-mastery-v3/app/Http/Controllers/SupplierController.php)
- Kontroler Sablon: [SablonController.php](file:///d:/laragon/www/an-mastery-v3/app/Http/Controllers/SablonController.php)

## Contoh kode
Berikut adalah contoh struktur penulisan controller yang direkomendasikan:
```php
namespace App\Http\Controllers;

use App\Repositories\SupplierRepository;
use App\Http\Requests\Supplier\SaveSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function __construct(
        private SupplierRepository $supplierRepository
    ) {}

    public function index()
    {
        $this->authorize('suppliers.view');

        if (request()->expectsJson()) {
            $filter = request('filter', 'active');
            $suppliers = $this->supplierRepository->getAll($filter);
            return SupplierResource::collection($suppliers);
        }

        return view('supplier.index');
    }

    public function store(SaveSupplierRequest $request)
    {
        $this->authorize('suppliers.create');
        $supplier = Supplier::create($request->validated());
        return new SupplierResource($supplier);
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
> Aksi pemulihan (`restore`) dan penghapusan permanen (`forceDelete`) harus mencari data menggunakan `onlyTrashed()` agar data yang berstatus terhapus secara soft-delete dapat terdeteksi: `Supplier::onlyTrashed()->findOrFail($id)`.
