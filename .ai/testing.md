# Testing Standards (testing.md)

## Tujuan
Dokumen ini mendefinisikan standar pengujian (*testing*) otomatis di aplikasi Laravel, baik menggunakan **Pest PHP** maupun **PHPUnit**. Dokumen ini memastikan setiap perubahan logika memiliki cakupan uji regresi yang memadai, andal, dan terisolasi.

## Kapan digunakan
Gunakan dokumen ini setiap kali Anda:
1. Menambahkan fitur atau logika bisnis baru (Action, Service, Controller, Model).
2. Memperbaiki bug atau melakukan refactoring kode.
3. Menulis atau memperbarui Feature Test dan Unit Test.

## Cara kerja
1. **Penegakan Tes (*Test Enforcement*)**: Setiap perubahan logika atau perilaku (*behavior*) wajib disertai atau diperbarui dengan tes otomatis jika memberikan perlindungan regresi yang bermakna. Perubahan murni teks (*copy*), layout tampilan Blade, atau styling CSS tidak memerlukan tes baru.
2. **Prioritaskan Automated Tests**: Hindari membuat skrip verifikasi ad-hoc atau pengujian manual berulang di Tinker jika fungsionalitas tersebut dapat dan seharusnya dibuktikan melalui Feature/Unit Test.
3. **Pemisahan Unit vs Feature Test**:
   - **Feature Test** (Utama): Menguji alur menyeluruh (HTTP request, middleware, database, response JSON/View, otorisasi). Sebagian besar tes aplikasi sebaiknya berupa Feature Test.
   - **Unit Test**: Menguji fungsi/kelas murni yang terisolasi tanpa interaksi penuh dengan framework/database jika tidak diperlukan.

## Struktur & Penamaan
- **Pest**: Buat tes menggunakan `php artisan make:test {NameTest} --pest` (atau `--unit --pest` untuk unit test).
- **PHPUnit**: Buat tes menggunakan `php artisan make:test {NameTest}` (atau `--unit` untuk unit test).
- **Konvensi Nama File**: Jangan sertakan direktori suite di dalam `{Name}`. Gunakan `ProductManagementTest`, bukan `Feature/ProductManagementTest`.
- **Dilarang Menghapus Tes**: Jangan menghapus file tes atau kasus uji yang sudah ada tanpa persetujuan eksplisit dari user, karena tes tersebut adalah bagian dari aset jaminan kualitas aplikasi.

## Contoh implementasi

### 1. Feature Test Menggunakan Pest
```php
use App\Models\User;
use App\Models\Product;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can display product list for authenticated user', function () {
    Product::factory()->count(3)->create();

    $this->actingAs($this->user)
        ->getJson(route('products.index'))
        ->assertOk()
        ->assertJsonStructure(['data']);
});

it('validates required fields when creating product', function () {
    $this->actingAs($this->user)
        ->postJson(route('products.store'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'price']);
});
```

### 2. Feature Test Menggunakan PHPUnit
```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_display_product_list(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson(route('products.index'));

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }
}
```

## Pemanfaatan Factory & Faker
- **Gunakan Model Factory**: Selalu buat dan gunakan Factory untuk menyiapkan data uji. Periksa apakah factory memiliki *custom states* sebelum membuat konfigurasi model secara manual.
- **Faker Helper**: Gunakan `$this->faker->word()` atau helper global `fake()->name()` / `fake()->safeEmail()` secara konsisten mengikuti pola proyek yang sudah ada.

## Cara Menjalankan Tes
Selalu jalankan kumpulan tes yang **paling sempit/spesifik** yang mencakup perubahan yang Anda buat untuk menghemat waktu:

```bash
# Menjalankan file tes tertentu via Artisan
php artisan test tests/Feature/ProductManagementTest.php

# Menjalankan tes dengan filter nama fungsi
php artisan test --filter=test_can_display_product_list --compact

# Menjalankan via Pest runner langsung
vendor/bin/pest tests/Feature/ProductManagementTest.php

# Menjalankan seluruh test suite (lakukan setelah tes spesifik berhasil)
php artisan test --compact
```

## Checklist
- [ ] Apakah fitur/perubahan logika baru sudah dilengkapi dengan Feature Test?
- [ ] Apakah pengujian mencakup skenario sukses (*happy path*) dan skenario gagal/validasi penting?
- [ ] Apakah data uji dibuat menggunakan Model Factory alih-alih insert mentah?
- [ ] Apakah tes yang terpengaruh sudah dijalankan dan dipastikan berstatus lulus (*passed*)?
- [ ] Apakah tidak ada file tes lama yang terhapus tanpa izin?

## Best Practice
- **Jalankan Ulang Setelah Setiap Edit**: Setiap kali mengubah file kode atau file tes, jalankan kembali tes terkait untuk memverifikasi tidak ada regresi.
- **Isolasi Database**: Gunakan trait `RefreshDatabase` atau `DatabaseMigrations` agar data uji tidak mengotori environment database lokal.
- **Tutup Celah Kegagalan**: Uji otorisasi (apakah user tanpa izin mendapat status 403) dan validasi (apakah payload invalid mendapat status 422).
