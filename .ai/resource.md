# API Resource Standards (resource.md)

## Tujuan
Dokumen ini menjelaskan standardisasi transformasi data respons menggunakan **API Resources** di Laravel pada proyek ini. Pola ini mengontrol data apa saja yang diekspos ke antarmuka JavaScript client-side dan menjamin keseragaman format data.

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
Referensi API Resource standar:
- Resource sederhana (tanpa relasi): `app/Http/Resources/CategoryResource.php`
- Resource dengan relasi bersyarat: `app/Http/Resources/ProductResource.php`

## Contoh kode
Berikut adalah contoh penulisan API Resource terstandar (ganti `{Module}` dengan nama modul Anda):
```php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\RupiahHelper;

class {Module}Resource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'amount'               => $this->amount,
            'amount_formated'      => RupiahHelper::format($this->amount), // Rp 150.000
            'date'                 => $this->date?->format('Y-m-d'), // Format ISO standar untuk Flatpickr/input form
            'date_formatted'       => $this->date?->translatedFormat('d F Y'), // Format teks ramah pengguna untuk Datatable/kartu
            'status'               => $this->status,
            'notes'                => $this->notes,
            'deleted_at'           => $this->deleted_at?->format('Y-m-d H:i:s'),
            'created_at'           => $this->created_at?->format('Y-m-d H:i:s'),
            'created_by'           => $this->created_by,
            'updated_by'           => $this->updated_by,
            'deleted_by'           => $this->deleted_by,

            // Relasi bersyarat — hanya dikirim jika sudah di-eager load
            'relation_a'           => new RelationAResource($this->whenLoaded('relationA')),
            'relation_b'           => new RelationBResource($this->whenLoaded('relationB')),
        ];
    }
}
```

## Hubungan dengan file lain
- API Resource digunakan di dalam Controller (`controller.md`) untuk merespons AJAX request.
- Respons JSON dari API Resource ditangkap dan digunakan untuk merender baris/kartu data di JavaScript (`javascript.md`) lewat utilitas `datatable.js` atau `cardgrid.js`.

## Checklist
- [ ] Apakah kelas API Resource mewarisi `Illuminate\Http\Resources\Json\JsonResource`?
- [ ] Apakah properti tanggal untuk form/input (`date`) menggunakan format ISO `format('Y-m-d')`?
- [ ] Apakah tampilan tanggal ramah pengguna dipisahkan ke properti `date_formatted` (`translatedFormat('d F Y')`)?
- [ ] Apakah properti timestamp audit trail diformat seragam menggunakan `format('Y-m-d H:i:s')`?
- [ ] Apakah semua data relasi dibungkus dengan method `$this->whenLoaded(...)`?
- [ ] Apakah bidang audit trail (`created_by`, `updated_by`, `deleted_by`) telah dipetakan secara lengkap?

## Best Practice
- **Hindari Eager Loading di Resource**: Jangan memanggil properti relasi secara langsung (misal: `$this->category->name`). Selalu gunakan `$this->whenLoaded` untuk menjamin efisiensi kueri basis data.
- **Sediakan Format Tambahan**: Selalu sediakan properti format mentah (misal: `'total'` => `$this->total`) dan properti terformat ramah pengguna (misal: `'total_formated'` => `RupiahHelper::format(...)`) secara bersamaan dalam respons.
- **Standar Format Tanggal**: Pisahkan antara field tanggal untuk manipulasi input/Flatpickr (`'date' => $this->date?->format('Y-m-d')`) dengan field tampilan teks (`'date_formatted' => $this->date?->translatedFormat('d F Y')`).

## Catatan penting
> [!IMPORTANT]
> 1. Jangan pernah mengembalikan relasi model bertingkat tanpa dibungkus dengan kelas Resource padanannya. Contoh: gunakan `new CategoryResource($this->whenLoaded('category'))` alih-alih `new JsonResource($this->category)`.
> 2. Jangan pernah mengirim tanggal berformat teks nama bulan lokal (contoh: `"21 September 2026"`) pada properti `date` yang dikonsumsi oleh komponen `<x-datepicker>`/Flatpickr. Parser Flatpickr hanya mengenali format standar `Y-m-d`. Format teks lokal akan menyebabkan parsing gagal dan tanggal tereset menjadi **1 Januari**. Selalu gunakan `Y-m-d` untuk `date` dan sediakan `date_formatted` untuk tampilan Datatable.


