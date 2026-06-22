<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Pelanggan.php — Controller Manajemen Pelanggan LaundryKu
| -------------------------------------------------------------------
| Menangani operasi CRUD pelanggan oleh Administrator dengan dukungan paginasi,
| validasi form, dan template layout dinamis terpusat.
*/

class Pelanggan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('auth');
        // Proteksi: hanya admin
        is_admin();

        $this->load->model(['Pelanggan_model']);
        $this->load->library(['session', 'form_validation', 'pagination']);
        $this->load->helper(['url', 'form']);
    }

    // ─── INDEX: Daftar Pelanggan ────────────────────────────────────────────

    /**
     * Tampilkan daftar semua pelanggan dengan paginasi.
     */
    public function index($page = 0)
    {
        $data['title']  = 'Manajemen Pelanggan — LaundryKu';
        $data['active'] = 'pelanggan';

        // Konfigurasi Paginasi
        $config['base_url']    = base_url('admin/pelanggan');
        $config['total_rows']  = $this->Pelanggan_model->count_all();
        $config['per_page']    = 5; // Tampilkan 5 pelanggan per halaman
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
        $data['pelanggan'] = $this->Pelanggan_model->get_paginated($config['per_page'], $page);
        $data['pagination_links'] = $this->pagination->create_links();
        $data['offset'] = $page;

        // Render dengan template
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('pelanggan/index', $data);
        $this->load->view('templates/footer', $data);
    }

    // ─── TAMBAH: Form Tambah Pelanggan ─────────────────────────────────────

    /**
     * Tampilkan form tambah pelanggan baru.
     */
    public function tambah()
    {
        $data['title']  = 'Tambah Pelanggan Baru — LaundryKu';
        $data['active'] = 'pelanggan';
        // Hanya ambil user biasa yang belum dikaitkan dengan pelanggan mana pun
        $data['users']  = $this->Pelanggan_model->get_users_available();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('pelanggan/tambah', $data);
        $this->load->view('templates/footer', $data);
    }

    // ─── SIMPAN: Proses Simpan Pelanggan ───────────────────────────────────

    /**
     * Proses simpan data pelanggan baru ke database.
     */
    public function simpan()
    {
        $this->form_validation->set_rules('id_user', 'User',    'required|is_natural_no_zero');
        $this->form_validation->set_rules('nama',    'Nama',    'required|trim|max_length[100]');
        $this->form_validation->set_rules('no_hp',   'No HP',   'required|trim|numeric|min_length[8]|max_length[15]', [
            'numeric' => 'Nomor HP harus berupa angka.',
            'min_length' => 'Nomor HP minimal 8 angka.',
            'max_length' => 'Nomor HP maksimal 15 angka.'
        ]);
        $this->form_validation->set_rules('alamat',  'Alamat',  'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->tambah();
            return;
        }

        $insert = [
            'id_user' => $this->input->post('id_user', TRUE),
            'nama'    => $this->input->post('nama',    TRUE),
            'no_hp'   => $this->input->post('no_hp',   TRUE),
            'alamat'  => $this->input->post('alamat',  TRUE),
        ];

        if ($this->Pelanggan_model->insert($insert)) {
            $this->session->set_flashdata('success', 'Data pelanggan berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data pelanggan.');
        }

        redirect('admin/pelanggan');
    }

    // ─── EDIT: Form Edit Pelanggan ──────────────────────────────────────────

    /**
     * Tampilkan form edit pelanggan berdasarkan id.
     *
     * @param int $id  id_pelanggan
     */
    public function edit($id)
    {
        $pelanggan = $this->Pelanggan_model->get_by_id($id);

        if (!$pelanggan) {
            $this->session->set_flashdata('error', 'Data pelanggan tidak ditemukan.');
            redirect('admin/pelanggan');
            return;
        }

        $data['title']     = 'Edit Data Pelanggan — LaundryKu';
        $data['active']    = 'pelanggan';
        $data['pelanggan'] = $pelanggan;
        // Ambil user biasa yang belum dikaitkan, ditambah dengan user_id milik pelanggan saat ini agar masuk pilihan dropdown
        $data['users']     = $this->Pelanggan_model->get_users_available($pelanggan['id_user']);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('pelanggan/edit', $data);
        $this->load->view('templates/footer', $data);
    }

    // ─── UPDATE: Proses Update Pelanggan ───────────────────────────────────

    /**
     * Proses update data pelanggan di database.
     *
     * @param int $id  id_pelanggan
     */
    public function update($id)
    {
        $this->form_validation->set_rules('id_user', 'User',    'required|is_natural_no_zero');
        $this->form_validation->set_rules('nama',    'Nama',    'required|trim|max_length[100]');
        $this->form_validation->set_rules('no_hp',   'No HP',   'required|trim|numeric|min_length[8]|max_length[15]', [
            'numeric' => 'Nomor HP harus berupa angka.',
            'min_length' => 'Nomor HP minimal 8 angka.',
            'max_length' => 'Nomor HP maksimal 15 angka.'
        ]);
        $this->form_validation->set_rules('alamat',  'Alamat',  'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->edit($id);
            return;
        }

        $update = [
            'id_user' => $this->input->post('id_user', TRUE),
            'nama'    => $this->input->post('nama',    TRUE),
            'no_hp'   => $this->input->post('no_hp',   TRUE),
            'alamat'  => $this->input->post('alamat',  TRUE),
        ];

        if ($this->Pelanggan_model->update($id, $update)) {
            $this->session->set_flashdata('success', 'Data pelanggan berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data pelanggan.');
        }

        redirect('admin/pelanggan');
    }

    // ─── HAPUS: Hapus Pelanggan ─────────────────────────────────────────────

    /**
     * Hapus data pelanggan dari database.
     *
     * @param int $id  id_pelanggan
     */
    public function hapus($id)
    {
        if ($this->Pelanggan_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data pelanggan berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data pelanggan.');
        }

        redirect('admin/pelanggan');
    }
}
