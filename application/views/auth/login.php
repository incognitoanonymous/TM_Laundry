<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login ke LaundryKu — Sistem Informasi Manajemen Laundry">
    <title><?= $title ?? 'Masuk — LaundryKu' ?></title>
<style>
/* LaundryKu — Login Page CSS */
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
    display: flex; align-items: center; justify-content: center; padding: 20px;
}
a { text-decoration: none; color: inherit; }
.login-box { width: 100%; max-width: 420px; }
.login-logo { text-align: center; margin-bottom: 28px; }
.login-logo .icon {
    width: 62px; height: 62px;
    background: linear-gradient(135deg, #2563eb, #0ea5e9);
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem;
    margin: 0 auto 12px;
    box-shadow: 0 8px 20px rgba(37,99,235,.3);
}
.login-logo h1 { font-size: 1.4rem; font-weight: 800; color: var(--white); }
.login-logo p  { font-size: .8rem; color: rgba(255,255,255,.5); margin-top: 3px; }
.login-card {
    background: var(--white); border-radius: var(--radius-lg);
    padding: 36px; box-shadow: var(--shadow-lg);
}
.form-group { margin-bottom: 16px; display: flex; flex-direction: column; gap: 6px; }
label { font-size: .82rem; font-weight: 600; color: var(--gray-700); }
.form-control {
    width: 100%; padding: 10px 13px;
    border: 1.5px solid var(--gray-200); border-radius: var(--radius);
    font-size: .875rem; color: var(--gray-800); background: var(--white);
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
    transition: background var(--transition); margin-top: 6px;
}
.btn-primary:hover { background: var(--primary-dark); }
.alert {
    padding: 12px 16px; border-radius: var(--radius);
    margin-bottom: 16px; font-size: .875rem;
    display: flex; align-items: center; gap: 8px; border-left: 4px solid;
}
.alert-error   { background: #fef2f2; color: #991b1b; border-color: #dc2626; }
.alert-success { background: #f0fdf4; color: #166534; border-color: #16a34a; }
.error-msg  { font-size: .75rem; color: var(--danger); }
.login-links { text-align: center; margin-top: 16px; }
.login-links a { color: var(--primary); font-size: .82rem; display: block; margin-top: 8px; }
.login-links a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="login-page">
    <div class="login-box">

        <!-- Logo & Brand -->
        <div class="login-logo">
            <div class="icon">🧺</div>
            <h1>LaundryKu</h1>
            <p>Sistem Informasi Manajemen Laundry</p>
        </div>

        <!-- Flash Message -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error">⚠️ <?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">✅ <?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>

        <!-- Login Card -->
        <div class="login-card">
            <form action="<?= base_url('auth/proses_login') ?>" method="post" class="login-form">
                <!-- Native CodeIgniter 3 CSRF input -->
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                <div class="form-group">
                    <label for="username">👤 Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        placeholder="Masukkan username"
                        value="<?= set_value('username') ?>"
                        required
                        autocomplete="username">
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
                        placeholder="Masukkan password"
                        required
                        autocomplete="current-password">
                    <?php if (form_error('password')): ?>
                        <span class="error-msg"><?= form_error('password') ?></span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary" id="btn-login">
                    🚀 Masuk
                </button>
            </form>

            <div class="login-links">
                <a href="<?= base_url('register') ?>">📝 Belum punya akun? Daftar Sekarang</a>
                <a href="<?= base_url('cek-status') ?>">🔍 Cek Status Laundry (Tanpa Login)</a>
            </div>
        </div>

        <!-- Hint Akun Demo -->
        <div style="text-align:center; margin-top:18px; color:rgba(255,255,255,.5); font-size:.78rem;">
            Demo: <strong style="color:rgba(255,255,255,.8)">admin</strong> / <strong style="color:rgba(255,255,255,.8)">1234</strong>
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
