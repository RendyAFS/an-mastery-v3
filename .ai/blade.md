# Blade Template Standards (blade.md)

## Tujuan
Dokumen ini menjelaskan standar penyusunan layout, penulisan sintaks, dan pemanfaatan komponen **Blade Template** pada proyek ini.

## Kapan digunakan
Gunakan panduan ini setiap kali Anda membuat file view `.blade.php` baru, mendesain form, menyusun struktur tabel, menyertakan modal, atau membuat komponen UI baru.

## Cara kerja
1. **Layouting**: Halaman view utama memperluas kerangka dasar layout admin `layouts.main` menggunakan direktif `@extends` dan menetapkan judul halaman melalui parameter array asosiatif.
2. **Modularisasi Halaman**: Halaman view menyertakan komponen input modal yang kompleks melalui direktif `@include` agar file utama tetap ramping.
3. **Penyuntikan Aset Dinamis**: Skrip JavaScript halaman disuntikkan secara dinamis menggunakan blok `@push('scripts')` untuk memuat file JS melalui Vite `@vite('resources/js/...')`.
4. **Komponen Kustom**: Elemen antarmuka berulang seperti DataTable, CardGrid, dan Button Loading dideklarasikan menggunakan komponen Blade berbasis tag (seperti `<x-datatable>` dan `<x-button-loading>`).

## Struktur
Setiap halaman Blade standard terstruktur sebagai berikut:
- **Deklarasi Layout**: `@extends('layouts.main', ['title' => 'Judul Halaman'])`
- **Pemuatan Aset Halaman**: Blok `@push('scripts')` berisi pemanggilan file JS halaman via `@vite('...')`.
- **Blok Konten Utama**: `@section('content')`
  - Informasi judul halaman dan tombol aksi utama.
  - Komponen tabel `<x-datatable>` atau grid `<x-cardgrid>`.
  - Blok include untuk modul formulir modal (`@include('...modal')`).
- **Penutup Konten**: `@endsection`

## Contoh implementasi
Referensi berkas Blade yang sudah ada di proyek ini:
- View index modul simple: [supplier/index.blade.php](file:///d:/laragon/www/an-mastery-v3/resources/views/supplier/index.blade.php)
- Layout utama admin: [layouts/main.blade.php](file:///d:/laragon/www/an-mastery-v3/resources/views/layouts/main.blade.php)

## Contoh kode
Berikut adalah kerangka penulisan halaman view terstandar (ganti `{module}` dan `{Module}` dengan nama modul Anda):
```html
@extends('layouts.main', ['title' => '{Module}'])

@push('scripts')
    @vite('resources/js/pages/{module}/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        {{-- Header Halaman --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">{Module}</h1>
                <p class="text-sm text-(--color-dark-gray)">Manage {module} data</p>
            </div>

            <button type="button" data-hs-overlay="#hs-{module}-modal" id="btn-create-{module}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-(--color-primary) text-white cursor-pointer">
                <i data-lucide="plus" class="size-4"></i>
                Add {Module}
            </button>
        </div>

        {{-- Komponen DataTable --}}
        <x-datatable id="{module}s-datatable" filterId="filter-{module}s">
            <thead class="border-b">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium uppercase">Name</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase text-center">Is Active</th>
                    <th class="px-6 py-3 text-xs font-medium uppercase text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-(--color-gray) dark:divide-(--color-dark-gray)"></tbody>
        </x-datatable>
    </div>

    @include('{module}.modal')
@endsection
```

## Hubungan dengan file lain
- View Blade memuat berkas JavaScript spesifik halaman (`javascript.md`).
- Komponen tabel dan grid berinteraksi langsung dengan berkas helper `datatable.md` dan `cardgrid.md`.
- Komponen styling UI dikontrol oleh Tailwind CSS v4 (`css.md`).

## Checklist
- [ ] Apakah halaman view Anda sudah memperluas `@extends('layouts.main', ...)`?
- [ ] Apakah skrip JavaScript halaman dimuat dengan aman menggunakan `@push('scripts')` dan `@vite(...)`?
- [ ] Apakah elemen data dinamis dalam form modal (`form.blade.php`) menggunakan name tag yang cocok dengan Form Request rules?
- [ ] Apakah ID kontainer modal sudah sinkron dengan target tombol `data-hs-overlay`?

## Best Practice
- **Gunakan Komponen Kustom**: Selalu gunakan komponen tombol loading `<x-button-loading>` untuk submit form agar animasi loading berjalan seragam.
- **Isolasi Element**: Jaga agar tag `@include` modal berada di luar kontainer utama halaman untuk mencegah interferensi tata letak CSS flex/grid.

## Catatan penting
> [!IMPORTANT]
> Proyek ini menggunakan **Lucide Icons**. Pastikan Anda menulis elemen ikon menggunakan format `<i data-lucide="nama-ikon" class="size-4"></i>` dan memicu `initLucide()` dari skrip Javascript jika merender HTML baru secara dinamis.
