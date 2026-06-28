<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * templates/navbar.php
 * Render bagian atas konten (navbar/topbar) dan container notifikasi alert.
 */
$username = $this->session->userdata('username');
$role     = $this->session->userdata('role');

// Ambil notifikasi pelanggan secara dinamis
$notifications = [];
if ($role === 'user') {
    $CI =& get_instance();
    $CI->load->model('Pelanggan_model');
    $id_user = $CI->session->userdata('id_user');
    $pel = $CI->Pelanggan_model->get_by_user($id_user);
    if ($pel) {
        $active_trx = $CI->db->where('id_pelanggan', $pel['id_pelanggan'])
                             ->where('status !=', 'Diambil')
                             ->order_by('updated_at', 'DESC')
                             ->get('transaksi')
                             ->result_array();
        
        foreach ($active_trx as $t) {
            $code = $t['kode_transaksi'];
            $id_trx = $t['id_transaksi'];
            
            // 1. Notifikasi Penjemputan
            if ($t['is_jemput'] == 1 && $t['status_jemput'] === 'Menunggu Penjemputan') {
                $notifications[] = [
                    'id_transaksi' => $id_trx,
                    'text' => "🚚 Pakaian Anda (#$code) sedang menunggu penjemputan kurir.",
                    'time' => $t['updated_at']
                ];
            }
            
            // 2. Notifikasi Proses Cucian
            if ($t['status'] === 'Menunggu') {
                $notifications[] = [
                    'id_transaksi' => $id_trx,
                    'text' => "⏳ Cucian Anda (#$code) dalam antrean pengerjaan.",
                    'time' => $t['updated_at']
                ];
            } elseif ($t['status'] === 'Dicuci') {
                $notifications[] = [
                    'id_transaksi' => $id_trx,
                    'text' => "🧼 Cucian Anda (#$code) sedang dicuci.",
                    'time' => $t['updated_at']
                ];
            } elseif ($t['status'] === 'Dikeringkan') {
                $notifications[] = [
                    'id_transaksi' => $id_trx,
                    'text' => "☀️ Cucian Anda (#$code) sedang dikeringkan.",
                    'time' => $t['updated_at']
                ];
            } elseif ($t['status'] === 'Disetrika') {
                $notifications[] = [
                    'id_transaksi' => $id_trx,
                    'text' => "💨 Cucian Anda (#$code) sedang disetrika dan dirapikan.",
                    'time' => $t['updated_at']
                ];
            } elseif ($t['status'] === 'Selesai') {
                if ($t['is_antar'] == 1 && $t['status_antar'] === 'Menunggu Pengantaran') {
                    $notifications[] = [
                        'id_transaksi' => $id_trx,
                        'text' => "🛵 Cucian Anda (#$code) sudah SELESAI! Pakaian akan segera diantarkan kurir.",
                        'time' => $t['updated_at']
                    ];
                } else {
                    $notifications[] = [
                        'id_transaksi' => $id_trx,
                        'text' => "🎉 Cucian Anda (#$code) sudah SELESAI! Silakan ambil ke outlet.",
                        'time' => $t['updated_at']
                    ];
                }
            }
        }
    }
}
?>
<!-- ════ AREA KONTEN UTAMA ════ -->
<main class="main-content">
    
    <!-- Topbar Navigasi -->
    <div class="topbar">
        <h2><?= htmlspecialchars($title ?? 'Dashboard') ?></h2>
        <div class="topbar-right" style="display: flex; align-items: center; gap: 16px;">
            
            <?php if ($role === 'user'): ?>
            <!-- Notification Bell Dropdown -->
            <div class="notification-wrapper" style="position: relative; display: inline-block;">
                <button id="notification-bell-btn" style="background: none; border: none; font-size: 1.35rem; cursor: pointer; position: relative; padding: 4px; display: flex; align-items: center; justify-content: center; color: var(--gray-600); transition: color 0.2s;" title="Notifikasi Cucian">
                    🔔
                    <?php if (count($notifications) > 0): ?>
                        <span class="notification-badge" style="position: absolute; top: -2px; right: -2px; background: var(--danger); color: var(--white); font-size: 0.65rem; font-weight: 700; border-radius: 50%; min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; padding: 2px; border: 1.5px solid var(--white);">
                            <?= count($notifications) ?>
                        </span>
                    <?php endif; ?>
                </button>
                
                <!-- Floating Dropdown Box -->
                <div id="notification-dropdown" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 10px; width: 320px; background: var(--white); border: 1px solid var(--gray-200); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); border-radius: var(--radius-lg); z-index: 1000; overflow: hidden;">
                    <div style="padding: 12px 16px; border-bottom: 1px solid var(--gray-100); background: var(--gray-50); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; font-weight: 700; color: var(--gray-900);">🔔 Notifikasi Aktivitas</span>
                        <span class="badge badge-proses" style="font-size: 0.7rem; padding: 2px 6px;"><?= count($notifications) ?> Aktif</span>
                    </div>
                    <div style="max-height: 280px; overflow-y: auto;">
                        <?php if (empty($notifications)): ?>
                            <div style="padding: 24px; text-align: center; color: var(--gray-400); font-size: 0.8rem;">
                                Tidak ada notifikasi cucian aktif saat ini.
                            </div>
                        <?php else: ?>
                            <?php foreach ($notifications as $n): ?>
                                <a href="<?= base_url('user/detail/' . $n['id_transaksi']) ?>" style="display: block; padding: 12px 16px; border-bottom: 1px solid var(--gray-100); text-decoration: none; color: var(--gray-700); transition: background 0.15s; font-size: 0.8rem;" onmouseover="this.style.background='var(--gray-50)'" onmouseout="this.style.background='var(--white)'">
                                    <div style="line-height: 1.4; color: var(--gray-800); margin-bottom: 4px;">
                                        <?= htmlspecialchars($n['text']) ?>
                                    </div>
                                    <span style="font-size: 0.68rem; color: var(--gray-400); font-weight: 500;">
                                        🕒 <?= date('d M, H:i', strtotime($n['time'])) ?> WIB
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <script>
            document.addEventListener("DOMContentLoaded", function() {
                const bellBtn = document.getElementById('notification-bell-btn');
                const dropdown = document.getElementById('notification-dropdown');
                
                if (bellBtn && dropdown) {
                    bellBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                            dropdown.style.display = 'block';
                        } else {
                            dropdown.style.display = 'none';
                        }
                    });
                    
                    document.addEventListener('click', function(e) {
                        if (!dropdown.contains(e.target) && e.target !== bellBtn) {
                            dropdown.style.display = 'none';
                        }
                    });
                }
            });
            </script>
            <?php endif; ?>

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
