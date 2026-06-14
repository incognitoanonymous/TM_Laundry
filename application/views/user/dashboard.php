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
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
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
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($transaksi, 0, 5) as $t): ?>
                <tr>
                    <td>
                        <code style="font-size:.8rem; background:var(--gray-100); padding:3px 8px; border-radius:5px; font-weight:600; color:var(--primary);">
                            <?= htmlspecialchars($t['kode_transaksi']) ?>
                        </code>
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

<?php endif; ?>
