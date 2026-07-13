# API Test Report

Berikut adalah laporan hasil pengujian automated test untuk fitur REST API menggunakan Laravel Feature Test dan database test `db_uts_fullstack_testing`.

| Test ID | Skenario | Expected | Actual | Status |
| ------- | -------- | -------- | ------ | ------ |
| AUTH_01 | Login berhasil sebagai admin | HTTP 200, return token, missing password | Sesuai | PASS |
| AUTH_02 | Login berhasil sebagai member | HTTP 200, return token | Sesuai | PASS |
| AUTH_03 | Login gagal karena credential salah | HTTP 401 | Sesuai | PASS |
| AUTH_04 | Login validation error | HTTP 422 | Sesuai | PASS |
| AUTH_05 | Me berhasil dengan token | HTTP 200, return user data, missing password | Sesuai | PASS |
| AUTH_06 | Me tanpa token mendapat 401 | HTTP 401 | Sesuai | PASS |
| AUTH_07 | Logout berhasil dan token revoked | HTTP 200, subsequent request HTTP 401 | Sesuai | PASS |
| ADMIN_01 | Admin bisa akses api admin | HTTP 200 | Sesuai | PASS |
| ADMIN_02 | Member dapat 403 saat akses api admin | HTTP 403, JSON response | Sesuai | PASS |
| ADMIN_03 | Guest dapat 401 saat akses api admin | HTTP 401 | Sesuai | PASS |
| ADMIN_04 | Admin bisa melihat list inventory dengan filter | HTTP 200, data difilter | Sesuai | PASS |
| ADMIN_05 | Admin bisa membuat inventory | HTTP 201, database has new inventory | Sesuai | PASS |
| ADMIN_06 | Create inventory validation error | HTTP 422 | Sesuai | PASS |
| ADMIN_07 | Admin bisa menghapus inventory tanpa transaksi | HTTP 200, database missing inventory | Sesuai | PASS |
| ADMIN_08 | Delete inventory ditolak 409 jika punya schedule | HTTP 409 | Sesuai | PASS |
| ADMIN_09 | Admin members lookup hanya tampilkan member | HTTP 200, tidak ada admin | Sesuai | PASS |
| ADMIN_10 | Admin bisa membuat schedule tanpa bentrok | HTTP 201 | Sesuai | PASS |
| ADMIN_11 | Schedule ditolak jika bentrok | HTTP 409, overlap ditolak | Sesuai | PASS |
| MBR_01 | Member bisa melihat inventory available | HTTP 200, status borrowed tidak tampil | Sesuai | PASS |
| MBR_02 | Member hanya melihat schedule miliknya | HTTP 200, schedule id user lain tidak tampil | Sesuai | PASS |
| MBR_03 | Detail schedule milik orang lain ditolak | HTTP 403 (atau 404) | Sesuai | PASS |
| MBR_04 | Check in berhasil | HTTP 200, schedule -> checked_in, inv -> borrowed | Sesuai | PASS |
| MBR_05 | Check in ulang ditolak | HTTP 409 | Sesuai | PASS |
| MBR_06 | Check in tanpa schedule id | HTTP 422 | Sesuai | PASS |
| BASE_01 | Guest is redirected from root to login | HTTP 302 to /login | Sesuai | PASS |

Semua test (26 skenario, 52 assertions) lulus dengan sempurna.
