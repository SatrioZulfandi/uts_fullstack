# API Gap Analysis

| Kebutuhan UAS | Kondisi Saat Ini | Status | Implementasi yang Dibutuhkan |
| ------------- | ---------------- | ------ | ---------------------------- |
| **Authentication** | | | |
| Login API | Sudah ada di `AuthController@login` | READY | Memastikan return format sesuai standar JSON yang baru. |
| Get User (Me) | Belum ada | MISSING | Menambahkan `AuthController@me` dengan response data user tanpa field sensitif. |
| Logout API | Sudah ada di `AuthController@logout` | NEEDS IMPROVEMENT | Pastikan hanya menghapus token saat ini (currentAccessToken), error handling jika null. |
| **Middleware & Authorization** | | | |
| Admin Middleware | `AdminMiddleware` ada, namun return 403 via `abort()`. | NEEDS IMPROVEMENT | Ubah middleware agar mendeteksi `$request->expectsJson()` lalu me-return response format standar JSON. |
| **Manage Inventory (Admin API)** | | | |
| List Inventory | Hanya ada versi Web (`InventoryController`). | MISSING | Buat `Api/Admin/InventoryController@index` dengan filter, search, pagination. |
| Create Inventory | Hanya ada versi Web. | MISSING | Buat `Api/Admin/InventoryController@store` menggunakan validation dari `InventoryRequest`. |
| Delete Inventory | Hanya ada versi Web. | MISSING | Buat `Api/Admin/InventoryController@destroy` dengan pengecekan relasi ke `borrowing_schedules` (409 Conflict). |
| **Members Lookup (Admin API)** | | | |
| List Members | Belum ada. | MISSING | Buat `Api/Admin/MemberController@index` untuk me-return user dengan role `member`. |
| **Manage Schedule (Admin API)** | | | |
| List Schedule | Hanya ada versi Web. | MISSING | Buat `Api/Admin/ScheduleController@index` dengan filter, eager loading. |
| Detail Schedule | Hanya ada versi Web. | MISSING | Buat `Api/Admin/ScheduleController@show`. |
| Create Schedule | Hanya ada versi Web. | MISSING | Buat `Api/Admin/ScheduleController@store` dengan validasi bentrok jadwal. |
| Update Schedule | Hanya ada versi Web. | MISSING | Buat `Api/Admin/ScheduleController@update` dengan validasi bentrok (mengabaikan schedule ini). |
| **Member Area API** | | | |
| List Available Inventories | Ada di `MemberController@availableInventories` | READY | Pastikan format respon sesuai standar. |
| My Schedules List | Belum ada | MISSING | Buat `MemberController@schedules` dengan eager load `inventory`. |
| My Schedule Detail | Belum ada | MISSING | Buat `MemberController@showSchedule` dengan proteksi kepemilikan. |
| Check-in | Ada di `MemberController@checkIn` | NEEDS IMPROVEMENT | Perkuat dengan `DB::transaction()` & `lockForUpdate()`, update error status jadi 409 untuk konflik. |
