# 🧺 LaundryKu — Sistem Informasi Manajemen Laundry

Aplikasi web manajemen laundry modern berbasis **PHP + CodeIgniter 3 + MySQL**.
Aplikasi ini sudah dioptimalkan dan dikonfigurasi secara dinamis sehingga siap langsung dijalankan di **Local Host (XAMPP/Laragon)** maupun **GitHub Codespaces** tanpa perlu mengedit file konfigurasi `base_url` secara manual!

---

## 📦 Teknologi

| Stack | Detail |
|-------|--------|
| **Backend** | PHP 7.4 s.d PHP 8.3 |
| **Framework** | CodeIgniter 3.1.13 |
| **Database** | MySQL / MariaDB |
| **Frontend** | HTML5 + CSS3 (Modern Vanilla CSS dengan Glassmorphism & Responsive Layout) |
| **Web Server** | Apache (XAMPP / Laragon) / PHP Built-in Server |

---

## 🔑 Akun Default (Login Credentials)

| Role / Akun | Username | Password | Keterangan |
|-------------|----------|----------|------------|
| **Admin (Owner/Kasir)** | `admin` | `1234` | Mengelola transaksi, pelanggan, berat riil, dan status setoran uang. |
| **User (Pelanggan 1)** | `budi` | `password123` | Pelanggan terdaftar, bisa memesan, upload bukti bayar, dan melihat histori. |
| **User (Pelanggan 2)** | `siti` | `password123` | Pelanggan terdaftar, bisa memesan, upload bukti bayar, dan melihat histori. |

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 💻 Opsi A: Jalankan di Local Host (XAMPP / Laragon)

1. **Persiapan Folder**:
   - Salin seluruh folder project `laundry` ini ke direktori web server Anda:
     - **XAMPP**: `C:\xampp\htdocs\laundry\`
     - **Laragon**: `C:\laragon\www\laundry\`

2. **Nyalakan Server & Database**:
   - Buka XAMPP Control Panel, lalu klik **Start** pada modul **Apache** dan **MySQL**.

3. **Import Database**:
   - Buka browser dan akses **phpMyAdmin** di `http://localhost/phpmyadmin/`.
   - Buat database baru bernama **`db_laundry`**.
   - Klik tab **Import**, pilih file **`db_laundry.sql`** yang ada di root project ini, lalu klik **Go / Import**.

4. **Konfigurasi Database**:
   - Konfigurasi default sudah di-set otomatis terhubung ke `127.0.0.1` tanpa password. 
   - Jika Anda mengubah password MySQL lokal Anda, silakan sesuaikan di file `application/config/database.php` pada baris password:
     ```php
     'password' => 'password_baru_anda',
     ```

5. **Jalankan Aplikasi**:
   - Buka browser Anda dan akses: **`http://localhost/laundry/`**

---

### ☁️ Opsi B: Jalankan di GitHub Codespaces (Cloud)

Jika Anda membuka proyek ini melalui **GitHub Codespaces**, ikuti perintah-perintah terminal berikut untuk menginstal dan menyalakan aplikasi:

#### 1. Instal & Aktifkan MySQL Server di Codespaces
Jalankan perintah ini satu per satu di terminal Codespace Anda untuk memasang dan mengaktifkan database MySQL:
```bash
# Update list paket
sudo apt-get update

# Install MySQL Server
sudo apt-get install -y mysql-server

# Nyalakan service MySQL
sudo service mysql start
```

#### 2. Atur Hak Akses Database (Penting!)
Agar CodeIgniter dapat terhubung ke MySQL secara otomatis tanpa kendala *permission socket*, ubah autentikasi user root menjadi akses TCP lokal (tanpa password):
```bash
sudo mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';"
```

#### 3. Buat Database & Import File SQL
```bash
sudo mysql -e "CREATE DATABASE db_laundry;"
sudo mysql db_laundry < db_laundry.sql
```

#### 4. Instal Ekstensi PHP-MySQL
Sistem Ubuntu di Codespaces memerlukan ekstensi PHP-MySQL agar program dapat berkomunikasi dengan database. Jalankan perintah instalasi berikut:
```bash
sudo apt-get install -y php8.3 php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl
```

#### 5. Jalankan Web Server PHP
Jalankan server built-in PHP dengan basis PHP 8.3:
```bash
php8.3 -S 0.0.0.0:8080
```

#### 6. Buka Aplikasi di Browser
- VS Code di browser Anda akan menampilkan notifikasi pop-up di pojok kanan bawah bertuliskan **"Your application running on port 8080 is available."**
- Klik tombol **Open in Browser** pada pop-up tersebut.
- Atau, Anda bisa pergi ke tab **Ports** di bagian bawah samping terminal, lalu klik ikon 🌐 (bola dunia) pada baris port `8080` untuk langsung membukanya di tab browser baru.

---

## 💡 Fitur Unggulan & Logika Manusiawi LaundryKu

Aplikasi ini telah dimodifikasi secara khusus agar selaras dengan **alur kerja nyata di lapangan (logika bisnis laundry yang logis)**:

1. **Alur Pembayaran Pasca-Timbang (Post-Weighing)**:
   - Pelanggan tidak perlu membayar di awal saat melakukan pemesanan online untuk menghindari bayar dua kali akibat perbedaan berat timbangan.
   - Tombol pembayaran (QRIS) disembunyikan bagi pelanggan selama pakaian masih dalam status `Menunggu` (belum ditimbang & diverifikasi admin).
   - Ditampilkan peringatan bahwa total biaya awal hanyalah estimasi berat kotor pelanggan.

2. **Verifikasi Berat Cucian Riil oleh Admin**:
   - Setelah kurir menjemput pakaian, Admin menimbang berat pakaian yang sebenarnya dan meng-update berat tersebut di dashboard admin.
   - Sistem akan otomatis menghitung total harga baru secara tepat berdasarkan berat timbangan toko.
   - Pelanggan langsung menerima notifikasi badge peringatan di dashboard pribadinya bahwa berat telah disesuaikan dan siap dibayar.

3. **Verifikasi Pembayaran & Proteksi Kunci Bukti Bayar**:
   - Pelanggan dapat mengunggah bukti transfer QRIS. Setelah Admin memverifikasi bukti tersebut dan mengubah status pembayaran menjadi **Lunas**, pelanggan **dikunci secara otomatis** agar tidak bisa mengunggah ulang/menimpa bukti pembayaran demi menjaga integritas data keuangan toko.

4. **Konfirmasi Setoran Uang Fisik (Internal Admin)**:
   - Menyediakan fitur pelacakan setoran uang kasir: apakah uang pembayaran (terutama COD/Tunai yang dipegang kurir) telah disetorkan secara fisik ke laci kasir outlet.
   - Admin memiliki tombol cepat **`📥 Terima Uang`** di panel daftarnya.
   - Status ini disembunyikan sepenuhnya dari panel pelanggan untuk menjaga privasi pembukuan toko.

5. **Catatan Khusus Pelanggan**:
   - Pelanggan bisa menulis instruksi khusus saat memesan laundry (misal: *"pisahkan pakaian putih"*, *"jangan pakai pewangi"*, dll.). Catatan ini ditampilkan jelas di dashboard admin, detail pelanggan, serta rekap laporan Excel.

6. **Prediksi Tanggal Selesai Otomatis**:
   - Sistem memprediksi estimasi tanggal selesai berdasarkan jenis layanan secara otomatis (+1 hari untuk Express, +3 hari untuk Reguler/Setrika). Prediksi ini ditampilkan di halaman detail pelanggan dan menu lacak status publik.

7. **Tombol WhatsApp Instan & Ekspor Laporan Excel**:
   - Admin dapat menghubungi pelanggan dalam 1 klik melalui tombol WhatsApp otomatis di daftar pelanggan.
   - Laporan transaksi dapat diekspor menjadi file Excel (.xls) lengkap dengan rekap data, total berat, total omzet, hingga status setoran kasir.

---

## 🗄️ Struktur Database Utama

- **`users`**: Menyimpan data autentikasi (id, username, password hash, role admin/user).
- **`pelanggan`**: Menyimpan data profil (id, nama, nomor telepon WhatsApp, alamat rumah, link GPS map).
- **`transaksi`**: Menyimpan data order laundry (kode, berat estimasi/riil, total harga, metode bayar, status bayar, status jemput/antar, catatan khusus, status setoran admin `uang_diterima`).

---

*Dibuat dengan ❤️ untuk sistem manajemen laundry yang aman, logis, dan ramah pengguna.*
