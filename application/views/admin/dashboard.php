<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * admin/dashboard.php
 * Halaman utama panel admin - menampilkan statistik metrik bisnis dan daftar transaksi terbaru.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>🏠 Dashboard</h2>
        <p>Selamat datang! Berikut ringkasan aktivitas operasional laundry Anda.</p>
    </div>
    <div>
        <a href="<?= base_url('admin/transaksi/tambah') ?>" class="btn btn-primary" id="btn-tambah-transaksi-dashboard">
            ➕ Transaksi Baru
        </a>
    </div>
</div>

<!-- ── Bagian Atas: Statistik Finansial & Akun ── -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); margin-bottom: 20px;">
    <div class="stat-card" style="border-top: 4px solid var(--success);">
        <div class="stat-icon green" style="background:#dcfce7; color:#166534;">💰</div>
        <div class="stat-info">
            <p>Total Pendapatan</p>
            <h3 style="font-size:1.5rem;">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">👥</div>
        <div class="stat-info">
            <p>Total Pelanggan</p>
            <h3><?= $total_pelanggan ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon sky" style="background:#e0f2fe; color:#075985;">🛡️</div>
        <div class="stat-info">
            <p>Total Akun User</p>
            <h3><?= $total_user ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow" style="background:#fef9c3; color:#854d0e;">🧾</div>
        <div class="stat-info">
            <p>Total Transaksi</p>
            <h3><?= $total_transaksi ?></h3>
        </div>
    </div>
</div>

<h3 style="font-size:1rem; font-weight:600; color:var(--gray-700); margin-bottom:14px; margin-top:28px;">🔄 Monitoring Status Cucian</h3>

<!-- ── Bagian Tengah: Detail Status Cucian ── -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
    <div class="stat-card">
        <div class="stat-icon red" style="background:#fee2e2; color:#991b1b; font-size:1.1rem; width:42px; height:42px;">⏳</div>
        <div class="stat-info">
            <p>Menunggu</p>
            <h3 style="font-size:1.4rem;"><?= $total_menunggu ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue" style="background:#dbeafe; color:#1e40af; font-size:1.1rem; width:42px; height:42px;">🫧</div>
        <div class="stat-info">
            <p>Dicuci</p>
            <h3 style="font-size:1.4rem;"><?= $total_dicuci ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon sky" style="background:#e0f2fe; color:#075985; font-size:1.1rem; width:42px; height:42px;">☀️</div>
        <div class="stat-info">
            <p>Dikeringkan</p>
            <h3 style="font-size:1.4rem;"><?= $total_dikeringkan ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow" style="background:#fef9c3; color:#854d0e; font-size:1.1rem; width:42px; height:42px;">🔌</div>
        <div class="stat-info">
            <p>Disetrika</p>
            <h3 style="font-size:1.4rem;"><?= $total_disetrika ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green" style="background:#dcfce7; color:#166534; font-size:1.1rem; width:42px; height:42px;">✅</div>
        <div class="stat-info">
            <p>Selesai</p>
            <h3 style="font-size:1.4rem;"><?= $total_selesai ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon sky" style="background:#f1f5f9; color:#475569; font-size:1.1rem; width:42px; height:42px;">📦</div>
        <div class="stat-info">
            <p>Diambil</p>
            <h3 style="font-size:1.4rem;"><?= $total_diambil ?></h3>
        </div>
    </div>
</div>

<!-- ── Bagian Bawah: Tabel Transaksi Terbaru ── -->
<div class="card" style="margin-top:28px;">
    <div class="card-header">
        <h3>🧾 Transaksi Terbaru (Maks. 5 data)</h3>
        <a href="<?= base_url('admin/transaksi') ?>" class="btn btn-secondary btn-sm" id="btn-lihat-semua-dashboard">
            Lihat Semua Transaksi →
        </a>
    </div>
    <div class="table-responsive">
        <?php if (empty($transaksi_terbaru)): ?>
            <div class="empty-state">
                <div class="empty-icon">🧺</div>
                <p>Belum ada data transaksi masuk. <a href="<?= base_url('admin/transaksi/tambah') ?>">Buat sekarang</a>.</p>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Jenis Layanan</th>
                    <th>Berat</th>
                    <th>Total Harga</th>
                    <th>Tanggal Masuk</th>
                    <th>Status Cucian</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach (array_slice($transaksi_terbaru, 0, 5) as $t): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <code style="font-size:.8rem; background:var(--gray-100); padding:3px 8px; border-radius:5px; font-weight:600; color:var(--primary);">
                            <?= htmlspecialchars($t['kode_transaksi']) ?>
                        </code>
                    </td>
                    <td style="font-weight:500;"><?= htmlspecialchars($t['nama_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($t['jenis_layanan']) ?></td>
                    <td><?= number_format($t['berat'], 2) ?> kg</td>
                    <td style="font-weight:600; color:var(--gray-900);">Rp <?= number_format($t['harga'], 0, ',', '.') ?></td>
                    <td><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
                    <td>
                        <?php
                        $badge_class = 'proses';
                        $status = $t['status'];
                        if ($status === 'Selesai') {
                            $badge_class = 'selesai';
                        } elseif ($status === 'Diambil') {
                            $badge_class = 'diambil';
                        }
                        ?>
                        <span class="badge badge-<?= $badge_class ?>">
                            <?= htmlspecialchars($status) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
