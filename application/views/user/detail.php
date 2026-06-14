<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * user/detail.php
 * Halaman rincian (invoice) cucian tunggal milik pelanggan (User).
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>🧾 Rincian Transaksi</h2>
        <p>Detail nota transaksi cucian laundry Anda.</p>
    </div>
    <div>
        <a href="<?= base_url('user/riwayat') ?>" class="btn btn-secondary" id="btn-kembali-riwayat">
            &larr; Kembali ke Riwayat
        </a>
    </div>
</div>

<!-- ── INVOICE CARD ── -->
<div class="card" style="max-width: 700px; margin: 0 auto; border-top: 4px solid var(--primary);">
    <div class="card-header" style="display:flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <div>
            <h3 style="font-size: 1.1rem;">🧾 NOTA TRANSAKSI LAUNDRY</h3>
            <span style="font-size: .75rem; color: var(--gray-400);">Usaha LaundryKu Terpercaya</span>
        </div>
        <div style="text-align: right;">
            <code style="font-size: 1rem; font-weight:700; color: var(--primary); background: var(--primary-light); padding: 4px 10px; border-radius: 6px;">
                <?= htmlspecialchars($transaksi['kode_transaksi']) ?>
            </code>
        </div>
    </div>
    
    <div class="card-body" style="padding: 30px;">
        
        <!-- Baris Info Pelanggan & Tanggal -->
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px; border-bottom: 1px solid var(--gray-200); padding-bottom: 20px; margin-bottom: 20px;">
            <div>
                <span style="display:block; font-size: .72rem; color: var(--gray-400); text-transform: uppercase; font-weight:600;">Pelanggan</span>
                <strong style="font-size: .95rem; color: var(--gray-800);"><?= htmlspecialchars($transaksi['nama_pelanggan']) ?></strong>
                <span style="display:block; font-size: .8rem; color: var(--gray-600); margin-top:2px;">📞 <?= htmlspecialchars($transaksi['no_hp']) ?></span>
                <span style="display:block; font-size: .8rem; color: var(--gray-500); margin-top:2px;">🏠 <?= htmlspecialchars($transaksi['alamat']) ?></span>
            </div>
            <div style="text-align: right;">
                <span style="display:block; font-size: .72rem; color: var(--gray-400); text-transform: uppercase; font-weight:600;">Tanggal Masuk</span>
                <strong style="font-size: .95rem; color: var(--gray-800);"><?= date('d F Y', strtotime($transaksi['tanggal'])) ?></strong>
                
                <span style="display:block; font-size: .72rem; color: var(--gray-400); text-transform: uppercase; font-weight:600; margin-top:14px;">Status Cucian</span>
                <?php
                $badge_class = 'proses';
                $status = $transaksi['status'];
                if ($status === 'Selesai') {
                    $badge_class = 'selesai';
                } elseif ($status === 'Diambil') {
                    $badge_class = 'diambil';
                }
                ?>
                <span class="badge badge-<?= $badge_class ?>" style="margin-top: 4px; font-size:.8rem; padding: 4px 14px;">
                    <?= htmlspecialchars($status) ?>
                </span>
            </div>
        </div>

        <!-- Rincian Layanan & Harga -->
        <div style="margin-bottom: 30px;">
            <h4 style="font-size: .85rem; text-transform: uppercase; color: var(--gray-600); letter-spacing: .05em; margin-bottom: 12px; font-weight:600;">Rincian Item</h4>
            
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--gray-50); border-bottom: 1px solid var(--gray-200);">
                        <th style="padding: 10px 14px; text-align: left; font-size: .75rem; color: var(--gray-500);">Layanan Laundry</th>
                        <th style="padding: 10px 14px; text-align: right; font-size: .75rem; color: var(--gray-500); width: 100px;">Tarif / kg</th>
                        <th style="padding: 10px 14px; text-align: right; font-size: .75rem; color: var(--gray-500); width: 100px;">Berat</th>
                        <th style="padding: 10px 14px; text-align: right; font-size: .75rem; color: var(--gray-500); width: 140px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid var(--gray-100);">
                        <td style="padding: 14px; font-weight: 600; color: var(--gray-800);"><?= htmlspecialchars($transaksi['jenis_layanan']) ?></td>
                        <td style="padding: 14px; text-align: right; color: var(--gray-600);">
                            <?php
                            $tarif = 0;
                            if ($transaksi['jenis_layanan'] === 'Cuci Reguler') $tarif = 7000;
                            elseif ($transaksi['jenis_layanan'] === 'Cuci Express') $tarif = 10000;
                            elseif ($transaksi['jenis_layanan'] === 'Cuci + Setrika') $tarif = 12000;
                            echo 'Rp ' . number_format($tarif, 0, ',', '.');
                            ?>
                        </td>
                        <td style="padding: 14px; text-align: right; color: var(--gray-600);"><?= number_format($transaksi['berat'], 2) ?> kg</td>
                        <td style="padding: 14px; text-align: right; font-weight: 700; color: var(--gray-800);">Rp <?= number_format($transaksi['harga'], 0, ',', '.') ?></td>
                    </tr>
                    
                    <!-- Total Bayar -->
                    <tr>
                        <td colspan="2"></td>
                        <td style="padding: 16px 14px; text-align: right; font-weight: 600; color: var(--gray-600); font-size: .85rem;">TOTAL BAYAR:</td>
                        <td style="padding: 16px 14px; text-align: right; font-weight: 800; color: var(--primary); font-size: 1.15rem;">Rp <?= number_format($transaksi['harga'], 0, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Timeline 6 Status Cucian -->
        <div>
            <h4 style="font-size: .85rem; text-transform: uppercase; color: var(--gray-600); letter-spacing: .05em; margin-bottom: 16px; font-weight:600;">Status Pengerjaan Cucian</h4>
            
            <div style="display: flex; flex-direction: column; gap: 12px; background: var(--gray-50); padding: 18px 24px; border-radius: var(--radius-lg); border: 1px solid var(--gray-200);">
                <?php
                $status_flow = ['Menunggu', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
                $current_index = array_search($transaksi['status'], $status_flow);
                if ($current_index === FALSE) $current_index = 0;
                
                foreach ($status_flow as $idx => $step):
                    $is_passed = $idx <= $current_index;
                    $is_current = $idx === $current_index;
                ?>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .75rem; 
                            background: <?= $is_current ? 'var(--primary)' : ($is_passed ? 'var(--success)' : 'var(--gray-200)') ?>;
                            color: <?= $is_passed ? 'var(--white)' : 'var(--gray-400)' ?>; font-weight: 700;">
                            <?= $is_passed ? '✓' : $idx + 1 ?>
                        </div>
                        <span style="font-size: .85rem; font-weight: <?= $is_current ? '700' : '500' ?>; 
                            color: <?= $is_current ? 'var(--primary)' : ($is_passed ? 'var(--gray-800)' : 'var(--gray-400)') ?>;">
                            <?= htmlspecialchars($step) ?> 
                            <?= $is_current ? '<small style="font-weight:600; color:var(--primary-dark); font-style:italic;">(Sedang berada di tahap ini)</small>' : '' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>
