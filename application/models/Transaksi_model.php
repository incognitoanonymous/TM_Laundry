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
        $this->db->select('transaksi.*, pelanggan.nama AS nama_pelanggan');
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
        $this->db->select('transaksi.*, pelanggan.nama AS nama_pelanggan');
        $this->db->from($this->table);
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');

        $this->_apply_search_filters($keyword, $status, $tanggal, $id_pelanggan);

        $this->db->limit($limit, $start);
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
        return $this->db->insert($this->table, $data);
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
        $this->db->where('id_transaksi', $id);
        return $this->db->update($this->table, $data);
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
        $this->db->where('id_transaksi', $id);
        return $this->db->update($this->table, ['status' => $status]);
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
        return $this->db->delete($this->table, ['id_transaksi' => $id]);
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
