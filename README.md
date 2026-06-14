# 🧺 LaundryKu — Sistem Informasi Manajemen Laundry

Aplikasi web manajemen laundry berbasis **PHP + CodeIgniter 3 + MySQL**.
Dirancang untuk usaha laundry skala kecil-menengah, cocok sebagai **project portfolio** maupun **tugas kuliah**.

---

## 📦 Teknologi

| Stack | Detail |
|-------|--------|
| Backend | PHP 7.4+ |
| Framework | CodeIgniter 3.x |
| Database | MySQL / MariaDB |
| Frontend | HTML5 + CSS3 (Vanilla, tanpa framework) |
| Web Server | Apache (XAMPP / WAMP / Laragon) |

---

## 🚀 Cara Instalasi

### 1. Install XAMPP / Laragon
Download dan install [XAMPP](https://www.apachefriends.org) atau [Laragon](https://laragon.org).

### 2. Download CodeIgniter 3
- Download CodeIgniter 3.x dari: https://codeigniter.com/download
- Extract dan rename folder menjadi `laundry`

### 3. Copy File Project
Salin seluruh isi folder project ini ke dalam folder `laundry` yang sudah berisi CodeIgniter.
Pastikan struktur akhir seperti ini:
```
htdocs/laundry/
├── application/       ← folder dari project ini
├── system/            ← folder CodeIgniter
├── assets/            ← folder dari project ini
├── .htaccess          ← dari project ini
└── index.php          ← dari CodeIgniter
```

### 4. Import Database
- Buka phpMyAdmin: `http://localhost/phpmyadmin`
- Buat database baru (atau langsung jalankan SQL)
- Import file `db_laundry.sql` dari project ini

### 5. Konfigurasi Database
Edit file `application/config/database.php`:
```php
'hostname' => 'localhost',
'username' => 'root',     // sesuaikan
'password' => '',         // sesuaikan
'database' => 'db_laundry',
```

### 6. Konfigurasi Base URL
Edit file `application/config/config.php`:
```php
$config['base_url'] = 'http://localhost/laundry/';
```

### 7. Aktifkan mod_rewrite (untuk URL cantik)
Pastikan `mod_rewrite` aktif di Apache (di XAMPP sudah aktif default).
File `.htaccess` sudah tersedia di root project.

### 8. Jalankan Aplikasi
Buka browser dan akses: **http://localhost/laundry**

---

## 🔑 Akun Default

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `1234` |
| User | `budi` | `password123` |
| User | `siti` | `password123` |

---

## 🗂️ Struktur Project

```
laundry/
├── application/
│   ├── config/
│   │   ├── config.php        ← Konfigurasi utama (base_url, session, csrf)
│   │   ├── database.php      ← Koneksi database MySQL
│   │   ├── routes.php        ← Routing URL lengkap
│   │   └── autoload.php      ← Autoload library & helper
│   │
│   ├── controllers/
│   │   ├── Auth.php          ← Login, logout, validasi session
│   │   ├── Admin.php         ← Dashboard admin + statistik
│   │   ├── Pelanggan.php     ← CRUD data pelanggan
│   │   ├── Transaksi.php     ← CRUD transaksi + update status
│   │   ├── User.php          ← Dashboard user + riwayat
│   │   └── Status.php        ← Cek status (publik, tanpa login)
│   │
│   ├── models/
│   │   ├── Auth_model.php        ← Cek user untuk login
│   │   ├── Pelanggan_model.php   ← CRUD data pelanggan
│   │   └── Transaksi_model.php   ← CRUD transaksi + generate kode
│   │
│   └── views/
│       ├── auth/
│       │   └── login.php         ← Halaman login
│       ├── layouts/
│       │   ├── header_admin.php  ← Sidebar + navbar admin (partial)
│       │   ├── footer_admin.php  ← Penutup layout admin
│       │   ├── header_user.php   ← Sidebar + navbar user (partial)
│       │   └── footer_user.php   ← Penutup layout user
│       ├── admin/
│       │   └── dashboard.php     ← Dashboard admin
│       ├── pelanggan/
│       │   ├── index.php         ← Daftar pelanggan
│       │   ├── tambah.php        ← Form tambah pelanggan
│       │   └── edit.php          ← Form edit pelanggan
│       ├── transaksi/
│       │   ├── index.php         ← Daftar transaksi
│       │   ├── tambah.php        ← Form tambah transaksi
│       │   └── edit.php          ← Form edit transaksi
│       ├── user/
│       │   ├── dashboard.php     ← Dashboard user
│       │   └── riwayat.php       ← Riwayat laundry user
│       └── status/
│           └── cek.php           ← Cek status publik
│
├── assets/
│   └── css/
│       └── style.css             ← CSS utama (clean & responsive)
│
├── db_laundry.sql                ← Script SQL database lengkap
├── .htaccess                     ← URL rewrite (hapus index.php)
└── README.md                     ← Panduan ini
```

---

## 🌐 Routing URL

| URL | Keterangan |
|-----|------------|
| `/` | Redirect ke halaman login |
| `/login` | Halaman login |
| `/logout` | Proses logout |
| `/admin/dashboard` | Dashboard admin |
| `/admin/pelanggan` | Daftar pelanggan |
| `/admin/pelanggan/tambah` | Form tambah pelanggan |
| `/admin/pelanggan/edit/{id}` | Form edit pelanggan |
| `/admin/transaksi` | Daftar transaksi |
| `/admin/transaksi/tambah` | Form tambah transaksi |
| `/admin/transaksi/edit/{id}` | Form edit transaksi |
| `/admin/transaksi/status/{id}` | Update status cucian |
| `/user/dashboard` | Dashboard user |
| `/user/riwayat` | Riwayat transaksi user |
| `/cek-status` | Cek status laundry (publik) |

---

## 🔄 Flow Aplikasi

```
Buka Aplikasi
      │
      ▼
  Halaman Login ──────────────────────────► Cek Status (tanpa login)
      │
      ▼
  Validasi (username + password)
      │
      ├─── role = admin ──► Dashboard Admin
      │                          │
      │                     ┌────┴────┐
      │                     │         │
      │               Data Pelanggan  Data Transaksi
      │                (CRUD)          (CRUD + Update Status)
      │
      └─── role = user ──► Dashboard User
                                │
                           ┌────┴────┐
                           │         │
                      Riwayat     Cek Status
                      Laundry
```

---

## 💡 Fitur Utama

### Admin
- ✅ Dashboard dengan statistik (total pelanggan, transaksi, status)
- ✅ CRUD Data Pelanggan (tambah, lihat, edit, hapus)
- ✅ CRUD Data Transaksi (tambah, lihat, edit, hapus)
- ✅ Update status cucian secara cepat (dropdown inline)
- ✅ Kode transaksi otomatis (`TRX-YYYYMMDD-NNN`)

### User / Pelanggan
- ✅ Dashboard dengan statistik cucian pribadi
- ✅ Riwayat transaksi laundry
- ✅ Cek status cucian

### Publik (Tanpa Login)
- ✅ Cek status laundry via kode transaksi

---

## 🔒 Keamanan

- Password di-hash dengan `password_hash()` (bcrypt)
- Verifikasi password dengan `password_verify()`
- Session-based authentication
- CSRF Protection aktif
- Proteksi halaman admin (redirect jika bukan admin)
- Sanitasi input dengan `$this->input->post('field', TRUE)`
- Validasi form dengan Form Validation CI

---

## 🗄️ Struktur Database

```
db_laundry
├── users          (id_user, username, password, role)
├── pelanggan      (id_pelanggan, id_user, nama, no_hp, alamat)
└── transaksi      (id_transaksi, kode_transaksi, id_pelanggan,
                    jenis_layanan, berat, harga, status, tanggal)
```

**Relasi:**
- `users` 1 → 1 `pelanggan` (1 user = 1 profil pelanggan)
- `pelanggan` 1 → N `transaksi` (1 pelanggan = banyak transaksi)

---

## ✍️ Contoh Kode Transaksi

| Format | Contoh |
|--------|--------|
| `TRX-{YYYYMMDD}-{NNN}` | `TRX-20260527-001` |

Kode dibuat otomatis oleh sistem saat admin membuat transaksi baru.
Nomor urut `NNN` dihitung berdasarkan jumlah transaksi pada hari yang sama.

---

## 📞 Kontak

Project ini dibuat sebagai **contoh aplikasi nyata** untuk keperluan:
- 🎓 Tugas kuliah / skripsi
- 💼 Portfolio pengembang web
- 🧪 Bahan belajar PHP + CodeIgniter

---

*Dibuat dengan ❤️ menggunakan PHP + CodeIgniter 3 + MySQL*
