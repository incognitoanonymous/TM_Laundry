<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * transaksi/edit.php
 * Form edit data transaksi laundry - Administrator.
 * Harga total dihitung otomatis berbasis server dan diestimasikan real-time via Javascript.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>✏️ Edit Transaksi</h2>
        <p>Ubah detail informasi transaksi laundry: <strong><?= htmlspecialchars($transaksi['kode_transaksi']) ?></strong></p>
    </div>
    <div>
        <a href="<?= base_url('admin/transaksi') ?>" class="btn btn-secondary" id="btn-kembali-transaksi-edit">
            &larr; Kembali ke Daftar
        </a>
    </div>
</div>

<!-- ── Form Card ── -->
<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <h3>📝 Form Edit Transaksi</h3>
        <div style="font-size: .85rem; color: var(--gray-600); font-weight: 500;">
            Kode TRX: <code style="background: var(--primary-light); color: var(--primary); padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: .8rem;">
                <?= htmlspecialchars($transaksi['kode_transaksi']) ?>
            </code>
        </div>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/transaksi/update/' . $transaksi['id_transaksi']) ?>" method="post" id="form-edit-transaksi">
            <!-- Native CodeIgniter 3 CSRF input -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="form-grid" style="grid-template-columns: 1fr; gap: 16px;">

                <!-- Pilih Pelanggan -->
                <div class="form-group">
                    <label for="id_pelanggan">👤 Pilih Pelanggan</label>
                    <select name="id_pelanggan" id="id_pelanggan" class="form-control" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php foreach ($pelanggan_list as $p): ?>
                            <option value="<?= $p['id_pelanggan'] ?>" <?= set_select('id_pelanggan', $p['id_pelanggan'], $p['id_pelanggan'] == $transaksi['id_pelanggan']) ?>>
                                <?= htmlspecialchars($p['nama']) ?> (<?= htmlspecialchars($p['no_hp']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (form_error('id_pelanggan')): ?>
                        <span class="error-msg"><?= form_error('id_pelanggan') ?></span>
                    <?php endif; ?>
                </div>

                <!-- Detail Layanan, Status, Tanggal Masuk -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                    
                    <!-- Jenis Layanan -->
                    <div class="form-group">
                        <label for="jenis_layanan">👕 Jenis Layanan</label>
                        <select name="jenis_layanan" id="jenis_layanan" class="form-control" required>
                            <option value="">-- Pilih Layanan --</option>
                            <option value="Cuci Reguler" <?= set_select('jenis_layanan', 'Cuci Reguler', $transaksi['jenis_layanan'] === 'Cuci Reguler') ?>>Cuci Reguler (Rp 7.000/kg)</option>
                            <option value="Cuci Express" <?= set_select('jenis_layanan', 'Cuci Express', $transaksi['jenis_layanan'] === 'Cuci Express') ?>>Cuci Express (Rp 10.000/kg)</option>
                            <option value="Cuci + Setrika" <?= set_select('jenis_layanan', 'Cuci + Setrika', $transaksi['jenis_layanan'] === 'Cuci + Setrika') ?>>Cuci + Setrika (Rp 12.000/kg)</option>
                        </select>
                        <?php if (form_error('jenis_layanan')): ?>
                            <span class="error-msg"><?= form_error('jenis_layanan') ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Status Cucian (6 Status) -->
                    <div class="form-group">
                        <label for="status">🔄 Status Cucian</label>
                        <select name="status" id="status" class="form-control" required>
                            <?php 
                            $status_options = ['Menunggu', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
                            foreach ($status_options as $opt):
                            ?>
                                <option value="<?= $opt ?>" <?= set_select('status', $opt, $transaksi['status'] === $opt) ?>>
                                    <?= htmlspecialchars($opt) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (form_error('status')): ?>
                            <span class="error-msg"><?= form_error('status') ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Tanggal Masuk -->
                    <div class="form-group">
                        <label for="tanggal">📅 Tanggal Transaksi</label>
                        <input 
                            type="date" 
                            id="tanggal" 
                            name="tanggal" 
                            class="form-control"
                            value="<?= set_value('tanggal', $transaksi['tanggal']) ?>" 
                            required>
                        <?php if (form_error('tanggal')): ?>
                            <span class="error-msg"><?= form_error('tanggal') ?></span>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Kolom Berat -->
                <div class="form-group" style="max-width: 280px;">
                    <label for="berat">⚖️ Berat Cucian (kg)</label>
                    <input 
                        type="number" 
                        id="berat" 
                        name="berat" 
                        class="form-control"
                        placeholder="Contoh: 3.5"
                        value="<?= set_value('berat', $transaksi['berat']) ?>"
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
                            <strong style="display:block; font-size: .85rem; color: var(--gray-600); font-weight:600;">Kalkulasi Harga Otomatis (Saat Ini)</strong>
                            <span style="font-size: .75rem; color: var(--gray-400);">Berat: <span id="label-berat"><?= number_format($transaksi['berat'], 2) ?></span> kg | Tarif layanan terpilih</span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <h3 style="font-size: 1.6rem; color: var(--primary); font-weight: 800;" id="box-estimasi">
                            Rp <span id="estimasi-harga"><?= number_format($transaksi['harga'], 0, ',', '.') ?></span>
                        </h3>
                    </div>
                </div>

            </div>

            <div style="margin-top: 24px; display: flex; gap: 10px; justify-content: flex-end;">
                <a href="<?= base_url('admin/transaksi') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="btn-update-transaksi">💾 Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>

<!-- JavaScript Kalkulasi Dinamis -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const inputBerat = document.getElementById('berat');
    const labelBerat = document.getElementById('label-berat');
    const selectLayanan = document.getElementById('jenis_layanan');
    const estimasiText = document.getElementById('estimasi-harga');

    function hitungEstimasi() {
        const berat = parseFloat(inputBerat.value) || 0;
        labelBerat.innerText = berat.toFixed(2);
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
        estimasiText.innerText = total.toLocaleString('id-ID');
    }

    inputBerat.addEventListener('input', hitungEstimasi);
    selectLayanan.addEventListener('change', hitungEstimasi);
});
</script>
