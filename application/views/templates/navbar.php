<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * templates/navbar.php
 * Render bagian atas konten (navbar/topbar) dan container notifikasi alert.
 */
$username = $this->session->userdata('username');
$role     = $this->session->userdata('role');
?>
<!-- ════ AREA KONTEN UTAMA ════ -->
<main class="main-content">
    
    <!-- Topbar Navigasi -->
    <div class="topbar">
        <h2><?= htmlspecialchars($title ?? 'Dashboard') ?></h2>
        <div class="topbar-right">
            <span class="badge-role <?= $role === 'admin' ? 'admin' : 'user' ?>">
                <?= $role === 'admin' ? 'Admin' : 'Pelanggan' ?>
            </span>
            <span style="font-size:.85rem;color:var(--gray-600);font-weight:500;">
                👤 <?= htmlspecialchars($username ?? '') ?>
            </span>
        </div>
    </div>

    <!-- Container Flash Alert Sistem -->
    <div style="padding: 0 28px;">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success" style="margin-top:16px;" id="alert-flash-success">
                ✅ <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error" style="margin-top:16px;" id="alert-flash-error">
                ⚠️ <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pembuka Area Halaman Utama -->
    <div class="page-body">
