<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| User.php — Controller Multi-Peran User
| -------------------------------------------------------------------
| 1. Untuk Admin: Menangani CRUD akun login (tabel users) + Paginasi.
| 2. Untuk Pelanggan: Menangani Dashboard, Riwayat Cucian Pribadi,
|    dan Manajemen Profil Diri (Edit nama/kontak/alamat & ganti password).
*/

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['User_model', 'Pelanggan_model', 'Transaksi_model']);
        $this->load->library(['session', 'form_validation', 'pagination']);
        $this->load->helper(['url', 'form', 'auth']);
    }

    // =========================================================================
    // 🔒 BAGIAN 1: CRUD AKUN USER (HANYA UNTUK ADMIN)
    // =========================================================================

    /**
     * Tampilkan daftar seluruh user dengan paginasi.
     */
    public function index()
    {
        // Proteksi admin
        is_admin();

        $data['title']  = 'Manajemen User Sistem — LaundryKu';
        $data['active'] = 'user';

        // Konfigurasi paginasi CodeIgniter
        $config['base_url']    = base_url('admin/user');
        $config['total_rows']  = $this->User_model->count_all();
        $config['per_page']    = 5; // Tampilkan 5 user per halaman
        $config['uri_segment'] = 3;

        // Styling paginasi yang rapi
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

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['users'] = $this->User_model->get_paginated($config['per_page'], $page);
        $data['pagination_links'] = $this->pagination->create_links();
        $data['offset'] = $page;

        // Load templates
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('admin/user/index', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Form tambah user baru.
     */
    public function tambah()
    {
        is_admin();

        $data['title']  = 'Tambah User Baru — LaundryKu';
        $data['active'] = 'user';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('admin/user/tambah', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Proses simpan user baru.
     */
    public function simpan()
    {
        is_admin();

        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[4]|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');
        $this->form_validation->set_rules('role',     'Role',     'required|in_list[admin,user]');

        $role = $this->input->post('role', TRUE);

        if ($role === 'user') {
            $this->form_validation->set_rules('nama',   'Nama Lengkap',   'required|trim|max_length[100]');
            $this->form_validation->set_rules('no_hp',  'Nomor HP',       'required|trim|max_length[20]');
            $this->form_validation->set_rules('alamat', 'Alamat Lengkap', 'required|trim');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->tambah();
            return;
        }

        // Jalankan Database Transaction untuk menjaga integritas data relasional
        $this->db->trans_start();

        $user_insert = [
            'username' => $this->input->post('username', TRUE),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'role'     => $role,
        ];

        $this->User_model->insert($user_insert);
        $id_user = $this->db->insert_id();

        if ($role === 'user') {
            $pelanggan_insert = [
                'id_user' => $id_user,
                'nama'    => $this->input->post('nama', TRUE),
                'no_hp'   => $this->input->post('no_hp', TRUE),
                'alamat'  => $this->input->post('alamat', TRUE),
            ];
            $this->Pelanggan_model->insert($pelanggan_insert);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal menambahkan user baru.');
        } else {
            $this->session->set_flashdata('success', 'User ' . ($role === 'user' ? 'dan data Pelanggan ' : '') . 'berhasil ditambahkan.');
        }

        redirect('admin/user');
    }

    /**
     * Form edit user.
     */
    public function edit($id)
    {
        is_admin();

        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin/user');
            return;
        }

        $data['title']  = 'Edit User — LaundryKu';
        $data['active'] = 'user';
        $data['user']   = $user;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('admin/user/edit', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Proses update user.
     */
    public function update($id)
    {
        is_admin();

        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin/user');
            return;
        }

        // Validasi unik kecuali username saat ini
        $is_unique = '';
        if ($this->input->post('username') !== $user['username']) {
            $is_unique = '|is_unique[users.username]';
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[4]' . $is_unique);
        $this->form_validation->set_rules('role',     'Role',     'required|in_list[admin,user]');

        if (!empty($this->input->post('password'))) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[4]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
            return;
        }

        $update = [
            'username' => $this->input->post('username', TRUE),
            'role'     => $this->input->post('role', TRUE),
        ];

        // Jika password diisi, update password
        if (!empty($this->input->post('password'))) {
            $update['password'] = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        }

        if ($this->User_model->update($id, $update)) {
            $this->session->set_flashdata('success', 'User berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui user.');
        }

        redirect('admin/user');
    }

    /**
     * Hapus user.
     */
    public function hapus($id)
    {
        is_admin();

        // Cegah menghapus diri sendiri
        if ($id == $this->session->userdata('id_user')) {
            $this->session->set_flashdata('error', 'Gagal! Anda tidak dapat menghapus akun Anda sendiri.');
            redirect('admin/user');
            return;
        }

        if ($this->User_model->delete($id)) {
            $this->session->set_flashdata('success', 'User berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user.');
        }

        redirect('admin/user');
    }


    // =========================================================================
    // 👤 BAGIAN 2: HALAMAN PELANGGAN/USER (DASHBOARD, RIWAYAT, PROFIL)
    // =========================================================================

    /**
     * Dashboard Pelanggan.
     */
    public function dashboard()
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);

        if (!$pelanggan) {
            // Pelanggan belum dibuat datanya di profil
            $data['title']     = 'Dashboard Pelanggan — LaundryKu';
            $data['active']    = 'dashboard';
            $data['pelanggan'] = NULL;
            $data['transaksi'] = [];
            $data['total']     = 0;
            $data['proses']    = 0;
            $data['selesai']   = 0;
            $data['diambil']   = 0;
        } else {
            $transaksi = $this->Transaksi_model->get_by_pelanggan($pelanggan['id_pelanggan']);
            
            $data['title']     = 'Dashboard Pelanggan — LaundryKu';
            $data['active']    = 'dashboard';
            $data['pelanggan'] = $pelanggan;
            $data['transaksi'] = $transaksi;
            $data['total']     = count($transaksi);
            
            // Kategori status proses (Menunggu, Dicuci, Dikeringkan, Disetrika)
            $proses_statuses = ['Menunggu', 'Dicuci', 'Dikeringkan', 'Disetrika'];
            $data['proses']    = count(array_filter($transaksi, fn($t) => in_array($t['status'], $proses_statuses)));
            $data['selesai']   = count(array_filter($transaksi, fn($t) => $t['status'] === 'Selesai'));
            $data['diambil']   = count(array_filter($transaksi, fn($t) => $t['status'] === 'Diambil'));
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('user/dashboard', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Riwayat laundry milik pribadi.
     */
    public function riwayat()
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);

        if (!$pelanggan) {
            $this->session->set_flashdata('error', 'Profil pelanggan Anda tidak ditemukan.');
            redirect('user/dashboard');
            return;
        }

        $data['title']     = 'Riwayat Transaksi Saya — LaundryKu';
        $data['active']    = 'riwayat';
        $data['pelanggan'] = $pelanggan;
        $data['transaksi'] = $this->Transaksi_model->get_by_pelanggan($pelanggan['id_pelanggan']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('user/riwayat', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Detail satu transaksi laundry milik pribadi.
     */
    public function detail($id)
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);
        $transaksi = $this->Transaksi_model->get_by_id($id);

        // Proteksi: transaksi harus ada dan milik pelanggan yang sedang login
        if (!$transaksi || !$pelanggan || $transaksi['id_pelanggan'] != $pelanggan['id_pelanggan']) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan atau bukan milik Anda.');
            redirect('user/riwayat');
            return;
        }

        $data['title']     = 'Detail Transaksi — LaundryKu';
        $data['active']    = 'riwayat';
        $data['transaksi'] = $transaksi;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('user/detail', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Halaman profil saya (Melihat profil, edit data diri, dan ganti password).
     */
    public function profil()
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);
        $user = $this->User_model->get_by_id($id_user);

        if (!$pelanggan) {
            $this->session->set_flashdata('error', 'Profil pelanggan Anda tidak ditemukan.');
            redirect('user/dashboard');
            return;
        }

        $data['title']     = 'Profil Saya — LaundryKu';
        $data['active']    = 'profil';
        $data['pelanggan'] = $pelanggan;
        $data['user']      = $user;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('user/profil', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Proses memperbarui profil diri & ganti password.
     */
    public function update_profil()
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);

        if (!$pelanggan) {
            $this->session->set_flashdata('error', 'Profil tidak ditemukan.');
            redirect('user/dashboard');
            return;
        }

        // Aturan validasi
        $this->form_validation->set_rules('nama',   'Nama Lengkap',   'required|trim|max_length[100]');
        $this->form_validation->set_rules('no_hp',  'Nomor HP',       'required|trim|max_length[20]');
        $this->form_validation->set_rules('alamat', 'Alamat Lengkap', 'required|trim');

        // Jika password diisi
        if (!empty($this->input->post('password'))) {
            $this->form_validation->set_rules('password',      'Password Baru',           'min_length[4]');
            $this->form_validation->set_rules('konf_password', 'Konfirmasi Password Baru', 'matches[password]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->profil();
            return;
        }

        // 1. Simpan data update ke tabel pelanggan
        $update_pelanggan = [
            'nama'   => $this->input->post('nama', TRUE),
            'no_hp'  => $this->input->post('no_hp', TRUE),
            'alamat' => $this->input->post('alamat', TRUE),
        ];
        $this->Pelanggan_model->update($pelanggan['id_pelanggan'], $update_pelanggan);

        // 2. Jika password diisi, update di tabel users
        if (!empty($this->input->post('password'))) {
            $update_user = [
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
            ];
            $this->User_model->update($id_user, $update_user);
        }

        $this->session->set_flashdata('success', 'Profil dan informasi akun Anda berhasil diperbarui.');
        redirect('user/profil');
    }

    /**
     * Tampilkan halaman formulir pemesanan laundry mandiri (User).
     */
    public function pesan()
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);

        if (!$pelanggan) {
            $this->session->set_flashdata('error', 'Profil pelanggan Anda belum lengkap. Silakan hubungi admin atau lengkapi profil.');
            redirect('user/dashboard');
            return;
        }

        $data['title']     = 'Pesan Laundry Mandiri — LaundryKu';
        $data['active']    = 'pesan';
        $data['pelanggan'] = $pelanggan;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('user/pesan', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Proses simpan pesanan laundry mandiri (User).
     */
    public function proses_pesan()
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);

        if (!$pelanggan) {
            $this->session->set_flashdata('error', 'Profil pelanggan Anda tidak ditemukan.');
            redirect('user/dashboard');
            return;
        }

        $this->form_validation->set_rules('jenis_layanan', 'Jenis Layanan', 'required|trim|in_list[Cuci Reguler,Cuci Express,Cuci + Setrika]');
        $this->form_validation->set_rules('berat',         'Berat',         'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('tanggal',       'Tanggal',        'required');

        if ($this->form_validation->run() === FALSE) {
            $this->pesan();
            return;
        }

        $jenis_layanan = $this->input->post('jenis_layanan', TRUE);
        $berat         = floatval($this->input->post('berat', TRUE));

        // Kalkulasi tarif otomatis
        $tarif = 0;
        if ($jenis_layanan === 'Cuci Reguler') {
            $tarif = 7000;
        } elseif ($jenis_layanan === 'Cuci Express') {
            $tarif = 10000;
        } elseif ($jenis_layanan === 'Cuci + Setrika') {
            $tarif = 12000;
        }

        $harga = $berat * $tarif;

        $insert = [
            'kode_transaksi' => $this->Transaksi_model->generate_kode(),
            'id_pelanggan'   => $pelanggan['id_pelanggan'],
            'jenis_layanan'  => $jenis_layanan,
            'berat'          => $berat,
            'harga'          => $harga,
            'status'         => 'Menunggu',
            'tanggal'        => $this->input->post('tanggal', TRUE),
        ];

        if ($this->Transaksi_model->insert($insert)) {
            $this->session->set_flashdata('success', 'Pesanan laundry #' . $insert['kode_transaksi'] . ' berhasil dikirim.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengirimkan pesanan laundry.');
        }

        redirect('user/riwayat');
    }
}
