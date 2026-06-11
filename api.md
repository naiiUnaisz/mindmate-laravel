# API Documentation — MindMate

**Base URL:** `https://unaisah-digitallab.my.id/api`

**Format Response Umum:**
```json
{
    "success": true,
    "data": { ... },
    "message": "..."
}
```

Semua error response mengembalikan:
```json
{
    "success": false,
    "message": "Pesan error"
}
```

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
    "password": "rahasia123",
    "username": "johndoe"
}
```

| Parameter | Tipe | Required | Keterangan |
|---|---|---|---|
| `name` | string | Ya | Nama lengkap |
| `email` | string | Ya | Email (unique) |
| `password` | string | Ya | Min 6 karakter |
| `username` | string | Ya | Username (unique) |

**Response** `201 Created`
```json
{
    "success": true,
    "access_token": "1|abc123def456...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "username": "johndoe",
        "birthday": null,
        "age": null,
        "gender": null,
        "coin_balance": 0,
        "current_streak": 0,
        "restday_quota": 2,
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

---

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
    "success": true,
    "access_token": "2|xyz789abc...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "username": "johndoe",
        "birthday": "2000-01-15",
        "age": 26,
        "gender": "male",
        "coin_balance": 50,
        "current_streak": 3,
        "restday_quota": 2,
        "created_at": "2026-06-08T10:00:00.000000Z",
        "updated_at": "2026-06-08T12:00:00.000000Z"
    }
}
```

**Error Response** `401 Unauthorized`
```json
{
    "success": false,
    "message": "Email atau password salah"
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
            "task_type": "belajar",
            "is_routine": false,
            "is_checked": false,
            "created_at": "2026-06-08T10:00:00.000000Z",
            "updated_at": "2026-06-08T10:00:00.000000Z"
        }
    ]
}
```

#### 2.1.2 Lihat Detail Tugas

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

#### 2.1.3 Tambah Tugas

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

| Parameter | Tipe | Required | Default | Keterangan |
|---|---|---|---|---|
| `title` | string | Ya | - | Judul tugas |
| `description` | string | Tidak | null | Deskripsi tugas |
| `is_routine` | boolean | Tidak | false | Apakah tugas rutin |
| `coin_reward` | integer | Tidak | 10 | Koin yang didapat saat checklist |
| `task_type` | string | Tidak | null | Kategori tugas |

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
    "is_routine": true,
    "coin_reward": 15,
    "task_type": "belajar"
}
```

Semua parameter bersifat opsional.

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
        "coin_reward": 15,
        "task_type": "belajar",
        "is_routine": true,
        "is_checked": true,
        "created_at": "2026-06-08T10:00:00.000000Z",
        "updated_at": "2026-06-08T12:00:00.000000Z"
    }
}
```

**Error Response** `404 Not Found` (bukan pemilik tugas / tidak ditemukan)
```json
{
    "message": "No query results for model [App\\Models\\Task] 1"
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

| Parameter | Tipe | Default | Keterangan |
|---|---|---|---|
| `source` | string | `"cart"` | Asal checklist: `"cart"` (To-Do List) atau `"puzzle"` (Halaman Puzzle) |

**Logic Penting:**
- Jika `source = "puzzle"`:
  - Membuka 1 piece puzzle (maksimal 6 per hari)
  - Jika puzzle mencapai 6/6: bonus **+100 coin** + streak bertambah
  - Jika puzzle sudah penuh (6/6): tidak ada reward (tidak membuka piece baru)
- Jika `source = "cart"`:
  - Mendapatkan `coin_reward` dari task
  - Task ditandai `is_checked = true`

**Response** `200 OK` (cart)
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

**Response** `200 OK` (puzzle — buka piece)
```json
{
    "success": true,
    "message": "Tugas berhasil diselesaikan!",
    "data": {
        "source": "puzzle",
        "puzzle_opened": true,
        "current_puzzle_count": 3,
        "coins_earned": 10,
        "current_coin_balance": 60,
        "current_streak": 0
    }
}
```

**Response** `200 OK` (puzzle — full puzzle + bonus)
```json
{
    "success": true,
    "message": "Tugas berhasil diselesaikan!",
    "data": {
        "source": "puzzle",
        "puzzle_opened": true,
        "current_puzzle_count": 6,
        "coins_earned": 110,
        "current_coin_balance": 170,
        "current_streak": 1
    }
}
```

**Error Response** `400 Bad Request` (tugas sudah diceklis hari ini)
```json
{
    "success": false,
    "message": "Tugas ini sudah diselesaikan hari ini."
}
```

---

### 2.2 Fitur Daily Record (Puzzle, Mood, Rest Day)

#### 2.2.1 Lihat Daily Record Hari Ini

```
GET /daily-record
```

Mengembalikan data record harian termasuk mood, status rest day, progress puzzle, dan task yang sudah dikerjakan hari ini.

**Response** `200 OK` (sudah ada aktivitas hari ini)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "date": "2026-06-08",
        "mood_level": "good",
        "is_rest_day": false,
        "puzzle_completed_count": 3,
        "daily_task_items": [
            {
                "id": 1,
                "daily_record_id": 1,
                "task_id": 1,
                "is_completed": true,
                "task": {
                    "id": 1,
                    "user_id": 1,
                    "title": "Belajar Laravel",
                    "description": null,
                    "coin_reward": 10,
                    "task_type": null,
                    "is_routine": false,
                    "is_checked": false,
                    "created_at": "2026-06-08T10:00:00.000000Z",
                    "updated_at": "2026-06-08T10:00:00.000000Z"
                },
                "created_at": "2026-06-08T10:00:00.000000Z",
                "updated_at": "2026-06-08T10:00:00.000000Z"
            }
        ],
        "puzzle_pieces": [
            {
                "id": 1,
                "daily_record_id": 1,
                "daily_task_item_id": 1,
                "piece_number": 1,
                "is_opened": true,
                "opened_at": "2026-06-08T10:00:00.000000Z",
                "created_at": "2026-06-08T10:00:00.000000Z",
                "updated_at": "2026-06-08T10:00:00.000000Z"
            }
        ],
        "created_at": "2026-06-08T10:00:00.000000Z",
        "updated_at": "2026-06-08T10:00:00.000000Z"
    }
}
```

**Response** `200 OK` (belum ada aktivitas hari ini)
```json
{
    "success": true,
    "data": {
        "date": "2026-06-08",
        "mood_level": null,
        "is_rest_day": false,
        "puzzle_completed_count": 0,
        "daily_task_items": [],
        "puzzle_pieces": []
    }
}
```

#### 2.2.2 Catat Mood Harian

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

#### 2.2.3 Gunakan Rest Day

```
POST /daily-record/rest-day
```

**Response** `200 OK`
```json
{
    "success": true,
    "message": "Rest Day berhasil diaktifkan! Nikmati waktu istirahatmu hari ini.",
    "data": {
        "current_restday_quota": 1,
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

### 2.3 Fitur Hiburan (Relax)

#### 2.3.1 Daftar Aplikasi Hiburan

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
            "category": "movie",
            "url": "https://youtube.com",
            "coin_cost": 30,
            "duration_minutes": 30,
            "created_at": null,
            "updated_at": null
        }
    ]
}
```

#### 2.3.2 Beli Sesi Hiburan

```
POST /apps/{id}/purchase
```

**Logic:**
- Harga mengalami **inflasi** setiap pembelian di hari yang sama: `harga_asli × 2^jumlah_pembelian_hari_ini`
- Hanya bisa 1 sesi aktif dalam satu waktu
- Sesi otomatis memiliki `expired_at` = waktu sekarang + durasi menit

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

#### 2.3.3 Selesaikan / Absen Sesi Hiburan

```
POST /apps/complete
```

**Logic Denda:**
- Jika waktu sekarang **melebihi** `expired_at`:
  - Grace period < 1 menit: dianggap **tepat waktu** (tidak kena denda, status `absen_success`)
  - Jika telat ≥ 1 menit: denda = **menit telat × 5 coin** (status `fined`)
- Jika **tepat waktu / lebih awal**: status `absen_success`, tanpa denda

> Catatan: Saldo koin bisa menjadi **minus** jika jumlah denda melebihi saldo yang dimiliki.

**Response** `200 OK` (tepat waktu / grace period)
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
        "current_coin_balance": -5
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

---

### 2.4 Riwayat Koin

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

### 2.5 Fitur Profil & Logout

#### 2.5.1 Profil User

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
        "username": "johndoe",
        "birthday": "2000-01-15",
        "age": 26,
        "gender": "male",
        "coin_balance": 75,
        "current_streak": 3,
        "restday_quota": 2,
        "created_at": "2026-06-08T10:00:00.000000Z",
        "updated_at": "2026-06-08T12:00:00.000000Z"
    }
}
```

#### 2.5.2 Edit Profil

```
PUT /user/profile
```

**Request Body** (semua opsional)
```json
{
    "name": "John Updated",
    "email": "johnbaru@example.com",
    "username": "johnbaru",
    "birthday": "2001-05-20",
    "gender": "male"
}
```

| Parameter | Tipe | Required | Keterangan |
|---|---|---|---|
| `name` | string | Tidak | Nama lengkap |
| `email` | string | Tidak | Email (unique, kecuali milik sendiri) |
| `username` | string | Tidak | Username (unique, kecuali milik sendiri) |
| `birthday` | date | Tidak | Tanggal lahir (format `YYYY-MM-DD`) |
| `gender` | string | Tidak | `male` atau `female` |

**Response** `200 OK`
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "name": "John Updated",
        "email": "johnbaru@example.com",
        "username": "johnbaru",
        "birthday": "2001-05-20",
        "age": 25,
        "gender": "male",
        "coin_balance": 75,
        "current_streak": 3,
        "restday_quota": 2,
        "created_at": "2026-06-08T10:00:00.000000Z",
        "updated_at": "2026-06-08T13:00:00.000000Z"
    }
}
```

#### 2.5.3 Logout

Hapus token akses yang sedang digunakan.

```
POST /logout
```

**Response** `200 OK`
```json
{
    "success": true,
    "message": "Logout successfully"
}
```

---

## 3. Ringkasan Endpoint

| Method | Endpoint | Auth | Fungsi |
|---|---|---|---|
| POST | `/register` | - | Register akun baru |
| POST | `/login` | - | Login & dapatkan token |
| POST | `/logout` | Sanctum | Hapus token |
| GET | `/tasks` | Sanctum | Lihat semua tugas |
| GET | `/tasks/{id}` | Sanctum | Lihat detail tugas |
| POST | `/tasks` | Sanctum | Tambah tugas baru |
| PUT | `/tasks/{id}` | Sanctum | Update tugas |
| DELETE | `/tasks/{id}` | Sanctum | Hapus tugas (soft delete) |
| POST | `/tasks/{id}/check` | Sanctum | Checklist tugas harian |
| GET | `/apps` | Sanctum | Daftar aplikasi hiburan |
| POST | `/apps/{id}/purchase` | Sanctum | Beli sesi hiburan |
| POST | `/apps/complete` | Sanctum | Absen selesai hiburan |
| GET | `/coin-histori` | Sanctum | Riwayat koin |
| GET | `/daily-record` | Sanctum | Daily record hari ini |
| POST | `/daily-record/mood` | Sanctum | Catat mood harian |
| POST | `/daily-record/rest-day` | Sanctum | Gunakan rest day |
| GET | `/user/profile` | Sanctum | Lihat profil user |
| PUT | `/user/profile` | Sanctum | Edit profil user |
