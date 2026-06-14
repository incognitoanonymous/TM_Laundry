<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * transaksi/index.php
 * Halaman utama daftar transaksi laundry - Administrator.
 */
?>

<!-- ── Page Header ── -->
<div class="page-header">
    <div class="page-header-title">
        <h2>🧾 Data Transaksi Laundry</h2>
        <p>Kelola dan pantau seluruh transaksi laundry beserta status cucian pelanggan.</p>
    </div>
    <div>
        <a href="<?= base_url('admin/transaksi/tambah') ?>" class="btn btn-primary" id="btn-tambah-transaksi-index">
            ➕ Tambah Transaksi Baru
        </a>
    </div>
</div>

<!-- ── FORM PENCARIAN & FILTER ── -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3>🔍 Pencarian & Filter Data</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/transaksi') ?>" method="get" style="display: flex; flex-direction: column; gap: 14px;">
            <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                
                <!-- Pencarian Kata Kunci -->
                <div class="form-group">
                    <label for="keyword">Kata Kunci Cari</label>
                    <input 
                        type="text" 
                        id="keyword" 
                        name="keyword" 
                        class="form-control" 
                        placeholder="Nama / No HP / Kode TRX" 
                        value="<?= htmlspecialchars($keyword ?? '') ?>">
                </div>

                <!-- Filter Status -->
                <div class="form-group">
                    <label for="status">Status Cucian</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">-- Semua Status --</option>
                        <?php 
                        $status_options = ['Menunggu', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
                        foreach ($status_options as $opt):
                        ?>
                            <option value="<?= $opt ?>" <?= ($status_filter === $opt) ? 'selected' : '' ?>>
                                <?= $opt ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filter Tanggal -->
                <div class="form-group">
                    <label for="tanggal">Tanggal Transaksi</label>
                    <input 
                        type="date" 
                        id="tanggal" 
                        name="tanggal" 
                        class="form-control" 
                        value="<?= htmlspecialchars($tanggal ?? '') ?>">
                </div>

                <!-- Filter Pelanggan -->
                <div class="form-group">
                    <label for="id_pelanggan">Pelanggan</label>
                    <select id="id_pelanggan" name="id_pelanggan" class="form-control">
                        <option value="">-- Semua Pelanggan --</option>
                        <?php foreach ($pelanggan_list as $pel): ?>
                            <option value="<?= $pel['id_pelanggan'] ?>" <?= ($pelanggan_id == $pel['id_pelanggan']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($pel['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                <a href="<?= base_url('admin/transaksi') ?>" class="btn btn-secondary btn-sm" style="padding: 8px 16px;">
                    🧹 Bersihkan Filter
                </a>
                <button type="submit" class="btn btn-primary btn-sm" style="padding: 8px 20px;">
                    🔍 Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ── TABEL DATA TRANSAKSI ── -->
<div class="card">
    <div class="card-header">
        <h3>🧾 Daftar Transaksi Laundry</h3>
        <span style="font-size:.8rem; color:var(--gray-600); font-weight:500;">
            Ditemukan: <strong><?= $total_rows ?></strong> transaksi | Baris ke-<?= $offset + 1 ?> s.d ke-<?= $offset + count($transaksi) ?>
        </span>
    </div>
    <div class="table-responsive">
        <?php if (empty($transaksi)): ?>
            <div class="empty-state">
                <div class="empty-icon">🧾</div>
                <p>Tidak ada transaksi laundry yang cocok dengan kriteria filter.</p>
            </div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Kode</th>
                    <th>Nama Pelanggan</th>
                    <th>Jenis Layanan</th>
                    <th>Berat</th>
                    <th>Total Harga</th>
                    <th>Tanggal Masuk</th>
                    <th style="width: 170px;">Ubah Cepat Status</th>
                    <th style="width: 100px; text-align: center;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = $offset + 1; foreach ($transaksi as $t): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <code style="font-size:.8rem; background:var(--gray-100); padding:3px 8px; border-radius:5px; font-weight:600; color:var(--primary);">
                            <?= htmlspecialchars($t['kode_transaksi']) ?>
                        </code>
                    </td>
                    <td style="font-weight: 600; color:var(--gray-900);">
                        <?= htmlspecialchars($t['nama_pelanggan']) ?>
                    </td>
                    <td><?= htmlspecialchars($t['jenis_layanan']) ?></td>
                    <td><?= number_format($t['berat'], 2) ?> kg</td>
                    <td style="font-weight: 700; color:var(--gray-900);">
                        Rp <?= number_format($t['harga'], 0, ',', '.') ?>
                    </td>
                    <td><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
                    <td>
                        <!-- Form update status inline cepat -->
                        <form action="<?= base_url('admin/transaksi/status/' . $t['id_transaksi']) ?>" method="post" style="display:inline;">
                            <!-- Native CodeIgniter 3 CSRF input -->
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            
                            <select 
                                name="status" 
                                class="form-control" 
                                style="padding: 4px 8px; font-size: .8rem; font-weight: 500; min-width: 120px; border-color: var(--gray-300);" 
                                onchange="this.form.submit()">
                                <?php foreach ($status_options as $opt): ?>
                                    <option value="<?= $opt ?>" <?= ($t['status'] === $opt) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($opt) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td>
                        <div class="action-btns" style="justify-content: center;">
                            <a href="<?= base_url('admin/transaksi/edit/' . $t['id_transaksi']) ?>" 
                               class="btn btn-warning btn-sm" 
                               id="btn-edit-transaksi-<?= $t['id_transaksi'] ?>"
                               title="Edit Transaksi Lengkap">
                                ✏️
                            </a>
                            <a href="<?= base_url('admin/transaksi/hapus/' . $t['id_transaksi']) ?>" 
                               class="btn btn-danger btn-sm" 
                               id="btn-hapus-transaksi-<?= $t['id_transaksi'] ?>"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus data transaksi ini secara permanen?');"
                               title="Hapus Transaksi">
                                🗑️
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
