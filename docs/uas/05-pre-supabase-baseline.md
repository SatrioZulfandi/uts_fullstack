# Pre-Supabase Baseline

Baseline dicatat sebelum migrasi database ke Supabase PostgreSQL.

## Environment

| Item | Nilai |
|------|-------|
| PHP | 8.5.7 |
| Laravel | 13.19.0 |
| Database Driver | MySQL (`db_uts_fullstack`) |
| Database Testing | MySQL (`db_uts_fullstack_testing`) |
| Branch | `feature/complete-rest-api` |
| Commit Terakhir | `fab67f6` |

## Migration Status

| Migration | Status |
|-----------|--------|
| `0001_01_01_000000_create_users_table` | Ran |
| `0001_01_01_000001_create_cache_table` | Ran |
| `0001_01_01_000002_create_jobs_table` | Ran |
| `2024_01_01_000003_create_inventories_table` | Ran |
| `2024_01_01_000004_create_borrowing_schedules_table` | Ran |
| `2024_01_01_000005_create_personal_access_tokens_table` | Ran |

Total: **6 migration**, semua berstatus Ran.

## API Routes

Total: **15 routes**

```
GET|HEAD   api/admin/inventories
POST       api/admin/inventories
DELETE     api/admin/inventories/{inventory}
GET|HEAD   api/admin/members
GET|HEAD   api/admin/schedules
POST       api/admin/schedules
GET|HEAD   api/admin/schedules/{schedule}
PATCH      api/admin/schedules/{schedule}
POST       api/check-in
GET|HEAD   api/inventories
POST       api/login
POST       api/logout
GET|HEAD   api/me
GET|HEAD   api/my-schedules
GET|HEAD   api/my-schedules/{schedule}
```

## Automated Test

```
Tests:      26 passed (52 assertions)
Duration:   1.78s
Driver:     MySQL
```

Semua test lulus tanpa error.

## Build

```
vite v7.3.6 building client environment for production...
✓ 55 modules transformed.
✓ built in 562ms
```

Build berhasil tanpa error.

## Masalah yang Sudah Ada

Tidak ada masalah yang terdeteksi sebelum migrasi Supabase.
