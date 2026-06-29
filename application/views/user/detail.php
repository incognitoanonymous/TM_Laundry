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
    <div style="display: flex; gap: 8px;">
        <?php if ($transaksi['status'] === 'Menunggu' && $transaksi['status_pembayaran'] === 'Belum Bayar' && $transaksi['status_jemput'] !== 'Sudah Dijemput'): ?>
            <a href="<?= base_url('user/pesan/batalkan/' . $transaksi['id_transaksi']) ?>" class="btn" style="background: var(--danger); color: white; display: inline-flex; align-items: center;" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan laundry ini? Poin Anda (jika digunakan) akan dikembalikan.');" id="btn-batalkan-pesanan">
                ❌ Batalkan Pesanan
            </a>
        <?php endif; ?>
        <a href="<?= base_url('user/riwayat') ?>" class="btn btn-secondary" id="btn-kembali-riwayat">
            &larr; Kembali ke Riwayat
        </a>
        <button onclick="window.print()" class="btn btn-primary" id="btn-cetak-invoice">
            🖨️ Cetak PDF / Invoice
        </button>
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
        
        <!-- Notifikasi Selisih Berat Estimasi vs Riil -->
        <?php if (!empty($transaksi['berat_estimasi']) && (float)$transaksi['berat_estimasi'] != (float)$transaksi['berat']): ?>
            <?php 
            $selisih = abs((float)$transaksi['berat'] - (float)$transaksi['berat_estimasi']);
            
            $tarif_layanan = 0;
            if ($transaksi['jenis_layanan'] === 'Cuci Reguler') $tarif_layanan = 7000;
            elseif ($transaksi['jenis_layanan'] === 'Cuci Express') $tarif_layanan = 10000;
            elseif ($transaksi['jenis_layanan'] === 'Cuci + Setrika') $tarif_layanan = 12000;
            
            $ongkir_rate = 0;
            if ($transaksi['is_jemput'] == 1) $ongkir_rate += 10000;
            if ($transaksi['is_antar'] == 1) $ongkir_rate += 10000;
            
            $tarif_total = $tarif_layanan + $ongkir_rate;
            $selisih_harga = $selisih * $tarif_total;
            ?>
            
            <?php if ((float)$transaksi['berat'] > (float)$transaksi['berat_estimasi']): ?>
                <div class="alert alert-warning" style="margin-bottom: 20px; border-left: 4px solid var(--warning); background: #fffbeb; color: #92400e; padding: 14px 20px; border-radius: var(--radius); font-size: .85rem; line-height: 1.5; text-align: left;">
                    ⚠️ <strong>Penyesuaian Berat Cucian:</strong> Berat pakaian Anda setelah ditimbang oleh Admin adalah <strong><?= number_format($transaksi['berat'], 2) ?> kg</strong> (estimasi awal: <strong><?= number_format($transaksi['berat_estimasi'], 2) ?> kg</strong>). 
                    Terdapat kekurangan pembayaran sebesar <strong>Rp <?= number_format($selisih_harga, 0, ',', '.') ?></strong> yang telah ditambahkan ke total tagihan Anda.
                </div>
            <?php else: ?>
                <?php if ($transaksi['status_pembayaran'] === 'Lunas'): ?>
                    <div class="alert alert-success" style="margin-bottom: 20px; border-left: 4px solid var(--success); background: #f0fdf4; color: #166534; padding: 14px 20px; border-radius: var(--radius); font-size: .85rem; line-height: 1.5; text-align: left;">
                        💡 <strong>Kelebihan Pembayaran (Refund Cash):</strong> Berat pakaian Anda setelah ditimbang oleh Admin adalah <strong><?= number_format($transaksi['berat'], 2) ?> kg</strong> (estimasi awal: <strong><?= number_format($transaksi['berat_estimasi'], 2) ?> kg</strong>). 
                        Karena tagihan Anda sudah <strong>LUNAS</strong>, uang kembalian/refund sebesar <strong>Rp <?= number_format($selisih_harga, 0, ',', '.') ?></strong> akan diserahkan secara <strong>tunai/cash</strong> oleh kurir saat pengantaran atau kasir saat pengambilan pakaian.
                    </div>
                <?php else: ?>
                    <div class="alert alert-info" style="margin-bottom: 20px; border-left: 4px solid var(--primary); background: var(--primary-light); color: var(--primary-dark); padding: 14px 20px; border-radius: var(--radius); font-size: .85rem; line-height: 1.5; text-align: left;">
                        💡 <strong>Penyesuaian Berat Cucian:</strong> Berat pakaian Anda setelah ditimbang oleh Admin adalah <strong><?= number_format($transaksi['berat'], 2) ?> kg</strong> (estimasi awal: <strong><?= number_format($transaksi['berat_estimasi'], 2) ?> kg</strong>). 
                        Total tagihan Anda disesuaikan (berkurang sebesar <strong>Rp <?= number_format($selisih_harga, 0, ',', '.') ?></strong>).
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        
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
                        <td style="padding: 14px; text-align: right; font-weight: 700; color: var(--gray-800);">
                            <?php
                            $laundry_cost = $transaksi['harga'] - $transaksi['ongkir_jemput'] - $transaksi['ongkir_antar'];
                            echo 'Rp ' . number_format($laundry_cost, 0, ',', '.');
                            ?>
                        </td>
                    </tr>
                    
                    <?php if ($transaksi['is_jemput'] == 1): ?>
                    <tr style="border-bottom: 1px solid var(--gray-100);">
                        <td colspan="2"></td>
                        <td style="padding: 10px 14px; text-align: right; font-weight: 600; color: var(--gray-500); font-size: .85rem;">Biaya Jemput:</td>
                        <td style="padding: 10px 14px; text-align: right; font-weight: 700; color: var(--gray-700); font-size: .9rem;">Rp <?= number_format($transaksi['ongkir_jemput'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endif; ?>

                    <?php if ($transaksi['is_antar'] == 1): ?>
                    <tr style="border-bottom: 1px solid var(--gray-100);">
                        <td colspan="2"></td>
                        <td style="padding: 10px 14px; text-align: right; font-weight: 600; color: var(--gray-500); font-size: .85rem;">Biaya Antar:</td>
                        <td style="padding: 10px 14px; text-align: right; font-weight: 700; color: var(--gray-700); font-size: .9rem;">Rp <?= number_format($transaksi['ongkir_antar'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endif; ?>

                    <!-- Total Bayar -->
                    <tr>
                        <td colspan="2"></td>
                        <td style="padding: 16px 14px; text-align: right; font-weight: 600; color: var(--gray-600); font-size: .85rem;">TOTAL BAYAR:</td>
                        <td style="padding: 16px 14px; text-align: right; font-weight: 800; color: var(--primary); font-size: 1.15rem;">Rp <?= number_format($transaksi['harga'], 0, ',', '.') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ESTIMASI SELESAI & CATATAN -->
        <div style="margin-bottom: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            <!-- Box Estimasi Selesai -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: var(--radius-lg); padding: 18px 20px;">
                <h4 style="font-size: .85rem; text-transform: uppercase; color: #475569; letter-spacing: .05em; margin-bottom: 10px; font-weight:600; display: flex; align-items: center; gap: 6px;">
                    📅 Estimasi Selesai
                </h4>
                <div style="font-size: .85rem; color: var(--gray-800); line-height: 1.5;">
                    <?php
                    $tanggal_masuk = $transaksi['tanggal'];
                    $jenis_layanan = $transaksi['jenis_layanan'];
                    $days = ($jenis_layanan === 'Cuci Express') ? 1 : 3;
                    $estimasi_selesai = date('d F Y', strtotime($tanggal_masuk . " + {$days} days"));
                    ?>
                    <strong><?= $estimasi_selesai ?></strong>
                    <span style="font-size: .75rem; color: var(--gray-400); display: block; margin-top: 4px;">
                        * Layanan <?= htmlspecialchars($jenis_layanan) ?> selesai dalam <?= $days ?> hari sejak pakaian diterima.
                    </span>
                </div>
            </div>

            <!-- Box Catatan Khusus -->
            <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: var(--radius-lg); padding: 18px 20px;">
                <h4 style="font-size: .85rem; text-transform: uppercase; color: #b45309; letter-spacing: .05em; margin-bottom: 10px; font-weight:600; display: flex; align-items: center; gap: 6px;">
                    📝 Catatan Khusus
                </h4>
                <div style="font-size: .85rem; color: #92400e; line-height: 1.5; font-style: <?= empty($transaksi['catatan']) ? 'italic' : 'normal' ?>;">
                    <?= !empty($transaksi['catatan']) ? nl2br(htmlspecialchars($transaksi['catatan'])) : 'Tidak ada catatan khusus.' ?>
                </div>
            </div>
        </div>

        <!-- DETAIL PEMBAYARAN -->
        <div style="margin-bottom: 30px; background: #fafafa; border: 1px solid var(--gray-200); border-radius: var(--radius-lg); padding: 18px 24px;">
            <h4 style="font-size: .85rem; text-transform: uppercase; color: var(--gray-600); letter-spacing: .05em; margin-bottom: 12px; font-weight:600; display: flex; align-items: center; gap: 6px;">
                💳 Informasi & Status Pembayaran
            </h4>
            <div style="font-size: .85rem; color: var(--gray-800); line-height: 1.6;">
                <div style="margin-bottom: 8px;">
                    <span style="color: var(--gray-500); display: block; font-size: .75rem; font-weight: 600;">METODE PEMBAYARAN</span>
                    <strong style="font-size: 1rem; color: var(--gray-800);"><?= htmlspecialchars($transaksi['metode_pembayaran']) ?></strong>
                </div>

                <div style="margin-bottom: 12px;">
                    <span style="color: var(--gray-500); display: block; font-size: .75rem; font-weight: 600; margin-bottom: 4px;">STATUS PEMBAYARAN</span>
                    <?php
                    $status_bayar = $transaksi['status_pembayaran'];
                    ?>
                    <span class="badge" style="font-size: .8rem; padding: 4px 12px; font-weight: 700; color: white; border-radius: 4px; display: inline-block;
                        background: <?= $status_bayar === 'Lunas' ? '#10b981' : ($status_bayar === 'Menunggu Verifikasi' ? '#f59e0b' : '#ef4444') ?>;">
                        <?= htmlspecialchars($status_bayar) ?>
                    </span>
                </div>

                <?php if (in_array($transaksi['metode_pembayaran'], ['QRIS', 'Transfer Bank'])): ?>
                    <?php if ($status_bayar === 'Belum Bayar'): ?>
                        <?php if ($transaksi['status'] === 'Menunggu'): ?>
                            <div style="border-top: 1px dashed var(--gray-300); padding-top: 14px; margin-top: 14px;">
                                <div style="background: #eff6ff; border: 1px solid #bfdbfe; color: #1e3a8a; padding: 14px; border-radius: var(--radius); font-size: .82rem; line-height: 1.5;">
                                    <strong>⏳ Menunggu Penimbangan Pakaian</strong><br>
                                    Pembayaran belum diaktifkan karena pakaian Anda masih dalam proses penerimaan/penjemputan untuk ditimbang oleh Admin. 
                                    <br><br>
                                    Setelah pakaian ditimbang secara akurat dan status cucian mulai diproses (Dicuci), info pembayaran (rekening/QRIS) dan form upload bukti bayar akan muncul di sini dengan nominal tagihan yang sudah disesuaikan. Hal ini untuk mencegah kelebihan atau kekurangan pembayaran.
                                </div>
                            </div>
                        <?php else: ?>
                            <div style="border-top: 1px dashed var(--gray-300); padding-top: 14px; margin-top: 14px;">
                                <?php if ($transaksi['metode_pembayaran'] === 'QRIS'): ?>
                                    <p style="margin-bottom: 12px; font-size: .82rem; color: var(--gray-600);">
                                        Silakan scan kode QRIS di bawah ini menggunakan aplikasi pembayaran e-wallet Anda (Gopay, OVO, Dana, LinkAja, Mobile Banking) sebesar <strong>Rp <?= number_format($transaksi['harga'], 0, ',', '.') ?></strong>:
                                    </p>
                                    <div style="text-align: center; margin: 15px 0;">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode('Invoice:'.$transaksi['kode_transaksi'].';Total:'.$transaksi['harga']) ?>" alt="QRIS Code" style="border: 2px solid var(--gray-200); padding: 10px; background: white; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                                        <div style="font-size: .75rem; color: var(--gray-400); margin-top: 4px; font-weight: 500;">QRIS DYNAMIC CODE</div>
                                    </div>
                                <?php elseif ($transaksi['metode_pembayaran'] === 'Transfer Bank'): ?>
                                    <p style="margin-bottom: 12px; font-size: .82rem; color: var(--gray-600);">
                                        Silakan transfer pembayaran ke nomor rekening resmi toko sebesar <strong>Rp <?= number_format($transaksi['harga'], 0, ',', '.') ?></strong>:
                                    </p>
                                    <div style="background: #f8fafc; border: 1.5px solid var(--gray-200); padding: 16px; border-radius: 8px; margin: 15px 0; font-size: .85rem; line-height: 1.6;">
                                        🏦 <strong>INFORMASI REKENING TRANSFER:</strong><br>
                                        <span style="display: block; margin-top: 6px;">🏦 <strong>Bank:</strong> Bank Central Asia (BCA)</span>
                                        <span style="display: block;">💳 <strong>No. Rekening:</strong> 8010 541 233</span>
                                        <span style="display: block;">👤 <strong>Atas Nama:</strong> CV LaundryKu Mandiri Utama</span>
                                    </div>
                                <?php elseif ($transaksi['metode_pembayaran'] === 'Virtual Account'): ?>
                                    <p style="margin-bottom: 12px; font-size: .82rem; color: var(--gray-600);">
                                        Silakan lakukan pembayaran ke Virtual Account resmi toko sebesar <strong>Rp <?= number_format($transaksi['harga'], 0, ',', '.') ?></strong>:
                                    </p>
                                    <div style="background: #f8fafc; border: 1.5px solid var(--gray-200); padding: 16px; border-radius: 8px; margin: 15px 0; font-size: .85rem; line-height: 1.6;">
                                        ⚡ <strong>INFORMASI VIRTUAL ACCOUNT (VA):</strong><br>
                                        <span style="display: block; margin-top: 6px;">🏦 <strong>Bank Mandiri VA:</strong> 88908 123 4567 890</span>
                                        <span style="display: block;">🏦 <strong>Bank BCA VA:</strong> 3901 0812 3456 789</span>
                                        <span style="display: block; color: var(--gray-500); font-size: .75rem; margin-top: 4px;">* Kode VA di atas adalah VA Billing CV LaundryKu Utama</span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Form Upload Bukti -->
                                <form action="<?= base_url('user/upload_bukti/' . $transaksi['id_transaksi']) ?>" method="post" enctype="multipart/form-data" style="background: var(--gray-50); border: 1.5px dashed var(--gray-300); padding: 16px; border-radius: 8px;">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                    <div class="form-group" style="margin-bottom: 12px;">
                                        <label for="bukti_pembayaran" style="font-weight: 600; font-size: .8rem; display: block; margin-bottom: 6px;">📤 Unggah Foto Bukti Transfer/Bayar</label>
                                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" accept="image/*" required style="font-size: .8rem; padding: 6px 12px; width: 100%; border: 1px solid var(--gray-300); border-radius: 4px; background: white;">
                                        <small style="display:block; color: var(--gray-400); margin-top: 4px; font-size: .7rem;">Format file: JPG, JPEG, PNG. Max: 2 MB.</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary" style="font-size: .8rem; padding: 6px 14px; width: 100%; font-weight: 600; cursor: pointer;">
                                        🚀 Kirim Bukti Pembayaran
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php elseif ($status_bayar === 'Menunggu Verifikasi'): ?>
                        <div style="border-top: 1px dashed var(--gray-300); padding-top: 14px; margin-top: 14px;">
                            <div style="background: #fffbeb; border: 1px solid #fef3c7; color: #b45309; padding: 12px; border-radius: var(--radius); font-size: .82rem; display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                <span>⏳</span>
                                <div>
                                    <strong>Menunggu Verifikasi Admin</strong><br>
                                    Bukti pembayaran telah berhasil Anda kirim. Admin akan mencocokkan dengan mutasi rekening.
                                </div>
                            </div>
                            <?php if (!empty($transaksi['bukti_pembayaran'])): ?>
                                <span style="color: var(--gray-500); display: block; font-size: .75rem; font-weight: 600; margin-bottom: 6px;">BUKTI PEMBAYARAN ANDA</span>
                                <a href="<?= base_url('assets/uploads/bukti_bayar/' . $transaksi['bukti_pembayaran']) ?>" target="_blank">
                                    <img src="<?= base_url('assets/uploads/bukti_bayar/' . $transaksi['bukti_pembayaran']) ?>" alt="Bukti Bayar" style="max-width: 150px; border: 1.5px solid var(--gray-300); border-radius: var(--radius); display: block;">
                                </a>
                                <small style="color: var(--gray-400); font-size: .7rem; display: block; margin-top: 4px;">* Klik gambar untuk melihat ukuran penuh</small>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($status_bayar === 'Lunas'): ?>
                        <div style="border-top: 1px dashed var(--gray-300); padding-top: 14px; margin-top: 14px;">
                            <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px; border-radius: var(--radius); font-size: .82rem; display: flex; align-items: center; gap: 8px;">
                                <span>✅</span>
                                <div>
                                    <strong>Pembayaran Berhasil / LUNAS</strong><br>
                                    Terima kasih, pembayaran Anda telah diverifikasi oleh Administrator kami.
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Tunai -->
                    <div style="border-top: 1px dashed var(--gray-300); padding-top: 14px; margin-top: 14px;">
                        <p style="margin: 0; font-size: .82rem; color: var(--gray-600);">
                            💵 <strong>Pembayaran Tunai (COD)</strong><br>
                            Silakan lakukan pembayaran langsung secara tunai di outlet kami atau serahkan kepada kurir penjemput/pengantar saat penyerahan pakaian.
                            <?php if ($transaksi['status'] === 'Menunggu'): ?>
                                <br><span style="font-size: .75rem; color: #b45309; display: block; margin-top: 6px; font-weight: 500;">⚠️ <strong>Catatan:</strong> Total tagihan di atas adalah estimasi awal dan dapat berubah setelah pakaian Anda ditimbang oleh Admin di outlet.</span>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Informasi Penjemputan (Jika Ada) -->
        <?php if ($transaksi['is_jemput'] == 1): ?>
        <div style="margin-bottom: 30px; background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: var(--radius-lg); padding: 18px 24px;">
            <h4 style="font-size: .85rem; text-transform: uppercase; color: var(--gray-600); letter-spacing: .05em; margin-bottom: 12px; font-weight:600; display: flex; align-items: center; gap: 6px;">
                🚚 Layanan Penjemputan Cucian
            </h4>
            <div style="font-size: .85rem; color: var(--gray-800); line-height: 1.6;">
                <div style="margin-bottom: 8px;">
                    <span style="color: var(--gray-500); display: block; font-size: .75rem; font-weight: 600;">ALAMAT JEMPUT</span>
                    <strong><?= htmlspecialchars($transaksi['alamat_jemput']) ?></strong>
                </div>
                <?php if (!empty($transaksi['gps_jemput'])): ?>
                <div style="margin-bottom: 12px;">
                    <span style="color: var(--gray-500); display: block; font-size: .75rem; font-weight: 600;">LINK GPS / GOOGLE MAPS</span>
                    <a href="<?= htmlspecialchars($transaksi['gps_jemput']) ?>" target="_blank" class="btn btn-secondary" style="font-size: .75rem; padding: 4px 8px; margin-top: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                        📍 Buka Google Maps
                    </a>
                </div>
                <?php endif; ?>
                <div style="margin-bottom: 8px;">
                    <span style="color: var(--gray-500); display: block; font-size: .75rem; font-weight: 600;">STATUS PENJEMPUTAN</span>
                    <?php
                    $is_done_jemput = $transaksi['status_jemput'] === 'Sudah Dijemput';
                    ?>
                    <span class="badge badge-<?= $is_done_jemput ? 'selesai' : 'proses' ?>" style="font-size: .8rem; padding: 4px 10px; display: inline-block; margin-top: 4px;">
                        <?= htmlspecialchars($transaksi['status_jemput']) ?>
                    </span>
                </div>

                <?php if (!$is_done_jemput): ?>
                <div style="margin-top: 16px; border-top: 1px dashed var(--gray-300); padding-top: 14px;">
                    <span style="display:block; font-size: .8rem; color: var(--gray-600); margin-bottom: 8px;">Apakah kurir kami sudah menjemput pakaian Anda? Silakan klik tombol di bawah untuk konfirmasi:</span>
                    <a href="<?= base_url('user/pesan/konfirmasi_jemput/' . $transaksi['id_transaksi']) ?>" class="btn btn-primary" style="font-size: .8rem; padding: 6px 14px; background: var(--success); border-color: var(--success); text-decoration: none; color: var(--white); display: inline-block; border-radius: var(--radius); font-weight: 600;" onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi bahwa cucian Anda sudah dijemput oleh kurir?');">
                        ✔️ Ya, Pakaian Sudah Dijemput
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Informasi Pengantaran (Jika Ada) -->
        <?php if ($transaksi['is_antar'] == 1): ?>
        <div style="margin-bottom: 30px; background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: var(--radius-lg); padding: 18px 24px;">
            <h4 style="font-size: .85rem; text-transform: uppercase; color: var(--gray-600); letter-spacing: .05em; margin-bottom: 12px; font-weight:600; display: flex; align-items: center; gap: 6px;">
                🛵 Layanan Pengantaran Laundry
            </h4>
            <div style="font-size: .85rem; color: var(--gray-800); line-height: 1.6;">
                <div style="margin-bottom: 8px;">
                    <span style="color: var(--gray-500); display: block; font-size: .75rem; font-weight: 600;">ALAMAT ANTAR</span>
                    <strong><?= htmlspecialchars($transaksi['alamat_antar']) ?></strong>
                </div>
                <?php if (!empty($transaksi['gps_antar'])): ?>
                <div style="margin-bottom: 12px;">
                    <span style="color: var(--gray-500); display: block; font-size: .75rem; font-weight: 600;">LINK GPS / GOOGLE MAPS</span>
                    <a href="<?= htmlspecialchars($transaksi['gps_antar']) ?>" target="_blank" class="btn btn-secondary" style="font-size: .75rem; padding: 4px 8px; margin-top: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                        📍 Buka Google Maps
                    </a>
                </div>
                <?php endif; ?>
                <div style="margin-bottom: 8px;">
                    <span style="color: var(--gray-500); display: block; font-size: .75rem; font-weight: 600;">STATUS PENGANTARAN</span>
                    <?php
                    $is_done_antar = $transaksi['status_antar'] === 'Sudah Diantarkan';
                    ?>
                    <span class="badge badge-<?= $is_done_antar ? 'selesai' : 'proses' ?>" style="font-size: .8rem; padding: 4px 10px; display: inline-block; margin-top: 4px;">
                        <?= htmlspecialchars($transaksi['status_antar']) ?>
                    </span>
                </div>

                <?php if (!$is_done_antar && $transaksi['status'] === 'Selesai'): ?>
                <div style="margin-top: 16px; border-top: 1px dashed var(--gray-300); padding-top: 14px;">
                    <span style="display:block; font-size: .8rem; color: var(--gray-600); margin-bottom: 8px;">Apakah pakaian Anda sudah selesai diantarkan kembali ke rumah? Silakan klik tombol di bawah untuk konfirmasi penerimaan:</span>
                    <a href="<?= base_url('user/pesan/konfirmasi_antar/' . $transaksi['id_transaksi']) ?>" class="btn btn-primary" style="font-size: .8rem; padding: 6px 14px; background: var(--success); border-color: var(--success); text-decoration: none; color: var(--white); display: inline-block; border-radius: var(--radius); font-weight: 600;" onclick="return confirm('Apakah Anda yakin pakaian laundry sudah Anda terima dengan baik di rumah?');">
                        ✔️ Ya, Pakaian Sudah Diterima
                    </a>
                </div>
                <?php elseif ($transaksi['status'] !== 'Selesai' && !$is_done_antar): ?>
                <div style="margin-top: 12px; font-size: .75rem; color: var(--gray-500); font-style: italic;">
                    * Layanan pengantaran akan diproses setelah pengerjaan cucian berstatus <strong>Selesai</strong>.
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- RATING & REVIEW TESTIMONI -->
        <?php if ($transaksi['status'] === 'Diambil'): ?>
        <div style="margin-bottom: 30px; background: #fffcf0; border: 1px solid #fef3c7; border-radius: var(--radius-lg); padding: 18px 24px;">
            <h4 style="font-size: .85rem; text-transform: uppercase; color: #b45309; letter-spacing: .05em; margin-bottom: 12px; font-weight:600; display: flex; align-items: center; gap: 6px;">
                ⭐ Ulasan & Kepuasan Pelanggan
            </h4>
            
            <?php if (empty($transaksi['rating'])): ?>
                <form action="<?= base_url('user/rate_transaksi/' . $transaksi['id_transaksi']) ?>" method="post">
                    <!-- CSRF Protection -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    
                    <span style="display:block; font-size: .82rem; color: var(--gray-600); margin-bottom: 8px;">Pakaian Anda telah diterima! Bagaimana penilaian Anda terhadap layanan kami?</span>
                    
                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 12px;">
                        <span style="font-size: .85rem; font-weight: 600; color: var(--gray-700);">Bintang: </span>
                        <div class="stars-rate" style="display: flex; gap: 10px;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label style="cursor: pointer; display: flex; align-items: center; gap: 2px;">
                                    <input type="radio" name="rating" value="<?= $i ?>" required style="width: 16px; height: 16px;">
                                    <span style="font-size: 1rem; color: #f59e0b;">★ <?= $i ?></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 12px;">
                        <textarea name="review" class="form-control" rows="3" placeholder="Tuliskan ulasan atau komentar Anda mengenai pelayanan kami..." style="font-size: .82rem;" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="font-size: .8rem; padding: 6px 14px; background: #d97706; border-color: #d97706; color: var(--white); font-weight: 600; border-radius: var(--radius); cursor: pointer;">
                        💾 Kirim Ulasan
                    </button>
                </form>
            <?php else: ?>
                <div style="font-size: .85rem; color: var(--gray-800); line-height: 1.6;">
                    <div style="margin-bottom: 6px; display: flex; align-items: center; gap: 4px;">
                        <span style="font-weight: 600; color: var(--gray-500); font-size: .75rem;">RATING ANDA:</span>
                        <span style="color: #f59e0b; font-size: 1.1rem; font-weight: 700;">
                            <?= str_repeat('★', $transaksi['rating']) ?><?= str_repeat('☆', 5 - $transaksi['rating']) ?>
                            (<?= $transaksi['rating'] ?>/5)
                        </span>
                    </div>
                    <div>
                        <span style="font-weight: 600; color: var(--gray-500); display: block; font-size: .75rem; margin-bottom: 2px;">ULASAN ANDA:</span>
                        <span style="font-style: italic; color: var(--gray-700);">"<?= htmlspecialchars($transaksi['review']) ?>"</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Timeline 6 Status Cucian -->
        <div>
            <h4 style="font-size: .85rem; text-transform: uppercase; color: var(--gray-600); letter-spacing: .05em; margin-bottom: 16px; font-weight:600;">Status Pengerjaan Cucian</h4>
            
            <div style="display: flex; flex-direction: column; gap: 12px; background: var(--gray-50); padding: 18px 24px; border-radius: var(--radius-lg); border: 1px solid var(--gray-200);">
                <?php
                $status_flow = ['Menunggu', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
                $current_index = array_search($transaksi['status'], $status_flow);
                if ($current_index === FALSE) $current_index = 0;
                
                foreach ($status_flow as $idx => $step):
                    $is_bypassed = ($step === 'Disetrika' && $transaksi['jenis_layanan'] !== 'Cuci + Setrika');
                    if ($is_bypassed) {
                        $is_passed = false;
                        $is_current = false;
                    } else {
                        $is_passed = $idx <= $current_index;
                        $is_current = $idx === $current_index;
                    }
                ?>
                    <div style="display: flex; align-items: center; gap: 12px; <?= $is_bypassed ? 'opacity: 0.55;' : '' ?>">
                        <div style="width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .75rem; 
                            background: <?= $is_bypassed ? 'var(--danger)' : ($is_current ? 'var(--primary)' : ($is_passed ? 'var(--success)' : 'var(--gray-200)')) ?>;
                            color: <?= $is_bypassed || $is_passed ? 'var(--white)' : 'var(--gray-400)' ?>; font-weight: 700;">
                            <?= $is_bypassed ? '✗' : ($is_passed ? '✓' : $idx + 1) ?>
                        </div>
                        <span style="font-size: .85rem; font-weight: <?= $is_current ? '700' : '500' ?>; 
                            color: <?= $is_bypassed ? 'var(--danger)' : ($is_current ? 'var(--primary)' : ($is_passed ? 'var(--gray-800)' : 'var(--gray-400)')) ?>;">
                            <?= htmlspecialchars($step) ?> 
                            <?= $is_bypassed ? '<small style="font-weight:600; color:var(--danger); font-style:italic;">(Tidak Berlaku)</small>' : '' ?>
                            <?= $is_current ? '<small style="font-weight:600; color:var(--primary-dark); font-style:italic;">(Sedang berada di tahap ini)</small>' : '' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>
