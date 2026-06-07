# API Documentation — MindMate

**Base URL:** `https://unaisah-digitallab.my.id/api`

---

## 1. Public Endpoints (Tanpa Token)

### 1.1 Register

Mendaftarkan akun baru.

**Endpoint**
```
POST /register
```

**Request Body** (JSON)
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "rahasia123"
}
```

**Response** `201 Created`
```json
{
    "access_token": "1|abc123def456...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null,
        "created_at": "2026-06-08T10:00:00.000000Z",
        "updated_at": "2026-06-08T10:00:00.000000Z"
    }
}
```

**Error Response** `422 Unprocessable Content` (validasi gagal)
```json
{
    "message": "The email has already been taken.",
    "errors": {
        "email": ["The email has already been taken."]
    }
}
```

### 1.2 Login

Login dan mendapatkan token akses.

**Endpoint**
```
POST /login
```

**Request Body** (JSON)
```json
{
    "email": "john@example.com",
    "password": "rahasia123"
}
```

**Response** `200 OK`
```json
{
    "access_token": "2|xyz789abc...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2026-06-08T10:00:00.000000Z",
        "updated_at": "2026-06-08T10:00:00.000000Z"
    }
}
```

**Error Response** `404 Not Found`
```json
{
    "status": "error",
    "message": "Email not found"
}
```

**Error Response** (password salah)
```json
{
    "status": "error",
    "message": "password is not match"
}
```

---

## 2. Protected Endpoints (Wajib Bearer Token)

Semua endpoint di bawah **wajib** menyertakan token autentikasi pada HTTP Header:

```
Authorization: Bearer {access_token}
```

---

### 2.1 Fitur Tasks

#### 2.1.1 Lihat Semua Tugas

```
GET /tasks
```

**Response** `200 OK`
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "title": "Belajar Laravel",
            "description": "Membaca dokumentasi routes",
            "coin_reward": 10,
            "task_type": null,
            "is_routine": false,
            "is_checked": false,
            "created_at": "2026-06-08T10:00:00.000000Z",
            "updated_at": "2026-06-08T10:00:00.000000Z",
            "deleted_at": null
        }
    ]
}
```

#### 2.1.2 Tambah Tugas

```
POST /tasks
```

**Request Body**
```json
{
    "title": "Belajar Laravel",
    "description": "Membaca dokumentasi routes",
    "is_routine": false,
    "coin_reward": 10,
    "task_type": "belajar"
}
```

**Response** `201 Created`
```json
{
    "success": true,
    "message": "Tugas berhasil ditambahkan",
    "data": {
        "id": 1,
        "user_id": 1,
        "title": "Belajar Laravel",
        "description": "Membaca dokumentasi routes",
        "coin_reward": 10,
        "task_type": "belajar",
        "is_routine": false,
        "is_checked": false,
        "created_at": "2026-06-08T10:00:00.000000Z",
        "updated_at": "2026-06-08T10:00:00.000000Z"
    }
}
```

#### 2.1.3 Lihat Detail Tugas

```
GET /tasks/{id}
```

**Response** `200 OK`
```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "title": "Belajar Laravel",
        "description": "Membaca dokumentasi routes",
        "coin_reward": 10,
        "task_type": "belajar",
        "is_routine": false,
        "is_checked": false,
        "created_at": "2026-06-08T10:00:00.000000Z",
        "updated_at": "2026-06-08T10:00:00.000000Z"
    }
}
```

#### 2.1.4 Update Tugas

```
PUT /tasks/{id}
```

**Request Body**
```json
{
    "title": "Belajar Laravel Lanjutan",
    "description": "Membaca dokumentasi Eloquent",
    "is_checked": true,
    "is_routine": true
}
```

**Response** `200 OK`
```json
{
    "success": true,
    "message": "Tugas berhasil diperbarui",
    "data": {
        "id": 1,
        "user_id": 1,
        "title": "Belajar Laravel Lanjutan",
        "description": "Membaca dokumentasi Eloquent",
        "coin_reward": 10,
        "task_type": "belajar",
        "is_routine": true,
        "is_checked": true,
        "created_at": "2026-06-08T10:00:00.000000Z",
        "updated_at": "2026-06-08T12:00:00.000000Z"
    }
}
```

**Error Response** `403 Forbidden` (bukan pemilik tugas)
```json
{
    "message": "Unauthorized"
}
```

#### 2.1.5 Hapus Tugas (Soft Delete)

```
DELETE /tasks/{id}
```

**Response** `200 OK`
```json
{
    "success": true,
    "message": "Tugas berhasil dihapus"
}
```

#### 2.1.6 Checklist Tugas Harian (Dapat Koin & Puzzle)

```
POST /tasks/{id}/check
```

Menandai tugas selesai untuk hari ini. Koin otomatis ditambahkan, dan potongan puzzle dibuka (jika dari halaman puzzle).

**Request Body** (opsional)
```json
{
    "source": "cart"
}
```

| Parameter | Tipe   | Default | Keterangan                                                    |
|-----------|--------|---------|---------------------------------------------------------------|
| `source`  | string | `"cart"`| Asal checklist: `"cart"` (To-Do List) atau `"puzzle"` (Puzzle) |

**Response** `200 OK`
```json
{
    "success": true,
    "message": "Tugas berhasil diselesaikan!",
    "data": {
        "source": "cart",
        "puzzle_opened": false,
        "current_puzzle_count": 0,
        "coins_earned": 10,
        "current_coin_balance": 50,
        "current_streak": 0
    }
}
```

**Error Response** `400 Bad Request` (tugas sudah diceklis hari ini)
```json
{
    "message": "Tugas ini sudah diselesaikan hari ini."
}
```

---

### 2.2 Fitur Hiburan (Relax)

#### 2.2.1 Daftar Aplikasi Hiburan

```
GET /apps
```

**Response** `200 OK`
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "youtube",
            "title": "YouTube",
            "url": "https://youtube.com",
            "coin_cost": 30,
            "duration_minutes": 30,
            "created_at": null,
            "updated_at": null
        }
    ]
}
```

#### 2.2.2 Beli Sesi Hiburan

```
POST /apps/{id}/purchase
```

**Response** `200 OK`
```json
{
    "success": true,
    "message": "Pembelian berhasil! Selamat bersantai.",
    "data": {
        "app_name": "YouTube",
        "deep_link_url": "https://youtube.com",
        "duration_minutes": 30,
        "expired_at": "2026-06-08T10:30:00",
        "coins_spent": 30,
        "current_coin_balance": 20
    }
}
```

**Error Response** `400 Bad Request` (koin tidak cukup)
```json
{
    "success": false,
    "message": "Koin kamu tidak cukup! Harga aplikasi ini sudah naik menjadi 60 koin karena faktor inflasi harian. Selesaikan tugas dulu yuk."
}
```

**Error Response** `400 Bad Request` (masih punya sesi aktif)
```json
{
    "success": false,
    "message": "Kamu masih punya sesi hiburan yang aktif!"
}
```

#### 2.2.3 Selesaikan / Absen Sesi Hiburan

```
POST /apps/complete
```

**Response** `200 OK` (tepat waktu)
```json
{
    "success": true,
    "message": "Hebat! Kamu disiplin dan absen tepat waktu. Sesi bermain ditutup.",
    "data": {
        "status": "absen_success",
        "current_coin_balance": 20
    }
}
```

**Response** `200 OK` (telat — kena denda)
```json
{
    "success": true,
    "message": "Waduh! Kamu telat absen 5 menit. Koin kamu dipotong 25 sebagai denda.",
    "data": {
        "status": "fined",
        "fine_amount": 25,
        "current_coin_balance": 75
    }
}
```

**Error Response** `400 Bad Request` (tidak ada sesi aktif)
```json
{
    "success": false,
    "message": "Kamu tidak memiliki sesi hiburan yang aktif saat ini."
}
```

#### 2.2.4 Riwayat Koin

```
GET /coin-histori
```

**Response** `200 OK`
```json
{
    "success": true,
    "current_balance": 75,
    "data": [
        {
            "id": 3,
            "amount": 25,
            "status": "expense",
            "description": "Denda: Telat absen hiburan",
            "date": "2026-06-08 10:35:00"
        },
        {
            "id": 2,
            "amount": 30,
            "status": "expense",
            "description": "Membeli hiburan: YouTube",
            "date": "2026-06-08 10:00:00"
        },
        {
            "id": 1,
            "amount": 10,
            "status": "reward",
            "description": "Menyelesaikan tugas: Belajar Laravel",
            "date": "2026-06-08 09:00:00"
        }
    ]
}
```

---

### 2.3 Fitur Mood & Rest Day

#### 2.3.1 Catat Mood Harian

```
POST /daily-record/mood
```

**Request Body**
```json
{
    "mood_level": "good"
}
```

Nilai `mood_level` yang valid: `"good"`, `"neutral"`, `"bad"`.

**Response** `200 OK`
```json
{
    "success": true,
    "message": "Mood kamu hari ini berhasil dicatat!",
    "data": {
        "date": "2026-06-08",
        "mood_level": "good"
    }
}
```

#### 2.3.2 Gunakan Rest Day

```
POST /daily-record/rest-day
```

**Response** `200 OK`
```json
{
    "success": true,
    "message": "Rest Day berhasil diaktifkan! Nikmati waktu istirahatmu hari ini.",
    "data": {
        "current_restday_quota": 2,
        "is_rest_day": true
    }
}
```

**Error Response** `400 Bad Request` (rest day sudah dipakai hari ini)
```json
{
    "success": false,
    "message": "Kamu sudah mengambil jatah Rest Day untuk hari ini."
}
```

**Error Response** `400 Bad Request` (kuota habis)
```json
{
    "success": false,
    "message": "Kuota Rest Day kamu sudah habis! Tetap semangat kerjakan tugas ya."
}
```

---

### 2.4 Fitur Profil & Logout

#### 2.4.1 Profil User

```
GET /user/profile
```

**Response** `200 OK`
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "coin_balance": 75,
        "current_streak": 3,
        "restday_quota": 2,
        "created_at": "2026-06-08T10:00:00.000000Z",
        "updated_at": "2026-06-08T12:00:00.000000Z"
    }
}
```

#### 2.4.2 Logout

Hapus token akses yang sedang digunakan.

```
POST /logout
```

**Response** `200 OK`
```json
{
    "status": "success",
    "message": "Logout successfully"
}
```
