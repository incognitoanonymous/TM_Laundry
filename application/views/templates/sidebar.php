<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * templates/sidebar.php
 * Render sidebar dinamis berdasarkan peran akun pengguna yang sedang login.
 */
$username = $this->session->userdata('username');
$role     = $this->session->userdata('role');
$active   = $active ?? '';
?>
<!-- ════ SIDEBAR LAUNDRYKU ════ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo-icon">🧺</div>
        <div>
            <h1 style="color:var(--white);">LaundryKu</h1>
            <small><?= $role === 'admin' ? 'Panel Administrator' : 'Panel Pelanggan' ?></small>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <?php if ($role === 'admin'): ?>
            <a href="<?= base_url('admin/dashboard') ?>" class="<?= $active === 'dashboard' ? 'active' : '' ?>" id="nav-dashboard">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <a href="<?= base_url('admin/user') ?>" class="<?= $active === 'user' ? 'active' : '' ?>" id="nav-user">
                <span class="nav-icon">🛡️</span> Data User
            </a>
            <a href="<?= base_url('admin/pelanggan') ?>" class="<?= $active === 'pelanggan' ? 'active' : '' ?>" id="nav-pelanggan">
                <span class="nav-icon">👥</span> Data Pelanggan
            </a>
            <a href="<?= base_url('admin/transaksi') ?>" class="<?= $active === 'transaksi' ? 'active' : '' ?>" id="nav-transaksi">
                <span class="nav-icon">🧾</span> Data Transaksi
            </a>
            <a href="<?= base_url('admin/laporan') ?>" class="<?= $active === 'laporan' ? 'active' : '' ?>" id="nav-laporan">
                <span class="nav-icon">📊</span> Laporan Keuangan
            </a>
        <?php else: ?>
            <a href="<?= base_url('user/dashboard') ?>" class="<?= $active === 'dashboard' ? 'active' : '' ?>" id="nav-dashboard">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <a href="<?= base_url('user/pesan') ?>" class="<?= $active === 'pesan' ? 'active' : '' ?>" id="nav-pesan">
                <span class="nav-icon">🧺</span> Pesan Laundry
            </a>
            <a href="<?= base_url('user/riwayat') ?>" class="<?= $active === 'riwayat' ? 'active' : '' ?>" id="nav-riwayat">
                <span class="nav-icon">📋</span> Riwayat Laundry
            </a>
            <a href="<?= base_url('user/profil') ?>" class="<?= $active === 'profil' ? 'active' : '' ?>" id="nav-profil">
                <span class="nav-icon">👤</span> Profil Saya
            </a>
        <?php endif; ?>

        <div class="nav-label" style="margin-top:14px;">Lainnya</div>
        <a href="<?= base_url('cek-status') ?>" target="_blank" id="nav-cek-status">
            <span class="nav-icon">🔍</span> Cek Status Publik
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="avatar"><?= strtoupper(substr($username ?? 'U', 0, 1)) ?></div>
            <div class="user-detail">
                <strong><?= htmlspecialchars($username ?? '') ?></strong>
                <span><?= $role === 'admin' ? 'Admin' : 'Pelanggan' ?></span>
            </div>
        </div>
        <a href="<?= base_url('logout') ?>" class="btn-logout" id="btn-logout-sidebar">
            🚪 Keluar
        </a>
    </div>
</aside>
