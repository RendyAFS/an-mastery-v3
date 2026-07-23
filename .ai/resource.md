# API Resource Standards (resource.md)

## Tujuan
Dokumen ini menjelaskan standardisasi transformasi data respons menggunakan **API Resources** di Laravel pada proyek **AN Mastery V3**. Pola ini mengontrol data apa saja yang diekspos ke antarmuka JavaScript client-side dan menjamin keseragaman format data.

## Kapan digunakan
Gunakan dokumen ini setiap kali Anda membuat API Resource baru atau memperbarui properti data JSON yang dikirimkan dari server Laravel ke JavaScript client-side.

## Cara kerja
1. **Transformasi Format**: Model Eloquent dipetakan ke dalam bentuk array. Properti tanggal diformat ke bentuk string terstandar (misal: `Y-m-d H:i:s` atau `Y-m-d`).
2. **Pemuatan Relasional Bersyarat**: Relasi tabel hanya diekspos jika dimuat secara eksplisit di level kueri repository. Hal ini dilakukan menggunakan method `$this->whenLoaded('relation_name')` untuk mencegah query N+1 yang tidak disengaja.
3. **Format Rupiah**: Nilai uang atau nominal numerik diformat menjadi string rupiah terformat menggunakan bantuan `RupiahHelper::format($number)`.
4. **Data Audit Trail**: Bidang log penjejakan audit (seperti `created_by`, `updated_by`, `deleted_by`) dari package userstamps dipetakan secara konsisten.

## Struktur
Setiap kelas API Resource berada di bawah folder `app/Http/Resources/` dan diberi nama berakhiran `{ModuleName}Resource.php`. Susunan kode utama adalah:
- Namespace: `App\Http\Resources`
- Deklarasi kelas: `class {ModuleName}Resource extends JsonResource`
- Method `toArray(Request $request)`: Mengembalikan array asosiatif representasi data JSON.

## Contoh implementasi
Pola API Resource dapat dipelajari pada berkas:
- Resource Supplier: [SupplierResource.php](file:///d:/laragon/www/an-mastery-v3/app/Http/Resources/SupplierResource.php)
- Resource Sablon: [SablonResource.php](file:///d:/laragon/www/an-mastery-v3/app/Http/Resources/SablonResource.php)

## Contoh kode
Berikut adalah contoh penulisan API Resource terstandar:
```php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\RupiahHelper;

class SablonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'total_sablon'          => $this->total_sablon,
            'total_sablon_formated' => RupiahHelper::format($this->total_sablon), // Rp 150.000
            'date_sablon'           => $this->date_sablon?->format('Y-m-d'),
            'status'                => $this->status,
            'notes'                 => $this->notes,
            'created_at'            => $this->created_at?->format('Y-m-d H:i:s'),
            'deleted_at'            => $this->deleted_at?->format('Y-m-d H:i:s'),
            'created_by'            => $this->created_by,
            'updated_by'            => $this->updated_by,
            'deleted_by'            => $this->deleted_by,

            // Relasi bersyarat untuk menghindari lazy loading
            'supplier'              => new SupplierResource($this->whenLoaded('supplier')),
            'fabric'                => new FabricResource($this->whenLoaded('fabric')),
        ];
    }
}
```

## Hubungan dengan file lain
- API Resource digunakan di dalam Controller (`controller.md`) untuk merespons AJAX request.
- Respons JSON dari API Resource ditangkap dan digunakan untuk merender baris/kartu data di JavaScript (`javascript.md`) lewat utilitas `datatable.js` atau `cardgrid.js`.

## Checklist
- [ ] Apakah kelas API Resource mewarisi `Illuminate\Http\Resources\Json\JsonResource`?
- [ ] Apakah properti tanggal sudah diformat secara seragam menggunakan format `format('Y-m-d H:i:s')`?
- [ ] Apakah semua data relasi dibungkus dengan method `$this->whenLoaded(...)`?
- [ ] Apakah bidang audit trail (`created_by`, `updated_by`, `deleted_by`) telah dipetakan secara lengkap?

## Best Practice
- **Hindari Eager Loading di Resource**: Jangan memanggil properti relasi secara langsung (misal: `$this->supplier->name`). Selalu gunakan `$this->whenLoaded` untuk menjamin efisiensi kueri basis data.
- **Sediakan Format Tambahan**: Selalu sediakan properti format mentah (misal: `'total_sablon' => $this->total_sablon`) dan properti terformat ramah pengguna (misal: `'total_sablon_formated' => RupiahHelper::format(...)`) secara bersamaan dalam respons.

## Catatan penting
> [!IMPORTANT]
> Jangan pernah mengembalikan relasi model bertingkat tanpa dibungkus dengan kelas Resource padanannya. Contoh: gunakan `new SupplierResource($this->whenLoaded('supplier'))` alih-alih `new JsonResource($this->supplier)`.
