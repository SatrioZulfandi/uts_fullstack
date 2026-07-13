# Deployment Database ke Supabase PostgreSQL

Dokumentasi ini merangkum proses migrasi dari database lokal MySQL ke Supabase PostgreSQL untuk backend REST API Laravel 13 Smart-Hub.

## A. Arsitektur

```text
Laravel 13 REST API
→ PDO PostgreSQL (pdo_pgsql)
→ Supabase PostgreSQL (Session Pooler)
```

**Alasan Pemilihan Pooler**: Komputer/jaringan lokal pengembangan tidak mendukung IPv6 yang dibutuhkan untuk direct connection Supabase. Oleh karena itu, koneksi dilakukan melalui Supavisor Session Pooler (port 5432) yang menyediakan endpoint IPv4 dan kompatibel penuh dengan skenario transaksi aplikasi ini.

## B. Persiapan

1. Membuat Supabase project baru khusus untuk UTS.
2. Mengambil informasi koneksi dari pengaturan Database > Connection string > Session Pooler.
3. Memastikan ekstensi PHP `pdo_pgsql` aktif di server backend lokal.

## C. Environment

Konfigurasi berikut ditambahkan pada file `.env` (jangan dimasukkan ke Git):

```env
DB_CONNECTION=pgsql
DB_HOST=<supabase-pooler-host>
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=<supabase-username>
DB_PASSWORD=<supabase-password>
DB_SSLMODE=require
```

Pada file `.env.example`, konfigurasi tersebut hanya berupa placeholder kosong.

## D. Migration

Terdapat kompatibilitas penuh dari migration bawaan Laravel Schema Builder terhadap PostgreSQL, sehingga tidak banyak modifikasi yang diperlukan pada migration. 

Dua penyesuaian khusus dilakukan pada schema dan logic aplikasi:
1. **Case-insensitive Search**: Diubah dari `LIKE` menjadi `whereLike()` di kontroler karena PostgreSQL `LIKE` bersifat case-sensitive. `whereLike()` adalah fungsi agnostik database di Laravel 13 yang memastikan testing di MySQL lokal dan production PostgreSQL memiliki perilaku yang sama.
2. **Restrict Foreign Key**: Relasi `inventory_id` pada `borrowing_schedules` diubah dari cascade delete menjadi restrict delete melalui file migration baru (`2026_07_13_120545_change_inventory_foreign_key_to_restrict_on_borrowing_schedules_table`) demi menjaga konsistensi dengan logic penolakan HTTP 409 pada REST API.

**Command:**
```bash
php artisan migrate --force
php artisan db:seed --force
```

## E. Verification

- [x] Tabel `users`, `inventories`, `borrowing_schedules`, dan `personal_access_tokens` berhasil ter-migrate.
- [x] Seeder membuat 1 admin, 2 member, 4 inventaris (2 workspace, 2 equipment), dan 2 jadwal contoh.
- [x] Fitur enum dikonversi sempurna oleh Laravel menjadi `CHECK constraint` di PostgreSQL.
- [x] Restrict foreign key berhasil menjaga data.
- [x] Alur API login, schedule, inventory, check-in, dan logout berjalan mulus di atas Supabase.

## F. Testing

Semua *automated test* tetap dipertahankan pada **MySQL lokal** (`db_uts_fullstack_testing`). Test suites PostgreSQL belum ditambahkan di environment CI/CD dikarenakan ketidaktersediaan container Docker lokal saat deployment awal ini. MySQL Test Suite bertindak sebagai baseline regression tests. Testing Guard diterapkan pada file `TestCase.php` untuk menolak dijalankannya *destructive testing* (misal: refresh database) terhadap hostname Supabase. 

* **Database Testing:** MySQL (local)
* **Jumlah Test:** 29 passed
* **Jumlah Assertion:** 61 assertions

## G. Troubleshooting

- **Enum vs Native Enum:** Kita tidak menggunakan raw query untuk native enum di PostgreSQL. Kita menyerahkan ke Laravel Schema Builder yang menghasilkan `CHECK ("status" in ('available', ...))` karena pendekatan ini lebih aman saat ada update/rollback migration di masa mendatang dan berfungsi lintas database.
- **Foreign Key Cascade:** Awalnya, menghapus inventory akan otomatis menghapus schedule (cascade). Ini telah ditimpa dengan restrict migration agar API HTTP 409 sesuai dengan aturan business process.
- **Search Case Sensitivity:** Telah diatasi dengan `whereLike` Laravel 13 yang menangani string matching lowercasing secara aman untuk semua SQL flavors.

## H. Security

- [x] File `.env` dan `.env.testing` berada di `.gitignore`.
- [x] Credential **tidak** ada dalam *source code* atau Git commit history.
- [x] Supabase Auth dan Service-Role Keys **tidak digunakan**; auth secara utuh dilayani oleh backend menggunakan Laravel Sanctum.
- [x] Automated tests diblokir secara eksplisit agar tidak mengenai database Supabase (Guard di `TestCase.php`).
