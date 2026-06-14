<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * admin/user/index.php
 * Halaman daftar user sistem laundry - diakses oleh Administrator.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>🛡️ Manajemen User</h2>
        <p>Kelola data akun login administrator maupun pelanggan laundry.</p>
    </div>
    <div>
        <a href="<?= base_url('admin/user/tambah') ?>" class="btn btn-primary" id="btn-tambah-user">
            ➕ Tambah User Baru
        </a>
    </div>
</div>

<!-- ── Tabel Data User ── -->
<div class="card">
    <div class="card-header">
        <h3>👥 Daftar Pengguna Sistem</h3>
        <span style="font-size:.8rem; color:var(--gray-600); font-weight:500;">
            Menampilkan baris ke-<?= $offset + 1 ?> s.d ke-<?= $offset + count($users) ?>
        </span>
    </div>
    <div class="table-responsive">
        <?php if (empty($users)): ?>
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <p>Belum ada data user terdaftar.</p>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">ID User</th>
                    <th>Username</th>
                    <th>Hak Akses (Role)</th>
                    <th style="width: 200px; text-align: center;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><code>#<?= $u['id_user'] ?></code></td>
                    <td style="font-weight: 600; color:var(--gray-900);">
                        <?= htmlspecialchars($u['username']) ?>
                    </td>
                    <td>
                        <?php
                        $role_class = 'user';
                        if ($u['role'] === 'admin') {
                            $role_class = 'admin';
                        }
                        ?>
                        <span class="badge-role <?= $role_class ?>">
                            <?= htmlspecialchars($u['role']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns" style="justify-content: center;">
                            <a href="<?= base_url('admin/user/edit/' . $u['id_user']) ?>" class="btn btn-warning btn-sm" id="btn-edit-user-<?= $u['id_user'] ?>">
                                ✏️ Edit
                            </a>
                            <a href="<?= base_url('admin/user/hapus/' . $u['id_user']) ?>" 
                               class="btn btn-danger btn-sm" 
                               id="btn-hapus-user-<?= $u['id_user'] ?>"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus akun user ini? Seluruh data pelanggan yang terkait juga akan terhapus secara permanen.');">
                                🗑️ Hapus
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- ── Link Paginasi ── -->
<?= $pagination_links ?>
