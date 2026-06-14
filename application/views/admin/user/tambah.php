<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * admin/user/tambah.php
 * Form tambah user baru oleh Administrator.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>➕ Tambah User</h2>
        <p>Daftarkan akun login baru ke dalam sistem.</p>
    </div>
    <div>
        <a href="<?= base_url('admin/user') ?>" class="btn btn-secondary" id="btn-kembali-user">
            &larr; Kembali ke Daftar
        </a>
    </div>
</div>

<!-- ── Form Card ── -->
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3>📝 Form Tambah Akun Pengguna</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/user/simpan') ?>" method="post">
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
                        value="<?= set_value('username') ?>"
                        required>
                    <?php if (form_error('username')): ?>
                        <span class="error-msg"><?= form_error('username') ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">🔒 Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="Masukkan password akun baru" 
                        required>
                    <?php if (form_error('password')): ?>
                        <span class="error-msg"><?= form_error('password') ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="role">🛡️ Hak Akses (Role)</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="user" <?= set_select('role', 'user') ?>>user (Pelanggan)</option>
                        <option value="admin" <?= set_select('role', 'admin') ?>>admin (Administrator)</option>
                    </select>
                    <?php if (form_error('role')): ?>
                        <span class="error-msg"><?= form_error('role') ?></span>
                    <?php endif; ?>
                </div>

            </div>

            <!-- ── Dynamic Customer Fields for Role = User ── -->
            <div id="customer-fields-container" style="display: none; border-top: 1.5px dashed var(--gray-200); padding-top: 20px; margin-top: 20px;">
                <h4 style="margin-bottom: 16px; color: var(--primary); display: flex; align-items: center; gap: 6px;">
                    <span>👥</span> Data Profil Pelanggan Baru
                </h4>
                
                <div class="form-grid" style="grid-template-columns: 1fr; gap: 16px;">
                    <div class="form-group">
                        <label for="nama">👤 Nama Lengkap Pelanggan</label>
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama" 
                            class="form-control" 
                            placeholder="Contoh: Budi Santoso" 
                            value="<?= set_value('nama') ?>">
                        <?php if (form_error('nama')): ?>
                            <span class="error-msg"><?= form_error('nama') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="no_hp">📞 Nomor HP / WhatsApp</label>
                        <input 
                            type="text" 
                            id="no_hp" 
                            name="no_hp" 
                            class="form-control" 
                            placeholder="Contoh: 08123456789" 
                            value="<?= set_value('no_hp') ?>">
                        <?php if (form_error('no_hp')): ?>
                            <span class="error-msg"><?= form_error('no_hp') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="alamat">🏠 Alamat Lengkap Rumah</label>
                        <textarea 
                            id="alamat" 
                            name="alamat" 
                            class="form-control" 
                            placeholder="Contoh: Jl. Sudirman No. 25, Bandung" 
                            rows="3"><?= set_value('alamat') ?></textarea>
                        <?php if (form_error('alamat')): ?>
                            <span class="error-msg"><?= form_error('alamat') ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div style="margin-top: 24px; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="reset" class="btn btn-secondary" id="btn-reset-user">Reset Form</button>
                <button type="submit" class="btn btn-primary" id="btn-simpan-user">🚀 Simpan User</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const customerContainer = document.getElementById('customer-fields-container');
    const namaInput = document.getElementById('nama');
    const noHpInput = document.getElementById('no_hp');
    const alamatInput = document.getElementById('alamat');

    function toggleCustomerFields() {
        if (roleSelect.value === 'user') {
            customerContainer.style.display = 'block';
            namaInput.setAttribute('required', 'required');
            noHpInput.setAttribute('required', 'required');
            alamatInput.setAttribute('required', 'required');
        } else {
            customerContainer.style.display = 'none';
            namaInput.removeAttribute('required');
            noHpInput.removeAttribute('required');
            alamatInput.removeAttribute('required');
        }
    }

    roleSelect.addEventListener('change', toggleCustomerFields);
    
    // Run once on load to handle validation errors preserving selected value
    toggleCustomerFields();
});
</script>
