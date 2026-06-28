<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * laporan/index.php
 * Halaman rekap laporan keuangan dan transaksi laundry - Administrator.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>📊 Laporan Transaksi & Pendapatan</h2>
        <p>Analisis pendapatan laundry, jumlah pelanggan, dan status penyelesaian cucian.</p>
    </div>
    <div>
        <a href="<?= base_url('admin/laporan/export_excel?' . http_build_query($_GET)) ?>" class="btn btn-success" id="btn-export-laporan-excel">
            🟢 Ekspor Excel
        </a>
    </div>
</div>

<!-- ── PANEL FILTER LAPORAN ── -->
<div class="card" style="margin-bottom: 28px;">
    <div class="card-header">
        <h3>🔍 Saring Laporan</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/laporan') ?>" method="get" id="form-filter-laporan" style="display:flex; flex-direction:column; gap:16px;">
            
            <div style="display: flex; gap: 20px; align-items: center; border-bottom: 1px solid var(--gray-200); padding-bottom: 12px; flex-wrap: wrap;">
                <strong style="font-size: .85rem; color: var(--gray-700);">Pilih Jenis Filter:</strong>
                
                <label style="display: flex; align-items: center; gap: 6px; font-size: .85rem; cursor: pointer; font-weight: 500;">
                    <input type="radio" name="filter_type" value="harian" <?= ($filter_type === 'harian') ? 'checked' : '' ?> onchange="toggleFilterInputs()">
                    📅 Harian
                </label>

                <label style="display: flex; align-items: center; gap: 6px; font-size: .85rem; cursor: pointer; font-weight: 500;">
                    <input type="radio" name="filter_type" value="bulanan" <?= ($filter_type === 'bulanan') ? 'checked' : '' ?> onchange="toggleFilterInputs()">
                    📅 Bulanan
                </label>

                <label style="display: flex; align-items: center; gap: 6px; font-size: .85rem; cursor: pointer; font-weight: 500;">
                    <input type="radio" name="filter_type" value="rentang" <?= ($filter_type === 'rentang') ? 'checked' : '' ?> onchange="toggleFilterInputs()">
                    📅 Rentang Tanggal
                </label>
            </div>

            <!-- Bagian Input Form yang Dinamis -->
            <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                
                <!-- Input Filter Harian -->
                <div class="form-group filter-input-group" id="group-harian" style="display: none;">
                    <label for="tanggal">Pilih Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= htmlspecialchars($tanggal ?? date('Y-m-d')) ?>">
                </div>

                <!-- Input Filter Bulanan -->
                <div class="form-group filter-input-group" id="group-bulanan" style="display: none; grid-column: span 2;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label for="bulan">Pilih Bulan</label>
                            <select id="bulan" name="bulan" class="form-control">
                                <?php
                                $indo_months = [
                                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                ];
                                foreach ($indo_months as $m_val => $m_name):
                                ?>
                                    <option value="<?= $m_val ?>" <?= ($bulan === $m_val) ? 'selected' : '' ?>>
                                        <?= $m_name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="tahun">Pilih Tahun</label>
                            <select id="tahun" name="tahun" class="form-control">
                                <?php 
                                $current_year = date('Y');
                                for ($yr = $current_year - 5; $yr <= $current_year + 5; $yr++): 
                                ?>
                                    <option value="<?= $yr ?>" <?= ($tahun == $yr) ? 'selected' : '' ?>>
                                        <?= $yr ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Input Filter Rentang Tanggal -->
                <div class="form-group filter-input-group" id="group-rentang" style="display: none; grid-column: span 2;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label for="tgl_mulai">Tanggal Mulai</label>
                            <input type="date" id="tgl_mulai" name="tgl_mulai" class="form-control" value="<?= htmlspecialchars($tgl_mulai ?? date('Y-m-d')) ?>">
                        </div>
                        <div>
                            <label for="tgl_akhir">Tanggal Akhir</label>
                            <input type="date" id="tgl_akhir" name="tgl_akhir" class="form-control" value="<?= htmlspecialchars($tgl_akhir ?? date('Y-m-d')) ?>">
                        </div>
                    </div>
                </div>

            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" id="btn-submit-laporan" style="padding: 10px 24px;">
                    📊 Rekapitulasi Data Laporan
                </button>
            </div>

        </form>
    </div>
</div>

<!-- ── KARTU REKAPITULASI STATISTIK HASIL FILTER ── -->
<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); margin-bottom: 28px;">
    <div class="stat-card" style="border-top: 4px solid var(--success);">
        <div class="stat-icon green" style="background:#dcfce7; color:#166534;">💰</div>
        <div class="stat-info">
            <p>Total Pendapatan</p>
            <h3 style="font-size:1.4rem;">Rp <?= number_format($statistik['total_pendapatan'] ?? 0, 0, ',', '.') ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow" style="background:#fef9c3; color:#854d0e;">🧾</div>
        <div class="stat-info">
            <p>Total Transaksi</p>
            <h3><?= $statistik['total_transaksi'] ?? 0 ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">👥</div>
        <div class="stat-info">
            <p>Total Pelanggan</p>
            <h3><?= $statistik['total_pelanggan'] ?? 0 ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green" style="background:#f0fdf4; color:#15803d; font-size:1.15rem; width:44px; height:44px;">✅</div>
        <div class="stat-info">
            <p>Cucian Selesai</p>
            <h3><?= $statistik['laundry_selesai'] ?? 0 ?></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon sky" style="background:#f1f5f9; color:#475569; font-size:1.15rem; width:44px; height:44px;">📦</div>
        <div class="stat-info">
            <p>Cucian Diambil</p>
            <h3><?= $statistik['laundry_diambil'] ?? 0 ?></h3>
        </div>
    </div>
</div>

<!-- ── DETAIL TRANSAKSI HASIL FILTER ── -->
<div class="card">
    <div class="card-header">
        <h3>🧾 Rincian Transaksi Terkait</h3>
        <span style="font-size:.8rem; color:var(--gray-600); font-weight:500;">
            Periode/Filter Terpilih
        </span>
    </div>
    <div class="table-responsive">
        <?php if (empty($laporan_transaksi)): ?>
            <div class="empty-state">
                <div class="empty-icon">📊</div>
                <p>Tidak ada transaksi laundry pada periode penyaringan terpilih.</p>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Kode</th>
                    <th>Nama Pelanggan</th>
                    <th>Jenis Layanan</th>
                    <th>Berat</th>
                    <th>Total Harga</th>
                    <th>Tanggal Masuk</th>
                    <th>Status Cucian</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($laporan_transaksi as $t): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <code style="font-size:.8rem; background:var(--gray-100); padding:3px 8px; border-radius:5px; font-weight:600; color:var(--primary);">
                            <?= htmlspecialchars($t['kode_transaksi']) ?>
                        </code>
                    </td>
                    <td style="font-weight: 600; color:var(--gray-900);"><?= htmlspecialchars($t['nama_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($t['jenis_layanan']) ?></td>
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

<!-- JavaScript Toggle Filter Input Form -->
<script>
function toggleFilterInputs() {
    const filterType = document.querySelector('input[name="filter_type"]:checked').value;
    
    // Sembunyikan semua input group
    document.querySelectorAll('.filter-input-group').forEach(function(el) {
        el.style.display = 'none';
    });

    // Tampilkan yang terpilih
    if (filterType === 'harian') {
        document.getElementById('group-harian').style.display = 'block';
    } else if (filterType === 'bulanan') {
        document.getElementById('group-bulanan').style.display = 'block';
    } else if (filterType === 'rentang') {
        document.getElementById('group-rentang').style.display = 'block';
    }
}

document.addEventListener("DOMContentLoaded", function() {
    toggleFilterInputs();
});
</script>
