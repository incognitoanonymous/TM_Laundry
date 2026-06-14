<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * pelanggan/index.php
 * Halaman daftar pelanggan laundry - diakses oleh Administrator.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>👥 Data Pelanggan</h2>
        <p>Kelola profil data diri pelanggan laundry Anda.</p>
    </div>
    <div>
        <a href="<?= base_url('admin/pelanggan/tambah') ?>" class="btn btn-primary" id="btn-tambah-pelanggan">
            ➕ Tambah Pelanggan Baru
        </a>
    </div>
</div>

<!-- ── Tabel Data Pelanggan ── -->
<div class="card">
    <div class="card-header">
        <h3>👥 Daftar Pelanggan Laundry</h3>
        <span style="font-size:.8rem; color:var(--gray-600); font-weight:500;">
            Menampilkan baris ke-<?= $offset + 1 ?> s.d ke-<?= $offset + count($pelanggan) ?>
        </span>
    </div>
    <div class="table-responsive">
        <?php if (empty($pelanggan)): ?>
            <div class="empty-state">
                <div class="empty-icon">👤</div>
                <p>Belum ada data pelanggan terdaftar.</p>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Nama Lengkap</th>
                    <th>Username Akun</th>
                    <th>Nomor WhatsApp</th>
                    <th>Alamat Rumah</th>
                    <th style="width: 180px; text-align: center;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = $offset + 1; foreach ($pelanggan as $p): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td style="font-weight: 600; color:var(--gray-900);">
                        <?= htmlspecialchars($p['nama']) ?>
                    </td>
                    <td>
                        <code style="font-size:.8rem; background:var(--gray-100); padding:3px 8px; border-radius:5px; font-weight:600; color:var(--primary);">
                            @<?= htmlspecialchars($p['username'] ?? '-') ?>
                        </code>
                    </td>
                    <td><?= htmlspecialchars($p['no_hp']) ?></td>
                    <td style="max-width:260px;">
                        <span style="display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?= htmlspecialchars($p['alamat']) ?>">
                            <?= htmlspecialchars($p['alamat']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-btns" style="justify-content: center;">
                            <a href="<?= base_url('admin/pelanggan/edit/' . $p['id_pelanggan']) ?>"
                               class="btn btn-warning btn-sm"
                               id="btn-edit-pelanggan-<?= $p['id_pelanggan'] ?>">
                                ✏️ Edit
                            </a>
                            <a href="<?= base_url('admin/pelanggan/hapus/' . $p['id_pelanggan']) ?>"
                               class="btn btn-danger btn-sm"
                               id="btn-hapus-pelanggan-<?= $p['id_pelanggan'] ?>"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini? Semua data transaksi laundry terkait juga akan terhapus secara permanen.');">
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
