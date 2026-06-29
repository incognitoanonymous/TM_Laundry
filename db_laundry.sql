-- ============================================================
-- DATABASE: db_laundry
-- Sistem Informasi Manajemen Laundry
-- ============================================================

CREATE DATABASE IF NOT EXISTS `db_laundry`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `db_laundry`;

-- ============================================================
-- TABEL: users
-- Menyimpan akun login (admin & user/pelanggan)
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id_user`    INT(11)      NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(50)  NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `role`       ENUM('admin','user') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABEL: pelanggan
-- Data pelanggan laundry, terhubung ke tabel users
-- ============================================================
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id_pelanggan` INT(11)      NOT NULL AUTO_INCREMENT,
  `id_user`      INT(11)      NOT NULL,
  `nama`         VARCHAR(100) NOT NULL,
  `no_hp`        VARCHAR(20)  NOT NULL,
  `alamat`       TEXT         NOT NULL,
  `poin`         INT          NOT NULL DEFAULT 0,
  `created_at`   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pelanggan`),
  CONSTRAINT `fk_pelanggan_user`
    FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABEL: transaksi
-- Data transaksi laundry, terhubung ke tabel pelanggan
-- ============================================================
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi`   INT(11)       NOT NULL AUTO_INCREMENT,
  `kode_transaksi` VARCHAR(30)   NOT NULL UNIQUE,
  `id_pelanggan`   INT(11)       NOT NULL,
  `jenis_layanan`  VARCHAR(50)   NOT NULL,
  `berat`          DECIMAL(5,2)  NOT NULL,
  `berat_estimasi` DECIMAL(5,2)  DEFAULT NULL,
  `harga`          DECIMAL(12,2) NOT NULL,
  `status`         ENUM('Menunggu','Dicuci','Dikeringkan','Disetrika','Selesai','Diambil') NOT NULL DEFAULT 'Menunggu',
  `tanggal`        DATE          NOT NULL,
  `is_jemput`      TINYINT(1)    NOT NULL DEFAULT 0,
  `alamat_jemput`  TEXT          DEFAULT NULL,
  `gps_jemput`     VARCHAR(255)  DEFAULT NULL,
  `status_jemput`  ENUM('Menunggu Penjemputan','Sudah Dijemput') DEFAULT 'Menunggu Penjemputan',
  `ongkir_jemput`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `is_antar`       TINYINT(1)    NOT NULL DEFAULT 0,
  `alamat_antar`   TEXT          DEFAULT NULL,
  `gps_antar`      VARCHAR(255)  DEFAULT NULL,
  `status_antar`   ENUM('Menunggu Pengantaran','Sudah Diantarkan') DEFAULT 'Menunggu Pengantaran',
  `ongkir_antar`   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `rating`         INT           DEFAULT NULL,
  `review`         TEXT          DEFAULT NULL,
  `poin_earned`    INT           NOT NULL DEFAULT 0,
  `poin_used`      INT           NOT NULL DEFAULT 0,
  `reward_used`    VARCHAR(100)  DEFAULT NULL,
  `metode_pembayaran` ENUM('Tunai','QRIS','Transfer Bank','Virtual Account') NOT NULL DEFAULT 'Tunai',
  `status_pembayaran` ENUM('Belum Bayar','Menunggu Verifikasi','Lunas') NOT NULL DEFAULT 'Belum Bayar',
  `bukti_pembayaran`  VARCHAR(255)  DEFAULT NULL,
  `catatan`           TEXT          DEFAULT NULL,
  `uang_diterima`     TINYINT(1)    NOT NULL DEFAULT 0,
  `created_at`     TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaksi`),
  CONSTRAINT `fk_transaksi_pelanggan`
    FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- DEFAULT AKUN ADMIN & DUMMY DATA
-- Password default: "1234" (di-hash menggunakan Bcrypt)
-- ============================================================

-- 1. Insert Akun Users (1 Admin, 5 Pelanggan)
INSERT INTO `users` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$Zx1ZBDMTzlj8Ao1fxAF1buJnDN445CwFXiziglB43b8CEU7w0zA62', 'admin'),
(2, 'budi', '$2y$10$Zx1ZBDMTzlj8Ao1fxAF1buJnDN445CwFXiziglB43b8CEU7w0zA62', 'user'),
(3, 'siti', '$2y$10$Zx1ZBDMTzlj8Ao1fxAF1buJnDN445CwFXiziglB43b8CEU7w0zA62', 'user'),
(4, 'andi', '$2y$10$Zx1ZBDMTzlj8Ao1fxAF1buJnDN445CwFXiziglB43b8CEU7w0zA62', 'user'),
(5, 'dewi', '$2y$10$Zx1ZBDMTzlj8Ao1fxAF1buJnDN445CwFXiziglB43b8CEU7w0zA62', 'user'),
(6, 'roni', '$2y$10$Zx1ZBDMTzlj8Ao1fxAF1buJnDN445CwFXiziglB43b8CEU7w0zA62', 'user');

-- 2. Insert Data Pelanggan
INSERT INTO `pelanggan` (`id_pelanggan`, `id_user`, `nama`, `no_hp`, `alamat`) VALUES
(1, 2, 'Budi Santoso', '081234567890', 'Jl. Merdeka No. 10, Jakarta'),
(2, 3, 'Siti Aminah', '082345678901', 'Jl. Sudirman No. 25, Bandung'),
(3, 4, 'Andi Wijaya', '083456789012', 'Jl. Gatot Subroto No. 50, Surabaya'),
(4, 5, 'Dewi Lestari', '084567890123', 'Jl. Diponegoro No. 12, Yogyakarta'),
(5, 6, 'Roni Hidayat', '085678901234', 'Jl. Pemuda No. 8, Semarang');

-- 3. Insert Data Transaksi (10 Transaksi dummy)
-- Tarif otomatis:
-- Cuci Reguler   : Rp 7.000 / kg
-- Cuci Express   : Rp 10.000 / kg
-- Cuci + Setrika : Rp 12.000 / kg
INSERT INTO `transaksi` (`kode_transaksi`, `id_pelanggan`, `jenis_layanan`, `berat`, `harga`, `status`, `tanggal`) VALUES
('TRX-20260520-001', 1, 'Cuci Reguler', 3.00, 21000.00, 'Diambil', '2026-05-20'),
('TRX-20260522-001', 2, 'Cuci + Setrika', 4.50, 54000.00, 'Diambil', '2026-05-22'),
('TRX-20260525-001', 3, 'Cuci Express', 2.00, 20000.00, 'Selesai', '2026-05-25'),
('TRX-20260526-001', 4, 'Cuci Reguler', 5.00, 35000.00, 'Disetrika', '2026-05-26'),
('TRX-20260528-001', 5, 'Cuci + Setrika', 3.20, 38400.00, 'Dikeringkan', '2026-05-28'),
('TRX-20260529-001', 1, 'Cuci Express', 1.50, 15000.00, 'Dicuci', '2026-05-29'),
('TRX-20260530-001', 2, 'Cuci Reguler', 2.80, 19600.00, 'Menunggu', '2026-05-30'),
('TRX-20260531-001', 3, 'Cuci + Setrika', 6.00, 72000.00, 'Menunggu', '2026-05-31'),
('TRX-20260531-002', 4, 'Cuci Reguler', 2.00, 14000.00, 'Menunggu', '2026-05-31'),
('TRX-20260531-003', 5, 'Cuci Express', 4.00, 40000.00, 'Menunggu', '2026-05-31');
