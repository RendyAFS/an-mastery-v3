<div align="center">

  <img src="public/assets/Logo-AnMastery.webp" width="160" alt="AN Mastery Logo" style="max-width: 100%;">

# 🚀 AN Mastery V3

  <a href="https://git.io/typing-svg">
    <img src="https://readme-typing-svg.demolab.com?font=Outfit&weight=600&size=20&pause=1200&color=38B2AC&center=true&vCenter=true&width=600&lines=Sistem+Manajemen+Konveksi+%26+Sablon+Terpadu;Fabric+Inventory+%E2%80%A2+Screen+Printing+Orders;Supplier+Billing+%E2%80%A2+Employee+Attendance;Automatic+Weekly+%26+Monthly+Payroll;Powered+by+Laravel+12+%2B+Tailwind+CSS+v4" alt="Typing SVG" />
  </a>

  <br>

[![Version](https://img.shields.io/badge/App_Version-v1.1.9-6d9886?style=for-the-badge)](config/app.php)
[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS v4](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Preline UI v3](https://img.shields.io/badge/Preline_UI-v3.0-0066FF?style=for-the-badge&logo=preline&logoColor=white)](https://preline.co)
[![AlpineJS v3](https://img.shields.io/badge/Alpine.js-v3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)
[![License](https://img.shields.io/badge/License-Proprietary-gold?style=for-the-badge)](LICENSE)

</div>

---

## 📌 Tentang Proyek

**AN Mastery V3** adalah platform **Sistem Manajemen Konveksi & Sablon Terpadu** yang dirancang khusus untuk operasional **Andri Sablon** (Gedangsewu, Tulungagung, Jawa Timur).

Aplikasi ini mengintegrasikan seluruh alur bisnis dari hulu ke hilir secara otomatis, presisi, dan real-time:

<table>
  <tr>
    <td width="50%">
      <h3>📦 Manajemen Operational Backend</h3>
      <ul>
        <li><b>Katalog & Stok Kain</b> — Pelacakan jenis kain, warna, dan sampel.</li>
        <li><b>Produksi Sablon</b> — Order pesanan, progres status, dan detail spesifikasi.</li>
        <li><b>Tagihan Supplier</b> — Manajemen invoice & riwayat pembayaran.</li>
        <li><b>Absensi & Gaji</b> — Rekap absensi harian dan kalkulasi gaji otomatis.</li>
      </ul>
    </td>
    <td width="50%">
      <h3>🌐 Public Showcase (Welcome Page)</h3>
      <ul>
        <li><b>Hero & Profile</b> — Branding interaktif dengan lokasi workshop.</li>
        <li><b>Production Gallery</b> — Showcase foto hasil karya dengan Lightbox.</li>
        <li><b>Fabric Showcase</b> — Katalog interaktif sampel kain & tekstur.</li>
        <li><b>Multi-Bahasa & Dark Mode</b> — Beralih Bahasa (ID/EN) & Mode Gelap.</li>
      </ul>
    </td>
  </tr>
</table>

---

## ⚡ Fitur Unggulan Sistem

```gantt
    title Alur Operasional Terpadu AN Mastery
    dateFormat  YYYY-MM-DD
    section Inventaris Kain
    Input Stok Kain           :done,    des1, 2026-01-01,2026-01-03
    section Order Sablon
    Pencatatan Pesanan        :active,  des2, 2026-01-03, 3d
    Proses Produksi Sablon    :         des3, after des2, 5d
    section Tagihan & Gaji
    Revisi Tagihan Supplier   :         des4, after des2, 4d
    Rekap Absensi & Gaji      :         des5, 2026-01-10, 2d
```

| Fitur                            | Deskripsi                                                                               | Teknologi Pendukung                                   |
| :------------------------------- | :-------------------------------------------------------------------------------------- | :---------------------------------------------------- |
| **🚀 Client-side DataTables**    | Rendering tabel instan berbasis JSON API Resource tanpa reload halaman.                 | `jQuery DataTables`, `ApiProvider`, `Axios`           |
| **🎴 Dynamic CardGrid**          | Visualisasi data berbentuk kartu interaktif asinkron untuk katalog & galeri.            | `cardgrid.js`, `Preline UI`                           |
| **🌐 Multi-Language (i18n)**     | Lokalisasi lengkap Bahasa Indonesia (`id`) & English (`en`) hingga ke objek JavaScript. | `lang.md`, `trans.js`, `lang.blade.php`               |
| **🌙 Dark & Light Mode**         | Peralihan mode tampilan otomatis / manual tersimpan di `localStorage`.                  | `toggle-dark-mode.js`, `theme.css`                    |
| **🔐 Granular Permission**       | Otorisasi hak akses berbasis Role & Permission per modul.                               | `Spatie Laravel Permission`                           |
| **📸 FilePond & Camera Capture** | Upload media terkompresi otomatis & capture foto via webcam browser.                    | `FilePond`, `camera-capture.js`, `image-processor.js` |

---

## 🛠️ Arsitektur Sistem (Hybrid Monolith)

Aplikasi dibangun menggunakan pola **Hybrid Monolith** dengan arsitektur berlapis (_Layered Architecture_) yang bersih:

```mermaid
graph LR
    subgraph Client ["Client Side (Browser)"]
        UI[Blade Views + Preline UI]
        JS[PageScript IIFE + AlpineJS]
        API_CLIENT[ApiProvider Axios]
    end

    subgraph Backend ["Backend Side (Laravel 12)"]
        ROUTE[Web / API Routing]
        CTRL[Controller Coordinator]
        REQ[Form Request Validation]
        REPO[Repository Query Class]
        ACT[Action Transaction Class]
        RES[API Resource JSON]
        DB[(Database MySQL)]
    end

    UI -->|DOM Action| JS
    JS -->|AJAX Call| API_CLIENT
    API_CLIENT -->|HTTP Request| ROUTE
    ROUTE --> CTRL
    CTRL -->|Validate| REQ
    CTRL -->|Read Query| REPO
    CTRL -->|Write Transaction| ACT
    REPO --> DB
    ACT --> DB
    CTRL -->|Format JSON| RES
    RES -->|Response| API_CLIENT
```

---

## 💻 Tech Stack Overview

<div align="center">

| Kategori              | Teknologi Utama                                                                                                                                                                                                                                                                                                                    |
| :-------------------- | :--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Backend Framework** | ![Laravel](https://img.shields.io/badge/-Laravel_12-FF2D20?style=flat-square&logo=laravel&logoColor=white) ![PHP](https://img.shields.io/badge/-PHP_8.2+-777BB4?style=flat-square&logo=php&logoColor=white)                                                                                                                        |
| **Frontend Styling**  | ![Tailwind CSS](https://img.shields.io/badge/-Tailwind_CSS_v4-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white) ![Preline UI](https://img.shields.io/badge/-Preline_UI_v3-0066FF?style=flat-square&logo=preline&logoColor=white)                                                                                         |
| **Frontend Logic**    | ![AlpineJS](https://img.shields.io/badge/-Alpine.js_v3-8BC0D0?style=flat-square&logo=alpine.js&logoColor=white) ![jQuery](https://img.shields.io/badge/-jQuery-0769AD?style=flat-square&logo=jquery&logoColor=white) ![JavaScript](https://img.shields.io/badge/-ES6+_JS-F7DF1E?style=flat-square&logo=javascript&logoColor=black) |
| **Packages**          | `Spatie Permission`, `Spatie MediaLibrary`, `Laravel Fortify`, `Tightenco Ziggy`                                                                                                                                                                                                                                                   |
| **Build & Tooling**   | ![Vite](https://img.shields.io/badge/-Vite-646CFF?style=flat-square&logo=vite&logoColor=white) ![Composer](https://img.shields.io/badge/-Composer-885630?style=flat-square&logo=composer&logoColor=white)                                                                                                                          |

</div>

---

## 📂 Struktur Direktori Utama

<details>
<summary><b>🔍 Klik untuk melihat pohon direktori proyek</b></summary>

```text
an-mastery-v3/
├── .ai/                       # Knowledge Base & Panduan AI Agent (.ai/*.md)
├── AGENTS.md                  # AI Agent Entry Point Guide
├── app/
│   ├── Actions/               # Logika Bisnis Penulisan / Transaksi (Store/Update)
│   ├── Console/Commands/      # Artisan Command (MakeModuleCommand)
│   ├── Helpers/               # Helper Kustom (RupiahHelper)
│   ├── Http/
│   │   ├── Controllers/       # Controller HTTP & API
│   │   ├── Requests/          # Form Request Validation
│   │   └── Resources/         # API Resources untuk Standarisasi JSON Response
│   ├── Models/                # Eloquent Models
│   └── Repositories/          # Logika Kueri Pembacaan Data
├── database/
│   ├── migrations/            # Migrasi Skema Database
│   └── seeders/               # Data Awal / Seeder
├── lang/
│   ├── en/                    # Kamus Bahasa Inggris (welcome.php, models.php, dll)
│   └── id/                    # Kamus Bahasa Indonesia (welcome.php, models.php, dll)
├── public/
│   ├── assets/                # Logo (.webp, .ico) & Gambar Statis
│   └── js/ & css/             # Library Javascript & CSS Publik
├── resources/
│   ├── css/                   # Stylesheet Tailwind CSS v4 & Custom Component CSS
│   ├── js/                    # Javascript Spesifik Modul & 30+ Utilities (ApiProvider)
│   └── views/                 # Blade Layouts, Components, & View per Modul
└── routes/
    └── web.php                # Perutean Web & API Utama
```

</details>

---

## 🚀 Langkah Instalasi & Pengoperasian Lokal

1. **Clone Repositori & Masuk ke Direktori**:

    ```bash
    git clone https://github.com/RendyAFS/an-mastery-v3.git
    cd an-mastery-v3
    ```

2. **Instal Dependensi PHP & JavaScript**:

    ```bash
    composer install
    npm install
    ```

3. **Konfigurasi Environment (`.env`)**:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    > Sesuaikan variabel `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` dengan kredensial database lokal Anda.

4. **Jalankan Migrasi & Seeder Database**:

    ```bash
    php artisan migrate --seed
    ```

5. **Jalankan Server Pengembang**:

    ```bash
    # Jalankan Vite Hot Module Replacement (HMR)
    npm run dev

    # Jalankan Web Server Laravel (Terminal terpisah)
    php artisan serve
    ```

    Aplikasi dapat diakses melalui browser di `http://127.0.0.1:8000`.

---

## 🏷️ Pengaturan & Pengecekan Versi Aplikasi (App Version & PWA)

Versi aplikasi dikelola secara terpusat dan otomatis tersinkronisasi ke seluruh komponen antarmuka pengguna serta Service Worker PWA.

### 1. Cara Mengatur / Mengubah Versi

Set variabel versi di file `.env` (atau di [`config/app.php`](file:///D:/laragon/www/an-mastery-v3/config/app.php)):

```env
APP_VERSION=1.1.9
```

### 2. Lokasi Tampilan Versi di Aplikasi

Nilai versi aplikasi secara otomatis muncul di:

- **Navbar Header** (di bawah logo _AN Mastery_)
- **Sidebar Navigation** (di bawah logo _AN Mastery_)
- **Splash Screen Loader** (saat pemuatan aplikasi)
- **Footer Landing Page**
- **Dynamic PWA Service Worker** (`/sw.js` -> `CACHE_NAME = "an-mastery-v1.1.9"`)

### 3. Cara Mengecek Versi via Terminal / CLI

Anda dapat memeriksa versi aplikasi yang sedang aktif dari terminal menggunakan perintah Artisan:

```bash
php artisan config:show app.version
```

---

## 📄 Lisensi & Kredit

<div align="center">

Hak Cipta © 2026 **AN Mastery** — Dibuat untuk **Andri Sablon, Gedangsewu — Tulungagung**.<br>
_Designed with precision & engineered for performance._

</div>
