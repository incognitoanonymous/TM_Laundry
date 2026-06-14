<?php
/*
| layouts/header_user.php
| Header & sidebar untuk halaman User / Pelanggan.
*/
$username = $this->session->userdata('username');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard — LaundryKu' ?></title>
<style>
/* ============================================================
   LaundryKu — Embedded CSS (User Layout)
   Vanilla CSS, tidak ada framework eksternal
   ============================================================ */

:root {
    --primary:      #0284c7;
    --primary-dark: #0369a1;
    --success:      #16a34a;
    --warning:      #d97706;
    --danger:       #dc2626;
    --gray-50:      #f8fafc;
    --gray-100:     #f1f5f9;
    --gray-200:     #e2e8f0;
    --gray-400:     #94a3b8;
    --gray-600:     #475569;
    --gray-700:     #334155;
    --gray-800:     #1e293b;
    --gray-900:     #0f172a;
    --white:        #ffffff;
    --radius:       10px;
    --radius-lg:    16px;
    --shadow:       0 1px 3px rgba(0,0,0,.10), 0 1px 2px rgba(0,0,0,.06);
    --shadow-md:    0 4px 6px -1px rgba(0,0,0,.10);
    --transition:   .2s ease;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: Arial, Helvetica, sans-serif;
    background: var(--gray-50);
    color: var(--gray-800);
    line-height: 1.6;
    min-height: 100vh;
}
a { text-decoration: none; color: inherit; }
.app-wrapper { display: flex; min-height: 100vh; }

/* Sidebar */
.sidebar {
    width: 240px; background: #0c4a6e; color: var(--white);
    display: flex; flex-direction: column;
    position: fixed; top: 0; left: 0; bottom: 0; z-index: 100;
}
.sidebar-brand {
    padding: 24px 20px 20px;
    border-bottom: 1px solid rgba(255,255,255,.08);
    display: flex; align-items: center; gap: 10px;
}
.sidebar-brand .logo-icon {
    width: 38px; height: 38px; background: var(--primary);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
}
.sidebar-brand h1 { font-size: 1.1rem; font-weight: 700; }
.sidebar-brand small { display: block; font-size: .65rem; color: var(--gray-400); margin-top: 1px; }
.sidebar-nav { flex: 1; padding: 16px 0; }
.nav-label {
    font-size: .65rem; font-weight: 600; text-transform: uppercase;
    letter-spacing: .08em; color: var(--gray-400); padding: 10px 20px 6px;
}
.sidebar-nav a {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 20px; font-size: .875rem;
    color: rgba(255,255,255,.65);
    transition: all var(--transition);
    border-left: 3px solid transparent;
}
.sidebar-nav a:hover, .sidebar-nav a.active {
    background: rgba(255,255,255,.06); color: var(--white);
    border-left-color: var(--primary);
}
.sidebar-nav a .nav-icon { font-size: 1rem; width: 20px; text-align: center; }
.sidebar-footer { padding: 16px 20px; border-top: 1px solid rgba(255,255,255,.08); }
.sidebar-footer .user-info { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.sidebar-footer .avatar {
    width: 34px; height: 34px; background: var(--primary);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .85rem;
}
.sidebar-footer .user-detail { font-size: .8rem; }
.sidebar-footer .user-detail strong { display: block; color: var(--white); }
.sidebar-footer .user-detail span { color: var(--gray-400); }
.sidebar-footer .btn-logout {
    width: 100%; display: flex; align-items: center; gap: 8px;
    padding: 9px 14px; background: rgba(220,38,38,.15);
    color: #fca5a5; border-radius: var(--radius);
    font-size: .825rem; transition: background var(--transition);
}
.sidebar-footer .btn-logout:hover { background: rgba(220,38,38,.3); }

/* Main */
.main-content { margin-left: 240px; flex: 1; display: flex; flex-direction: column; }
.topbar {
    background: var(--white); border-bottom: 1px solid var(--gray-200);
    padding: 14px 28px;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 50; box-shadow: var(--shadow);
}
.topbar h2 { font-size: 1.05rem; font-weight: 600; }
.topbar-right { display: flex; align-items: center; gap: 12px; }
.badge-role {
    padding: 4px 12px; border-radius: 999px;
    font-size: .72rem; font-weight: 600; text-transform: uppercase;
}
.badge-role.admin { background: #dbeafe; color: #1d4ed8; }
.badge-role.user  { background: #dcfce7; color: #15803d; }
.page-body { padding: 28px; flex: 1; }

/* Alerts */
.alert {
    padding: 12px 18px; border-radius: var(--radius);
    margin-bottom: 20px; font-size: .875rem;
    display: flex; align-items: center; gap: 10px; border-left: 4px solid;
}
.alert-success { background: #f0fdf4; color: #166534; border-color: #16a34a; }
.alert-error   { background: #fef2f2; color: #991b1b; border-color: #dc2626; }
.alert-info    { background: #eff6ff; color: #1e40af; border-color: #2563eb; }
.alert-warning { background: #fffbeb; color: #92400e; border-color: #d97706; }

/* Stats */
.stats-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 18px; margin-bottom: 28px;
}
.stat-card {
    background: var(--white); border-radius: var(--radius-lg);
    padding: 22px; box-shadow: var(--shadow);
    display: flex; align-items: center; gap: 16px;
    transition: transform var(--transition);
}
.stat-card:hover { transform: translateY(-2px); }
.stat-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.stat-icon.blue   { background: #dbeafe; }
.stat-icon.green  { background: #dcfce7; }
.stat-icon.yellow { background: #fef9c3; }
.stat-icon.sky    { background: #e0f2fe; }
.stat-info p { font-size: .78rem; color: var(--gray-600); margin-bottom: 2px; }
.stat-info h3 { font-size: 1.8rem; font-weight: 700; color: var(--gray-900); line-height: 1; }

/* Card */
.card { background: var(--white); border-radius: var(--radius-lg); box-shadow: var(--shadow); overflow: hidden; }
.card-header {
    padding: 16px 22px; border-bottom: 1px solid var(--gray-200);
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
}
.card-header h3 { font-size: .95rem; font-weight: 600; }
.card-body { padding: 22px; }

/* Table */
.table-responsive { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: .85rem; }
thead { background: var(--gray-50); }
th {
    padding: 11px 16px; text-align: left; font-weight: 600;
    color: var(--gray-600); font-size: .78rem;
    text-transform: uppercase; letter-spacing: .04em;
    white-space: nowrap; border-bottom: 1px solid var(--gray-200);
}
td {
    padding: 12px 16px; color: var(--gray-700);
    border-bottom: 1px solid var(--gray-100); vertical-align: middle;
}
tr:last-child td { border-bottom: none; }
tr:hover td { background: var(--gray-50); }

/* Badge */
.badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 11px; border-radius: 999px; font-size: .72rem; font-weight: 600;
}
.badge::before { content: '●'; font-size: .55rem; }
.badge-proses   { background: #fef9c3; color: #854d0e; }
.badge-selesai  { background: #dcfce7; color: #166534; }
.badge-diambil  { background: #e0f2fe; color: #075985; }

/* Buttons */
.btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--radius);
    font-size: .85rem; font-weight: 500; cursor: pointer;
    border: none; transition: all var(--transition);
    text-decoration: none; white-space: nowrap; font-family: inherit;
}
.btn-primary   { background: var(--primary); color: var(--white); }
.btn-primary:hover { background: var(--primary-dark); }
.btn-secondary { background: var(--gray-100); color: var(--gray-700); }
.btn-secondary:hover { background: var(--gray-200); }
.btn-sm { padding: 5px 10px; font-size: .78rem; }

/* Page Header */
.page-header {
    display: flex; align-items: flex-start;
    justify-content: space-between; margin-bottom: 22px; gap: 16px;
}
.page-header-title h2 { font-size: 1.25rem; font-weight: 700; color: var(--gray-900); }
.page-header-title p  { font-size: .82rem; color: var(--gray-600); margin-top: 2px; }

/* Empty */
.empty-state { text-align: center; padding: 48px 20px; color: var(--gray-400); }
.empty-state .empty-icon { font-size: 3rem; margin-bottom: 10px; }
.empty-state p { font-size: .9rem; }

@media (max-width: 768px) {
    .sidebar { display: none; }
    .main-content { margin-left: 0; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .topbar { padding: 12px 16px; }
    .page-body { padding: 16px; }
}
@media (max-width: 480px) {
    .stats-grid { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; }
}
</style>
</head>
<body>
<div class="app-wrapper">

    <!-- ════ SIDEBAR USER ════ -->
    <aside class="sidebar" id="sidebar" style="background:#0c4a6e;">

        <div class="sidebar-brand">
            <div class="logo-icon" style="background:#0284c7;">🧺</div>
            <div>
                <h1>LaundryKu</h1>
                <small>Panel Pelanggan</small>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Menu</div>

            <a href="<?= base_url('user/dashboard') ?>"
               id="nav-dashboard"
               class="<?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>"
               style="<?= ($active ?? '') === 'dashboard' ? 'border-left-color:#0284c7;' : '' ?>">
                <span class="nav-icon">🏠</span> Dashboard
            </a>

            <a href="<?= base_url('user/riwayat') ?>"
               id="nav-riwayat"
               class="<?= ($active ?? '') === 'riwayat' ? 'active' : '' ?>"
               style="<?= ($active ?? '') === 'riwayat' ? 'border-left-color:#0284c7;' : '' ?>">
                <span class="nav-icon">📋</span> Riwayat Laundry
            </a>

            <a href="<?= base_url('cek-status') ?>"
               id="nav-cek"
               class="<?= ($active ?? '') === 'cek' ? 'active' : '' ?>">
                <span class="nav-icon">🔍</span> Cek Status
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="avatar" style="background:#0284c7;">
                    <?= strtoupper(substr($username ?? 'U', 0, 1)) ?>
                </div>
                <div class="user-detail">
                    <strong><?= htmlspecialchars($username ?? '') ?></strong>
                    <span>Pelanggan</span>
                </div>
            </div>
            <a href="<?= base_url('logout') ?>" class="btn-logout" id="btn-logout">
                🚪 Logout
            </a>
        </div>
    </aside>
    <!-- ════ END SIDEBAR ════ -->

    <main class="main-content">

        <div class="topbar">
            <h2><?= $title ?? 'Dashboard' ?></h2>
            <div class="topbar-right">
                <span class="badge-role user">Pelanggan</span>
                <span style="font-size:.82rem;color:var(--gray-600);">
                    👤 <?= htmlspecialchars($username ?? '') ?>
                </span>
            </div>
        </div>

        <div style="padding: 0 28px;">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success" style="margin-top:16px;">
                    ✅ <?= $this->session->flashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-error" style="margin-top:16px;">
                    ⚠️ <?= $this->session->flashdata('error') ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="page-body">
