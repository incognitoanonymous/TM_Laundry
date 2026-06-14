<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * pelanggan/tambah.php
 * Form tambah profil pelanggan baru oleh Administrator.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>➕ Tambah Pelanggan</h2>
        <p>Isi form di bawah untuk mendaftarkan profil pelanggan baru.</p>
    </div>
    <div>
        <a href="<?= base_url('admin/pelanggan') ?>" class="btn btn-secondary" id="btn-kembali-pelanggan">
            &larr; Kembali ke Daftar
        </a>
    </div>
</div>

<!-- ── Form Card ── -->
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <h3>📝 Form Data Pelanggan</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/pelanggan/simpan') ?>" method="post" id="form-tambah-pelanggan">
            <!-- Native CodeIgniter 3 CSRF input -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="form-grid" style="grid-template-columns: 1fr; gap: 16px;">

                <!-- Pilih User Akun -->
                <div class="form-group">
                    <label for="id_user">👤 Hubungkan ke Akun User (Username)</label>
                    <select name="id_user" id="id_user" class="form-control" required>
                        <option value="">-- Pilih Akun User --</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id_user'] ?>" <?= set_select('id_user', $u['id_user']) ?>>
                                @<?= htmlspecialchars($u['username']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form-text">Pilih akun login yang terelasi satu-ke-satu dengan data pelanggan ini.</span>
                    <?php if (form_error('id_user')): ?>
                        <span class="error-msg"><?= form_error('id_user') ?></span>
                    <?php endif; ?>
                </div>

                <!-- Nama -->
                <div class="form-group">
                    <label for="nama">📛 Nama Lengkap</label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama" 
                        class="form-control"
                        placeholder="Contoh: Budi Santoso"
                        value="<?= set_value('nama') ?>"
                        required 
                        maxlength="100">
                    <?php if (form_error('nama')): ?>
                        <span class="error-msg"><?= form_error('nama') ?></span>
                    <?php endif; ?>
                </div>

                <!-- No. HP -->
                <div class="form-group">
                    <label for="no_hp">📞 No. HP / WhatsApp</label>
                    <input 
                        type="text" 
                        id="no_hp" 
                        name="no_hp" 
                        class="form-control"
                        placeholder="Contoh: 081234567890"
                        value="<?= set_value('no_hp') ?>"
                        required 
                        maxlength="20">
                    <?php if (form_error('no_hp')): ?>
                        <span class="error-msg"><?= form_error('no_hp') ?></span>
                    <?php endif; ?>
                </div>

                <!-- Alamat -->
                <div class="form-group">
                    <label for="alamat">🏠 Alamat Rumah</label>
                    <textarea 
                        id="alamat" 
                        name="alamat" 
                        class="form-control"
                        rows="3"
                        placeholder="Masukkan alamat lengkap pelanggan"
                        required><?= set_value('alamat') ?></textarea>
                    <?php if (form_error('alamat')): ?>
                        <span class="error-msg"><?= form_error('alamat') ?></span>
                    <?php endif; ?>
                </div>

            </div><!-- /form-grid -->

            <div style="margin-top: 24px; display: flex; gap: 10px; justify-content: flex-end;">
                <a href="<?= base_url('admin/pelanggan') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="btn-simpan-pelanggan">💾 Simpan Pelanggan</button>
            </div>

        </form>
    </div>
</div>
