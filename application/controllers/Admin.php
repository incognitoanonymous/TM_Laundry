<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Admin.php — Controller Dashboard Administrator
| -------------------------------------------------------------------
| Menyajikan halaman utama admin dengan statistik 10 metrik bisnis laundry
| (pelanggan, user, transaksi, status detail cucian, dan pendapatan).
*/

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memastikan helper auth dipanggil
        $this->load->helper('auth');
        // Proteksi: halaman ini hanya boleh diakses oleh admin
        is_admin();

        $this->load->model(['Pelanggan_model', 'Transaksi_model', 'User_model']);
        $this->load->library('session');
    }

    /**
     * Tampilkan dashboard utama dengan ringkasan statistik bisnis.
     */
    public function dashboard()
    {
        $data['title']  = 'Dashboard Utama Admin — LaundryKu';
        $data['active'] = 'dashboard';

        // 1. Ringkasan Pelanggan & User
        $data['total_pelanggan'] = $this->Pelanggan_model->count_all();
        $data['total_user']      = $this->User_model->count_all();
        $data['total_transaksi'] = $this->Transaksi_model->count_all();

        // 2. Ringkasan Status Transaksi (6 Status)
        $data['total_menunggu']    = $this->Transaksi_model->count_by_status('Menunggu');
        $data['total_dicuci']      = $this->Transaksi_model->count_by_status('Dicuci');
        $data['total_dikeringkan'] = $this->Transaksi_model->count_by_status('Dikeringkan');
        $data['total_disetrika']   = $this->Transaksi_model->count_by_status('Disetrika');
        $data['total_selesai']     = $this->Transaksi_model->count_by_status('Selesai');
        $data['total_diambil']     = $this->Transaksi_model->count_by_status('Diambil');

        // 3. Ringkasan Pendapatan Total
        $this->db->select_sum('harga');
        $sum = $this->db->get('transaksi')->row_array();
        $data['total_pendapatan'] = $sum['harga'] ?? 0;

        // 4. Ambil 5 transaksi terbaru untuk preview
        $data['transaksi_terbaru'] = $this->Transaksi_model->get_all();

        // Load dengan template layout yang seragam
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates/footer', $data);
    }
}
