# CSS Standards (css.md)

## Tujuan
Dokumen ini menjelaskan standardisasi styling menggunakan **Tailwind CSS v4** dan variabel warna tema kustom di proyek **AN Mastery V3**. Dokumen ini menjamin kebersihan kelas utilitas dan keseragaman skema warna tema (termasuk mode gelap/dark mode).

## Kapan digunakan
Gunakan dokumen ini setiap kali Anda merancang antarmuka UI baru, mengkustomisasi warna teks/latar belakang, memodifikasi elemen badge, mengontrol spacing, atau mengedit berkas css komponen di proyek ini.

## Cara kerja
1. **Tailwind CSS v4 Compilation**: Proyek ini menggunakan arsitektur Tailwind CSS v4 terbaru. Konfigurasi warna dilakukan langsung di dalam berkas `:root` CSS alih-alih file konfigurasi Javascript.
2. **Tema Terpusat (`theme.css`)**: Semua variabel CSS tema dideklarasikan di dalam `:root` di berkas `theme.css`.
3. **Pemuatan Gaya**: Tailwind v4 mengenali variabel CSS `:root` tersebut dan mengkompilasinya menjadi kelas utilitas bawaan, misalnya:
   - Warna Utama: `bg-(--color-primary)` atau `text-(--color-primary)`.
   - Warna Abu-abu: `border-(--color-gray)` atau `bg-(--color-light-gray)`.
4. **Variasi Kelas**: Komponen rumit yang membutuhkan transisi warna mode gelap terisolasi (seperti badge) dikelompokkan ke dalam file CSS terpisah di bawah direktori `components/`.

## Struktur
Berkas-berkas CSS proyek berada di bawah folder `resources/css/` dengan struktur sebagai berikut:
- **`app.css`**: Berkas utama penggabung yang memuat Tailwind `@import "tailwindcss";`, Preline variants `@source`, dan mengimpor seluruh berkas komponen pendukung.
- **`theme.css`**: Mendefinisikan semua variabel warna utama, info proses, penanda badge, dan status abu-abu.
- **`components/`**:
  - `badge.css`: Gaya kelas `.badge` kustom dengan pemanfaatan `color-mix` untuk warna latar transparan.
  - `checkbox.css`: Kustomisasi tombol centang agar seragam dengan warna tema utama.
  - `datatable.css`: Mengontrol overlay loader loading asinkron pada datatables.
  - `form.css`: Gaya input text dan textarea yang konsisten.
  - `scrollbar.css`: Mengatur tampilan scrollbar agar tipis dan elegan.

## Contoh implementasi
Pola berkas CSS dan tema dapat dipelajari pada berkas:
- Berkas Utama: [app.css](file:///d:/laragon/www/an-mastery-v3/resources/css/app.css)
- Variabel Tema: [theme.css](file:///d:/laragon/www/an-mastery-v3/resources/css/theme.css)
- Gaya Lencana: [badge.css](file:///d:/laragon/www/an-mastery-v3/resources/css/components/badge.css)

## Contoh kode
### 1. Deklarasi Tema (`theme.css`):
```css
:root {
    --color-primary: #6d9886;
    --color-secondary: #f2e7d5;
    --color-dark: #282b31;
    --color-light: #f7f7f7;
    --color-gray: #c0c0c0;
    --color-light-gray: #ececec;
    --color-dark-gray: #949494;
    --color-success: #66a76b;
    --color-danger: #d44e4e;
}
```

### 2. Penggunaan Kelas Utilitas pada Blade:
```html
<div class="bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light) border border-(--color-gray)/20">
    <span class="badge badge-success">Active</span>
</div>
```

## Hubungan dengan file lain
- Gaya CSS ini dimuat di layout utama [main.blade.php](file:///d:/laragon/www/an-mastery-v3/resources/views/layouts/main.blade.php) menggunakan tag `@vite(['resources/css/app.css'])`.
- Kelas utilitas CSS ini digunakan di semua view halaman Blade (`blade.md`) dan tag string HTML dinamis di JavaScript (`javascript.md`).

## Checklist
- [ ] Apakah warna kustom Anda diambil dari variabel yang terdaftar di `theme.css`?
- [ ] Apakah warna latar belakang gelap menggunakan `dark:bg-(--color-dark)` atau `dark:bg-(--color-dark-slate)`?
- [ ] Apakah kelas badge kustom sudah menggunakan pola `.badge` dan `.badge-{status}`?
- [ ] Apakah modifikasi CSS baru sudah ditaruh di dalam folder `components/` dan diimpor di `app.css`?

## Best Practice
- **Gunakan Variabel CSS**: Jangan pernah menulis kode warna heksadesimal mentah di kelas HTML. Gunakan penulisan kelas utilitas variabel seperti `text-(--color-primary)` atau `bg-(--color-success)`.
- **Dukungan Dark Mode**: Selalu pasangkan kelas `dark:` pada elemen tata letak utama untuk mendukung peralihan warna tema gelap yang mulus.

## Catatan penting
> [!IMPORTANT]
> Proyek ini menggunakan **Tailwind CSS v4**. Jangan menulis sintaks konfigurasi warna di dalam berkas Javascript `tailwind.config.js` karena berkas tersebut sudah tidak digunakan lagi di versi Tailwind terbaru ini.
