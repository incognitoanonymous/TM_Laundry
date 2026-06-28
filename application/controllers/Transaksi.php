<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Transaksi.php — Controller Transaksi LaundryKu
| -------------------------------------------------------------------
| Menangani operasi CRUD transaksi laundry, pencarian multi-kolom,
| filter kustom (status, tanggal, pelanggan), paginasi dinamis,
| kalkulasi harga otomatis berbasis tarif resmi, dan pembaruan 6 status cucian.
*/

class Transaksi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('auth');
        // Proteksi admin
        is_admin();

        $this->load->model(['Transaksi_model', 'Pelanggan_model']);
        $this->load->library(['session', 'form_validation', 'pagination']);
        $this->load->helper(['url', 'form']);
    }

    // ─── INDEX: Daftar Transaksi (Paginasi, Pencarian, & Filter) ─────────────

    /**
     * Tampilkan data transaksi laundry dengan fitur pencarian, filter, dan paginasi.
     */
    public function index($page = 0)
    {
        $data['title']  = 'Manajemen Transaksi — LaundryKu';
        $data['active'] = 'transaksi';

        // Ambil input pencarian & filter dari query string (GET) agar pagination link bekerja optimal
        $keyword       = $this->input->get('keyword', TRUE);
        $status        = $this->input->get('status', TRUE);
        $tanggal       = $this->input->get('tanggal', TRUE);
        $id_pelanggan  = $this->input->get('id_pelanggan', TRUE);

        // Bersihkan variabel kosong
        $keyword      = !empty($keyword) ? trim($keyword) : NULL;
        $status       = !empty($status) ? trim($status) : NULL;
        $tanggal      = !empty($tanggal) ? trim($tanggal) : NULL;
        $id_pelanggan = !empty($id_pelanggan) ? intval($id_pelanggan) : NULL;

        // Ambil data pelanggan untuk filter dropdown
        $data['pelanggan_list'] = $this->Pelanggan_model->get_all();

        // Menyimpan nilai filter saat ini ke view
        $data['keyword']      = $keyword;
        $data['status_filter']= $status;
        $data['tanggal']      = $tanggal;
        $data['pelanggan_id'] = $id_pelanggan;

        // Hitung total baris yang cocok dengan filter untuk konfigurasi paginasi
        $total_rows = $this->Transaksi_model->count_filtered($keyword, $status, $tanggal, $id_pelanggan);

        // Konfigurasi Paginasi
        // Mempertahankan query string GET di link paginasi (sangat penting!)
        $config['reuse_query_string'] = TRUE;
        $config['base_url']    = base_url('admin/transaksi');
        $config['total_rows']  = $total_rows;
        $config['per_page']    = 5; // Tampilkan 5 transaksi per halaman
        $config['uri_segment'] = 3;

        // Styling Paginasi
        $config['full_tag_open']   = '<div class="pagination" style="display:flex; gap:5px; margin-top:20px; justify-content:center;">';
        $config['full_tag_close']  = '</div>';
        $config['first_link']      = 'Awal';
        $config['last_link']       = 'Akhir';
        $config['next_link']       = 'Selanjutnya &raquo;';
        $config['prev_link']       = '&laquo; Sebelumnya';
        $config['num_tag_open']    = '<span style="padding: 6px 12px; background:var(--white); border:1.5px solid var(--gray-200); border-radius:var(--radius); font-size:.85rem; font-weight:500;">';
        $config['num_tag_close']   = '</span>';
        $config['cur_tag_open']    = '<span style="padding: 6px 12px; background:var(--primary); color:var(--white); border:1.5px solid var(--primary); border-radius:var(--radius); font-size:.85rem; font-weight:600;">';
        $config['cur_tag_close']   = '</span>';

        $this->pagination->initialize($config);

        // Cari page offset dari parameter atau fallback ke segment 3
        $page = ($page) ? intval($page) : (($this->uri->segment(3)) ? intval($this->uri->segment(3)) : 0);
        
        $data['transaksi'] = $this->Transaksi_model->get_paginated($config['per_page'], $page, $keyword, $status, $tanggal, $id_pelanggan);
        $data['pagination_links'] = $this->pagination->create_links();
        $data['offset'] = $page;
        $data['total_rows'] = $total_rows;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('transaksi/index', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Ekspor data transaksi ke format Excel (custom styling).
     */
    public function export_excel()
    {
        // Ambil filter dari query string
        $keyword       = $this->input->get('keyword', TRUE);
        $status        = $this->input->get('status', TRUE);
        $tanggal       = $this->input->get('tanggal', TRUE);
        $id_pelanggan  = $this->input->get('id_pelanggan', TRUE);

        $keyword      = !empty($keyword) ? trim($keyword) : NULL;
        $status       = !empty($status) ? trim($status) : NULL;
        $tanggal      = !empty($tanggal) ? trim($tanggal) : NULL;
        $id_pelanggan = !empty($id_pelanggan) ? intval($id_pelanggan) : NULL;

        $data['transaksi'] = $this->Transaksi_model->get_filtered($keyword, $status, $tanggal, $id_pelanggan);
        
        // Atur header download Excel (.xls)
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Transaksi_LaundryKu_" . date('Ymd_His') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('transaksi/excel', $data);
    }

    // ─── TAMBAH: Form Tambah Transaksi ─────────────────────────────────────

    /**
     * Tampilkan form tambah transaksi baru.
     */
    public function tambah()
    {
        $data['title']          = 'Tambah Transaksi Baru — LaundryKu';
        $data['active']         = 'transaksi';
        $data['pelanggan_list'] = $this->Pelanggan_model->get_all();
        $data['kode_otomatis']  = $this->Transaksi_model->generate_kode();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('transaksi/tambah', $data);
        $this->load->view('templates/footer', $data);
    }

    // ─── SIMPAN: Proses Simpan Transaksi ───────────────────────────────────

    /**
     * Proses hitung harga otomatis & simpan transaksi baru.
     */
    public function simpan()
    {
        $this->form_validation->set_rules('id_pelanggan',  'Pelanggan',      'required|is_natural_no_zero');
        $this->form_validation->set_rules('jenis_layanan', 'Jenis Layanan',  'required|trim|in_list[Cuci Reguler,Cuci Express,Cuci + Setrika]');
        $this->form_validation->set_rules('berat',         'Berat (kg)',     'required|numeric');
        $this->form_validation->set_rules('tanggal',       'Tanggal',        'required');
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'required|trim|in_list[Tunai,QRIS]');
        $this->form_validation->set_rules('status_pembayaran', 'Status Pembayaran', 'required|trim|in_list[Belum Bayar,Menunggu Verifikasi,Lunas]');

        $is_jemput = $this->input->post('is_jemput') ? 1 : 0;
        if ($is_jemput) {
            $this->form_validation->set_rules('alamat_jemput', 'Alamat Jemput', 'required|trim');
            $this->form_validation->set_rules('status_jemput', 'Status Jemput', 'required|trim|in_list[Menunggu Penjemputan,Sudah Dijemput]');
        }

        $is_antar = $this->input->post('is_antar') ? 1 : 0;
        if ($is_antar) {
            $this->form_validation->set_rules('alamat_antar', 'Alamat Antar', 'required|trim');
            $this->form_validation->set_rules('status_antar', 'Status Antar', 'required|trim|in_list[Menunggu Pengantaran,Sudah Diantarkan]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->tambah();
            return;
        }

        $jenis_layanan = $this->input->post('jenis_layanan', TRUE);
        $berat         = floatval($this->input->post('berat', TRUE));

        // Hitung harga otomatis berbasis server-side tarif
        $tarif = $this->_get_tarif($jenis_layanan);
        $harga = $berat * $tarif;

        $ongkir_jemput = $is_jemput ? ($berat * 10000.00) : 0.00;
        $ongkir_antar  = $is_antar ? ($berat * 10000.00) : 0.00;

        $insert = [
            'kode_transaksi'    => $this->Transaksi_model->generate_kode(),
            'id_pelanggan'      => $this->input->post('id_pelanggan',  TRUE),
            'jenis_layanan'     => $jenis_layanan,
            'berat'             => $berat,
            'harga'             => $harga + $ongkir_jemput + $ongkir_antar, // Hasil kalkulasi otomatis + ongkir
            'status'            => 'Menunggu', // Status default awal
            'tanggal'           => $this->input->post('tanggal',       TRUE),
            'is_jemput'         => $is_jemput,
            'alamat_jemput'     => $is_jemput ? $this->input->post('alamat_jemput', TRUE) : NULL,
            'gps_jemput'        => $is_jemput ? $this->input->post('gps_jemput', TRUE) : NULL,
            'status_jemput'     => $is_jemput ? $this->input->post('status_jemput', TRUE) : 'Sudah Dijemput',
            'ongkir_jemput'     => $ongkir_jemput,
            'is_antar'          => $is_antar,
            'alamat_antar'      => $is_antar ? $this->input->post('alamat_antar', TRUE) : NULL,
            'gps_antar'         => $is_antar ? $this->input->post('gps_antar', TRUE) : NULL,
            'status_antar'      => $is_antar ? $this->input->post('status_antar', TRUE) : 'Sudah Diantarkan',
            'ongkir_antar'      => $ongkir_antar,
            'metode_pembayaran' => $this->input->post('metode_pembayaran', TRUE),
            'status_pembayaran' => $this->input->post('status_pembayaran', TRUE),
            'catatan'           => $this->input->post('catatan', TRUE) ? trim($this->input->post('catatan', TRUE)) : NULL,
            'uang_diterima'     => $this->input->post('uang_diterima') ? 1 : 0,
        ];

        if ($this->Transaksi_model->insert($insert)) {
            $this->session->set_flashdata('success', 'Transaksi #' . $insert['kode_transaksi'] . ' berhasil ditambahkan. Harga: Rp ' . number_format($harga, 0, ',', '.'));
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data transaksi.');
        }

        redirect('admin/transaksi');
    }

    // ─── EDIT: Form Edit Transaksi ──────────────────────────────────────────

    /**
     * Tampilkan form edit transaksi.
     */
    public function edit($id)
    {
        $transaksi = $this->Transaksi_model->get_by_id($id);

        if (!$transaksi) {
            $this->session->set_flashdata('error', 'Data transaksi tidak ditemukan.');
            redirect('admin/transaksi');
            return;
        }

        $data['title']          = 'Edit Transaksi — LaundryKu';
        $data['active']         = 'transaksi';
        $data['transaksi']      = $transaksi;
        $data['pelanggan_list'] = $this->Pelanggan_model->get_all();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('transaksi/edit', $data);
        $this->load->view('templates/footer', $data);
    }

    // ─── UPDATE: Proses Update Transaksi ───────────────────────────────────

    /**
     * Hitung ulang harga otomatis & simpan perubahan transaksi.
     */
    public function update($id)
    {
        $this->form_validation->set_rules('id_pelanggan',  'Pelanggan',      'required|is_natural_no_zero');
        $this->form_validation->set_rules('jenis_layanan', 'Jenis Layanan',  'required|trim|in_list[Cuci Reguler,Cuci Express,Cuci + Setrika]');
        $this->form_validation->set_rules('berat',         'Berat (kg)',     'required|numeric');
        $this->form_validation->set_rules('status',        'Status',         'required|trim|in_list[Menunggu,Dicuci,Dikeringkan,Disetrika,Selesai,Diambil]');
        $this->form_validation->set_rules('tanggal',       'Tanggal',        'required');
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'required|trim|in_list[Tunai,QRIS]');
        $this->form_validation->set_rules('status_pembayaran', 'Status Pembayaran', 'required|trim|in_list[Belum Bayar,Menunggu Verifikasi,Lunas]');

        $is_jemput = $this->input->post('is_jemput') ? 1 : 0;
        if ($is_jemput) {
            $this->form_validation->set_rules('alamat_jemput', 'Alamat Jemput', 'required|trim');
            $this->form_validation->set_rules('status_jemput', 'Status Jemput', 'required|trim|in_list[Menunggu Penjemputan,Sudah Dijemput]');
        }

        $is_antar = $this->input->post('is_antar') ? 1 : 0;
        if ($is_antar) {
            $this->form_validation->set_rules('alamat_antar', 'Alamat Antar', 'required|trim');
            $this->form_validation->set_rules('status_antar', 'Status Antar', 'required|trim|in_list[Menunggu Pengantaran,Sudah Diantarkan]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
            return;
        }

        $jenis_layanan = $this->input->post('jenis_layanan', TRUE);
        $status        = $this->input->post('status', TRUE);
        if ($status === 'Disetrika' && $jenis_layanan !== 'Cuci + Setrika') {
            $this->session->set_flashdata('error', 'Status "Disetrika" hanya diperbolehkan untuk layanan Cuci + Setrika.');
            $this->edit($id);
            return;
        }
        $berat         = floatval($this->input->post('berat', TRUE));

        // Ambil data transaksi lama untuk memeriksa penggunaan reward
        $transaksi = $this->Transaksi_model->get_by_id($id);
        if (!$transaksi) {
            $this->session->set_flashdata('error', 'Data transaksi tidak ditemukan.');
            redirect('admin/transaksi');
            return;
        }

        // Kalkulasi ulang harga otomatis
        $tarif = $this->_get_tarif($jenis_layanan);
        $harga = $berat * $tarif;

        // Cek dan terapkan diskon reward dari transaksi asli
        $reward = isset($transaksi['reward_used']) ? $transaksi['reward_used'] : '';
        if (stripos($reward, 'Free Cuci') !== FALSE) {
            $harga = 0.00;
        }

        $ongkir_jemput = $is_jemput ? ($berat * 10000.00) : 0.00;
        $ongkir_antar  = $is_antar ? ($berat * 10000.00) : 0.00;

        if (stripos($reward, 'Free Kurir') !== FALSE) {
            $ongkir_jemput = 0.00;
            $ongkir_antar = 0.00;
        }

        $update = [
            'id_pelanggan'      => $this->input->post('id_pelanggan',  TRUE),
            'jenis_layanan'     => $jenis_layanan,
            'berat'             => $berat,
            'harga'             => $harga + $ongkir_jemput + $ongkir_antar,
            'status'            => $this->input->post('status',        TRUE),
            'tanggal'           => $this->input->post('tanggal',       TRUE),
            'is_jemput'         => $is_jemput,
            'alamat_jemput'     => $is_jemput ? $this->input->post('alamat_jemput', TRUE) : NULL,
            'gps_jemput'        => $is_jemput ? $this->input->post('gps_jemput', TRUE) : NULL,
            'status_jemput'     => $is_jemput ? $this->input->post('status_jemput', TRUE) : 'Sudah Dijemput',
            'ongkir_jemput'     => $ongkir_jemput,
            'is_antar'          => $is_antar,
            'alamat_antar'      => $is_antar ? $this->input->post('alamat_antar', TRUE) : NULL,
            'gps_antar'         => $is_antar ? $this->input->post('gps_antar', TRUE) : NULL,
            'status_antar'      => $is_antar ? $this->input->post('status_antar', TRUE) : 'Sudah Diantarkan',
            'ongkir_antar'      => $ongkir_antar,
            'metode_pembayaran' => $this->input->post('metode_pembayaran', TRUE),
            'status_pembayaran' => $this->input->post('status_pembayaran', TRUE),
            'catatan'           => $this->input->post('catatan', TRUE) ? trim($this->input->post('catatan', TRUE)) : NULL,
            'uang_diterima'     => $this->input->post('uang_diterima') ? 1 : 0,
        ];

        if ($this->Transaksi_model->update($id, $update)) {
            $this->session->set_flashdata('success', 'Data transaksi berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data transaksi.');
        }

        redirect('admin/transaksi');
    }

    // ─── UPDATE STATUS: Ubah Cepat Status Cucian ────────────────────────────

    /**
     * Mengubah cepat status transaksi (dropdown inline / tombol cepat).
     */
    public function update_status($id)
    {
        $status = $this->input->post('status', TRUE);
        $allowed = ['Menunggu', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];

        if (!in_array($status, $allowed)) {
            $this->session->set_flashdata('error', 'Status cucian tidak valid.');
            redirect('admin/transaksi');
            return;
        }

        $transaksi = $this->Transaksi_model->get_by_id($id);
        if (!$transaksi) {
            $this->session->set_flashdata('error', 'Data transaksi tidak ditemukan.');
            redirect('admin/transaksi');
            return;
        }

        if ($status === 'Disetrika' && $transaksi['jenis_layanan'] !== 'Cuci + Setrika') {
            $this->session->set_flashdata('error', 'Status "Disetrika" hanya diperbolehkan untuk layanan Cuci + Setrika.');
            redirect('admin/transaksi');
            return;
        }

        if ($this->Transaksi_model->update_status($id, $status)) {
            $this->session->set_flashdata('success', 'Status cucian transaksi berhasil diperbarui menjadi: ' . $status);
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui status cucian.');
        }

        redirect('admin/transaksi');
    }

    /**
     * Memverifikasi pembayaran QRIS transaksi secara instan (dari sisi Admin).
     */
    public function verifikasi_bayar($id)
    {
        $transaksi = $this->Transaksi_model->get_by_id($id);

        if (!$transaksi) {
            $this->session->set_flashdata('error', 'Data transaksi tidak ditemukan.');
            redirect('admin/transaksi');
            return;
        }

        $update = [
            'status_pembayaran' => 'Lunas'
        ];

        if ($this->Transaksi_model->update($id, $update)) {
            $this->session->set_flashdata('success', 'Transaksi #' . $transaksi['kode_transaksi'] . ' berhasil diverifikasi Lunas.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memverifikasi pembayaran.');
        }

        redirect('admin/transaksi');
    }

    /**
     * Mengonfirmasi uang setoran pembayaran secara fisik sudah masuk/diterima kasir (Admin).
     */
    public function konfirmasi_terima_uang($id)
    {
        $transaksi = $this->Transaksi_model->get_by_id($id);

        if (!$transaksi) {
            $this->session->set_flashdata('error', 'Data transaksi tidak ditemukan.');
            redirect('admin/transaksi');
            return;
        }

        $update = [
            'uang_diterima' => 1
        ];

        // Jika dia konfirmasi terima uang, otomatis status pembayaran diset Lunas jika sebelumnya belum Lunas
        if ($transaksi['status_pembayaran'] !== 'Lunas') {
            $update['status_pembayaran'] = 'Lunas';
        }

        if ($this->Transaksi_model->update($id, $update)) {
            $this->session->set_flashdata('success', 'Uang pembayaran transaksi #' . $transaksi['kode_transaksi'] . ' berhasil dikonfirmasi diterima Kasir/Admin.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengonfirmasi penerimaan uang.');
        }

        redirect('admin/transaksi');
    }

    // ─── HAPUS: Hapus Transaksi ─────────────────────────────────────────────

    /**
     * Hapus transaksi dari database.
     */
    public function hapus($id)
    {
        if ($this->Transaksi_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data transaksi berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data transaksi.');
        }

        redirect('admin/transaksi');
    }

    // ─── HELPER PRIVATE: Hitung Tarif Layanan ───────────────────────────────

    /**
     * Tarif resmi:
     * Cuci Reguler   : Rp 7.000 / kg
     * Cuci Express   : Rp 10.000 / kg
     * Cuci + Setrika : Rp 12.000 / kg
     */
    private function _get_tarif($layanan)
    {
        switch ($layanan) {
            case 'Cuci Reguler':
                return 7000;
            case 'Cuci Express':
                return 10000;
            case 'Cuci + Setrika':
                return 12000;
            default:
                return 0;
        }
    }
}
