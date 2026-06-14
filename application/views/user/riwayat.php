<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * user/riwayat.php
 * Halaman riwayat lengkap transaksi laundry milik pelanggan yang sedang login.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>📋 Riwayat Laundry</h2>
        <p>Semua riwayat transaksi cucian atas nama: <strong><?= htmlspecialchars($pelanggan['nama']) ?></strong></p>
    </div>
    <div>
        <a href="<?= base_url('cek-status') ?>" target="_blank" class="btn btn-primary" id="btn-cek-status-riwayat">
            🔍 Cek Status Laundry
        </a>
    </div>
</div>

<!-- ── Tabel Riwayat Transaksi ── -->
<div class="card">
    <div class="card-header">
        <h3>🧾 Daftar Transaksi Laundry Anda</h3>
        <span style="font-size:.82rem; color:var(--gray-600); font-weight:500;">
            Total: <strong><?= count($transaksi) ?></strong> transaksi
        </span>
    </div>
    <div class="table-responsive">
        <?php if (empty($transaksi)): ?>
            <div class="empty-state">
                <div class="empty-icon">🧺</div>
                <p>Belum ada riwayat cucian laundry terdaftar untuk akun Anda.</p>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Kode Transaksi</th>
                    <th>Jenis Layanan</th>
                    <th>Berat Cucian</th>
                    <th>Total Harga</th>
                    <th>Tanggal Masuk</th>
                    <th>Status Cucian</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($transaksi as $t): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <code style="font-size:.8rem; background:var(--gray-100); padding:3px 8px; border-radius:5px; font-weight:600; color:var(--primary);">
                            <?= htmlspecialchars($t['kode_transaksi']) ?>
                        </code>
                    </td>
                    <td style="font-weight: 500; color:var(--gray-900);"><?= htmlspecialchars($t['jenis_layanan']) ?></td>
                    <td><?= number_format($t['berat'], 2) ?> kg</td>
                    <td style="font-weight: 700; color:var(--gray-900);">Rp <?= number_format($t['harga'], 0, ',', '.') ?></td>
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

<!-- ── Keterangan Alur Status ── -->
<div class="card" style="margin-top:24px;">
    <div class="card-header">
        <h3>📌 Alur & Penjelasan Status Laundry</h3>
    </div>
    <div class="card-body">
        <p style="font-size: .82rem; color: var(--gray-600); margin-bottom: 16px;">
            Operasional cucian Anda akan mengalami 6 tahapan status di bawah ini:
        </p>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
            <div style="display:flex; align-items:center; gap:8px;">
                <span class="badge badge-proses">Menunggu</span>
                <span style="font-size:.78rem; color:var(--gray-600);">Antrean cuci / masuk sistem</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span class="badge badge-proses">Dicuci</span>
                <span style="font-size:.78rem; color:var(--gray-600);">Sedang dibersihkan di mesin</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span class="badge badge-proses">Dikeringkan</span>
                <span style="font-size:.78rem; color:var(--gray-600);">Proses jemur / pengeringan</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span class="badge badge-proses">Disetrika</span>
                <span style="font-size:.78rem; color:var(--gray-600);">Proses setrika & packing</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span class="badge badge-selesai">Selesai</span>
                <span style="font-size:.78rem; color:var(--gray-600);">Rapi, siap untuk diambil</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span class="badge badge-diambil">Diambil</span>
                <span style="font-size:.78rem; color:var(--gray-600);">Sudah diterima oleh Anda</span>
            </div>
        </div>
    </div>
</div>
