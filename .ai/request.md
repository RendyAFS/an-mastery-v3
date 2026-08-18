# Form Request Standards (request.md)

## Tujuan
Dokumen ini menjelaskan standar pembuatan dan penulisan berkas validasi **Form Request** di Laravel pada proyek ini.

## Kapan digunakan
Gunakan panduan ini setiap kali Anda menambahkan atau mengedit skema validasi input form untuk modul baru maupun fitur yang sudah ada.

## Cara kerja
1. **Otorisasi Default**: Karena pemeriksaan otorisasi hak akses pengguna ditangani secara eksplisit di level Controller menggunakan Spatie Permission, method `authorize()` di kelas Form Request selalu dikembalikan dengan nilai `true`.
2. **Aturan Validasi**: Aturan validasi didefinisikan secara rapi di dalam method `rules()` mengembalikan array aturan Laravel.
3. **Kustomisasi Pesan**: Pesan kegagalan validasi kustom ditulis di dalam method `messages()` untuk memberikan informasi kesalahan yang ramah pengguna.

## Struktur
Setiap kelas Form Request kustom berada di folder `app/Http/Requests/{ModuleName}/` dan memiliki struktur kode sebagai berikut:
- Namespace: `App\Http\Requests\{ModuleName}`
- Deklarasi kelas: `class Save{ModuleName}Request extends FormRequest`
- Method `authorize()`: Mengembalikan `true`.
- Method `rules()`: Mengembalikan daftar key-value aturan validasi field.
- Method `messages()`: Mengembalikan kustomisasi pesan kesalahan validasi field.

## Contoh implementasi
Referensi struktur Form Request standar:
- Request modul simple: `app/Http/Requests/Category/SaveCategoryRequest.php`
- Request modul dengan array dinamis: `app/Http/Requests/Product/SaveProductRequest.php`

## Contoh kode
Berikut adalah contoh standard implementasi Form Request (ganti `{Module}` dengan nama modul Anda):
```php
namespace App\Http\Requests\{Module};

use Illuminate\Foundation\Http\FormRequest;

class Save{Module}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Hak akses dikontrol via Policy di Controller
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
            'notes'       => 'nullable|string',
            // Validasi relasi/FK:
            'relation_id' => 'nullable|exists:related_table,id',
            // Validasi array dinamis:
            'details.*.item_id' => 'required|exists:items,id',
            'details.*.qty'     => 'required|numeric|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'Name is required.',
            'details.*.item_id.required' => 'Item on row :index is required.',
        ];
    }
}
```

## Hubungan dengan file lain
- Form Request disuntikkan ke dalam method `store()` dan `update()` pada Controller yang diatur di `controller.md`.
- Jika validasi gagal, Axios client-side (`ApiProvider.js`) menangkap status HTTP `422 Unprocessable Entity` secara otomatis dan menampilkan pesannya ke layar pembaca.

## Checklist
- [ ] Apakah kelas Form Request mewarisi `Illuminate\Foundation\Http\FormRequest`?
- [ ] Apakah method `authorize()` sudah dipastikan mengembalikan nilai `true`?
- [ ] Apakah Anda telah mendefinisikan skema kustom di method `messages()` untuk semua rules `required`?
- [ ] Apakah tipe data rules sudah sesuai dengan kolom tipe data di tabel database?

## Best Practice
- **Satu Request untuk Aksi CRUD**: Gunakan berkas `Save{ModuleName}Request` tunggal untuk aksi tambah (Store) dan edit (Update) demi efisiensi kode, kecuali jika ada perbedaan aturan validasi yang sangat besar.
- **Validasi Array Dinamis**: Jika form memiliki baris dinamis, gunakan validasi array dot-notation (misal: `'item_details.*.item_id' => 'required|exists:items,id'`).

## Catatan penting
> [!IMPORTANT]
> Proyek ini menampilkan pesan kesalahan validasi secara dinamis melalui toast alert. Tulis pesan kesalahan yang jelas dan langsung pada intinya di dalam method `messages()` agar pengguna dapat langsung memahami input yang salah.

