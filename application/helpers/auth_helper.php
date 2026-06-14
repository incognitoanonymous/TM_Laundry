<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| auth_helper.php — Helper Autentikasi LaundryKu
| -------------------------------------------------------------------
| Membantu proteksi halaman internal berdasarkan status login dan role.
*/

if (!function_exists('is_logged_in')) {
    /**
     * Cek apakah user sudah login.
     * Jika belum, simpan pesan flash dan redirect ke halaman login.
     */
    function is_logged_in()
    {
        $CI =& get_instance();
        if (!$CI->session->userdata('logged_in')) {
            $CI->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }
    }
}

if (!function_exists('is_admin')) {
    /**
     * Proteksi halaman admin.
     * Harus login dan ber-role 'admin'. Jika role = 'user', redirect ke dashboard user.
     */
    function is_admin()
    {
        $CI =& get_instance();
        is_logged_in();
        if ($CI->session->userdata('role') !== 'admin') {
            $CI->session->set_flashdata('error', 'Anda tidak memiliki hak akses ke halaman admin.');
            redirect('user/dashboard');
        }
    }
}

if (!function_exists('is_user')) {
    /**
     * Proteksi halaman user/pelanggan.
     * Harus login dan ber-role 'user'. Jika role = 'admin', redirect ke dashboard admin.
     */
    function is_user()
    {
        $CI =& get_instance();
        is_logged_in();
        if ($CI->session->userdata('role') !== 'user') {
            $CI->session->set_flashdata('error', 'Admin diarahkan ke panel dashboard admin.');
            redirect('admin/dashboard');
        }
    }
}
