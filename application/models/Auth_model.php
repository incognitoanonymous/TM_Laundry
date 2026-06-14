<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| ---------------------------------------------------------------
| Auth_model.php
| ---------------------------------------------------------------
| Model untuk autentikasi: login & ambil data user.
*/

class Auth_model extends CI_Model {

    /**
     * Cek apakah username ada di database.
     * Mengembalikan data user (array) atau FALSE jika tidak ada.
     *
     * @param  string $username
     * @return array|bool
     */
    public function get_user_by_username($username)
    {
        $query = $this->db->get_where('users', ['username' => $username]);
        return $query->row_array();
    }

    /**
     * Ambil data user berdasarkan id_user.
     *
     * @param  int $id_user
     * @return array|bool
     */
    public function get_user_by_id($id_user)
    {
        $query = $this->db->get_where('users', ['id_user' => $id_user]);
        return $query->row_array();
    }
}
