# Backend Architecture Guide (backend.md)

## Tujuan
Dokumen ini menjelaskan struktur, tanggung jawab, dan standardisasi pengembangan sisi backend (Laravel 12) pada proyek **AN Mastery V3**.

## Kapan digunakan
Rujuklah dokumen ini setiap kali Anda merancang struktur logika baru di sisi server, membuat API endpoint, menulis kueri database, atau melakukan penanganan transaksi bisnis di Laravel.

## Cara kerja
Backend proyek ini membagi kode ke dalam beberapa layer fungsional terpisah (Layered Architecture):
1. **Routing Layer**: Mengarahkan URL request ke controller yang tepat dan menerapkan middleware otentikasi/aktifitas.
2. **Controller Layer**: Bertindak sebagai koordinator rute. Menerima request, memicu otorisasi, mendelegasikan kueri ke Repository/Action, dan mengembalikan view atau JSON Resource.
3. **Validation Layer (Form Request)**: Memastikan keamanan dan validitas data input sebelum diproses oleh sistem.
4. **Logika Kueri (Repository)**: Menangani semua aksi pembacaan data (`SELECT`) dari database.
5. **Logika Penulisan (Action/Eloquent)**: Menangani semua penulisan data (`INSERT`, `UPDATE`, `DELETE`) dan transaksi database yang kompleks.
6. **Transformation Layer (API Resource)**: Memformat model Eloquent menjadi respons JSON yang rapi dan konsisten.

## Struktur
Berikut adalah diagram dependensi antar-layer backend:

```text
               [ HTTP Request ]
                       │
                       ▼
                 [ Controller ] ──(Otorisasi via Policy)
                 /            \
                v              v
     [ Form Request ]     [ Repository ]
            │                  │
            ▼                  ▼
     [ Action Class ] ──> [ Eloquent Model ]
            │
            ▼
     [ API Resource ] ──> [ JSON Response ]
```

### Kapan Menggunakan Kelas Action vs Eloquent Langsung di Controller
- **Eloquent Langsung di Controller**: Digunakan untuk operasi tulis sederhana pada satu model tunggal yang tidak memiliki efek samping atau relasi berjenjang (misal: `Supplier::create($request->validated())` atau `$supplier->delete()`).
- **Kelas Action (`SaveAction`)**: Wajib digunakan jika operasi tulis melibatkan transaksi database (`DB::transaction`), sinkronisasi tabel relasi/pivot (misal: detail sablon, detail pekerja), pemrosesan berkas/media statis, atau logika bisnis kompleks lainnya.

## Contoh implementasi
Dalam pembuatan data Sablon, controller mendelegasikan penyimpanan ke kelas Action:
- Controller: [SablonController.php](file:///d:/laragon/www/an-mastery-v3/app/Http/Controllers/SablonController.php)
- Action: [SaveSablonAction.php](file:///d:/laragon/www/an-mastery-v3/app/Actions/Sablon/SaveSablonAction.php)
- Request: [SaveSablonRequest.php](file:///d:/laragon/www/an-mastery-v3/app/Http/Requests/Sablon/SaveSablonRequest.php)

## Contoh kode
Berikut adalah contoh pembagian layer pembacaan data di Repository:
```php
// App\Repositories\SupplierRepository.php
namespace App\Repositories;

use App\Models\Supplier;

class SupplierRepository
{
    public function getAll($filter = 'active')
    {
        $query = Supplier::query()->orderBy('id', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }
        return $query->get();
    }
}
```

## Hubungan dengan file lain
- Aturan penulisan controller secara detail dibahas di `controller.md`.
- Pola validasi dibahas di `request.md`.
- Pola kueri dibahas di `repository.md`.
- Format respons API dibahas di `resource.md`.

## Checklist
- [ ] Apakah operasi tulis data Anda sudah melibatkan transaksi multi-tabel? Jika ya, apakah logika tersebut sudah dibungkus dalam kelas Action?
- [ ] Apakah kueri SQL/Eloquent Anda sudah ditaruh di dalam kelas Repository dan bukan langsung di Controller?
- [ ] Apakah Anda telah memanggil `$this->authorize(...)` di awal method controller Anda untuk validasi izin akses?

## Best Practice
- **Keep Controllers Skinny**: Controller hanya boleh membaca input, memanggil Repository/Action, dan mengembalikan response. Jangan letakkan kueri SQL atau operasi logika bisnis mentah di dalam controller.
- **Dependency Injection**: Selalu gunakan constructor-based dependency injection di controller untuk memanggil Repository.
- **No Yajra Controllers**: Ingat, backend tidak menggunakan pagination server-side Yajra. Kembalikan data aslinya menggunakan standard `JsonResource::collection($query->get())`.

## Catatan penting
> [!IMPORTANT]
> Proyek ini menggunakan library **Spatie Laravel Permission** untuk mengontrol izin akses. Setiap method CRUD di controller harus memverifikasi otorisasi terlebih dahulu menggunakan method `$this->authorize('nama_permission')`.
