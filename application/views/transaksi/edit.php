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
                                if ($opt === 'Disetrika' && $transaksi['jenis_layanan'] !== 'Cuci + Setrika') {
                                    continue;
                                }
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

                <!-- Pilihan Penjemputan -->
                <div class="form-group" style="margin-top: 10px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" id="is_jemput" name="is_jemput" value="1" <?= set_checkbox('is_jemput', '1', $transaksi['is_jemput'] == 1) ?> style="width: 18px; height: 18px;">
                        🚚 Butuh Layanan Penjemputan Cucian?
                    </label>
                </div>

                <!-- Form Detail Penjemputan (Toggled via JS) -->
                <div id="form-detail-jemput" style="display: none; background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 16px; margin-top: 5px;">
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label for="alamat_jemput">📍 Alamat Penjemputan Lengkap</label>
                        <textarea 
                            name="alamat_jemput" 
                            id="alamat_jemput" 
                            rows="3" 
                            class="form-control" 
                            placeholder="Masukkan alamat lengkap penjemputan cucian"><?= set_value('alamat_jemput', $transaksi['alamat_jemput']) ?></textarea>
                        <?php if (form_error('alamat_jemput')): ?>
                            <span class="error-msg"><?= form_error('alamat_jemput') ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label for="gps_jemput">🔗 Link Google Maps / GPS (Opsional)</label>
                        <input 
                            type="text" 
                            name="gps_jemput" 
                            id="gps_jemput" 
                            class="form-control" 
                            placeholder="Contoh: https://maps.app.goo.gl/... atau koordinat GPS"
                            value="<?= set_value('gps_jemput', $transaksi['gps_jemput']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="status_jemput">⚙️ Status Penjemputan</label>
                        <select name="status_jemput" id="status_jemput" class="form-control">
                            <option value="Menunggu Penjemputan" <?= set_select('status_jemput', 'Menunggu Penjemputan', $transaksi['status_jemput'] === 'Menunggu Penjemputan') ?>>Menunggu Penjemputan</option>
                            <option value="Sudah Dijemput" <?= set_select('status_jemput', 'Sudah Dijemput', $transaksi['status_jemput'] === 'Sudah Dijemput') ?>>Sudah Dijemput</option>
                        </select>
                    </div>
                </div>

                <!-- Pilihan Pengantaran -->
                <div class="form-group" style="margin-top: 10px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" id="is_antar" name="is_antar" value="1" <?= set_checkbox('is_antar', '1', $transaksi['is_antar'] == 1) ?> style="width: 18px; height: 18px;">
                        🛵 Butuh Layanan Pengantaran Cucian?
                    </label>
                </div>

                <!-- Form Detail Pengantaran (Toggled via JS) -->
                <div id="form-detail-antar" style="display: none; background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 16px; margin-top: 5px;">
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label for="alamat_antar">📍 Alamat Pengantaran Lengkap</label>
                        <textarea 
                            name="alamat_antar" 
                            id="alamat_antar" 
                            rows="3" 
                            class="form-control" 
                            placeholder="Masukkan alamat lengkap pengantaran cucian"><?= set_value('alamat_antar', $transaksi['alamat_antar']) ?></textarea>
                        <?php if (form_error('alamat_antar')): ?>
                            <span class="error-msg"><?= form_error('alamat_antar') ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label for="gps_antar">🔗 Link Google Maps / GPS (Opsional)</label>
                        <input 
                            type="text" 
                            name="gps_antar" 
                            id="gps_antar" 
                            class="form-control" 
                            placeholder="Contoh: https://maps.app.goo.gl/... atau koordinat GPS"
                            value="<?= set_value('gps_antar', $transaksi['gps_antar']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="status_antar">⚙️ Status Pengantaran</label>
                        <select name="status_antar" id="status_antar" class="form-control">
                            <option value="Menunggu Pengantaran" <?= set_select('status_antar', 'Menunggu Pengantaran', $transaksi['status_antar'] === 'Menunggu Pengantaran') ?>>Menunggu Pengantaran</option>
                            <option value="Sudah Diantarkan" <?= set_select('status_antar', 'Sudah Diantarkan', $transaksi['status_antar'] === 'Sudah Diantarkan') ?>>Sudah Diantarkan</option>
                        </select>
                    </div>
                </div>

                <!-- Pilihan Metode & Status Pembayaran -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; background: var(--gray-50); border: 1px solid var(--gray-200); padding: 16px; border-radius: var(--radius); margin-top: 10px;">
                    <div class="form-group">
                        <label for="metode_pembayaran">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
                            <option value="Tunai" <?= set_select('metode_pembayaran', 'Tunai', $transaksi['metode_pembayaran'] === 'Tunai') ?>>Tunai (COD)</option>
                            <option value="QRIS" <?= set_select('metode_pembayaran', 'QRIS', $transaksi['metode_pembayaran'] === 'QRIS') ?>>QRIS (Non-Tunai)</option>
                            <option value="Transfer Bank" <?= set_select('metode_pembayaran', 'Transfer Bank', $transaksi['metode_pembayaran'] === 'Transfer Bank') ?>>Transfer Bank</option>
                        </select>
                        <?php if (form_error('metode_pembayaran')): ?>
                            <span class="error-msg"><?= form_error('metode_pembayaran') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="status_pembayaran">Status Pembayaran</label>
                        <select name="status_pembayaran" id="status_pembayaran" class="form-control" required>
                            <option value="Belum Bayar" <?= set_select('status_pembayaran', 'Belum Bayar', $transaksi['status_pembayaran'] === 'Belum Bayar') ?>>Belum Bayar</option>
                            <option value="Menunggu Verifikasi" <?= set_select('status_pembayaran', 'Menunggu Verifikasi', $transaksi['status_pembayaran'] === 'Menunggu Verifikasi') ?>>Menunggu Verifikasi</option>
                            <option value="Lunas" <?= set_select('status_pembayaran', 'Lunas', $transaksi['status_pembayaran'] === 'Lunas') ?>>Lunas</option>
                        </select>
                        <?php if (form_error('status_pembayaran')): ?>
                            <span class="error-msg"><?= form_error('status_pembayaran') ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- CATATAN KHUSUS -->
                <div class="form-group" style="margin-top: 15px;">
                    <label for="catatan">📝 Catatan Khusus (Opsional)</label>
                    <textarea name="catatan" id="catatan" class="form-control" placeholder="Contoh: Pisahkan pakaian putih, dll." rows="3" style="resize: vertical; font-size: .85rem;"><?= set_value('catatan', $transaksi['catatan']) ?></textarea>
                    <?php if (form_error('catatan')): ?>
                        <span class="error-msg"><?= form_error('catatan') ?></span>
                    <?php endif; ?>
                </div>

                <!-- UANG DITERIMA (INTERNAL) -->
                <div class="form-group" style="margin-top: 15px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer; color: #1e3a8a; background: #eff6ff; padding: 10px 14px; border: 1px solid #bfdbfe; border-radius: var(--radius);">
                        <input type="checkbox" id="uang_diterima" name="uang_diterima" value="1" <?= set_checkbox('uang_diterima', '1', $transaksi['uang_diterima'] == 1) ?> style="width: 18px; height: 18px;">
                        💵 [Internal Admin] Uang Pembayaran Sudah Diterima Kasir/Admin
                    </label>
                </div>

                <!-- Preview Bukti Pembayaran -->
                <?php if ($transaksi['metode_pembayaran'] === 'QRIS' && !empty($transaksi['bukti_pembayaran'])): ?>
                <div style="background: var(--gray-50); border: 1px solid var(--gray-200); padding: 16px; border-radius: var(--radius); margin-top: 10px;">
                    <label style="font-weight: 600; display: block; margin-bottom: 8px;">🖼️ Bukti Pembayaran Pelanggan</label>
                    <a href="<?= base_url('assets/uploads/bukti_bayar/' . $transaksi['bukti_pembayaran']) ?>" target="_blank">
                        <img src="<?= base_url('assets/uploads/bukti_bayar/' . $transaksi['bukti_pembayaran']) ?>" alt="Bukti Pembayaran" style="max-width: 250px; border: 1.5px solid var(--gray-300); border-radius: var(--radius); display: block;">
                    </a>
                    <span style="font-size: .75rem; color: var(--gray-400); display: block; margin-top: 6px;">* Klik gambar untuk melihat ukuran penuh</span>
                </div>
                <?php endif; ?>

                <!-- Card Tampilan Estimasi Harga Otomatis -->
                <div class="stat-card" style="margin-top: 10px; background: var(--gray-50); border: 1.5px dashed var(--gray-300); display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-radius: var(--radius-lg);">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="font-size: 1.8rem;">🏷️</span>
                        <div>
                            <strong style="display:block; font-size: .85rem; color: var(--gray-600); font-weight:600;">Kalkulasi Harga Otomatis (Saat Ini)</strong>
                            <span style="font-size: .75rem; color: var(--gray-400);">Berat: <span id="label-berat"><?= number_format($transaksi['berat'], 2) ?></span> kg | Tarif layanan terpilih + ongkir (jika ada)</span>
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

<!-- JavaScript Kalkulasi Dinamis & Toggle Penjemputan -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const inputBerat = document.getElementById('berat');
    const labelBerat = document.getElementById('label-berat');
    const selectLayanan = document.getElementById('jenis_layanan');
    const estimasiText = document.getElementById('estimasi-harga');
    
    const checkboxJemput = document.getElementById('is_jemput');
    const formDetailJemput = document.getElementById('form-detail-jemput');
    const inputAlamatJemput = document.getElementById('alamat_jemput');

    const checkboxAntar = document.getElementById('is_antar');
    const formDetailAntar = document.getElementById('form-detail-antar');
    const inputAlamatAntar = document.getElementById('alamat_antar');

    const selectStatus = document.getElementById('status');
    // Save original options
    const originalStatusOptions = Array.from(selectStatus.options).map(opt => ({
        value: opt.value,
        text: opt.innerText,
        selected: opt.selected
    }));

    // If 'Disetrika' option is not in originalStatusOptions (because PHP filtered it out on load),
    // let's add it manually to our list of possible options so we can restore it if they select 'Cuci + Setrika'
    if (!originalStatusOptions.some(opt => opt.value === 'Disetrika')) {
        // Insert 'Disetrika' at index 3 (after 'Dikeringkan')
        originalStatusOptions.splice(3, 0, {
            value: 'Disetrika',
            text: 'Disetrika',
            selected: false
        });
    }

    function updateStatusDropdown() {
        const layanan = selectLayanan.value;
        const currentVal = selectStatus.value;
        
        // Clear all options
        selectStatus.innerHTML = '';
        
        originalStatusOptions.forEach(opt => {
            if (opt.value === 'Disetrika' && layanan !== 'Cuci + Setrika') {
                return; // skip Disetrika for non-setrika
            }
            
            const newOpt = document.createElement('option');
            newOpt.value = opt.value;
            newOpt.innerText = opt.text;
            if (opt.value === currentVal) {
                newOpt.selected = true;
            } else if (opt.value === 'Menunggu' && currentVal === 'Disetrika' && layanan !== 'Cuci + Setrika') {
                // If current selected status is Disetrika but it's now hidden, reset status to Menunggu
                newOpt.selected = true;
            }
            selectStatus.appendChild(newOpt);
        });
    }

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

        const total = (berat * tarif) + (checkboxJemput.checked ? (berat * 10000) : 0) + (checkboxAntar.checked ? (berat * 10000) : 0);
        estimasiText.innerText = total.toLocaleString('id-ID');
    }

    function toggleJemput() {
        if (checkboxJemput.checked) {
            formDetailJemput.style.display = 'block';
            inputAlamatJemput.setAttribute('required', 'required');
        } else {
            formDetailJemput.style.display = 'none';
            inputAlamatJemput.removeAttribute('required');
        }
    }

    function toggleAntar() {
        if (checkboxAntar.checked) {
            formDetailAntar.style.display = 'block';
            inputAlamatAntar.setAttribute('required', 'required');
        } else {
            formDetailAntar.style.display = 'none';
            inputAlamatAntar.removeAttribute('required');
        }
    }

    inputBerat.addEventListener('input', hitungEstimasi);
    selectLayanan.addEventListener('change', function() {
        updateStatusDropdown();
        hitungEstimasi();
    });
    
    checkboxJemput.addEventListener('change', function() {
        toggleJemput();
        hitungEstimasi();
    });
    
    checkboxAntar.addEventListener('change', function() {
        toggleAntar();
        hitungEstimasi();
    });

    // Jalankan pertama kali
    updateStatusDropdown();
    hitungEstimasi();
    toggleJemput();
    toggleAntar();
});
</script>
