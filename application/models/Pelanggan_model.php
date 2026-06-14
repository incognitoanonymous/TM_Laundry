<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Pelanggan_model.php — Model Pelanggan LaundryKu
| -------------------------------------------------------------------
| Menyediakan operasi database untuk CRUD pelanggan, JOIN data user,
| paginasi pelanggan, dan list user yang belum dikaitkan ke pelanggan.
*/

class Pelanggan_model extends CI_Model {

    private $table = 'pelanggan';

    // ─── READ (Daftar & Detail) ───────────────────────────────────────────

    /**
     * Ambil semua data pelanggan beserta username dari tabel users.
     * Menggunakan JOIN agar username juga tampil di tabel admin.
     *
     * @return array
     */
    public function get_all()
    {
        $this->db->select('pelanggan.*, users.username');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id_user = pelanggan.id_user', 'left');
        $this->db->order_by('pelanggan.id_pelanggan', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil data pelanggan berpaginasi.
     *
     * @param  int $limit
     * @param  int $start
     * @return array
     */
    public function get_paginated($limit, $start)
    {
        $this->db->select('pelanggan.*, users.username');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id_user = pelanggan.id_user', 'left');
        $this->db->limit($limit, $start);
        $this->db->order_by('pelanggan.id_pelanggan', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil satu pelanggan berdasarkan id_pelanggan.
     *
     * @param  int $id
     * @return array|bool
     */
    public function get_by_id($id)
    {
        $this->db->select('pelanggan.*, users.username');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id_user = pelanggan.id_user', 'left');
        $this->db->where('pelanggan.id_pelanggan', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Ambil data pelanggan berdasarkan id_user (untuk halaman user).
     *
     * @param  int $id_user
     * @return array|bool
     */
    public function get_by_user($id_user)
    {
        $query = $this->db->get_where($this->table, ['id_user' => $id_user]);
        return $query->row_array();
    }

    /**
     * Ambil semua username dari tabel users yang ber-role 'user' dan belum memiliki relasi di pelanggan.
     * Digunakan untuk dropdown saat tambah/edit pelanggan agar relasi tetap 1-ke-1.
     *
     * @param  int $current_id_user  User id yang sedang diedit (agar tetap masuk opsi dropdown saat edit)
     * @return array
     */
    public function get_users_available($current_id_user = NULL)
    {
        $this->db->select('users.*');
        $this->db->from('users');
        $this->db->where('users.role', 'user');
        
        // JOIN dengan pelanggan untuk mendeteksi yang sudah terikat
        $this->db->join('pelanggan', 'pelanggan.id_user = users.id_user', 'left');
        
        if ($current_id_user != NULL) {
            $this->db->group_start();
            $this->db->where('pelanggan.id_pelanggan', NULL);
            $this->db->or_where('users.id_user', $current_id_user);
            $this->db->group_end();
        } else {
            $this->db->where('pelanggan.id_pelanggan', NULL);
        }
        
        return $this->db->get()->result_array();
    }

    // ─── CREATE & UPDATE ──────────────────────────────────────────────────

    /**
     * Simpan data pelanggan baru ke database.
     *
     * @param  array $data
     * @return bool
     */
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update data pelanggan berdasarkan id_pelanggan.
     *
     * @param  int   $id
     * @param  array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->db->where('id_pelanggan', $id);
        return $this->db->update($this->table, $data);
    }

    // ─── DELETE ────────────────────────────────────────────────────────────

    /**
     * Hapus pelanggan berdasarkan id_pelanggan.
     *
     * @param  int $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, ['id_pelanggan' => $id]);
    }

    // ─── COUNT ─────────────────────────────────────────────────────────────

    /**
     * Hitung total jumlah pelanggan.
     *
     * @return int
     */
    public function count_all()
    {
        return $this->db->count_all($this->table);
    }
}
