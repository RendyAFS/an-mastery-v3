# JavaScript Standards (javascript.md)

## Tujuan
Dokumen ini menjelaskan standar penulisan kode JavaScript di proyek ini untuk memastikan kebersihan kode, keamanan ruang lingkup (*scope isolation*), standardisasi pemicuan AJAX, dan integrasi komponen interaktif UI serta pustaka utilitas JavaScript yang tersedia.

## Kapan digunakan
Gunakan panduan ini setiap kali Anda membuat file JS baru di folder `resources/js/pages/`, menulis interaksi DOM, memicu AJAX, mengontrol inisialisasi Preline UI, mengelola form upload/kamera, atau memproses utilitas input.

## Cara kerja
1. **Scope Isolation**: JavaScript per halaman dibungkus dalam modul IIFE kustom (`PageScript`) agar tidak mencemari objek global browser (`window`).
2. **Pemicu Awal**: Modul dieksekusi di dalam blok pemicu jQuery `$(function() { PageScript.init(); })` setelah dokumen HTML selesai dimuat (*DOM Ready*).
3. **Event Delegation**: Untuk berinteraksi dengan baris tabel/grid dinamis yang sering berubah, event binding dipasang menggunakan delegasi dokumen `$(document).on(...)`.
4. **AJAX Standard**: Semua request asinkron diproses menggunakan Axios client terstandar `ApiProvider` untuk otomatisasi loader layar dan penanganan error terpusat.
5. **AlpineJS Bindings**: Modul interaksi baris formulir kompleks menggunakan inisialisasi reaktivitas AlpineJS yang dihubungkan dengan daur hidup PageScript.
6. **Modular Utilities**: Pustaka utilitas terpusat di `resources/js/utils/` digunakan untuk menangani fungsi spesifik seperti format mata uang, upload file, capture kamera, filter storage, dan inisialisasi komponen UI.

## Struktur File JS

### Lokasi File
```
resources/
├── js/
│   ├── app.js              # Entry point utama, memuat utils global & Preline UI
│   ├── bootstrap.js        # Setup axios & dependensi dasar
│   ├── pages/
│   │   └── {module}/
│   │       ├── index.js            # Logika halaman list/index
│   │       ├── form.js             # Logika halaman form (create/edit)
│   │       └── alpine-component.js  # Reaktivitas tabel dinamis (opsional)
│   └── utils/
│       ├── api-provider.js         # HTTP client terpusat (Axios wrapper)
│       ├── button-group.js         # Pengelolaan tombol grup (single & multi select)
│       ├── button-loading.js       # Helper loading state pada tombol submit
│       ├── camera-capture.js       # Utilitas capture foto via kamera browser
│       ├── cardgrid.js             # Inisialisasi komponen card grid dinamis
│       ├── custom-alert.js         # Toast, Alert, Confirm global (window.Toast, dll)
│       ├── custom-select.js        # Inisialisasi HSSelect dengan clear button
│       ├── datatable.js            # Inisialisasi komponen DataTables dinamis
│       ├── disable-number-scroll.js# Mencegah scroll mouse merubah input number
│       ├── filepond.js             # Integration FilePond upload & preview
│       ├── filter-storage.js       # Persistensi parameter filter via sessionStorage
│       ├── fix-textarea-enter.js   # Perbaikan perilaku Enter pada textarea
│       ├── flatpickr-init.js       # Inisialisasi Flatpickr & date range minggu
│       ├── image-processor.js      # Utilitas kompresi & resize gambar client-side
│       ├── init-filter-storage.js  # Auto restore filter dari storage saat load
│       ├── init-theme.js           # Inisialisasi tema awal (dark/light)
│       ├── lang.js                 # Helper akses variabel lang dari window
│       ├── loading.js              # Overlay loading layar penuh (blockUI)
│       ├── lucide.js               # Inisialisasi ikon Lucide
│       ├── normalize-form.js       # Normalisasi FormData sebelum submit AJAX
│       ├── number-input.js         # Helper increment, decrement, & normalize angka
│       ├── reinit-ui.js            # Reinisialisasi cepat Preline UI setelah AJAX
│       ├── rupiah-input.js         # Format & unformat input mata uang Rupiah
│       ├── sidebar-mode.js         # Kontrol mode & perilaku sidebar (compact/full)
│       ├── splash-screen.js        # Helper animasi / kontrol splash screen
│       ├── suppress-hsdatatable-warning.js # Suppress warning HSDataTables di console
│       ├── toggle-dark-mode.js     # Toggle dark mode & switch event listener
│       ├── trans.js                # Helper terjemahan i18n dari window lang bag
│       ├── ui-init.js              # Inisialisasi ulang seluruh komponen UI & Lucide
│       └── week.js                 # Utilitas kalkulasi rentang tanggal minggu
```

### Struktur PageScript
Setiap halaman JavaScript standar (`index.js` atau `form.js`) terstruktur sebagai berikut:
- **Bagian Import**: Memuat dependensi global kustom (misal: `ApiProvider`, `initDatatable`, `normalizeFormInputs`).
- **Deklarasi PageScript**: Fungsi modul IIFE utama.
  - Deklarasi state internal halaman (misal: variabel datatable, parameter filter).
  - Deklarasi helper rendering internal halaman (misal: `renderCard`, `reloadDatatable`).
  - Fungsi submit form (`submitForm`) dibungkus blok `try-catch-finally` untuk memastikan loading state berhenti.
  - Fungsi penanganan aksi (`handleCreate`, `handleEdit`, `handleDelete`).
  - Fungsi binding event DOM (`bindEvents`).
  - Pengembalian method publik (`return { init() { ... } }`).
- **DOM Ready Listener**: Memicu `PageScript.init()`.

## Referensi Utils

### `ApiProvider` — `@/utils/api-provider`
HTTP client berbasis Axios dengan interceptor otomatis untuk loading overlay dan error handling terpusat.

```javascript
import ApiProvider from "@/utils/api-provider";

// GET dengan optional query params
const response = await ApiProvider.get(url, params);

// POST data
const response = await ApiProvider.post(url, payload);

// PUT / update
const response = await ApiProvider.put(url, payload);

// DELETE
const response = await ApiProvider.delete(url);
```
> Error HTTP (401, 403, 404, 419, 422, 500) ditangani otomatis dan ditampilkan via `Toast.error()`.

---

### `startLoading` / `stopLoading` — `@/utils/button-loading`
Mengontrol visual loading state pada elemen tombol submit agar user tidak bisa double-click.

```javascript
import { startLoading, stopLoading } from "@/utils/button-loading";

// Aktifkan loading pada tombol (disable + tampilkan spinner)
startLoading(buttonElement);

// Kembalikan tombol ke state normal
stopLoading(buttonElement);
```
> Tombol di Blade harus memiliki atribut `data-button-loading`, `data-spinner`, `data-text`, dan `data-loading-text`.

---

### `normalizeFormInputs` — `@/utils/normalize-form`
Menormalisasi data form sebelum dikirim ke API. Menangani checkbox, radio, file, dan input kosong (`"" → null`).

```javascript
import normalizeFormInputs from "@/utils/normalize-form";

const formData = new FormData(form);
let payload = Object.fromEntries(formData.entries());
payload = normalizeFormInputs(form, payload);
```

---

### `trans` — `@/utils/trans`
Mengakses string terjemahan yang di-inject ke `window` dari Blade, dengan dukungan placeholder.

```javascript
import trans from "@/utils/trans";

// trans(bagName, key, replacements)
const label = trans("langCrud", "add_title", { model: modelName });
const msg   = trans("langCrud", "deleted", { model: modelName });
```

---

### `initDatatable` — `@/utils/datatable`
Inisialisasi DataTables dengan konfigurasi standar proyek (AJAX, filter, row click).

```javascript
import initDatatable from "@/utils/datatable";

datatable = initDatatable({
    table: "#my-datatable",
    filterSelector: "#filter-input",
    onRowClick: (row) => handleEdit(row.id),
    ajax: {
        url: route("module.index"),
        method: "GET",
        dataSrc: "data",
        data: function (d) {
            d.filter = $("#filter-input").val();
        },
    },
    columns: [ /* definisi kolom DataTables */ ],
});

// Reload tanpa reset halaman
datatable.ajax.reload(null, false);
```

---

### `initCardGrid` — `@/utils/cardgrid`
Inisialisasi komponen Grid Kartu Dinamis berbasis AJAX.

```javascript
import initCardGrid from "@/utils/cardgrid";

const cardGrid = initCardGrid({
    containerSelector: "#card-grid-container",
    renderCard: (item) => `<div class="card">${item.name}</div>`,
    ajax: {
        url: route("module.index"),
        data: (d) => ({ search: d.search }),
    },
});
```

---

### `initUi` / `reinitUi` — `@/utils/ui-init` & `@/utils/reinit-ui`
Menginisialisasi ulang seluruh komponen Preline UI (dropdown, overlay, select, tabs, dll.) beserta Lucide icons dan RupiahInput setelah manipulasi DOM via AJAX.

```javascript
import initUi from "@/utils/ui-init";
import reinitUi from "@/utils/reinit-ui";

// Panggil setelah DOM diperbarui
initUi();
// Atau gunakan reinitUi() untuk penyegaran cepat elemen Preline
reinitUi();
```

---

### `RupiahInput` — `@/utils/rupiah-input`
Format input angka ke format mata uang Rupiah (`Intl.NumberFormat`). Sudah diinisialisasi otomatis via `initUi()`.

```javascript
import RupiahInput from "@/utils/rupiah-input";

// Format ulang satu input secara manual
RupiahInput.refresh(inputElement);

// Format nilai mentah ke string Rupiah
RupiahInput.format("150000"); // → "150.000"

// Ambil nilai numerik dari string Rupiah
RupiahInput.unformat("150.000"); // → "150000"
```

---

### `NumberInput` — `@/utils/number-input`
Utilitas pemrosesan dan normalisasi nilai input numerik.

```javascript
import NumberInput from "@/utils/number-input";

const nextVal = NumberInput.increment(currentVal, step);
const prevVal = NumberInput.decrement(currentVal, min, step);
const cleanVal = NumberInput.normalize(inputVal, defaultMin);
```

---

### `initFlatpickrAll` / `getFlatpickrInstance` — `@/utils/flatpickr-init`
Inisialisasi input tanggal Flatpickr dengan lokalisasi Bahasa Indonesia dan dukungan date range minggu.

```javascript
import { initFlatpickrAll, getFlatpickrInstance } from "@/utils/flatpickr-init";

// Inisialisasi elemen [data-flatpickr]
initFlatpickrAll();

// Ambil instance spesifik via ID
const picker = getFlatpickrInstance("date-picker-id");
```

---

### `FilePondHelper` — `@/utils/filepond`
Inisialisasi komponen upload gambar/file berbasis FilePond dengan integrasi kompresi otomatis.

```javascript
import FilePondHelper from "@/utils/filepond";

const pond = FilePondHelper.init({
    selector: "#avatar-upload",
    uploadUrl: route("upload.temp"),
    deleteUrl: route("upload.revert"),
    acceptedFileTypes: ["image/png", "image/jpeg"],
    isCircle: true,
    maxSize: 2048, // KB
});
```

---

### `processImageFile` — `@/utils/image-processor`
Kompresi dan penyesuaian ukuran gambar di browser sebelum dikirim ke server.

```javascript
import processImageFile from "@/utils/image-processor";

const compressedBlob = await processImageFile(file, {
    maxSizeBytes: 1024 * 1024,
    mimeType: "image/png",
});
```

---

### `CameraCapture` — `@/utils/camera-capture`
Utilitas pengambil foto menggunakan webcam/kamera perangkat browser.

```javascript
import CameraCapture from "@/utils/camera-capture";

CameraCapture.start("#video-preview", { width: 1280, height: 720 });
const imageBlob = await CameraCapture.takePhoto();
CameraCapture.stop();
```

---

### `FilterStorage` — `@/utils/filter-storage` & `@/utils/init-filter-storage`
Helper menyimpan dan mengembalikan state filter URL menggunakan `sessionStorage` per halaman.

```javascript
import FilterStorage from "@/utils/filter-storage";

// Load filter tersimpan
const params = FilterStorage.loadFilterParams();

// Simpan state filter baru ke URL & sessionStorage
FilterStorage.saveFilterParams(params);
```

---

### `ButtonGroup` / `setButtonGroupValue` — `@/utils/button-group`
Mengelola state aktif/inaktif tombol pilihan grup (single select atau multi select).

```javascript
// Atur nilai grup tombol secara programatis via ID input & container
window.setButtonGroupValue("status-input-id", "active");
```
> HTML elemen wajib menggunakan atribut `data-button-group`, `data-target="input_id"`, dan elemen tombol `.btn-group-item[data-value="..."]`.

---

### `CustomSelect` — `@/utils/custom-select`
Inisialisasi komponen `select[data-hs-select]` dengan tombol reset (`data-clear-select`).

```html
<!-- HTML structure -->
<select id="category_select" data-hs-select>...</select>
<button type="button" data-clear-select="category_select">Clear</button>
```

---

### `Week` / `WeekUtils` — `@/utils/week`
Helper untuk kalkulasi minggu dan format rentang tanggal minggu.

```javascript
import Week from "@/utils/week";

const currentWeek = Week.getCurrentWeek();
const dateRange = Week.getWeekDateRange(year, weekNumber);
```

---

### Global API Alert & Toast — `Toast`, `Alert`, `Confirm`, `flashToast`
Tersedia secara global di window (dimuat via `custom-alert.js`). **Tidak perlu di-import.**

```javascript
// Toast Notification
Toast.success(title, message, timeout?);
Toast.error(title, message, timeout?);
Toast.info(title, message, timeout?);
Toast.warning(title, message, timeout?);

// Modal Alert (satu tombol OK)
Alert.success(message, title?, confirmText?);
Alert.error(message, title?, confirmText?);

// Modal Konfirmasi (Promise-based)
const confirmed = await Confirm.show(message, title?, confirmText?, cancelText?);
const confirmed = await Confirm.delete(message?);

// Flash toast — tampil setelah redirect/reload
window.flashToast(type, title, message, timeout?);
```

---

### `Loading` — `@/utils/loading`
Overlay loading layar penuh menggunakan jQuery BlockUI. Dipanggil otomatis oleh `ApiProvider`.

```javascript
import Loading from "@/utils/loading";

Loading.start("Memproses...");
Loading.stop();
Loading.forceStop(); // Reset counter dan paksa tutup
```

---

### Utilitas Tema & UI Lainnya
- **`toggle-dark-mode.js` / `init-theme.js`**: Mengelola perpindahan tema gelap/terang.
- **`sidebar-mode.js`**: Mengontrol status lipatan (*collapse*) sidebar admin.
- **`disable-number-scroll.js`**: Mencegah perubahan nilai input number secara tidak sengaja saat mouse scroll.
- **`fix-textarea-enter.js`**: Menangani penekanan tombol Enter pada textarea dalam form.
- **`splash-screen.js`**: Mengontrol visibilitas splash screen awal aplikasi.

## Contoh implementasi
Implementasi referensi JavaScript standar untuk modul CRUD modal-based:
- Script Utama: `resources/js/pages/{module}/index.js`
- HTTP Client: `resources/js/utils/api-provider.js`
- Form Normalizer: `resources/js/utils/normalize-form.js`
- Translation Helper: `resources/js/utils/trans.js`

## Contoh kode
Pola standar PageScript untuk `index.js` modul CRUD dengan modal:

```javascript
import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import trans from "@/utils/trans";

const PageScript = (function () {
    let datatable;
    let form;
    const modelName = window.langModels?.ModelName ?? "Item";

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    const submitForm = async (submitter) => {
        const mode = form.dataset.mode;
        const id   = form.dataset.id;

        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());
        payload = normalizeFormInputs(form, payload);

        try {
            if (mode === "create") {
                await ApiProvider.post(route("module.store"), payload);
                Toast.success(window.langCustomAlert.success, trans("langCrud", "created", { model: modelName }));
            }
            if (mode === "edit") {
                await ApiProvider.put(route("module.update", id), payload);
                Toast.success(window.langCustomAlert.success, trans("langCrud", "updated", { model: modelName }));
            }
            closeModal();
            reloadDatatable();
        } catch (error) {
            // error sudah ditangani otomatis oleh ApiProvider
        } finally {
            stopLoading(submitter);
        }
    };

    const handleDelete = async (id) => {
        const confirmed = await Confirm.delete();
        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("module.destroy", id));
            Toast.success(window.langCustomAlert.success, trans("langCrud", "deleted", { model: modelName }));
            reloadDatatable();
        } catch (error) {
            console.error("Delete error:", error);
        }
    };

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", function () {
            handleDelete($(this).data("id"));
        });

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitter = e.submitter;
            if (submitter?.hasAttribute("data-button-loading")) {
                startLoading(submitter);
            }
            await submitForm(submitter);
        });
    };

    return {
        init() {
            form = document.getElementById("my-form");
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
```

## Hubungan dengan file lain
- JavaScript ini dikaitkan ke dalam halaman Blade (`blade.md`) via direktif `@vite('resources/js/pages/{module}/index.js')`.
- JavaScript berkoordinasi erat dengan komponen pembantu dinamis `datatable.md` dan `cardgrid.md`.
- Variabel bahasa (`window.langCrud`, `window.langModels`, dll.) di-inject dari controller via Blade, mengikuti standar `lang.md`.

## Checklist
- [ ] Apakah seluruh kode Javascript ditaruh di dalam struktur modular IIFE `PageScript`?
- [ ] Apakah event handler untuk aksi dalam tabel/grid menggunakan delegasi dokumen `$(document).on(...)`?
- [ ] Apakah fungsi `submitForm` Anda telah dibungkus blok `try-catch-finally` yang menghentikan loading tombol (`stopLoading`)?
- [ ] Apakah data formulir telah dinormalisasi menggunakan `normalizeFormInputs()` sebelum data dikirim ke API?
- [ ] Apakah pesan CRUD menggunakan `trans()` bukan string hardcode?

## Best Practice
- **Gunakan Global Toast/Confirm**: Manfaatkan API global yang sudah terintegrasi seperti `Toast.success(title, msg)`, `Toast.error(title, msg)`, `Confirm.show(msg, title)`, dan `Confirm.delete(msg)`.
- **Loading UI Indicator**: Selalu manfaatkan helper loading dinamis `startLoading(button)` dan `stopLoading(button)` pada tombol kirim form untuk mencegah double click submit.
- **Gunakan `trans()` untuk Pesan**: Jangan hardcode string pesan CRUD. Gunakan `trans("langCrud", "key", { model: modelName })` agar mendukung i18n.
- **Reinit UI setelah AJAX**: Panggil `initUi()` atau `reinitUi()` setelah konten DOM diperbarui secara dinamis agar komponen Preline UI (dropdown, tooltip, dll.) berfungsi kembali.

## Catatan penting
> [!IMPORTANT]
> Jangan pernah menggunakan fungsi `alert()` bawaan browser atau pustaka modal luar. Semua dialog konfirmasi penghapusan wajib menggunakan promise `Confirm.delete()` atau `Confirm.show()` yang terintegrasi dengan desain sistem proyek ini.

> [!NOTE]
> Variabel `modelName` selalu diambil dari `window.langModels?.{ModelName}` agar terjemahan nama model konsisten di semua bahasa. Sediakan fallback string sebagai nilai default.

