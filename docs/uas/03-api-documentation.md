# API Documentation Smart-Hub

Dokumentasi ini menjelaskan spesifikasi REST API untuk aplikasi Smart-Hub, yang mencakup Endpoint Publik, Member, dan Admin.

## Autentikasi

Semua API yang membutuhkan proteksi harus menggunakan header HTTP berikut:
```http
Accept: application/json
Authorization: Bearer {token}
```

### 1. Login
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `POST` | `/api/login` | Public | `{ "email": "admin@smarthub.com", "password": "password" }` | `{ "status": true, "message": "Login berhasil.", "data": { "user": { "id": 1, ... }, "token": "...", "token_type": "Bearer" } }` | `200` |

### 2. Me
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `GET` | `/api/me` | All | - | `{ "status": true, "message": "Data pengguna berhasil diambil.", "data": { "user": { ... } } }` | `200` |

### 3. Logout
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `POST` | `/api/logout` | All | - | `{ "status": true, "message": "Logout berhasil.", "data": null }` | `200` |

---

## Modul Inventory

### 4. Admin Inventory List
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `GET` | `/api/admin/inventories` | Admin | `?search=kamera&type=equipment&status=available&per_page=10` | `{ "status": true, "data": [...], "meta": {...}, "links": {...} }` | `200` |

### 5. Admin Create Inventory
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `POST` | `/api/admin/inventories` | Admin | `{ "name": "Tripod", "type": "equipment", "status": "available", "description": "..." }` | `{ "status": true, "message": "Inventaris berhasil ditambahkan.", "data": {...} }` | `201` |

### 6. Admin Delete Inventory
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `DELETE` | `/api/admin/inventories/{id}` | Admin | - | `{ "status": true, "message": "Inventaris berhasil dihapus." }` | `200` |

### 7. Member Available Inventories
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `GET` | `/api/inventories` | Member | - | `{ "status": true, "message": "Daftar inventaris yang tersedia.", "data": [...] }` | `200` |

---

## Modul Schedule & Member

### 8. Admin Members Lookup
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `GET` | `/api/admin/members` | Admin | `?search=john` | `{ "status": true, "data": [{ "id": 2, "name": "John", "email": "john@test.com" }] }` | `200` |

### 9. Admin Schedule List
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `GET` | `/api/admin/schedules` | Admin | `?status=booked` | `{ "status": true, "data": [...], "meta": {...} }` | `200` |

### 10. Admin Create Schedule
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `POST` | `/api/admin/schedules` | Admin | `{ "user_id": 2, "inventory_id": 1, "start_time": "2024-01-01 10:00:00", "end_time": "2024-01-01 12:00:00", "status": "booked" }` | `{ "status": true, "message": "Jadwal berhasil ditambahkan.", "data": {...} }` | `201` |

### 11. Admin Update Schedule
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `PATCH` | `/api/admin/schedules/{id}` | Admin | sama seperti create | `{ "status": true, "message": "Jadwal berhasil diperbarui.", "data": {...} }` | `200` |

### 12. Admin Schedule Detail
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `GET` | `/api/admin/schedules/{id}` | Admin | - | `{ "status": true, "data": {...} }` | `200` |

### 13. Member Schedule List (My Schedules)
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `GET` | `/api/my-schedules` | Member | `?status=booked` | `{ "status": true, "data": [...], "meta": {...} }` | `200` |

### 14. Member Schedule Detail
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `GET` | `/api/my-schedules/{id}` | Member | - | `{ "status": true, "data": {...} }` | `200` |

### 15. Check-in (Member)
| Method | Endpoint | Role | Request | Response | Status |
|--------|----------|------|---------|----------|--------|
| `POST` | `/api/check-in` | Member | `{ "schedule_id": 1 }` | `{ "status": true, "message": "Check-in berhasil dilakukan." }` | `200` |
