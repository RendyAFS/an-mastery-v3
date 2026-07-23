# AI Agent Entry Point Guide (AGENTS.md)

## Tujuan
Dokumen ini berfungsi sebagai panduan utama dan gerbang masuk (*entry point*) bagi AI Agent saat mengembangkan, melakukan pemeliharaan, atau memodifikasi fitur pada proyek **AN Mastery V3**. Dokumen ini memastikan AI memahami pola arsitektur proyek, aturan kepatuhan, serta cara membaca berkas dokumentasi penunjang lainnya.

## Kapan digunakan
AI Agent wajib membaca dokumen ini terlebih dahulu di awal sesi sebelum mulai menganalisis kode atau melakukan modifikasi apa pun dalam proyek ini. 

## Cara kerja
1. AI Agent membaca `AGENTS.md` untuk memahami aturan global dan batasan utama.
2. AI Agent mengikuti urutan membaca berkas yang direkomendasikan untuk mendalami topik-topik tertentu.
3. AI Agent merujuk pada standar arsitektur dan pola penamaan yang tertulis pada file `.ai/*.md` alih-alih menggunakan asumsi best practice umum Laravel.

## Struktur
Berikut adalah daftar seluruh file Knowledge Base yang tersedia di dalam folder `.ai/`:
- **`AGENTS.md`**: Dokumen panduan utama AI (*Entry Point*).
- **`project-architecture.md`**: Gambaran arsitektur aplikasi, alur request/response, dan dependensi utama.
- **`workflow.md`**: Siklus hidup dan langkah kerja pembuatan fitur dari awal hingga akhir.
- **`backend.md`**: Gambaran umum dan tanggung jawab komponen backend Laravel.
- **`frontend.md`**: Gambaran umum arsitektur aset JavaScript/CSS dan inisialisasi UI.
- **`routing.md`**: Standar penulisan rute web dan API.
- **`controller.md`**: Struktur controller dan koordinasinya dengan Repository/Action.
- **`request.md`**: Pola validasi input menggunakan Form Request.
- **`repository.md`**: Aturan penulisan query database dan pemisahan logika data.
- **`resource.md`**: Standardisasi format JSON response menggunakan API Resources.
- **`javascript.md`**: Pola interaksi client-side menggunakan PageScript (IIFE) dan AlpineJS.
- **`blade.md`**: Aturan layouting, komponen UI reusable, dan rendering views.
- **`css.md`**: Desain sistem styling dengan Tailwind CSS v4.
- **`datatable.md`**: Panduan integrasi tabel dinamis menggunakan komponen `x-datatable`.
- **`cardgrid.md`**: Panduan integrasi grid kartu dinamis menggunakan komponen `x-cardgrid`.
- **`module-default.md`**: Panduan pembuatan modul tipe Full Page CRUD.
- **`module-simple.md`**: Panduan pembuatan modul tipe Modal CRUD.
- **`naming-convention.md`**: Kamus standar penamaan seluruh berkas dan variabel proyek.
- **`best-practice.md`**: Checklist teknis, larangan keras, serta penyelesaian masalah (*troubleshooting*).

## Contoh implementasi
Urutan membaca dokumentasi yang direkomendasikan saat membuat fitur baru:
1. Baca `AGENTS.md` (Aturan global).
2. Baca `workflow.md` (Urutan pembuatan file).
3. Baca `naming-convention.md` (Cara memberi nama file/variabel).
4. Baca berkas spesifik sesuai modul yang dikerjakan: `module-default.md` atau `module-simple.md`.
5. Baca panduan UI jika diperlukan: `datatable.md` atau `cardgrid.md`.

## Contoh kode
Berikut adalah contoh struktur meta deskripsi yang wajib dipatuhi di setiap awal proses:
```markdown
# [Fitur Baru]
1. Identifikasi tipe modul: Default (Full Page) atau Simple (Modal-based)
2. Buat Backend (Routing -> Request -> Repository -> Action -> Controller)
3. Buat Frontend (Blade -> Javascript)
```

## Hubungan dengan file lain
Dokumen `AGENTS.md` terhubung langsung dengan semua berkas di dalam folder `.ai/`. Berkas ini merupakan jangkar koordinasi utama yang mengarahkan pembacaan ke file spesifik seperti `project-architecture.md` dan `workflow.md`.

## Checklist
- [ ] Sudahkah Anda membaca dokumen ini di awal sesi pengerjaan?
- [ ] Apakah tipe modul yang akan Anda buat sudah dipastikan (Default vs Simple)?
- [ ] Apakah Anda telah membaca berkas panduan khusus yang relevan dengan tugas Anda (misal `datatable.md` jika membuat tabel)?

## Best Practice
- **Patuhi Konsistensi Proyek**: Selalu ikuti pola yang sudah ada di proyek ini meskipun menurut Anda ada best practice eksternal yang lebih baik.
- **Validasi Terlebih Dahulu**: Periksa file generator `MakeModuleCommand.php` untuk melihat struktur dasar cetak biru modul baru.

## Catatan penting
> [!IMPORTANT]
> Proyek ini menggunakan **Tailwind CSS v4** dengan variabel CSS `:root` langsung pada utilitas kelas, serta **Preline UI v3** untuk interaksi JavaScript komponennya. Jangan mengimpor library UI lain tanpa izin tertulis dari user.
