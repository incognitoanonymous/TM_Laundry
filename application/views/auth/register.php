<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pendaftaran Pelanggan Baru — LaundryKu">
    <title><?= $title ?? 'Daftar Pelanggan Baru — LaundryKu' ?></title>
<style>
/* LaundryKu — Register Page CSS */
:root {
    --primary: #2563eb; --primary-dark: #1d4ed8;
    --gray-200: #e2e8f0; --gray-400: #94a3b8;
    --gray-700: #334155; --gray-800: #1e293b; --gray-900: #0f172a;
    --white: #ffffff; --danger: #dc2626;
    --radius: 10px; --radius-lg: 16px;
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.10), 0 4px 6px -2px rgba(0,0,0,.05);
    --transition: .2s ease;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: Arial, Helvetica, sans-serif;
    min-height: 100vh;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 60%, #1e3a5f 100%);
    display: flex; align-items: center; justify-content: center; padding: 20px 10px;
}
a { text-decoration: none; color: inherit; }
.register-box { width: 100%; max-width: 520px; }
.register-logo { text-align: center; margin-bottom: 20px; }
.register-logo .icon {
    width: 62px; height: 62px;
    background: linear-gradient(135deg, #2563eb, #0ea5e9);
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem;
    margin: 0 auto 12px;
    box-shadow: 0 8px 20px rgba(37,99,235,.3);
}
.register-logo h1 { font-size: 1.4rem; font-weight: 800; color: var(--white); }
.register-logo p  { font-size: .8rem; color: rgba(255,255,255,.5); margin-top: 3px; }
.register-card {
    background: var(--white); border-radius: var(--radius-lg);
    padding: 30px; box-shadow: var(--shadow-lg);
}
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-group { display: flex; flex-direction: column; gap: 4px; }
.form-group.full-width { grid-column: 1 / -1; }
label { font-size: .8rem; font-weight: 600; color: var(--gray-700); }
.form-control {
    width: 100%; padding: 10px 12px;
    border: 1.5px solid var(--gray-200); border-radius: var(--radius);
    font-size: .85rem; color: var(--gray-800); background: var(--white);
    font-family: inherit;
    transition: border-color var(--transition), box-shadow var(--transition);
}
.form-control:focus {
    outline: none; border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
}
.form-control::placeholder { color: var(--gray-400); }
.btn-primary {
    width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px;
    padding: 11px; background: var(--primary); color: var(--white);
    border: none; border-radius: var(--radius); font-size: .9rem; font-weight: 600;
    cursor: pointer; font-family: inherit;
    transition: background var(--transition); margin-top: 14px;
}
.btn-primary:hover { background: var(--primary-dark); }
.alert {
    padding: 12px 16px; border-radius: var(--radius);
    margin-bottom: 16px; font-size: .875rem;
    display: flex; align-items: center; gap: 8px; border-left: 4px solid;
}
.alert-error { background: #fef2f2; color: #991b1b; border-color: #dc2626; }
.error-msg { font-size: .72rem; color: var(--danger); margin-top: 2px; }
.register-links { text-align: center; margin-top: 16px; }
.register-links a { color: var(--primary); font-size: .82rem; display: block; }
.register-links a:hover { text-decoration: underline; }

@media (max-width: 580px) {
    .form-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<div class="register-page">
    <div class="register-box">

        <!-- Logo & Brand -->
        <div class="register-logo">
            <div class="icon">🧺</div>
            <h1>Daftar Pelanggan</h1>
            <p>Buat akun untuk melacak riwayat cucian Anda</p>
        </div>

        <!-- Flash Message -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error">⚠️ <?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

        <!-- Register Card -->
        <div class="register-card">
            <form action="<?= base_url('auth/proses_register') ?>" method="post" class="register-form">
                <!-- Native CodeIgniter 3 CSRF input -->
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                <div class="form-grid">
                    
                    <!-- KREDENSIAL AKUN -->
                    <div class="form-group">
                        <label for="username">👤 Username Baru</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control"
                            placeholder="Min. 4 karakter"
                            value="<?= set_value('username') ?>"
                            required>
                        <?php if (form_error('username')): ?>
                            <span class="error-msg"><?= form_error('username') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="nama">📛 Nama Lengkap</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            class="form-control"
                            placeholder="Nama sesuai KTP"
                            value="<?= set_value('nama') ?>"
                            required>
                        <?php if (form_error('nama')): ?>
                            <span class="error-msg"><?= form_error('nama') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">🔒 Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Password akun"
                            required>
                        <?php if (form_error('password')): ?>
                            <span class="error-msg"><?= form_error('password') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="konf_password">🔁 Ulangi Password</label>
                        <input
                            type="password"
                            id="konf_password"
                            name="konf_password"
                            class="form-control"
                            placeholder="Ketik ulang password"
                            required>
                        <?php if (form_error('konf_password')): ?>
                            <span class="error-msg"><?= form_error('konf_password') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group full-width">
                        <label for="no_hp">📞 Nomor Handphone (WhatsApp)</label>
                        <input
                            type="text"
                            id="no_hp"
                            name="no_hp"
                            class="form-control"
                            placeholder="Contoh: 0812XXXXXXXX"
                            value="<?= set_value('no_hp') ?>"
                            required>
                        <?php if (form_error('no_hp')): ?>
                            <span class="error-msg"><?= form_error('no_hp') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group full-width">
                        <label for="alamat">🏠 Alamat Rumah</label>
                        <textarea
                            id="alamat"
                            name="alamat"
                            class="form-control"
                            placeholder="Alamat lengkap tempat tinggal"
                            rows="3"
                            required><?= set_value('alamat') ?></textarea>
                        <?php if (form_error('alamat')): ?>
                            <span class="error-msg"><?= form_error('alamat') ?></span>
                        <?php endif; ?>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary" id="btn-register">
                    📝 Daftarkan Akun Saya
                </button>
            </form>

            <div class="register-links">
                <a href="<?= base_url('login') ?>">🔑 Sudah memiliki akun? Silakan Masuk</a>
            </div>
        </div>

    </div>
</div>

<script>
// Auto-close alert after 4 seconds
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.alert').forEach(function(el) {
        setTimeout(function() {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(function() { el.remove(); }, 400);
        }, 4000);
    });
});
</script>
</body>
</html>
