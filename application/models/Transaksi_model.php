<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Transaksi_model.php — Model Transaksi LaundryKu
| -------------------------------------------------------------------
| Menyediakan operasi database untuk CRUD transaksi, pencarian, filter,
| paginasi, dan generate kode transaksi otomatis.
*/

class Transaksi_model extends CI_Model {

    private $table = 'transaksi';

    // ─── READ (Daftar & Detail) ───────────────────────────────────────────

    /**
     * Ambil semua transaksi beserta nama pelanggan (JOIN).
     *
     * @return array
     */
    public function get_all()
    {
        $this->db->select('transaksi.*, pelanggan.nama AS nama_pelanggan, pelanggan.no_hp');
        $this->db->from($this->table);
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');
        $this->db->order_by('transaksi.tanggal', 'DESC');
        $this->db->order_by('transaksi.id_transaksi', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil data transaksi berpaginasi beserta pencarian dan filter.
     */
    public function get_paginated($limit, $start, $keyword = NULL, $status = NULL, $tanggal = NULL, $id_pelanggan = NULL)
    {
        $this->db->select('transaksi.*, pelanggan.nama AS nama_pelanggan, pelanggan.no_hp');
        $this->db->from($this->table);
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');

        $this->_apply_search_filters($keyword, $status, $tanggal, $id_pelanggan);

        $this->db->limit($limit, $start);
        $this->db->order_by('transaksi.tanggal', 'DESC');
        $this->db->order_by('transaksi.id_transaksi', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil data transaksi beserta nama pelanggan (JOIN) tanpa limit paginasi (untuk ekspor Excel).
     */
    public function get_filtered($keyword = NULL, $status = NULL, $tanggal = NULL, $id_pelanggan = NULL)
    {
        $this->db->select('transaksi.*, pelanggan.nama AS nama_pelanggan, pelanggan.no_hp');
        $this->db->from($this->table);
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');

        $this->_apply_search_filters($keyword, $status, $tanggal, $id_pelanggan);

        $this->db->order_by('transaksi.tanggal', 'DESC');
        $this->db->order_by('transaksi.id_transaksi', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Hitung jumlah transaksi setelah disaring (untuk keperluan paginasi filter).
     */
    public function count_filtered($keyword = NULL, $status = NULL, $tanggal = NULL, $id_pelanggan = NULL)
    {
        $this->db->from($this->table);
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');

        $this->_apply_search_filters($keyword, $status, $tanggal, $id_pelanggan);

        return $this->db->count_all_results();
    }

    /**
     * Terapkan pencarian dan filter secara terpusat.
     */
    private function _apply_search_filters($keyword = NULL, $status = NULL, $tanggal = NULL, $id_pelanggan = NULL)
    {
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('pelanggan.nama', $keyword);
            $this->db->or_like('pelanggan.no_hp', $keyword);
            $this->db->or_like('transaksi.kode_transaksi', $keyword);
            $this->db->group_end();
        }

        if (!empty($status)) {
            $this->db->where('transaksi.status', $status);
        }

        if (!empty($tanggal)) {
            $this->db->where('transaksi.tanggal', $tanggal);
        }

        if (!empty($id_pelanggan)) {
            $this->db->where('transaksi.id_pelanggan', $id_pelanggan);
        }
    }

    /**
     * Ambil satu transaksi berdasarkan id_transaksi beserta profil lengkap pelanggan.
     *
     * @param  int $id
     * @return array|bool
     */
    public function get_by_id($id)
    {
        $this->db->select('transaksi.*, pelanggan.nama AS nama_pelanggan, pelanggan.no_hp, pelanggan.alamat');
        $this->db->from($this->table);
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');
        $this->db->where('transaksi.id_transaksi', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Ambil transaksi berdasarkan id_pelanggan (untuk riwayat user).
     *
     * @param  int $id_pelanggan
     * @return array
     */
    public function get_by_pelanggan($id_pelanggan)
    {
        $this->db->where('id_pelanggan', $id_pelanggan);
        $this->db->order_by('tanggal', 'DESC');
        $this->db->order_by('id_transaksi', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Cari transaksi berdasarkan kode_transaksi (publik, untuk cek status).
     *
     * @param  string $kode
     * @return array|bool
     */
    public function get_by_kode($kode)
    {
        $this->db->select('transaksi.*, pelanggan.nama AS nama_pelanggan, pelanggan.no_hp, pelanggan.alamat');
        $this->db->from($this->table);
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');
        $this->db->where('transaksi.kode_transaksi', strtoupper(trim($kode)));
        return $this->db->get()->row_array();
    }

    // ─── CREATE & UPDATE ──────────────────────────────────────────────────

    /**
     * Simpan data transaksi baru.
     *
     * @param  array $data
     * @return bool
     */
    public function insert($data)
    {
        // 1. Hitung poin yang didapatkan dari jenis layanan
        $jenis = $data['jenis_layanan'];
        $poin_earned = 0;
        $reward = isset($data['reward_used']) ? $data['reward_used'] : '';
        if (stripos($reward, 'Free Cuci') === FALSE) {
            if ($jenis === 'Cuci Reguler') {
                $poin_earned = 1;
            } elseif ($jenis === 'Cuci Express') {
                $poin_earned = 2;
            } elseif ($jenis === 'Cuci + Setrika') {
                $poin_earned = 3;
            }
        }
        
        $data['poin_earned'] = $poin_earned;

        // Mulai database transaction untuk menjamin konsistensi
        $this->db->trans_start();

        // 2. Simpan transaksi
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        // 3. Tambah poin_earned ke pelanggan dan kurangi poin_used (jika ada)
        $id_pel = $data['id_pelanggan'];
        $poin_used = isset($data['poin_used']) ? intval($data['poin_used']) : 0;
        
        // Update poin pelanggan: poin = poin + poin_earned - poin_used
        $this->db->set('poin', "poin + {$poin_earned} - {$poin_used}", FALSE);
        $this->db->where('id_pelanggan', $id_pel);
        $this->db->update('pelanggan');

        $this->db->trans_complete();

        return $this->db->trans_status() !== FALSE;
    }

    /**
     * Update data transaksi berdasarkan id_transaksi.
     *
     * @param  int   $id
     * @param  array $data
     * @return bool
     */
    public function update($id, $data)
    {
        // Ambil data transaksi lama untuk menghitung ulang selisih poin
        $old = $this->db->where('id_transaksi', $id)->get($this->table)->row_array();
        if (!$old) {
            return false;
        }

        // Tentukan reward_used terbaru
        $reward = isset($data['reward_used']) ? $data['reward_used'] : (isset($old['reward_used']) ? $old['reward_used'] : '');
        $jenis = isset($data['jenis_layanan']) ? $data['jenis_layanan'] : $old['jenis_layanan'];

        // Hitung poin_earned baru
        $poin_earned = 0;
        if (stripos($reward, 'Free Cuci') === FALSE) {
            if ($jenis === 'Cuci Reguler') {
                $poin_earned = 1;
            } elseif ($jenis === 'Cuci Express') {
                $poin_earned = 2;
            } elseif ($jenis === 'Cuci + Setrika') {
                $poin_earned = 3;
            }
        }
        $data['poin_earned'] = $poin_earned;

        $poin_used = isset($data['poin_used']) ? intval($data['poin_used']) : $old['poin_used'];
        $id_pel = isset($data['id_pelanggan']) ? $data['id_pelanggan'] : $old['id_pelanggan'];

        // Jika status diubah menjadi Diambil, maka status pembayaran otomatis diset menjadi Lunas
        if (isset($data['status']) && $data['status'] === 'Diambil') {
            $data['status_pembayaran'] = 'Lunas';
        }

        $this->db->trans_start();

        // 1. Update data transaksi
        $this->db->where('id_transaksi', $id);
        $this->db->update($this->table, $data);

        // 2. Jika pelanggan tidak berubah, update selisihnya
        if ($old['id_pelanggan'] == $id_pel) {
            $diff_poin = ($poin_earned - $old['poin_earned']) - ($poin_used - $old['poin_used']);
            if ($diff_poin != 0) {
                $this->db->set('poin', "poin + ({$diff_poin})", FALSE);
                $this->db->where('id_pelanggan', $id_pel);
                $this->db->update('pelanggan');
            }
        } else {
            // Jika pelanggan berubah, kembalikan poin pelanggan lama dan kurangi pelanggan baru
            // Pelanggan lama: kurangi poin_earned lama, tambah kembali poin_used lama
            $diff_old = $old['poin_used'] - $old['poin_earned'];
            $this->db->set('poin', "poin + ({$diff_old})", FALSE);
            $this->db->where('id_pelanggan', $old['id_pelanggan']);
            $this->db->update('pelanggan');

            // Pelanggan baru: tambah poin_earned baru, kurangi poin_used baru
            $diff_new = $poin_earned - $poin_used;
            $this->db->set('poin', "poin + ({$diff_new})", FALSE);
            $this->db->where('id_pelanggan', $id_pel);
            $this->db->update('pelanggan');
        }

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    /**
     * Update hanya kolom status cucian.
     *
     * @param  int    $id
     * @param  string $status  'Menunggu' | 'Dicuci' | 'Dikeringkan' | 'Disetrika' | 'Selesai' | 'Diambil'
     * @return bool
     */
    public function update_status($id, $status)
    {
        $data = ['status' => $status];
        if ($status === 'Diambil') {
            $data['status_pembayaran'] = 'Lunas';
        }
        $this->db->where('id_transaksi', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Membatalkan transaksi: Mengubah status menjadi 'Dibatalkan', me-refund poin, 
     * dan menyetel poin_earned & poin_used menjadi 0.
     */
    public function batalkan_transaksi($id)
    {
        $old = $this->db->where('id_transaksi', $id)->get($this->table)->row_array();
        if (!$old) {
            return false;
        }

        $this->db->trans_start();

        // Kembalikan poin pelanggan (Refund poin)
        // Saldo poin baru = poin - poin_earned + poin_used
        $diff = $old['poin_used'] - $old['poin_earned'];
        if ($diff != 0) {
            $this->db->set('poin', "poin + ({$diff})", FALSE);
            $this->db->where('id_pelanggan', $old['id_pelanggan']);
            $this->db->update('pelanggan');
        }

        // Set status 'Dibatalkan', poin_earned = 0, poin_used = 0
        $this->db->where('id_transaksi', $id);
        $this->db->update($this->table, [
            'status' => 'Dibatalkan',
            'poin_earned' => 0,
            'poin_used' => 0
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    // ─── DELETE ────────────────────────────────────────────────────────────

    /**
     * Hapus transaksi berdasarkan id_transaksi.
     *
     * @param  int $id
     * @return bool
     */
    public function delete($id)
    {
        $old = $this->db->where('id_transaksi', $id)->get($this->table)->row_array();
        if (!$old) {
            return false;
        }

        $this->db->trans_start();

        // Kembalikan poin pelanggan
        // Saldo poin baru = poin - poin_earned + poin_used
        $diff = $old['poin_used'] - $old['poin_earned'];
        if ($diff != 0) {
            $this->db->set('poin', "poin + ({$diff})", FALSE);
            $this->db->where('id_pelanggan', $old['id_pelanggan']);
            $this->db->update('pelanggan');
        }

        // Hapus transaksi
        $this->db->where('id_transaksi', $id);
        $this->db->delete($this->table);

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    // ─── STATISTIK & COUNT ──────────────────────────────────────────────────

    /**
     * Hitung total semua transaksi.
     *
     * @return int
     */
    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    /**
     * Hitung transaksi berdasarkan status.
     *
     * @param  string $status
     * @return int
     */
    public function count_by_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results($this->table);
    }

    // ─── UTILITY ───────────────────────────────────────────────────────────

    /**
     * Generate kode transaksi otomatis.
     * Format: TRX-YYYYMMDD-NNN
     * Contoh: TRX-20260527-001
     *
     * @return string
     */
    public function generate_kode()
    {
        $tanggal = date('Ymd'); // Contoh: 20260531
        $prefix  = "TRX-{$tanggal}-";

        // Hitung transaksi pada hari ini untuk menentukan nomor urut selanjutnya
        $this->db->like('kode_transaksi', $prefix, 'after');
        $count = $this->db->count_all_results($this->table);

        // Nomor urut 3 digit, dimulai dari 001
        $nomor = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return $prefix . $nomor;
    }
}
