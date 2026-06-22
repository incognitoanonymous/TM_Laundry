<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * user/profil.php
 * Halaman manajemen profil dan kata sandi mandiri oleh pelanggan (User).
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>👤 Profil Saya</h2>
        <p>Kelola data informasi diri Anda serta perbarui kata sandi akun secara berkala.</p>
    </div>
</div>

<div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; align-items: start;">

    <!-- ── FORM EDIT DATA DIRI ── -->
    <div class="card">
        <div class="card-header">
            <h3>📝 Detail Data Diri</h3>
        </div>
        <div class="card-body">
            <form action="<?= base_url('user/profil/update') ?>" method="post">
                <!-- Native CodeIgniter 3 CSRF input -->
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                <div style="display:flex; flex-direction:column; gap:16px;">
                    
                    <div class="form-group">
                        <label>👤 Username Akun</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            value="@<?= htmlspecialchars($user['username']) ?>" 
                            style="background: var(--gray-100); cursor: not-allowed; font-weight:600;" 
                            disabled>
                        <span class="form-text">Username akun login tidak dapat diubah.</span>
                    </div>

                    <div class="form-group">
                        <label for="nama">📛 Nama Lengkap</label>
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama" 
                            class="form-control" 
                            value="<?= set_value('nama', $pelanggan['nama']) ?>" 
                            required 
                            maxlength="100">
                        <?php if (form_error('nama')): ?>
                            <span class="error-msg"><?= form_error('nama') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="no_hp">📞 Nomor WhatsApp (No. HP)</label>
                        <input 
                            type="text" 
                            id="no_hp" 
                            name="no_hp" 
                            class="form-control" 
                            value="<?= set_value('no_hp', $pelanggan['no_hp']) ?>" 
                            required 
                            maxlength="15">
                        <?php if (form_error('no_hp')): ?>
                            <span class="error-msg"><?= form_error('no_hp') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="alamat">🏠 Alamat Lengkap Tempat Tinggal</label>
                        <textarea 
                            id="alamat" 
                            name="alamat" 
                            class="form-control" 
                            rows="4" 
                            required><?= set_value('alamat', $pelanggan['alamat']) ?></textarea>
                        <?php if (form_error('alamat')): ?>
                            <span class="error-msg"><?= form_error('alamat') ?></span>
                        <?php endif; ?>
                    </div>

                    <h3 style="font-size: .9rem; border-top: 1px solid var(--gray-200); padding-top: 16px; margin-top: 8px; color: var(--gray-700);">🔒 Ubah Kata Sandi (Opsional)</h3>
                    
                    <div class="form-group">
                        <label for="password">Kata Sandi Baru</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control" 
                            placeholder="Biarkan kosong jika tidak ingin diubah">
                        <?php if (form_error('password')): ?>
                            <span class="error-msg"><?= form_error('password') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="konf_password">Ketik Ulang Kata Sandi Baru</label>
                        <input 
                            type="password" 
                            id="konf_password" 
                            name="konf_password" 
                            class="form-control" 
                            placeholder="Sama dengan kata sandi baru">
                        <?php if (form_error('konf_password')): ?>
                            <span class="error-msg"><?= form_error('konf_password') ?></span>
                        <?php endif; ?>
                    </div>

                </div>

                <div style="margin-top: 24px; display:flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary" id="btn-save-profil" style="padding: 10px 20px;">
                        💾 Simpan Pembaruan Profil
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- ── INFORMASI KEANGGOTAAN ── -->
    <div class="card" style="border-left: 4px solid var(--primary);">
        <div class="card-header">
            <h3>🏷️ Kartu Anggota Digital</h3>
        </div>
        <div class="card-body" style="background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%); padding: 30px;">
            <div style="display:flex; justify-content: space-between; align-items: start; margin-bottom: 24px;">
                <div>
                    <h4 style="font-size: 1.15rem; font-weight:800; color: var(--gray-800); letter-spacing: -.2px;">MEMBER LAUNDRYKU</h4>
                    <span style="font-size: .65rem; color: var(--gray-400); font-weight:600; letter-spacing: .05em; text-transform: uppercase;">Aplikasi Pelanggan Setia</span>
                </div>
                <span style="font-size: 2rem;">🧺</span>
            </div>
            
            <div style="margin-bottom: 24px;">
                <span style="display:block; font-size: .65rem; color: var(--gray-400); text-transform: uppercase; font-weight: 500; letter-spacing: .02em;">Kode Pelanggan</span>
                <code style="font-size: 1.1rem; font-weight:700; color: var(--primary); font-family: monospace;">
                    PLG-<?= str_pad($pelanggan['id_pelanggan'], 5, '0', STR_PAD_LEFT) ?>
                </code>
            </div>

            <div style="display:flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="display:block; font-size: .65rem; color: var(--gray-400); text-transform: uppercase; font-weight: 500;">Nama Member</span>
                    <strong style="font-size: .9rem; color: var(--gray-800);"><?= htmlspecialchars($pelanggan['nama']) ?></strong>
                </div>
                <div style="text-align: right;">
                    <span style="display:block; font-size: .65rem; color: var(--gray-400); text-transform: uppercase; font-weight: 500;">Anggota Sejak</span>
                    <strong style="font-size: .9rem; color: var(--gray-800);"><?= date('d M Y', strtotime($pelanggan['created_at'] ?? '2026-05-20')) ?></strong>
                </div>
            </div>
        </div>
    </div>

</div>
