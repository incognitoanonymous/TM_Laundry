<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * user/pesan.php
 * Halaman form pemesanan laundry secara mandiri oleh Pelanggan (User).
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>🧺 Pesan Laundry Mandiri</h2>
        <p>Silakan buat pesanan laundry baru secara mandiri di bawah ini.</p>
    </div>
    <div>
        <a href="<?= base_url('user/riwayat') ?>" class="btn btn-secondary" id="btn-kembali-riwayat">
            &larr; Riwayat Transaksi
        </a>
    </div>
</div>

<!-- ── Form Card ── -->
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3>📝 Form Isian Pesanan Laundry</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('user/proses_pesan') ?>" method="post">
            <!-- Native CodeIgniter 3 CSRF input -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="form-grid" style="grid-template-columns: 1fr; gap: 16px;">
                
                <div class="form-group">
                    <label for="jenis_layanan">🛠️ Jenis Layanan</label>
                    <select id="jenis_layanan" name="jenis_layanan" class="form-control" required>
                        <option value="Cuci Reguler" <?= set_select('jenis_layanan', 'Cuci Reguler') ?>>Cuci Reguler (Rp 7.000 / kg)</option>
                        <option value="Cuci Express" <?= set_select('jenis_layanan', 'Cuci Express') ?>>Cuci Express (Rp 10.000 / kg)</option>
                        <option value="Cuci + Setrika" <?= set_select('jenis_layanan', 'Cuci + Setrika', TRUE) ?>>Cuci + Setrika (Rp 12.000 / kg)</option>
                    </select>
                    <?php if (form_error('jenis_layanan')): ?>
                        <span class="error-msg"><?= form_error('jenis_layanan') ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="berat">⚖️ Perkiraan Berat (kg)</label>
                    <input 
                        type="number" 
                        id="berat" 
                        name="berat" 
                        class="form-control" 
                        min="0.1" 
                        step="0.1" 
                        value="<?= set_value('berat', '1.0') ?>" 
                        required>
                    <small style="color:var(--gray-400); display:block; margin-top:4px;">
                        * Timbang perkiraan berat pakaian Anda. Berat final akan diverifikasi kembali oleh admin saat serah terima pakaian.
                    </small>
                    <?php if (form_error('berat')): ?>
                        <span class="error-msg"><?= form_error('berat') ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="tanggal">📅 Tanggal Penyerahan</label>
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

            <div style="margin-top: 24px; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" id="btn-kirim-pesan">🚀 Kirim Pesanan Laundry</button>
            </div>
        </form>
    </div>
</div>
