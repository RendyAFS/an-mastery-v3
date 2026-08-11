# JavaScript Standards (javascript.md)

## Tujuan
Dokumen ini menjelaskan standar penulisan kode JavaScript di proyek ini untuk memastikan kebersihan kode, keamanan ruang lingkup (*scope isolation*), standardisasi pemicuan AJAX, dan integrasi komponen interaktif UI.

## Kapan digunakan
Gunakan panduan ini setiap kali Anda membuat file JS baru di folder `resources/js/pages/`, menulis interaksi DOM, memicu AJAX, mengontrol inisialisasi Preline UI, atau memproses input rupiah.

## Cara kerja
1. **Scope Isolation**: JavaScript per halaman dibungkus dalam modul IIFE kustom (`PageScript`) agar tidak mencemari objek global browser (`window`).
2. **Pemicu Awal**: Modul dieksekusi di dalam blok pemicu jQuery `$(function() { PageScript.init(); })` setelah dokumen HTML selesai dimuat (*DOM Ready*).
3. **Event Delegation**: Untuk berinteraksi dengan baris tabel dinamis yang sering berubah, event binding dipasang menggunakan delegasi dokumen `$(document).on(...)`.
4. **AJAX Standard**: Semua request asinkron diproses menggunakan Axios client terstandar `ApiProvider` untuk otomatisasi loader layar dan penanganan error terpusat.
5. **AlpineJS Bindings**: Modul interaksi baris formulir kompleks menggunakan inisialisasi reaktivitas AlpineJS yang dihubungkan dengan daur hidup PageScript.

## Struktur File JS

### Lokasi File
```
resources/
├── js/
│   ├── app.js              # Entry point utama, memuat utils global
│   ├── bootstrap.js        # Setup axios & dependensi dasar
│   ├── pages/
│   │   └── {module}/
│   │       ├── index.js           # Logika halaman list/index
│   │       ├── form.js            # Logika halaman form (create/edit)
│   │       └── alpine-component.js # Reaktivitas tabel dinamis (opsional)
│   └── utils/
│       ├── api-provider.js        # HTTP client terpusat (Axios wrapper)
│       ├── button-loading.js      # Helper loading state pada tombol submit
│       ├── camera-capture.js      # Utilitas kamera browser
│       ├── cardgrid.js            # Inisialisasi komponen card grid dinamis
│       ├── custom-alert.js        # Toast, Alert, Confirm global
│       ├── custom-select.js       # Inisialisasi select kustom
│       ├── datatable.js           # Inisialisasi komponen DataTables
│       ├── disable-number-scroll.js # Mencegah scroll pada input number
│       ├── filepond.js            # Inisialisasi FilePond upload
│       ├── fix-textarea-enter.js  # Perbaikan perilaku Enter pada textarea
│       ├── image-processor.js     # Utilitas kompresi/proses gambar
│       ├── init-theme.js          # Inisialisasi tema (dark/light)
│       ├── lang.js                # Helper akses variabel lang dari window
│       ├── loading.js             # Overlay loading layar penuh (blockUI)
│       ├── lucide.js              # Inisialisasi ikon Lucide
│       ├── normalize-form.js      # Normalisasi data FormData sebelum submit
│       ├── number-input.js        # Format input angka
│       ├── reinit-ui.js           # Reinisialisasi Preline UI setelah AJAX
│       ├── rupiah-input.js        # Format input mata uang Rupiah
│       ├── sidebar-mode.js        # Kontrol mode sidebar
│       ├── sidebar-state.js       # Persistensi state sidebar
│       ├── suppress-hsdatatable-warning.js # Suppress console warning HS
│       ├── toggle-dark-mode.js    # Toggle dark mode manual
│       ├── trans.js               # Helper terjemahan dari window lang bag
│       ├── ui-init.js             # Inisialisasi ulang seluruh komponen Preline UI
│       └── week.js                # Utilitas kalkulasi/tampilan minggu
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
// payload siap dikirim ke ApiProvider
```

---

### `trans` — `@/utils/trans`
Mengakses string terjemahan yang sudah di-inject ke `window` dari Blade, dengan dukungan placeholder.

```javascript
import trans from "@/utils/trans";

// trans(bagName, key, replacements)
// bagName: nama variabel window yang menyimpan dict bahasa
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

### `initUi` — `@/utils/ui-init`
Menginisialisasi ulang seluruh komponen Preline UI (dropdown, overlay, select, tabs, dll.) beserta Lucide icons dan RupiahInput. Diperlukan setelah konten DOM berubah via AJAX.

```javascript
import initUi from "@/utils/ui-init";

// Panggil setelah DOM diperbarui (misal: setelah modal dibuka atau datatable reload)
initUi();
```

---

### `RupiahInput` — `@/utils/rupiah-input`
Format input angka ke format mata uang Rupiah (via `Intl.NumberFormat`). Sudah diinisialisasi otomatis via `initUi()`.

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

### Global API — `Toast`, `Alert`, `Confirm`
Tersedia global di `window` (dimuat via `custom-alert.js`). **Tidak perlu di-import.**

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

// Konfirmasi hapus (teks bawaan)
const confirmed = await Confirm.delete(message?);

// Flash toast — tampil setelah redirect/reload
window.flashToast(type, title, message, timeout?);
```

---

### `Loading` — `@/utils/loading`
Overlay loading layar penuh menggunakan jQuery BlockUI. Dipanggil otomatis oleh `ApiProvider`. Gunakan hanya jika perlu kontrol manual.

```javascript
import Loading from "@/utils/loading";

Loading.start("Memproses...");
Loading.stop();
Loading.forceStop(); // Reset counter dan paksa tutup
```

## Contoh implementasi
Implementasi referensi JavaScript untuk modul CRUD modal-based:
- Contoh `index.js` lengkap: [supplier/index.js](file:///d:/laragon/www/an-mastery-v3/resources/js/pages/supplier/index.js)
- Pustaka AJAX Provider: [api-provider.js](file:///d:/laragon/www/an-mastery-v3/resources/js/utils/api-provider.js)
- Normalisasi Form: [normalize-form.js](file:///d:/laragon/www/an-mastery-v3/resources/js/utils/normalize-form.js)
- Terjemahan: [trans.js](file:///d:/laragon/www/an-mastery-v3/resources/js/utils/trans.js)

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
    const modelName = window.langModels?.ModelName ?? "Model";

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
            // error sudah ditangani ApiProvider
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
- [ ] Apakah format rupiah pada formulir telah dinormalisasi menggunakan `normalizeFormInputs()` sebelum data dikirim ke API?
- [ ] Apakah pesan CRUD menggunakan `trans()` bukan string hardcode?

## Best Practice
- **Gunakan Global Toast/Confirm**: Manfaatkan API global yang sudah terintegrasi seperti `Toast.success(title, msg)`, `Toast.error(title, msg)`, `Confirm.show(msg, title)`, dan `Confirm.delete(msg)`.
- **Loading UI Indicator**: Selalu manfaatkan helper loading dinamis `startLoading(button)` dan `stopLoading(button)` pada tombol kirim form untuk mencegah double click submit.
- **Gunakan `trans()` untuk Pesan**: Jangan hardcode string pesan CRUD. Gunakan `trans("langCrud", "key", { model: modelName })` agar mendukung i18n.
- **Reinit UI setelah AJAX**: Panggil `initUi()` setelah konten DOM diperbarui secara dinamis agar komponen Preline UI (dropdown, tooltip, dll.) berfungsi kembali.

## Catatan penting
> [!IMPORTANT]
> Jangan pernah menggunakan fungsi `alert()` bawaan browser atau pustaka modal luar. Semua dialog konfirmasi penghapusan wajib menggunakan promise `Confirm.delete()` atau `Confirm.show()` yang terintegrasi dengan desain sistem proyek ini.

> [!NOTE]
> Variabel `modelName` selalu diambil dari `window.langModels?.{ModelName}` agar terjemahan nama model konsisten di semua bahasa. Sediakan fallback string Indonesia sebagai nilai default.
