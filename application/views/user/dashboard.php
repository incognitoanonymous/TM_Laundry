<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * user/dashboard.php
 * Halaman utama panel pelanggan (User) - statistik transaksi cucian milik sendiri.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>👋 Halo, <?= htmlspecialchars($this->session->userdata('username')) ?>!</h2>
        <p>Selamat datang di panel pelanggan LaundryKu. Berikut status cucian Anda saat ini.</p>
    </div>
    <div>
        <a href="<?= base_url('user/profil') ?>" class="btn btn-primary" id="btn-edit-profil-dashboard">
            👤 Kelola Profil & Sandi
        </a>
    </div>
</div>

<?php if (!$pelanggan): ?>
    <!-- User belum dikaitkan dengan profil pelanggan -->
    <div class="alert alert-warning" id="alert-warning-unregistered">
        ⚠️ Akun Anda belum dikaitkan dengan data pelanggan. Silakan hubungi Administrator laundry untuk memetakan akun login Anda.
    </div>
<?php else: ?>

<!-- ── Statistik Cucian Pribadi ── -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <div class="stat-card">
        <div class="stat-icon yellow" style="background:#fee2e2; color:#991b1b;">⏳</div>
        <div class="stat-info">
            <p>Sedang Diproses</p>
            <h3><?= $proses ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green" style="background:#dcfce7; color:#166534;">✅</div>
        <div class="stat-info">
            <p>Sudah Selesai</p>
            <h3><?= $selesai ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon sky" style="background:#e0f2fe; color:#075985;">📦</div>
        <div class="stat-info">
            <p>Sudah Diambil</p>
            <h3><?= $diambil ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue" style="background:#f1f5f9; color:#475569;">🧾</div>
        <div class="stat-info">
            <p>Total Transaksi</p>
            <h3><?= $total ?></h3>
        </div>
    </div>
</div>

<!-- ── Ringkasan Data Pelanggan ── -->
<div class="card" style="margin-top: 24px; margin-bottom: 24px;">
    <div class="card-header">
        <h3>👤 Informasi Profil Pelanggan</h3>
        <a href="<?= base_url('user/profil') ?>" style="font-size: .8rem; color: var(--primary); font-weight: 600;">Edit Profil &rarr;</a>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
            <div>
                <span style="display:block; font-size: .75rem; color: var(--gray-400); font-weight: 500; text-transform: uppercase;">Nama Lengkap</span>
                <strong style="font-size: .95rem; color: var(--gray-800);"><?= htmlspecialchars($pelanggan['nama']) ?></strong>
            </div>
            <div>
                <span style="display:block; font-size: .75rem; color: var(--gray-400); font-weight: 500; text-transform: uppercase;">Nomor Handphone</span>
                <strong style="font-size: .95rem; color: var(--gray-800);"><?= htmlspecialchars($pelanggan['no_hp']) ?></strong>
            </div>
            <div>
                <span style="display:block; font-size: .75rem; color: var(--gray-400); font-weight: 500; text-transform: uppercase;">Alamat Rumah</span>
                <strong style="font-size: .95rem; color: var(--gray-800);"><?= htmlspecialchars($pelanggan['alamat']) ?></strong>
            </div>
            <div>
                <span style="display:block; font-size: .75rem; color: var(--gray-400); font-weight: 500; text-transform: uppercase;">🎁 Saldo Poin Reward</span>
                <strong style="font-size: .95rem; color: #1d4ed8; font-weight: 700;"><?= intval($pelanggan['poin']) ?> poin</strong>
            </div>
        </div>
    </div>
</div>

<!-- ── Tabel Transaksi Terbaru User ── -->
<div class="card">
    <div class="card-header">
        <h3>🧾 Transaksi Terakhir Anda (Maks. 5 data)</h3>
        <a href="<?= base_url('user/riwayat') ?>" class="btn btn-secondary btn-sm" id="btn-riwayat-semua-dashboard">
            Lihat Semua Riwayat &rarr;
        </a>
    </div>
    <div class="table-responsive">
        <?php if (empty($transaksi)): ?>
            <div class="empty-state">
                <div class="empty-icon">🧺</div>
                <p>Belum ada riwayat cucian laundry atas nama Anda.</p>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Jenis Layanan</th>
                    <th>Berat</th>
                    <th>Total Harga</th>
                    <th>Tanggal Masuk</th>
                    <th>Status Cucian</th>
                    <th>Layanan Kurir & Konfirmasi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($transaksi, 0, 5) as $t): ?>
                <tr>
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
                    <td style="font-weight: 500; color: var(--gray-900);"><?= htmlspecialchars($t['jenis_layanan']) ?></td>
                    <td><?= number_format($t['berat'], 2) ?> kg</td>
                    <td style="font-weight: 600;">Rp <?= number_format($t['harga'], 0, ',', '.') ?></td>
                    <td><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
                    <td>
                        <?php
                        $badge_class = 'proses';
                        $status = $t['status'];
                        if ($status === 'Selesai') {
                            $badge_class = 'selesai';
                        } elseif ($status === 'Diambil') {
                            $badge_class = 'diambil';
                        } elseif ($status === 'Dibatalkan') {
                            $badge_class = 'batal';
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

<?php endif; ?>
