<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * user/pesan.php
 * Form pemesanan laundry baru oleh Pelanggan (User).
 * Menyediakan kalkulasi harga otomatis, pilihan penjemputan, pilihan pengantaran,
 * dan Map Picker interaktif berbasis OpenStreetMap & Leaflet JS.
 */
?>

<!-- Leaflet CSS via CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<!-- Leaflet JS via CDN -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>🛒 Pesan Laundry Baru</h2>
        <p>Silakan isi detail pemesanan laundry Anda. Setelah pesanan dikirim, pakaian akan kami proses sesuai layanan.</p>
    </div>
    <div>
        <a href="<?= base_url('user/dashboard') ?>" class="btn btn-secondary" id="btn-kembali-dashboard">
            &larr; Kembali ke Dashboard
        </a>
    </div>
</div>

<!-- ── Form Card ── -->
<div class="card" style="max-width: 750px; margin: 0 auto; margin-bottom: 40px;">
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

                <!-- Poin Reward & Penukaran -->
                <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: var(--radius); padding: 14px 18px; display: flex; flex-direction: column; gap: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: .85rem; font-weight: 600; color: #1e3a8a;">🎁 Poin Reward Anda:</span>
                        <strong style="font-size: 1.1rem; color: #1d4ed8; font-weight: 800;" id="user-points-balance"><?= intval($pelanggan['poin']) ?> poin</strong>
                    </div>
                    
                    <div class="form-group" style="margin: 0; margin-top: 6px;">
                        <label for="reward_option" style="font-size: .8rem; color: #1e40af; font-weight: 600;">Tukarkan Poin Reward untuk Hadiah (Opsional):</label>
                        <select name="reward_option" id="reward_option" class="form-control" style="background: var(--white); border-color: #93c5fd; font-size: .82rem; padding: 6px 12px; font-weight: 500; color: var(--gray-700);">
                            <option value="">-- Tidak Menggunakan Reward --</option>
                            <option value="free_cuci_reguler" <?= $pelanggan['poin'] < 15 ? 'disabled' : '' ?>>Tukar 15 Poin: Free Cuci Reguler (Min. 15 Poin)</option>
                            <option value="free_cuci_express" <?= $pelanggan['poin'] < 18 ? 'disabled' : '' ?>>Tukar 18 Poin: Free Cuci Express (Min. 18 Poin)</option>
                            <option value="free_cuci_setrika" <?= $pelanggan['poin'] < 30 ? 'disabled' : '' ?>>Tukar 30 Poin: Free Cuci + Setrika (Min. 30 Poin)</option>
                            <option value="free_kurir" <?= $pelanggan['poin'] < 20 ? 'disabled' : '' ?>>Tukar 20 Poin: Free Kurir Jemput & Antar (Min. 20 Poin)</option>
                        </select>
                        <span style="font-size: .72rem; color: #3b82f6; display: block; margin-top: 4px; font-weight: 500;">* Free Kurir membebaskan biaya Jemput & Antar. Free layanan membebaskan biaya laundry reguler/express/setrika.</span>
                    </div>
                </div>

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

                <!-- OPSI LAYANAN JEMPUT & ANTAR -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-top: 10px;">
                    <!-- Pilihan Penjemputan -->
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer;">
                            <input type="checkbox" id="is_jemput" name="is_jemput" value="1" <?= set_checkbox('is_jemput', '1') ?> style="width: 18px; height: 18px;">
                            🚚 Layanan Jemput Pakaian (+Rp 10.000 / kg)
                        </label>
                    </div>

                    <!-- Pilihan Pengantaran -->
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer;">
                            <input type="checkbox" id="is_antar" name="is_antar" value="1" <?= set_checkbox('is_antar', '1') ?> style="width: 18px; height: 18px;">
                            🛵 Layanan Antar Kembali (+Rp 10.000 / kg)
                        </label>
                    </div>
                </div>

                <!-- DETAIL PENJEMPUTAN -->
                <div id="form-detail-jemput" style="display: none; background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 18px; margin-top: 5px;">
                    <h4 style="margin-bottom: 12px; font-size: .9rem; color: var(--primary); font-weight: 700; border-bottom: 1px solid var(--gray-200); padding-bottom: 6px;">🚚 Detail Lokasi Jemput</h4>
                    
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label for="alamat_jemput">📍 Alamat Lengkap Penjemputan</label>
                        <textarea 
                            name="alamat_jemput" 
                            id="alamat_jemput" 
                            rows="2" 
                            class="form-control" 
                            placeholder="Masukkan alamat lengkap penjemputan cucian"><?= set_value('alamat_jemput', $pelanggan['alamat']) ?></textarea>
                        <?php if (form_error('alamat_jemput')): ?>
                            <span class="error-msg"><?= form_error('alamat_jemput') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group" style="margin-bottom: 12px;">
                        <label for="gps_jemput">🔗 Link Google Maps Lokasi Jemput</label>
                        <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                            <input 
                                type="text" 
                                name="gps_jemput" 
                                id="gps_jemput" 
                                class="form-control" 
                                placeholder="Klik di peta atau tombol GPS"
                                value="<?= set_value('gps_jemput') ?>"
                                readonly>
                            <button type="button" id="btn-lokasi-saya-jemput" class="btn btn-secondary" style="font-size: .8rem; white-space: nowrap; padding: 0 12px; display: inline-flex; align-items: center; gap: 4px;">
                                🎯 Deteksi GPS
                            </button>
                        </div>
                        <div id="map-picker-jemput" style="height: 220px; width: 100%; border-radius: var(--radius); border: 1.5px solid var(--gray-300); z-index: 1;"></div>
                    </div>
                </div>

                <!-- DETAIL PENGANTARAN -->
                <div id="form-detail-antar" style="display: none; background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: var(--radius); padding: 18px; margin-top: 5px;">
                    <h4 style="margin-bottom: 12px; font-size: .9rem; color: var(--primary); font-weight: 700; border-bottom: 1px solid var(--gray-200); padding-bottom: 6px;">🛵 Detail Lokasi Antar</h4>
                    
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label style="display: flex; align-items: center; gap: 6px; font-weight: 500; cursor: pointer; margin-bottom: 8px; font-size: .8rem;">
                            <input type="checkbox" id="is_antar_sama" value="1" style="width: 15px; height: 15px;">
                            Alamat & lokasi sama dengan penjemputan
                        </label>
                        <label for="alamat_antar">📍 Alamat Lengkap Pengantaran</label>
                        <textarea 
                            name="alamat_antar" 
                            id="alamat_antar" 
                            rows="2" 
                            class="form-control" 
                            placeholder="Masukkan alamat lengkap pengantaran cucian"><?= set_value('alamat_antar', $pelanggan['alamat']) ?></textarea>
                        <?php if (form_error('alamat_antar')): ?>
                            <span class="error-msg"><?= form_error('alamat_antar') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group" style="margin-bottom: 12px;">
                        <label for="gps_antar">🔗 Link Google Maps Lokasi Antar</label>
                        <div style="display: flex; gap: 8px; margin-bottom: 8px;">
                            <input 
                                type="text" 
                                name="gps_antar" 
                                id="gps_antar" 
                                class="form-control" 
                                placeholder="Klik di peta atau tombol GPS"
                                value="<?= set_value('gps_antar') ?>"
                                readonly>
                            <button type="button" id="btn-lokasi-saya-antar" class="btn btn-secondary" style="font-size: .8rem; white-space: nowrap; padding: 0 12px; display: inline-flex; align-items: center; gap: 4px;">
                                🎯 Deteksi GPS
                            </button>
                        </div>
                        <div id="map-picker-antar" style="height: 220px; width: 100%; border-radius: var(--radius); border: 1.5px solid var(--gray-300); z-index: 1;"></div>
                    </div>
                </div>

                <!-- Card Tampilan Estimasi Harga Otomatis -->
                <div class="stat-card" style="margin-top: 10px; background: var(--gray-50); border: 1.5px dashed var(--gray-300); padding: 18px 24px; border-radius: var(--radius-lg);">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.8rem;">🏷️</span>
                            <div>
                                <strong style="display:block; font-size: .85rem; color: var(--gray-600); font-weight:600;">Kalkulasi Estimasi Tagihan</strong>
                                <span style="font-size: .75rem; color: var(--gray-400); display: block;" id="rincian-laundry">Laundry: Rp 0</span>
                                <span style="font-size: .75rem; color: var(--gray-400); display: block;" id="rincian-jemput">Jemput: Rp 0</span>
                                <span style="font-size: .75rem; color: var(--gray-400); display: block;" id="rincian-antar">Antar: Rp 0</span>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <strong style="display:block; font-size: .75rem; color: var(--gray-500); font-weight:600;">Total Bayar</strong>
                            <h3 style="font-size: 1.6rem; color: var(--primary); font-weight: 800;" id="box-estimasi">
                                Rp <span id="estimasi-harga">0</span>
                            </h3>
                        </div>
                    </div>
                <!-- PILIHAN METODE PEMBAYARAN -->
                <div class="form-group" style="margin-top: 15px;">
                    <label for="metode_pembayaran" style="font-weight: 600;">💵 Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required style="max-width: 320px;">
                        <option value="Tunai">Tunai (Bayar di tempat / COD)</option>
                        <option value="QRIS">QRIS (Scan Barcode & Upload Bukti Bayar)</option>
                        <option value="Transfer Bank">Transfer Bank (Kirim ke Rekening Toko & Upload Bukti)</option>
                    </select>
                </div>

                <!-- CATATAN KHUSUS -->
                <div class="form-group" style="margin-top: 15px;">
                    <label for="catatan" style="font-weight: 600;">📝 Catatan Khusus (Opsional)</label>
                    <textarea name="catatan" id="catatan" class="form-control" placeholder="Contoh: Pisahkan pakaian putih, jangan pakai pewangi menyengat, atau baju kemeja minta digantung." rows="3" style="resize: vertical; font-size: .85rem;"></textarea>
                </div>

            </div>

            <!-- PERNYATAAN PERSETUJUAN BERAT -->
            <div style="background: #fffbeb; border: 1px solid #fef3c7; padding: 16px 20px; border-radius: var(--radius); margin-top: 20px;">
                <label style="display: flex; align-items: start; gap: 10px; font-weight: 500; cursor: pointer; font-size: .82rem; line-height: 1.5; color: #92400e;">
                    <input type="checkbox" id="setuju_ketentuan_berat" name="setuju_ketentuan_berat" value="1" required style="width: 18px; height: 18px; margin-top: 2px;">
                    <span>
                        <strong>⚠️ Persetujuan Penyesuaian Berat:</strong> Saya setuju bahwa berat yang saya masukkan adalah estimasi awal. Berat pakaian akan ditimbang kembali secara akurat oleh Admin, dan saya setuju untuk membayar kekurangan biaya atau menerima pengembalian (refund) jika berat riil pakaian berbeda.
                    </span>
                </label>
                <?php if (form_error('setuju_ketentuan_berat')): ?>
                    <span class="error-msg" style="display:block; margin-top: 6px; color: var(--danger); font-size: .75rem;"><?= form_error('setuju_ketentuan_berat') ?></span>
                <?php endif; ?>
            </div>

            <div class="alert alert-info" style="margin-top: 20px; line-height: 1.5;">
                ℹ️ Status pesanan Anda akan diset menjadi <strong>Menunggu</strong> sampai pakaian diterima/dijemput oleh petugas outlet kami untuk ditimbang kembali secara akurat.
            </div>

            <div style="margin-top: 24px; display: flex; gap: 10px; justify-content: flex-end;">
                <a href="<?= base_url('user/dashboard') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="btn-kirim-pesanan">🚀 Kirim Pesanan Laundry</button>
            </div>

        </form>
    </div>
</div>

<!-- JavaScript Kalkulasi Dinamis & Leaflet Map Picker -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const inputBerat = document.getElementById('berat');
    const selectLayanan = document.getElementById('jenis_layanan');
    const checkboxJemput = document.getElementById('is_jemput');
    const checkboxAntar = document.getElementById('is_antar');
    const labelLaundry = document.getElementById('rincian-laundry');
    const labelJemput = document.getElementById('rincian-jemput');
    const labelAntar = document.getElementById('rincian-antar');
    const estimasiText = document.getElementById('estimasi-harga');
    const rewardSelect = document.getElementById('reward_option');

    function updateRewardOptions() {
        if (!rewardSelect) return;
        const layanan = selectLayanan.value;
        const optReguler = rewardSelect.querySelector('option[value="free_cuci_reguler"]');
        const optExpress = rewardSelect.querySelector('option[value="free_cuci_express"]');
        const optSetrika = rewardSelect.querySelector('option[value="free_cuci_setrika"]');
        
        const poin = <?= intval($pelanggan['poin']) ?>;
        
        if (optReguler) optReguler.disabled = (layanan !== 'Cuci Reguler' || poin < 15);
        if (optExpress) optExpress.disabled = (layanan !== 'Cuci Express' || poin < 18);
        if (optSetrika) optSetrika.disabled = (layanan !== 'Cuci + Setrika' || poin < 30);
        
        // Reset reward selection jika pilihan saat ini menjadi disabled
        const selectedOpt = rewardSelect.querySelector('option[value="' + rewardSelect.value + '"]');
        if (selectedOpt && selectedOpt.disabled) {
            rewardSelect.value = '';
        }
    }

    function hitungEstimasi() {
        const berat = parseFloat(inputBerat.value) || 0;
        const layanan = selectLayanan.value;
        const reward = rewardSelect ? rewardSelect.value : '';
        let tarif = 0;

        if (layanan === 'Cuci Reguler') {
            tarif = 7000;
        } else if (layanan === 'Cuci Express') {
            tarif = 10000;
        } else if (layanan === 'Cuci + Setrika') {
            tarif = 12000;
        }

        let hargaLaundry = berat * tarif;
        let ongkirJemput = checkboxJemput.checked ? (berat * 10000) : 0;
        let ongkirAntar = checkboxAntar.checked ? (berat * 10000) : 0;

        // Terapkan diskon reward di sisi client
        if (reward === 'free_cuci_reguler' && layanan === 'Cuci Reguler') {
            hargaLaundry = 0;
        } else if (reward === 'free_cuci_express' && layanan === 'Cuci Express') {
            hargaLaundry = 0;
        } else if (reward === 'free_cuci_setrika' && layanan === 'Cuci + Setrika') {
            hargaLaundry = 0;
        } else if (reward === 'free_kurir') {
            ongkirJemput = 0;
            ongkirAntar = 0;
        }

        const total = hargaLaundry + ongkirJemput + ongkirAntar;

        labelLaundry.innerText = 'Laundry: Rp ' + hargaLaundry.toLocaleString('id-ID');
        labelJemput.innerText = 'Jemput: Rp ' + ongkirJemput.toLocaleString('id-ID');
        labelAntar.innerText = 'Antar: Rp ' + ongkirAntar.toLocaleString('id-ID');
        estimasiText.innerText = total.toLocaleString('id-ID');
    }

    inputBerat.addEventListener('input', hitungEstimasi);
    selectLayanan.addEventListener('change', function() {
        updateRewardOptions();
        hitungEstimasi();
    });
    checkboxJemput.addEventListener('change', hitungEstimasi);
    checkboxAntar.addEventListener('change', hitungEstimasi);
    if (rewardSelect) {
        rewardSelect.addEventListener('change', hitungEstimasi);
    }
    
    // Jalankan pertama kali
    updateRewardOptions();
    hitungEstimasi();

    // 2. Logic Toggle Form Penjemputan & Pengantaran
    const formDetailJemput = document.getElementById('form-detail-jemput');
    const inputAlamatJemput = document.getElementById('alamat_jemput');

    const formDetailAntar = document.getElementById('form-detail-antar');
    const inputAlamatAntar = document.getElementById('alamat_antar');
    const checkboxAntarSama = document.getElementById('is_antar_sama');

    function toggleJemput() {
        if (checkboxJemput.checked) {
            formDetailJemput.style.display = 'block';
            inputAlamatJemput.setAttribute('required', 'required');
            setTimeout(initMapJemput, 150);
        } else {
            formDetailJemput.style.display = 'none';
            inputAlamatJemput.removeAttribute('required');
        }
    }

    function toggleAntar() {
        if (checkboxAntar.checked) {
            formDetailAntar.style.display = 'block';
            inputAlamatAntar.setAttribute('required', 'required');
            setTimeout(initMapAntar, 150);
        } else {
            formDetailAntar.style.display = 'none';
            inputAlamatAntar.removeAttribute('required');
        }
    }

    checkboxJemput.addEventListener('change', toggleJemput);
    checkboxAntar.addEventListener('change', toggleAntar);
    toggleJemput();
    toggleAntar();

    // 3. Logic Sinkronisasi Alamat Antar = Jemput
    const inputGpsJemput = document.getElementById('gps_jemput');
    const inputGpsAntar = document.getElementById('gps_antar');

    function sinkronisasiAlamat() {
        if (checkboxAntarSama.checked) {
            inputAlamatAntar.value = inputAlamatJemput.value;
            inputAlamatAntar.readOnly = true;
            
            inputGpsAntar.value = inputGpsJemput.value;
            
            // Sync Map Antar ke lokasi Map Jemput
            if (markerJemput && markerAntar && mapAntar) {
                const pos = markerJemput.getLatLng();
                markerAntar.setLatLng(pos);
                mapAntar.setView(pos, 15);
            }
        } else {
            inputAlamatAntar.readOnly = false;
        }
    }

    checkboxAntarSama.addEventListener('change', sinkronisasiAlamat);
    inputAlamatJemput.addEventListener('input', function() {
        if (checkboxAntarSama.checked) {
            inputAlamatAntar.value = inputAlamatJemput.value;
        }
    });

    // 4. Logic Leaflet Map Picker (Batam Center default)
    const defaultLat = 1.1278; // Nagoya Hill
    const defaultLng = 104.0152;

    // MAP JEMPUT
    let mapJemput = null;
    let markerJemput = null;

    function initMapJemput() {
        if (mapJemput !== null) {
            mapJemput.invalidateSize();
            return;
        }
        mapJemput = L.map('map-picker-jemput').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: 'OpenStreetMap'
        }).addTo(mapJemput);

        markerJemput = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(mapJemput);

        function updateJemputCoord(lat, lng) {
            const cleanLat = parseFloat(lat).toFixed(6);
            const cleanLng = parseFloat(lng).toFixed(6);
            inputGpsJemput.value = `https://www.google.com/maps?q=${cleanLat},${cleanLng}`;
            if (checkboxAntarSama.checked) {
                inputGpsAntar.value = inputGpsJemput.value;
                if (markerAntar && mapAntar) {
                    markerAntar.setLatLng([lat, lng]);
                    mapAntar.setView([lat, lng]);
                }
            }
        }

        updateJemputCoord(defaultLat, defaultLng);

        markerJemput.on('dragend', function() {
            const pos = markerJemput.getLatLng();
            updateJemputCoord(pos.lat, pos.lng);
        });

        mapJemput.on('click', function(e) {
            markerJemput.setLatLng(e.latlng);
            updateJemputCoord(e.latlng.lat, e.latlng.lng);
        });
    }

    // MAP ANTAR
    let mapAntar = null;
    let markerAntar = null;

    function initMapAntar() {
        if (mapAntar !== null) {
            mapAntar.invalidateSize();
            return;
        }
        mapAntar = L.map('map-picker-antar').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: 'OpenStreetMap'
        }).addTo(mapAntar);

        markerAntar = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(mapAntar);

        function updateAntarCoord(lat, lng) {
            const cleanLat = parseFloat(lat).toFixed(6);
            const cleanLng = parseFloat(lng).toFixed(6);
            inputGpsAntar.value = `https://www.google.com/maps?q=${cleanLat},${cleanLng}`;
        }

        updateAntarCoord(defaultLat, defaultLng);

        markerAntar.on('dragend', function() {
            if (checkboxAntarSama.checked) return;
            const pos = markerAntar.getLatLng();
            updateAntarCoord(pos.lat, pos.lng);
        });

        mapAntar.on('click', function(e) {
            if (checkboxAntarSama.checked) return;
            markerAntar.setLatLng(e.latlng);
            updateAntarCoord(e.latlng.lat, e.latlng.lng);
        });
    }

    // 5. Logic Geolocation (Cari Lokasi)
    function handleGeo(btn, targetMap, targetMarker, targetInput) {
        if (!navigator.geolocation) {
            alert('Fitur GPS tidak didukung browser Anda.');
            return;
        }
        btn.innerText = '⏳...';
        btn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                if (targetMap && targetMarker) {
                    targetMap.setView([lat, lng], 16);
                    targetMarker.setLatLng([lat, lng]);
                    const cleanLat = parseFloat(lat).toFixed(6);
                    const cleanLng = parseFloat(lng).toFixed(6);
                    targetInput.value = `https://www.google.com/maps?q=${cleanLat},${cleanLng}`;
                    if (targetInput === inputGpsJemput && checkboxAntarSama.checked) {
                        inputGpsAntar.value = inputGpsJemput.value;
                        if (markerAntar && mapAntar) {
                            markerAntar.setLatLng([lat, lng]);
                            mapAntar.setView([lat, lng]);
                        }
                    }
                }
                btn.innerText = '🎯 Deteksi GPS';
                btn.disabled = false;
            },
            function() {
                alert('Gagal mengakses GPS Anda.');
                btn.innerText = '🎯 Deteksi GPS';
                btn.disabled = false;
            },
            { enableHighAccuracy: true, timeout: 5000 }
        );
    }

    document.getElementById('btn-lokasi-saya-jemput').addEventListener('click', function() {
        handleGeo(this, mapJemput, markerJemput, inputGpsJemput);
    });

    document.getElementById('btn-lokasi-saya-antar').addEventListener('click', function() {
        if (checkboxAntarSama.checked) return;
        handleGeo(this, mapAntar, markerAntar, inputGpsAntar);
    });
});
</script>
