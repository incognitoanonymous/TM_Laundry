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
    public function index($page = 0)
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

        // Cari page offset dari parameter atau fallback ke segment 3
        $page = ($page) ? intval($page) : (($this->uri->segment(3)) ? intval($this->uri->segment(3)) : 0);
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

        $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric|min_length[4]|max_length[50]|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('role',     'Role',     'required|in_list[admin,user]');

        if ($this->form_validation->run() === FALSE) {
            $this->tambah();
            return;
        }

        $insert = [
            'username' => $this->input->post('username', TRUE),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'role'     => $this->input->post('role', TRUE),
        ];

        if ($this->User_model->insert($insert)) {
            $this->session->set_flashdata('success', 'User berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan user.');
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

        $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric|min_length[4]|max_length[50]' . $is_unique);
        $this->form_validation->set_rules('role',     'Role',     'required|in_list[admin,user]');

        if (!empty($this->input->post('password'))) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[4]|max_length[255]');
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
        $this->form_validation->set_rules('no_hp',  'Nomor HP',       'required|trim|numeric|min_length[8]|max_length[15]', [
            'numeric' => 'Nomor HP harus berupa angka.',
            'min_length' => 'Nomor HP minimal 8 angka.',
            'max_length' => 'Nomor HP maksimal 15 angka.'
        ]);
        $this->form_validation->set_rules('alamat', 'Alamat Lengkap', 'required|trim');

        // Jika password diisi
        if (!empty($this->input->post('password'))) {
            $this->form_validation->set_rules('password',      'Password Baru',           'min_length[4]|max_length[255]');
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
     * Tampilkan form pemesanan laundry untuk pelanggan.
     */
    public function pesan()
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);

        // Proteksi jika profil pelanggan kosong
        if (!$pelanggan || empty($pelanggan['nama']) || empty($pelanggan['no_hp']) || empty($pelanggan['alamat'])) {
            $this->session->set_flashdata('error', 'Silakan lengkapi Profil Anda (Nama, No HP, Alamat) terlebih dahulu sebelum membuat pesanan.');
            redirect('user/profil');
            return;
        }

        $data['title']     = 'Pesan Laundry Baru — LaundryKu';
        $data['active']    = 'pesan';
        $data['pelanggan'] = $pelanggan;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('user/pesan', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Memproses pesanan laundry baru pelanggan.
     */
    public function proses_pesan()
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);

        if (!$pelanggan || empty($pelanggan['nama']) || empty($pelanggan['no_hp']) || empty($pelanggan['alamat'])) {
            $this->session->set_flashdata('error', 'Profil tidak lengkap.');
            redirect('user/profil');
            return;
        }

        $this->form_validation->set_rules('jenis_layanan', 'Jenis Layanan',  'required|trim|in_list[Cuci Reguler,Cuci Express,Cuci + Setrika]');
        $this->form_validation->set_rules('berat',         'Estimasi Berat (kg)', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('tanggal',       'Tanggal Pengantaran', 'required');
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'required|in_list[Tunai,QRIS]');
        $this->form_validation->set_rules('setuju_ketentuan_berat', 'Persetujuan Penyesuaian Berat', 'required', [
            'required' => 'Anda harus menyetujui Ketentuan Penyesuaian Berat untuk melanjutkan.'
        ]);

        $is_jemput = $this->input->post('is_jemput') ? 1 : 0;
        if ($is_jemput) {
            $this->form_validation->set_rules('alamat_jemput', 'Alamat Jemput', 'required|trim');
        }

        $is_antar = $this->input->post('is_antar') ? 1 : 0;
        if ($is_antar) {
            $this->form_validation->set_rules('alamat_antar', 'Alamat Antar', 'required|trim');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->pesan();
            return;
        }

        $jenis_layanan = $this->input->post('jenis_layanan', TRUE);
        $berat         = floatval($this->input->post('berat', TRUE));
        
        // Tarif Resmi
        $tarif = 0;
        switch ($jenis_layanan) {
            case 'Cuci Reguler':
                $tarif = 7000;
                break;
            case 'Cuci Express':
                $tarif = 10000;
                break;
            case 'Cuci + Setrika':
                $tarif = 12000;
                break;
        }
        $harga = $berat * $tarif;

        $reward_option = $this->input->post('reward_option', TRUE);
        $poin_used = 0;
        $reward_used = NULL;
        
        $ongkir_jemput = $is_jemput ? ($berat * 10000.00) : 0.00;
        $ongkir_antar  = $is_antar ? ($berat * 10000.00) : 0.00;
        
        // Cek Poin Reward Pelanggan saat ini
        $current_poin = intval($pelanggan['poin']);
        
        if (!empty($reward_option)) {
            if ($reward_option === 'free_cuci_reguler') {
                if ($current_poin < 15) {
                    $this->session->set_flashdata('error', 'Poin tidak cukup untuk menukarkan Free Cuci Reguler.');
                    redirect('user/pesan');
                    return;
                }
                if ($jenis_layanan !== 'Cuci Reguler') {
                    $this->session->set_flashdata('error', 'Layanan terpilih harus Cuci Reguler untuk menukarkan reward ini.');
                    redirect('user/pesan');
                    return;
                }
                $poin_used = 15;
                $reward_used = 'Free Cuci Reguler (Tukar 15 Poin)';
                $harga = 0.00;
            } elseif ($reward_option === 'free_cuci_express') {
                if ($current_poin < 18) {
                    $this->session->set_flashdata('error', 'Poin tidak cukup untuk menukarkan Free Cuci Express.');
                    redirect('user/pesan');
                    return;
                }
                if ($jenis_layanan !== 'Cuci Express') {
                    $this->session->set_flashdata('error', 'Layanan terpilih harus Cuci Express untuk menukarkan reward ini.');
                    redirect('user/pesan');
                    return;
                }
                $poin_used = 18;
                $reward_used = 'Free Cuci Express (Tukar 18 Poin)';
                $harga = 0.00;
            } elseif ($reward_option === 'free_cuci_setrika') {
                if ($current_poin < 30) {
                    $this->session->set_flashdata('error', 'Poin tidak cukup untuk menukarkan Free Cuci + Setrika.');
                    redirect('user/pesan');
                    return;
                }
                if ($jenis_layanan !== 'Cuci + Setrika') {
                    $this->session->set_flashdata('error', 'Layanan terpilih harus Cuci + Setrika untuk menukarkan reward ini.');
                    redirect('user/pesan');
                    return;
                }
                $poin_used = 30;
                $reward_used = 'Free Cuci + Setrika (Tukar 30 Poin)';
                $harga = 0.00;
            } elseif ($reward_option === 'free_kurir') {
                if ($current_poin < 20) {
                    $this->session->set_flashdata('error', 'Poin tidak cukup untuk menukarkan Free Kurir.');
                    redirect('user/pesan');
                    return;
                }
                if (!$is_jemput && !$is_antar) {
                    $this->session->set_flashdata('error', 'Anda harus memilih minimal salah satu layanan kurir (Jemput atau Antar) untuk menggunakan reward Free Kurir.');
                    redirect('user/pesan');
                    return;
                }
                $poin_used = 20;
                $reward_used = 'Free Kurir (Jemput & Antar) (Tukar 20 Poin)';
                $ongkir_jemput = 0.00;
                $ongkir_antar = 0.00;
            }
        }

        $insert = [
            'kode_transaksi'    => $this->Transaksi_model->generate_kode(),
            'id_pelanggan'      => $pelanggan['id_pelanggan'],
            'jenis_layanan'     => $jenis_layanan,
            'berat'             => $berat,
            'berat_estimasi'    => $berat,
            'harga'             => $harga + $ongkir_jemput + $ongkir_antar, // Total = Harga Laundry + Ongkir Jemput + Ongkir Antar
            'status'            => 'Menunggu',
            'tanggal'           => $this->input->post('tanggal', TRUE),
            'is_jemput'         => $is_jemput,
            'alamat_jemput'     => $is_jemput ? $this->input->post('alamat_jemput', TRUE) : NULL,
            'gps_jemput'        => $is_jemput ? $this->input->post('gps_jemput', TRUE) : NULL,
            'status_jemput'     => $is_jemput ? 'Menunggu Penjemputan' : 'Sudah Dijemput',
            'ongkir_jemput'     => $ongkir_jemput,
            'is_antar'          => $is_antar,
            'alamat_antar'      => $is_antar ? $this->input->post('alamat_antar', TRUE) : NULL,
            'gps_antar'         => $is_antar ? $this->input->post('gps_antar', TRUE) : NULL,
            'status_antar'      => $is_antar ? 'Menunggu Pengantaran' : 'Sudah Diantarkan',
            'ongkir_antar'      => $ongkir_antar,
            'poin_used'         => $poin_used,
            'reward_used'       => $reward_used,
            'metode_pembayaran' => $this->input->post('metode_pembayaran', TRUE),
            'status_pembayaran' => 'Belum Bayar',
            'catatan'           => $this->input->post('catatan', TRUE) ? trim($this->input->post('catatan', TRUE)) : NULL,
        ];

        if ($this->Transaksi_model->insert($insert)) {
            $msg = 'Pesanan laundry #' . $insert['kode_transaksi'] . ' berhasil dibuat!';
            $extra = [];
            if ($is_jemput) {
                $extra[] = 'Layanan Jemput (+Rp 20.000)';
            }
            if ($is_antar) {
                $extra[] = 'Layanan Antar (+Rp 20.000)';
            }
            if (!empty($extra)) {
                $msg .= ' ' . implode(' & ', $extra) . ' telah ditambahkan ke tagihan.';
            } else {
                $msg .= ' Silakan serahkan pakaian Anda ke outlet.';
            }
            $this->session->set_flashdata('success', $msg);
            redirect('user/riwayat');
        } else {
            $this->session->set_flashdata('error', 'Gagal memproses pesanan laundry.');
            $this->pesan();
        }
    }

    /**
     * Konfirmasi bahwa cucian sudah dijemput oleh kurir (dari sisi Pelanggan).
     */
    public function konfirmasi_jemput($id)
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);
        $transaksi = $this->Transaksi_model->get_by_id($id);

        if (!$transaksi || !$pelanggan || $transaksi['id_pelanggan'] != $pelanggan['id_pelanggan']) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan atau bukan milik Anda.');
            redirect('user/riwayat');
            return;
        }

        if ($transaksi['is_jemput'] != 1) {
            $this->session->set_flashdata('error', 'Transaksi ini tidak menggunakan layanan penjemputan.');
            redirect('user/detail/' . $id);
            return;
        }

        // Update status_jemput menjadi 'Sudah Dijemput'
        $update = [
            'status_jemput' => 'Sudah Dijemput'
        ];

        if ($this->Transaksi_model->update($id, $update)) {
            $this->session->set_flashdata('success', 'Konfirmasi penjemputan berhasil! Terima kasih atas konfirmasinya.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui status penjemputan.');
        }

        redirect('user/detail/' . $id);
    }

    /**
     * Konfirmasi bahwa cucian sudah diantarkan kembali ke rumah (dari sisi Pelanggan).
     */
    public function konfirmasi_antar($id)
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);
        $transaksi = $this->Transaksi_model->get_by_id($id);

        if (!$transaksi || !$pelanggan || $transaksi['id_pelanggan'] != $pelanggan['id_pelanggan']) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan atau bukan milik Anda.');
            redirect('user/riwayat');
            return;
        }

        if ($transaksi['is_antar'] != 1) {
            $this->session->set_flashdata('error', 'Transaksi ini tidak menggunakan layanan pengantaran.');
            redirect('user/detail/' . $id);
            return;
        }

        // Update status_antar menjadi 'Sudah Diantarkan' dan status utama menjadi 'Diambil'
        $update = [
            'status_antar' => 'Sudah Diantarkan',
            'status'       => 'Diambil'
        ];

        // Jika pembayaran Tunai (COD) dan status pembayaran masih Belum Bayar,
        // otomatis ubah status pembayaran menjadi Lunas karena uang diserahkan langsung ke kurir
        if ($transaksi['metode_pembayaran'] === 'Tunai' && $transaksi['status_pembayaran'] === 'Belum Bayar') {
            $update['status_pembayaran'] = 'Lunas';
        }

        if ($this->Transaksi_model->update($id, $update)) {
            $this->session->set_flashdata('success', 'Konfirmasi penerimaan laundry berhasil! Anda sekarang dapat memberikan ulasan. Terima kasih.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui status pengantaran.');
        }

        redirect('user/detail/' . $id);
    }

    /**
     * Mengirimkan rating dan ulasan untuk transaksi yang sudah diambil.
     */
    public function rate_transaksi($id)
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);
        $transaksi = $this->Transaksi_model->get_by_id($id);

        if (!$transaksi || !$pelanggan || $transaksi['id_pelanggan'] != $pelanggan['id_pelanggan']) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan atau bukan milik Anda.');
            redirect('user/riwayat');
            return;
        }

        if ($transaksi['status'] !== 'Diambil') {
            $this->session->set_flashdata('error', 'Anda hanya dapat memberikan ulasan pada transaksi yang sudah diambil/selesai.');
            redirect('user/detail/' . $id);
            return;
        }

        if (!empty($transaksi['rating'])) {
            $this->session->set_flashdata('error', 'Anda sudah memberikan ulasan untuk transaksi ini.');
            redirect('user/detail/' . $id);
            return;
        }

        $this->form_validation->set_rules('rating', 'Rating', 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]');
        $this->form_validation->set_rules('review', 'Ulasan', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Silakan isi rating dan komentar ulasan dengan benar.');
            redirect('user/detail/' . $id);
            return;
        }

        $update = [
            'rating' => intval($this->input->post('rating')),
            'review' => $this->input->post('review', TRUE)
        ];

        if ($this->Transaksi_model->update($id, $update)) {
            $this->session->set_flashdata('success', 'Ulasan Anda berhasil dikirim! Terima kasih atas feedback Anda.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan ulasan Anda.');
        }

        redirect('user/detail/' . $id);
    }

    /**
     * Mengunggah bukti pembayaran QRIS (dari sisi Pelanggan).
     */
    public function upload_bukti($id)
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);
        $transaksi = $this->Transaksi_model->get_by_id($id);

        if (!$transaksi || !$pelanggan || $transaksi['id_pelanggan'] != $pelanggan['id_pelanggan']) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan atau bukan milik Anda.');
            redirect('user/riwayat');
            return;
        }

        if (!in_array($transaksi['metode_pembayaran'], ['QRIS', 'Transfer Bank', 'Virtual Account'])) {
            $this->session->set_flashdata('error', 'Transaksi ini tidak mendukung pembayaran non-tunai.');
            redirect('user/detail/' . $id);
            return;
        }

        if ($transaksi['status_pembayaran'] === 'Lunas') {
            $this->session->set_flashdata('error', 'Pembayaran transaksi ini sudah diverifikasi (Lunas).');
            redirect('user/detail/' . $id);
            return;
        }

        if ($transaksi['status'] === 'Menunggu') {
            $this->session->set_flashdata('error', 'Pembayaran belum dapat dilakukan karena pakaian belum ditimbang oleh outlet.');
            redirect('user/detail/' . $id);
            return;
        }

        // Setup upload library config
        $upload_path = './assets/uploads/bukti_bayar/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size']      = 2048; // 2MB
        $config['file_name']     = 'bukti_' . $transaksi['kode_transaksi'] . '_' . time();

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('bukti_pembayaran')) {
            $this->session->set_flashdata('error', 'Gagal mengunggah bukti: ' . $this->upload->display_errors('', ''));
            redirect('user/detail/' . $id);
            return;
        }

        $upload_data = $this->upload->data();
        $file_name = $upload_data['file_name'];

        // Hapus file lama jika ada
        if (!empty($transaksi['bukti_pembayaran']) && file_exists($upload_path . $transaksi['bukti_pembayaran'])) {
            @unlink($upload_path . $transaksi['bukti_pembayaran']);
        }

        $update = [
            'bukti_pembayaran'  => $file_name,
            'status_pembayaran' => 'Menunggu Verifikasi'
        ];

        if ($this->Transaksi_model->update($id, $update)) {
            $this->session->set_flashdata('success', 'Bukti pembayaran berhasil diunggah! Harap tunggu verifikasi dari Admin.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui status transaksi.');
        }

        redirect('user/detail/' . $id);
    }

    /**
     * Membatalkan pesanan laundry (hanya jika status masih Menunggu dan Belum Bayar).
     */
    public function batalkan_pesan($id)
    {
        is_user();

        $id_user = $this->session->userdata('id_user');
        $pelanggan = $this->Pelanggan_model->get_by_user($id_user);
        $transaksi = $this->Transaksi_model->get_by_id($id);

        if (!$transaksi || !$pelanggan || $transaksi['id_pelanggan'] != $pelanggan['id_pelanggan']) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan atau bukan milik Anda.');
            redirect('user/riwayat');
            return;
        }

        if ($transaksi['status'] !== 'Menunggu' || $transaksi['status_pembayaran'] !== 'Belum Bayar' || $transaksi['status_jemput'] === 'Sudah Dijemput') {
            $this->session->set_flashdata('error', 'Pesanan tidak dapat dibatalkan secara sepihak karena pakaian sudah dijemput kurir atau sedang diproses. Silakan hubungi Admin via WhatsApp.');
            redirect('user/detail/' . $id);
            return;
        }

        if ($this->Transaksi_model->delete($id)) {
            $this->session->set_flashdata('success', 'Pesanan laundry #' . $transaksi['kode_transaksi'] . ' berhasil dibatalkan dan poin Anda (jika ada) telah dikembalikan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal membatalkan pesanan.');
        }

        redirect('user/riwayat');
    }

    /**
     * Menghitung jarak garis lurus antara dua koordinat (Haversine formula).
     */
    private function _calculate_distance($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earth_radius * $c;
    }
}
