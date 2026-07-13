# Smart-Hub Management System - Backend API

Smart-Hub adalah aplikasi web berbasis Laravel untuk mengelola peminjaman inventaris seperti ruang kerja (workspace) dan perlengkapan studio (equipment). Aplikasi ini menyediakan REST API lengkap untuk otentikasi, manajemen inventaris, penjadwalan, dan transaksi check-in yang aman.

## 🚀 Fitur Utama & Struktur
- **Manajemen Inventaris:** Admin dapat mengelola *workspace* dan *equipment*.
- **Manajemen Penjadwalan:** Admin dapat membuat dan mengubah jadwal dengan mekanisme pencegahan jadwal bertabrakan (*overlap prevention*).
- **Check-in Atomik:** Mekanisme peminjaman menggunakan transaksi database dan penguncian baris (`lockForUpdate`) untuk mencegah *Double Check-in*.
- **Role-based Access Control:** Rute API terpisah untuk akses Admin dan Member, dilindungi oleh *Middleware* khusus.

## 🛠️ Tech Stack & Arsitektur
Aplikasi Backend ini beroperasi murni sebagai penyedia antarmuka REST API dan sumber kebenaran (*Source of Truth*) bagi aplikasi *Frontend*.
- **Framework:** Laravel 13
- **PHP:** >= 8.3
- **Database:** Supabase PostgreSQL (Local & Production)
- **API Authentication:** Laravel Sanctum (Token-based)

## 🔗 Tautan Repositori Pasangan
- **Frontend Repository:** [https://github.com/SatrioZulfandi/smart-hub-frontend](https://github.com/SatrioZulfandi/smart-hub-frontend)

## ⚙️ Persyaratan & Instalasi Lokal

1. **Clone repositori ini:**
   ```bash
   git clone https://github.com/SatrioZulfandi/uts_fullstack.git
   cd uts_fullstack
   ```
2. **Install Dependensi:**
   ```bash
   composer install
   ```
3. **Konfigurasi Lingkungan (.env):**
   Salin `.env.example` ke `.env`:
   ```bash
   cp .env.example .env
   ```
   *Atur kredensial Supabase PostgreSQL Anda. Jangan cantumkan password ini di GitHub.*
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=<supabase-pooler-host>
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=<supabase-username>
   DB_PASSWORD=<supabase-password>
   DB_SSLMODE=require
   ```
4. **Generate Key:**
   ```bash
   php artisan key:generate
   ```
5. **Migrasi Database & Seeding:**
   ```bash
   php artisan migrate --seed
   ```

## 🚀 Cara Menjalankan Project
Untuk melayani *request* dari Frontend, jalankan development server:
```bash
php artisan serve --port=8082
```
Backend akan berjalan di `http://127.0.0.1:8082`.
Pastikan Anda menjalankan aplikasi Frontend di terminal lain.

## 🧪 Testing & Kualitas Kode
Aplikasi dilengkapi dengan rangkaian automated test API yang menjamin stabilitas.
Untuk menjalankannya:
```bash
php artisan test
```

## 🔐 Akun Demo (Local Seeding)
- **Admin:**
  - Email: `admin@smarthub.com`
  - Password: `password`
- **Member:**
  - Email: `satrio@member.com`
  - Password: `password`

## 📖 Dokumentasi Lengkap
Dokumentasi teknis menyeluruh tersimpan di direktori `docs/uas/`:
- [02-api-gap-analysis.md](docs/uas/02-api-gap-analysis.md) - Rencana struktur API.
- [03-api-documentation.md](docs/uas/03-api-documentation.md) - Dokumentasi *endpoints*, *payloads*, dan format balasan *JSON*.
- [06-supabase-deployment.md](docs/uas/06-supabase-deployment.md) - Panduan spesifik terkait integrasi Supabase.

<p align="center">&copy; 2026 Smart-Hub Management System</p>
