# Upgrade to Laravel 13 Documentation

## Informasi Awal
- **Versi Laravel sebelumnya:** 12.58.0
- **Versi PHP sebelumnya:** 8.5.7
- **Dependency utama sebelumnya:**
  - `laravel/framework`: ^12.0
  - `laravel/tinker`: ^2.10.1
  - `phpunit/phpunit`: ^11.5.50
- **Hasil baseline:** Aplikasi berfungsi, API berjalan, dan automated test dasar (1 berhasil, 1 gagal default) sesuai dengan kondisi pra-upgrade.

## Perubahan yang Dilakukan
- **PHP Requirement:** Tetap di `^8.3` (Sesuai Laravel 13).
- **Laravel Framework:** Diperbarui menjadi `^13.0` (Terinstal `13.19.0`).
- **Laravel Tinker:** Diperbarui menjadi `^3.0` (Terinstal `3.0.2`).
- **PHPUnit:** Diperbarui menjadi `^12.0` (Terinstal `12.5.31`).
- **Sanctum:** Diperbarui secara pasif melalui pembaruan dependency.
- **Laravel UI:** Diperbarui secara pasif.
- **Package lain yang berubah:**
  - `guzzlehttp/guzzle`: 7.10.0 -> 7.14.1
  - `nesbot/carbon`: 3.11.4 -> 3.13.1
  - `symfony/*`: 7.4.x -> 8.1.x
- **File aplikasi yang diubah:**
  - `composer.json`: Untuk pembaruan versi.
- **Breaking change yang relevan:** Tidak ada. Kode sudah kompatibel dengan perubahan Laravel 13.
- **Breaking change yang tidak relevan:**
  - `VerifyCsrfToken` middleware: Tidak digunakan dalam format lama di `bootstrap/app.php` proyek ini.
  - Custom Helpers (`array_first`, `array_last`): Tidak digunakan.
  - Custom Session/Cache Prefix: Tidak relevan.
  - Pagination Views (Bootstrap 3): Tidak relevan.

## Compatibility Fix
- Tidak ada fix manual khusus yang diperlukan karena codebase tidak menggunakan fitur Laravel 12 yang deprecated atau diubah di Laravel 13.

## Verification
- **Laravel Version:** 13.19.0
- **PHP Version:** 8.5.7
- **Route Count:** 27
- **API Route:** 4
- **Test Result:** 1 berhasil, 1 gagal (sama seperti baseline: Redirect ke login).
- **Build Result:** Sukses (55 modul, Vite).
- **Browser Verification:** Sukses (server berjalan).
- **API Verification:** Sukses (Menerima status HTTP 401 saat tidak ada kredensial valid, menandakan router berjalan dan Sanctum berfungsi menahan akses).

## Masalah yang Ditemukan
Proses `composer update` sempat memakan waktu cukup lama pada tahap `Generating optimized autoload files` dikarenakan lambatnya proses dump di Windows, namun berhasil diselesaikan tanpa error.
