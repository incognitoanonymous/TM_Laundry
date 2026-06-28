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
                    <th>Layanan Kurir & Konfirmasi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($transaksi as $t): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <a href="<?= base_url('user/detail/' . $t['id_transaksi']) ?>" style="text-decoration: none;" title="Lihat Detail Transaksi">
                            <code style="font-size:.8rem; background:var(--gray-100); padding:3px 8px; border-radius:5px; font-weight:600; color:var(--primary); cursor: pointer;">
                                <?= htmlspecialchars($t['kode_transaksi']) ?><?= $t['is_jemput'] == 1 ? ' 🚚' : '' ?>
                            </code>
                        </a>
                        <?php if (!empty($t['berat_estimasi']) && (float)$t['berat_estimasi'] != (float)$t['berat']): ?>
                            <br>
                            <?php if ((float)$t['berat'] > (float)$t['berat_estimasi']): ?>
                                <span style="font-size: .65rem; color: #b45309; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 4px; padding: 2px 5px; font-weight: 600; display: inline-block; margin-top: 4px;" title="Timbangan riil lebih berat dari estimasi awal">
                                    ⚠️ Penyesuaian Berat (+ kg)
                                </span>
                            <?php else: ?>
                                <span style="font-size: .65rem; color: #166534; background: #f0fdf4; border: 1px solid #a7f3d0; border-radius: 4px; padding: 2px 5px; font-weight: 600; display: inline-block; margin-top: 4px;" title="Timbangan riil lebih ringan dari estimasi awal (Refund Cash)">
                                    💡 Kelebihan Berat (Refund)
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
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
                    <td>
                        <?php if ($t['is_jemput'] == 0 && $t['is_antar'] == 0): ?>
                            <span style="color: var(--gray-400); font-size: .8rem;">Bawa Sendiri</span>
                        <?php endif; ?>

                        <!-- Penjemputan -->
                        <?php if ($t['is_jemput'] == 1): ?>
                            <div style="margin-bottom: 8px;">
                                <span style="font-size: .72rem; font-weight: 600; color: var(--gray-500); display: block; margin-bottom: 2px;">JEMPUT:</span>
                                <?php if ($t['status_jemput'] === 'Sudah Dijemput'): ?>
                                    <span class="badge badge-selesai" style="font-size: .7rem; padding: 2px 6px;">🚚 Sudah Dijemput</span>
                                <?php else: ?>
                                    <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                        <span class="badge badge-proses" style="font-size: .7rem; padding: 2px 6px;">🚚 Menunggu Jemput</span>
                                        <a href="<?= base_url('user/pesan/konfirmasi_jemput/' . $t['id_transaksi']) ?>" class="btn btn-primary" style="font-size: .7rem; padding: 3px 8px; background: var(--success); border-color: var(--success); color: var(--white); font-weight: 600; text-decoration: none; border-radius: var(--radius); display: inline-block; text-align: center;" onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pakaian sudah dijemput oleh kurir?');">
                                            Konfirmasi Jemput
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Pengantaran -->
                        <?php if ($t['is_antar'] == 1): ?>
                            <div>
                                <span style="font-size: .72rem; font-weight: 600; color: var(--gray-500); display: block; margin-bottom: 2px;">ANTAR:</span>
                                <?php if ($t['status_antar'] === 'Sudah Diantarkan'): ?>
                                    <span class="badge badge-selesai" style="font-size: .7rem; padding: 2px 6px;">🛵 Sudah Diterima</span>
                                <?php else: ?>
                                    <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-start;">
                                        <span class="badge badge-proses" style="font-size: .7rem; padding: 2px 6px;">🛵 Menunggu Antar</span>
                                        <?php if ($t['status'] === 'Selesai'): ?>
                                            <a href="<?= base_url('user/pesan/konfirmasi_antar/' . $t['id_transaksi']) ?>" class="btn btn-primary" style="font-size: .7rem; padding: 3px 8px; background: var(--success); border-color: var(--success); color: var(--white); font-weight: 600; text-decoration: none; border-radius: var(--radius); display: inline-block; text-align: center;" onclick="return confirm('Apakah Anda yakin pakaian laundry sudah Anda terima?');">
                                                Konfirmasi Diterima
                                            </a>
                                        <?php else: ?>
                                            <span style="font-size: .65rem; color: var(--gray-400); font-style: italic;">(Tunggu Selesai)</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
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
