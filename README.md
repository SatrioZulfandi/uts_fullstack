# Smart-Hub Management System

Smart-Hub adalah aplikasi web berbasis Laravel untuk mengelola peminjaman inventaris seperti ruang kerja (workspace) dan perlengkapan studio (equipment). Aplikasi ini menyediakan Dashboard Admin dengan desain modern (terinspirasi dari Linear.app) dan REST API untuk aplikasi sisi member.

## 🚀 Fitur Utama

### 🖥️ Admin Dashboard (Web Panel)
- **Otentikasi:** Login khusus Administrator.
- **Manajemen Inventaris:** CRUD (Create, Read, Update, Delete) untuk workspace dan perlengkapan dengan status ketersediaan (*available, maintenance, borrowed*).
- **Jadwal Peminjaman:** Mengelola jadwal peminjaman dari member.
- **Statistik:** Ringkasan jumlah inventaris, status, dan peminjaman aktif.
- **UI/UX Modern:** Tampilan *Light Mode* yang bersih dan premium menggunakan Vanilla CSS.

### 📱 Member Frontend (BFF Architecture)
- **Terpisah:** Frontend untuk Member dikelola di repositori terpisah (`smart-hub-frontend`).
- **Tech Stack:** Laravel 13, Vue 3, Inertia.js, TailwindCSS.
- **BFF Pattern:** Tidak menggunakan koneksi database langsung, melainkan menggunakan `smart_hub.token` di Server File Session dan mengkonsumsi REST API Backend.

### 📱 REST API Lengkap
- **Otentikasi:** Login via Laravel Sanctum (Bearer Token).
- **Member API:**
  - Lihat daftar inventaris.
  - Lihat daftar jadwal peminjaman (`/api/my-schedules`).
  - Proses check-in peralatan secara atomik (`/api/check-in`).
- **Admin API:**
  - Manajemen penuh (CRUD) untuk *Inventory* dan *Schedules*.
  - Lookup daftar *Members*.
  - *Catatan:* Semua route Admin dilindungi oleh validasi Role Admin.

---

## 🛠️ Tech Stack

- **Framework:** Laravel 13
- **PHP:** >= 8.3
- **Database:** Supabase PostgreSQL (Production) / MySQL (Local Dev)
- **Frontend:** Blade Templating + Vanilla CSS (Custom Linear-style UI)
- **API Authentication:** Laravel Sanctum

---

## ☁️ Cloud Database (Supabase PostgreSQL)

Aplikasi ini menggunakan **Supabase PostgreSQL** sebagai database utama. Arsitektur backend hanya menggunakan koneksi PDO standar tanpa Supabase Auth atau Service-Role Key.

1. **Requirement:** Pastikan ekstensi `pdo_pgsql` aktif di file `php.ini` Anda.
2. **Koneksi:** Karena pengembangan menggunakan jaringan tanpa IPv6, koneksi wajib menggunakan fitur **Session Pooler** dari Supabase.
3. **Environment:** Di file `.env`, gunakan parameter berikut (jangan pernah commit password ke Git):
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=<supabase-session-pooler-host>
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=<supabase-username>
   DB_PASSWORD=<supabase-password>
   DB_SSLMODE=require
   ```
4. **Setup:**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```
> ⚠️ **PERINGATAN TESTING**: Jangan pernah menjalankan perintah *automated test*, `migrate:fresh`, atau `db:wipe` jika `.env` masih mengarah ke database production Supabase ini. Selalu gunakan MySQL lokal yang terpisah untuk testing.

*Dokumentasi lengkap deploy Supabase:* [docs/uas/06-supabase-deployment.md](docs/uas/06-supabase-deployment.md)

---

## ⚙️ Cara Instalasi (MySQL Lokal)

Jika Anda ingin menjalankan secara murni di MySQL lokal tanpa koneksi internet/Supabase:

1. **Clone repositori ini**
   ```bash
   git clone https://github.com/SatrioZulfandi/uts_fullstack.git
   cd uts_fullstack
   ```

2. **Install dependensi PHP**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env`.
   ```bash
   cp .env.example .env
   ```
   Atur koneksi database Anda di file `.env` (kembalikan ke `mysql`):
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeding**
   Jalankan migrasi beserta data awal (admin & member).
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000` di browser.

---

## 🔐 Kredensial Default

Setelah menjalankan `php artisan migrate:fresh --seed`, Anda bisa menggunakan akun berikut:

**Administrator (Akses Dashboard Web)**
- **Email:** `admin@smarthub.com`
- **Password:** `password`

**Member (Akses REST API / Sanctum)**
- **Email:** `satrio@member.com` | `zulfandi@member.com`
- **Password:** `password`

---

## 🧪 Testing

Jalankan pengujian automated API dan fungsionalitas dengan perintah berikut:
```bash
php artisan test
```
*Pastikan konfigurasi database di phpunit.xml sudah sesuai dengan environment Anda (disarankan menggunakan database MySQL terpisah untuk testing seperti `db_uts_fullstack_testing`).*

---

## 📝 Catatan Tambahan

- Akses ke rute `/admin/*` (Web & API) dilindungi oleh `AdminMiddleware` yang menolak akses bagi akun dengan role `member` (menghasilkan *403 Forbidden*).
- Gunakan header `Accept: application/json` dan `Authorization: Bearer {token}` untuk menguji REST API. Anda harus menembak `/api/login` terlebih dahulu untuk mendapatkan token.
- **Dokumentasi Lengkap API:** Silakan baca [03-api-documentation.md](docs/uas/03-api-documentation.md)
- **Laporan Testing API:** Silakan baca [04-api-test-report.md](docs/uas/04-api-test-report.md)

<p align="center">&copy; 2026 Smart-Hub Management System</p>
