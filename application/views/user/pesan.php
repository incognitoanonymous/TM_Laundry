<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * user/pesan.php
 * Form pemesanan laundry baru oleh Pelanggan (User).
 * Harga dihitung otomatis secara instan via Javascript.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>🛒 Pesan Laundry Baru</h2>
        <p>Silakan isi detail pemesanan laundry Anda. Setelah pesanan dikirim, serahkan cucian Anda ke outlet terdekat.</p>
    </div>
    <div>
        <a href="<?= base_url('user/dashboard') ?>" class="btn btn-secondary" id="btn-kembali-dashboard">
            &larr; Kembali ke Dashboard
        </a>
    </div>
</div>

<!-- ── Form Card ── -->
<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <h3>📝 Form Pemesanan Laundry</h3>
        <div style="font-size: .85rem; color: var(--gray-600); font-weight: 500;">
            Pelanggan: <strong style="color: var(--primary);"><?= htmlspecialchars($pelanggan['nama']) ?></strong>
        </div>
    </div>
    <div class="card-body">
        <form action="<?= base_url('user/pesan/proses') ?>" method="post" id="form-pesan-laundry">
            <!-- CSRF Protection -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="form-grid" style="grid-template-columns: 1fr; gap: 16px;">

                <!-- Detail Layanan & Tanggal dalam 2 kolom jika di desktop -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    
                    <!-- Jenis Layanan -->
                    <div class="form-group">
                        <label for="jenis_layanan">👕 Jenis Layanan</label>
                        <select name="jenis_layanan" id="jenis_layanan" class="form-control" required>
                            <option value="">-- Pilih Layanan --</option>
                            <option value="Cuci Reguler" <?= set_select('jenis_layanan', 'Cuci Reguler') ?>>Cuci Reguler (Rp 7.000/kg)</option>
                            <option value="Cuci Express" <?= set_select('jenis_layanan', 'Cuci Express') ?>>Cuci Express (Rp 10.000/kg)</option>
                            <option value="Cuci + Setrika" <?= set_select('jenis_layanan', 'Cuci + Setrika') ?>>Cuci + Setrika (Rp 12.000/kg)</option>
                        </select>
                        <?php if (form_error('jenis_layanan')): ?>
                            <span class="error-msg"><?= form_error('jenis_layanan') ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Tanggal Pengantaran -->
                    <div class="form-group">
                        <label for="tanggal">📅 Rencana Tanggal Pengantaran</label>
                        <input 
                            type="date" 
                            id="tanggal" 
                            name="tanggal" 
                            class="form-control"
                            value="<?= set_value('tanggal', date('Y-m-d')) ?>" 
                            required>
                        <?php if (form_error('tanggal')): ?>
                            <span class="error-msg"><?= form_error('tanggal') ?></span>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Estimasi Berat -->
                <div class="form-group" style="max-width: 280px;">
                    <label for="berat">⚖️ Estimasi Berat Cucian (kg)</label>
                    <input 
                        type="number" 
                        id="berat" 
                        name="berat" 
                        class="form-control"
                        placeholder="Contoh: 3"
                        value="<?= set_value('berat', '1') ?>"
                        step="0.01" 
                        min="0.01" 
                        required>
                    <?php if (form_error('berat')): ?>
                        <span class="error-msg"><?= form_error('berat') ?></span>
                    <?php endif; ?>
                </div>

                <!-- Card Tampilan Estimasi Harga Otomatis -->
                <div class="stat-card" style="margin-top: 10px; background: var(--gray-50); border: 1.5px dashed var(--gray-300); display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-radius: var(--radius-lg);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="font-size: 1.8rem;">🏷️</span>
                        <div>
                            <strong style="display:block; font-size: .85rem; color: var(--gray-600); font-weight:600;">Estimasi Total Harga</strong>
                            <span style="font-size: .75rem; color: var(--gray-400);">Dihitung dari estimasi berat & tarif jenis layanan</span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <h3 style="font-size: 1.6rem; color: var(--primary); font-weight: 800;" id="box-estimasi">
                            Rp <span id="estimasi-harga">0</span>
                        </h3>
                    </div>
                </div>

            </div>

            <div class="alert alert-info" style="margin-top: 20px; line-height: 1.5;">
                ℹ️ Status pesanan Anda akan diset menjadi <strong>Menunggu</strong> sampai pakaian diterima oleh petugas outlet kami untuk ditimbang kembali secara akurat.
            </div>

            <div style="margin-top: 24px; display: flex; gap: 10px; justify-content: flex-end;">
                <a href="<?= base_url('user/dashboard') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="btn-kirim-pesanan">🚀 Kirim Pesanan Laundry</button>
            </div>

        </form>
    </div>
</div>

<!-- JavaScript Kalkulasi Dinamis -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const inputBerat = document.getElementById('berat');
    const selectLayanan = document.getElementById('jenis_layanan');
    const estimasiText = document.getElementById('estimasi-harga');

    function hitungEstimasi() {
        const berat = parseFloat(inputBerat.value) || 0;
        const layanan = selectLayanan.value;
        let tarif = 0;

        if (layanan === 'Cuci Reguler') {
            tarif = 7000;
        } else if (layanan === 'Cuci Express') {
            tarif = 10000;
        } else if (layanan === 'Cuci + Setrika') {
            tarif = 12000;
        }

        const total = berat * tarif;
        
        // Format rupiah
        estimasiText.innerText = total.toLocaleString('id-ID');
    }

    inputBerat.addEventListener('input', hitungEstimasi);
    selectLayanan.addEventListener('change', hitungEstimasi);

    // Jalankan kalkulasi pertama kali
    hitungEstimasi();
});
</script>
