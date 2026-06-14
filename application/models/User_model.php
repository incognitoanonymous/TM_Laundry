<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| User_model.php — Model Manajemen User LaundryKu
| -------------------------------------------------------------------
| Menyediakan operasi database untuk CRUD tabel users.
*/

class User_model extends CI_Model {

    private $table = 'users';

    /**
     * Ambil semua data user.
     *
     * @return array
     */
    public function get_all()
    {
        $this->db->order_by('id_user', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Ambil data user berhalaman (paginasi).
     *
     * @param  int $limit
     * @param  int $start
     * @return array
     */
    public function get_paginated($limit, $start)
    {
        $this->db->limit($limit, $start);
        $this->db->order_by('id_user', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Ambil data satu user berdasarkan ID.
     *
     * @param  int $id
     * @return array|bool
     */
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id_user' => $id])->row_array();
    }

    /**
     * Simpan user baru ke database.
     *
     * @param  array $data
     * @return bool
     */
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update data user berdasarkan ID.
     *
     * @param  int   $id
     * @param  array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $this->db->where('id_user', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hapus data user berdasarkan ID.
     *
     * @param  int $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, ['id_user' => $id]);
    }

    /**
     * Hitung total seluruh akun user.
     *
     * @return int
     */
    public function count_all()
    {
        return $this->db->count_all($this->table);
    }
}
