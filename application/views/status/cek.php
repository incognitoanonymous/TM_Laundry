<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Cek status laundry Anda tanpa perlu login. Masukkan kode transaksi untuk melihat status cucian.">
    <title><?= $title ?? 'Cek Status Laundry — LaundryKu' ?></title>
<style>
/* LaundryKu — Cek Status Page CSS */
:root {
    --primary: #2563eb; --primary-dark: #1d4ed8;
    --secondary: #0ea5e9;
    --gray-50: #f8fafc; --gray-100: #f1f5f9; --gray-200: #e2e8f0;
    --gray-400: #94a3b8; --gray-600: #475569;
    --gray-800: #1e293b; --gray-900: #0f172a;
    --white: #ffffff; --danger: #dc2626;
    --radius: 10px; --radius-lg: 16px;
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,.10), 0 2px 4px -1px rgba(0,0,0,.06);
    --transition: .2s ease;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(160deg, #eff6ff 0%, #dbeafe 50%, #e0f2fe 100%);
    min-height: 100vh; padding: 40px 20px; color: var(--gray-800);
}
a { text-decoration: none; color: inherit; }
.status-container { max-width: 700px; margin: 0 auto; }
.status-header { text-align: center; margin-bottom: 32px; }
.status-header .brand-logo {
    width: 56px; height: 56px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; margin: 0 auto 14px;
    box-shadow: 0 6px 18px rgba(37,99,235,.25);
}
.status-header h1 { font-size: 1.6rem; font-weight: 800; color: var(--gray-900); }
.status-header p  { color: var(--gray-600); margin-top: 5px; font-size: .9rem; }
.status-nav { display: flex; justify-content: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
.status-card {
    background: var(--white); border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md); overflow: hidden; margin-bottom: 20px;
}
.status-search {
    padding: 24px 28px;
}
.status-search form { display: flex; gap: 10px; width: 100%; }
.form-control {
    flex: 1; padding: 10px 13px;
    border: 1.5px solid var(--gray-200); border-radius: var(--radius);
    font-size: .875rem; color: var(--gray-800); font-family: inherit;
    transition: border-color var(--transition), box-shadow var(--transition);
}
.form-control:focus {
    outline: none; border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
}
.form-control::placeholder { color: var(--gray-400); }
.btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--radius);
    font-size: .85rem; font-weight: 500; cursor: pointer;
    border: none; text-decoration: none; white-space: nowrap;
    font-family: inherit; transition: all var(--transition);
}
.btn-primary  { background: var(--primary); color: var(--white); }
.btn-primary:hover { background: var(--primary-dark); }
.btn-secondary { background: var(--gray-100); color: var(--gray-800); }
.btn-secondary:hover { background: var(--gray-200); }
.btn-sm { padding: 8px 16px; font-size: .85rem; font-weight: 600; }
.alert {
    padding: 12px 18px; border-radius: var(--radius); margin-bottom: 16px;
    font-size: .875rem; display: flex; align-items: center; gap: 10px; border-left: 4px solid;
}
.alert-success { background: #f0fdf4; color: #166534; border-color: #16a34a; }
.alert-error   { background: #fef2f2; color: #991b1b; border-color: #dc2626; }
.alert-info    { background: #eff6ff; color: #1e40af; border-color: #2563eb; }
.alert-warning { background: #fffbeb; color: #92400e; border-color: #d97706; }
.result-header {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: var(--white); padding: 20px 28px;
}
.result-header .kode { font-size: 1.1rem; font-weight: 700; letter-spacing: 1px; }
.result-header .nama { font-size: .85rem; opacity: .85; margin-top: 2px; }
.result-body { padding: 24px 28px; }
.result-row {
    display: flex; padding: 10px 0;
    border-bottom: 1px solid var(--gray-100); gap: 12px;
}
.result-row:last-child { border-bottom: none; }
.result-row .label { min-width: 140px; font-size: .82rem; color: var(--gray-600); font-weight: 500; }
.result-row .value { font-size: .9rem; color: var(--gray-900); font-weight: 500; }
.status-badge-lg {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 16px; border-radius: 999px;
    font-size: .85rem; font-weight: 700;
}
.status-badge-lg.proses  { background: #fef9c3; color: #854d0e; }
.status-badge-lg.selesai { background: #dcfce7; color: #166534; }
.status-badge-lg.diambil { background: #e0f2fe; color: #075985; }
</style>
</head>
<body>
<div>
    <div class="status-container">

        <!-- ── Header Brand ── -->
        <div class="status-header">
            <div class="brand-logo">🧺</div>
            <h1>LaundryKu</h1>
            <p>Cek status cucian Anda tanpa perlu login</p>
        </div>

        <!-- ── Navigasi ── -->
        <div class="status-nav">
            <a href="<?= base_url('login') ?>" class="btn btn-secondary btn-sm" id="btn-ke-login">
                🔑 Masuk ke Sistem
            </a>
            <a href="<?= base_url('register') ?>" class="btn btn-primary btn-sm" id="btn-ke-daftar">
                📝 Daftar Pelanggan Baru
            </a>
        </div>

        <!-- ── Flash ── -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error" style="margin-bottom:16px;">
                ⚠️ <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- ── Form Cek Status ── -->
        <div class="status-card">
            <div style="padding:24px 28px; border-bottom:1px solid var(--gray-100);">
                <h2 style="font-size:1rem; font-weight:700; color:var(--gray-800);">
                    🔍 Masukkan Kode Transaksi
                </h2>
                <p style="font-size:.82rem; color:var(--gray-600); margin-top:4px;">
                    Kode transaksi diberikan saat Anda menyerahkan cucian ke laundry.
                    Contoh: <code style="background:var(--gray-100); padding:2px 7px; border-radius:4px; font-weight:600; color:var(--primary);">TRX-20260520-001</code>
                </p>
            </div>
            <div class="status-search">
                <form action="<?= base_url('status/cari') ?>" method="post" id="form-cek-status">
                    <!-- Native CodeIgniter 3 CSRF input -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    
                    <input
                        type="text"
                        name="kode_transaksi"
                        id="kode_transaksi"
                        class="form-control"
                        placeholder="Masukkan kode transaksi..."
                        value="<?= isset($kode_cari) ? htmlspecialchars($kode_cari) : '' ?>"
                        style="text-transform:uppercase;"
                        required
                        autocomplete="off">
                    <button type="submit" class="btn btn-primary" id="btn-cari-status">
                        🔍 Cari
                    </button>
                </form>
            </div>
        </div>

        <!-- ── Hasil Pencarian ── -->
        <?php if ($tidak_ada): ?>
            <div class="status-card">
                <div class="card-body" style="padding:32px 28px; text-align:center;">
                    <div style="font-size:3rem; margin-bottom:12px;">❌</div>
                    <h3 style="font-size:1rem; color:var(--gray-800); margin-bottom:6px;">
                        Transaksi Tidak Ditemukan
                    </h3>
                    <p style="font-size:.85rem; color:var(--gray-600);">
                        Kode <strong><?= htmlspecialchars($kode_cari ?? '') ?></strong> tidak terdaftar di sistem.
                        Periksa kembali kesesuaian kode pada nota laundry Anda.
                    </p>
                </div>
            </div>
        <?php elseif ($hasil): ?>
            <?php
            $t = $hasil;
            $badge_class = 'proses';
            if ($t['status'] === 'Selesai') {
                $badge_class = 'selesai';
            } elseif ($t['status'] === 'Diambil') {
                $badge_class = 'diambil';
            }
            ?>
            <div class="status-card">
                <div class="result-header">
                    <div class="kode">📋 <?= htmlspecialchars($t['kode_transaksi']) ?></div>
                    <div class="nama">👤 <?= htmlspecialchars($t['nama_pelanggan']) ?></div>
                </div>
                <div class="result-body">

                    <div class="result-row">
                        <span class="label">Status Cucian</span>
                        <span class="value">
                            <span class="status-badge-lg <?= $badge_class ?>">
                                <?= htmlspecialchars($t['status']) ?>
                            </span>
                        </span>
                    </div>

                    <div class="result-row">
                        <span class="label">Jenis Layanan</span>
                        <span class="value"><?= htmlspecialchars($t['jenis_layanan']) ?></span>
                    </div>

                    <div class="result-row">
                        <span class="label">Berat Cucian</span>
                        <span class="value"><?= number_format($t['berat'], 2) ?> kg</span>
                    </div>

                    <div class="result-row">
                        <span class="label">Total Harga</span>
                        <span class="value" style="font-size:1.1rem; color:var(--primary); font-weight:700;">
                            Rp <?= number_format($t['harga'], 0, ',', '.') ?>
                        </span>
                    </div>

                    <div class="result-row">
                        <span class="label">Tanggal Masuk</span>
                        <span class="value"><?= date('d F Y', strtotime($t['tanggal'])) ?></span>
                    </div>

                </div>

                <!-- Timeline 6 Status Cucian -->
                <div style="padding: 0 28px 24px;">
                    <h4 style="font-size: .8rem; text-transform: uppercase; color: var(--gray-600); letter-spacing: .05em; margin-bottom: 12px; font-weight:600;">Status Pengerjaan Cucian</h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px; background: var(--gray-50); padding: 18px 24px; border-radius: var(--radius-lg); border: 1px solid var(--gray-200);">
                        <?php
                        $status_flow = ['Menunggu', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
                        $current_index = array_search($t['status'], $status_flow);
                        if ($current_index === FALSE) $current_index = 0;
                        
                        foreach ($status_flow as $idx => $step):
                            $is_passed = $idx <= $current_index;
                            $is_current = $idx === $current_index;
                        ?>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .7rem; 
                                    background: <?= $is_current ? 'var(--primary)' : ($is_passed ? 'var(--success)' : 'var(--gray-200)') ?>;
                                    color: <?= $is_passed ? 'var(--white)' : 'var(--gray-400)' ?>; font-weight: 700;">
                                    <?= $is_passed ? '✓' : $idx + 1 ?>
                                </div>
                                <span style="font-size: .82rem; font-weight: <?= $is_current ? '700' : '500' ?>; 
                                    color: <?= $is_current ? 'var(--primary)' : ($is_passed ? 'var(--gray-800)' : 'var(--gray-400)') ?>;">
                                    <?= htmlspecialchars($step) ?> 
                                    <?= $is_current ? '<small style="font-weight:600; color:var(--primary-dark); font-style:italic;">(Sedang berada di tahap ini)</small>' : '' ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Pesan Notifikasi Kondisi Status -->
                <div style="padding: 0 28px 24px;">
                    <?php if ($t['status'] === 'Menunggu'): ?>
                        <div class="alert alert-info" style="margin-bottom:0;">
                            ⏳ Cucian Anda <strong>menunggu antrean</strong> untuk segera dicuci oleh petugas kami.
                        </div>
                    <?php elseif (in_array($t['status'], ['Dicuci', 'Dikeringkan', 'Disetrika'])): ?>
                        <div class="alert alert-warning" style="margin-bottom:0;">
                            ⚙️ Cucian Anda <strong>sedang dalam pengerjaan operasional</strong>. Estimasi penyelesaian segera dilakukan.
                        </div>
                    <?php elseif ($t['status'] === 'Selesai'): ?>
                        <div class="alert alert-success" style="margin-bottom:0;">
                            ✅ Cucian Anda <strong>sudah selesai dikerjakan</strong>! Silakan datang ke laundry untuk mengambil cucian Anda.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info" style="margin-bottom:0;" style="border-color:var(--gray-400);">
                            📦 Cucian Anda <strong>sudah selesai diambil</strong> oleh pelanggan. Terima kasih telah memercayai cucian Anda kepada kami!
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        <?php endif; ?>

        <!-- Footer kecil -->
        <div style="text-align:center; margin-top:24px; color:var(--gray-400); font-size:.78rem;">
            © <?= date('Y') ?> LaundryKu — Sistem Informasi Manajemen Laundry
        </div>

    </div>
</div>

<script>
// Auto uppercase input kode transaksi
document.getElementById('kode_transaksi').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
});

// Auto-close alert
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
