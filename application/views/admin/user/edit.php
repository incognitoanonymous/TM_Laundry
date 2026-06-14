<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * admin/user/edit.php
 * Form edit user oleh Administrator.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>✏️ Edit User</h2>
        <p>Perbarui informasi akun pengguna.</p>
    </div>
    <div>
        <a href="<?= base_url('admin/user') ?>" class="btn btn-secondary" id="btn-kembali-user-edit">
            &larr; Kembali ke Daftar
        </a>
    </div>
</div>

<!-- ── Form Card ── -->
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3>📝 Form Edit Akun Pengguna</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/user/update/' . $user['id_user']) ?>" method="post">
            <!-- Native CodeIgniter 3 CSRF input -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="form-grid" style="grid-template-columns: 1fr; gap: 16px;">
                
                <div class="form-group">
                    <label for="username">👤 Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-control" 
                        placeholder="Contoh: andi_wijaya" 
                        value="<?= set_value('username', $user['username']) ?>"
                        required>
                    <?php if (form_error('username')): ?>
                        <span class="error-msg"><?= form_error('username') ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">🔒 Password Baru (Opsional)</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Kosongkan jika password tidak ingin diganti">
                    <span class="form-text" style="margin-top:2px;">Biarkan kolom ini kosong jika tidak berniat mengganti password pengguna saat ini.</span>
                    <?php if (form_error('password')): ?>
                        <span class="error-msg"><?= form_error('password') ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="role">🛡️ Hak Akses (Role)</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="user" <?= set_select('role', 'user', $user['role'] === 'user') ?>>user (Pelanggan)</option>
                        <option value="admin" <?= set_select('role', 'admin', $user['role'] === 'admin') ?>>admin (Administrator)</option>
                    </select>
                    <?php if (form_error('role')): ?>
                        <span class="error-msg"><?= form_error('role') ?></span>
                    <?php endif; ?>
                </div>

            </div>

            <div style="margin-top: 24px; display: flex; gap: 10px; justify-content: flex-end;">
                <a href="<?= base_url('admin/user') ?>" class="btn btn-secondary" id="btn-batal-user-edit">Batal</a>
                <button type="submit" class="btn btn-primary" id="btn-update-user">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
