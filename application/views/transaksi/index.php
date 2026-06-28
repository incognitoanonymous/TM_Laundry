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
    <div style="display: flex; gap: 8px;">
        <a href="<?= base_url('admin/transaksi/export_excel?' . http_build_query($_GET)) ?>" class="btn btn-success" id="btn-export-transaksi-excel">
            🟢 Ekspor Excel
        </a>
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
                    <th style="width: 140px;">Pembayaran</th>
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
                    <td style="font-weight: 600; color:var(--gray-900); padding-top: 10px; padding-bottom: 10px;">
                        <?= htmlspecialchars($t['nama_pelanggan']) ?>
                        <?php if (!empty($t['no_hp'])): ?>
                            <?php
                            $phone = preg_replace('/[^0-9]/', '', $t['no_hp']);
                            if (strpos($phone, '0') === 0) {
                                $phone = '62' . substr($phone, 1);
                            }
                            ?>
                            <br>
                            <a href="https://wa.me/<?= $phone ?>" target="_blank" style="font-size: .75rem; color: #10b981; text-decoration: underline; font-weight: 500; display: inline-flex; align-items: center; gap: 3px; margin-top: 2px;" title="Hubungi via WhatsApp">
                                💬 <?= htmlspecialchars($t['no_hp']) ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($t['is_jemput'] == 1): ?>
                            <br>
                            <span class="badge badge-<?= $t['status_jemput'] === 'Sudah Dijemput' ? 'selesai' : 'proses' ?>" style="font-size: .65rem; padding: 2px 6px; display: inline-block; margin-top: 4px; font-weight: 600;" title="Alamat: <?= htmlspecialchars($t['alamat_jemput']) ?>">
                                🚚 <?= htmlspecialchars($t['status_jemput']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($t['is_antar'] == 1): ?>
                            <br>
                            <span class="badge badge-<?= $t['status_antar'] === 'Sudah Diantarkan' ? 'selesai' : 'proses' ?>" style="font-size: .65rem; padding: 2px 6px; display: inline-block; margin-top: 4px; font-weight: 600;" title="Alamat: <?= htmlspecialchars($t['alamat_antar']) ?>">
                                🛵 <?= htmlspecialchars($t['status_antar']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($t['reward_used'])): ?>
                            <br>
                            <span style="display: inline-block; margin-top: 4px; font-size: .65rem; color: #1e3a8a; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 4px; padding: 2px 6px; font-weight: 600;" title="Points used: <?= $t['poin_used'] ?> pts">
                                🎁 <?= htmlspecialchars($t['reward_used']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($t['rating'])): ?>
                            <br>
                            <span style="display: inline-block; margin-top: 6px; font-size: .72rem; color: #b45309; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 4px; padding: 3px 8px; font-weight: 500; line-height: 1.3;" title="Review: <?= htmlspecialchars($t['review']) ?>">
                                ⭐ <?= str_repeat('★', $t['rating']) ?><?= str_repeat('☆', 5 - $t['rating']) ?>
                                <span style="display: block; color: var(--gray-600); font-weight: 500; font-size: .68rem; margin-top: 2px;">"<?= htmlspecialchars($t['review']) ?>"</span>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($t['catatan'])): ?>
                            <br>
                            <span style="display: inline-block; margin-top: 5px; font-size: .68rem; color: #78350f; background: #fef3c7; border: 1px solid #fde68a; border-radius: 4px; padding: 3px 6px; font-weight: 500; line-height: 1.3; max-width: 180px; white-space: normal;" title="Catatan Pelanggan">
                                📝 <strong>Catatan:</strong> <?= htmlspecialchars($t['catatan']) ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($t['jenis_layanan']) ?></td>
                    <td><?= number_format($t['berat'], 2) ?> kg</td>
                    <td style="font-weight: 700; color:var(--gray-900);">
                        Rp <?= number_format($t['harga'], 0, ',', '.') ?>
                    </td>
                    <td>
                        <span style="font-size: .7rem; color: var(--gray-500); font-weight: 600; display: block; margin-bottom: 2px;">
                            <?= htmlspecialchars($t['metode_pembayaran']) ?>
                        </span>
                        
                        <?php
                        $st = $t['status_pembayaran'];
                        $bg = '';
                        if ($st === 'Lunas') {
                            $bg = '#10b981';
                        } elseif ($st === 'Menunggu Verifikasi') {
                            $bg = '#f59e0b';
                        } else {
                            $bg = '#ef4444';
                        }
                        ?>
                        <span style="font-size: .72rem; padding: 2px 6px; font-weight: 700; color: white; border-radius: 4px; display: inline-block; background: <?= $bg ?>;">
                            <?= htmlspecialchars($st) ?>
                        </span>

                        <?php if ($st !== 'Lunas'): ?>
                            <div style="margin-top: 6px; display: flex; flex-direction: column; gap: 4px;">
                                <?php if ($st === 'Menunggu Verifikasi' && !empty($t['bukti_pembayaran'])): ?>
                                    <a href="<?= base_url('assets/uploads/bukti_bayar/' . $t['bukti_pembayaran']) ?>" target="_blank" style="font-size: .7rem; font-weight: 600; color: #3b82f6; text-decoration: underline; text-align: center; margin-bottom: 2px;" id="btn-lihat-bukti-<?= $t['id_transaksi'] ?>">
                                        🖼️ Lihat Bukti
                                    </a>
                                <?php endif; ?>
                                <a href="<?= base_url('admin/transaksi/verifikasi_bayar/' . $t['id_transaksi']) ?>" class="btn btn-primary" style="font-size: .68rem; padding: 2px 6px; background: #10b981; border-color: #10b981; color: white; border-radius: 3px; text-decoration: none; display: inline-block; text-align: center; font-weight: 600; width: 100%;" onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pembayaran diterima untuk transaksi ini (Lunas)?');" id="btn-verif-<?= $t['id_transaksi'] ?>">
                                    ✔️ Konfirmasi Bayar
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Status Uang Diterima Admin (Internal) -->
                        <div style="margin-top: 8px; border-top: 1px dashed var(--gray-300); padding-top: 6px; font-size: .68rem; text-align: left;">
                            <?php if ($t['uang_diterima'] == 1): ?>
                                <span style="color: #166534; font-weight: 700; display: block; text-align: center;">💰 Uang Diterima</span>
                            <?php else: ?>
                                <span style="color: #b45309; font-weight: 600; display: block; margin-bottom: 4px; text-align: center;">💸 Belum Diterima</span>
                                <a href="<?= base_url('admin/transaksi/konfirmasi_terima_uang/' . $t['id_transaksi']) ?>" class="btn" style="font-size: .65rem; padding: 2px 4px; background: #e0f2fe; border: 1px solid #bae6fd; color: #0369a1; border-radius: 3px; text-decoration: none; display: block; text-align: center; font-weight: 700;" onclick="return confirm('Apakah Anda yakin setoran/uang fisik untuk transaksi ini sudah diterima kasir/admin?');">
                                    📥 Terima Uang
                                </a>
                            <?php endif; ?>
                        </div>
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
                                <?php foreach ($status_options as $opt): 
                                    if ($opt === 'Disetrika' && $t['jenis_layanan'] !== 'Cuci + Setrika') {
                                        continue;
                                    }
                                ?>
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
