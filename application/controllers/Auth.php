<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Auth.php — Controller Autentikasi & Registrasi
| -------------------------------------------------------------------
| Menangani halaman login, registrasi pelanggan baru secara mandiri,
| proses autentikasi session, dan logout sistem.
*/

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Auth_model', 'Pelanggan_model', 'User_model']);
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
    }

    // ─── LOGIN: Halaman Form Login ─────────────────────────────────────────

    /**
     * Tampilkan form login.
     * Jika sudah login, langsung diarahkan ke dashboard masing-masing role.
     */
    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role($this->session->userdata('role'));
        }

        $data['title'] = 'Masuk Ke Sistem — LaundryKu';
        $this->load->view('auth/login', $data);
    }

    /**
     * Memproses data login POST.
     */
    public function proses_login()
    {
        $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric|min_length[4]|max_length[50]', [
            'alpha_numeric' => 'Username hanya boleh huruf dan angka.',
            'min_length' => 'Username minimal 4 karakter.',
            'max_length' => 'Username maksimal 50 karakter.'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]|max_length[255]', [
            'min_length' => 'Password minimal 4 karakter.',
            'max_length' => 'Password maksimal 255 karakter.'
        ]);

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Masuk Ke Sistem — LaundryKu';
            $this->load->view('auth/login', $data);
            return;
        }

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password');

        $user = $this->Auth_model->get_user_by_username($username);

        if ($user && password_verify($password, $user['password'])) {
            // Set session data
            $session_data = [
                'logged_in' => TRUE,
                'id_user'   => $user['id_user'],
                'username'  => $user['username'],
                'role'      => $user['role'],
            ];
            $this->session->set_userdata($session_data);

            $this->session->set_flashdata('success', 'Selamat datang kembali, ' . htmlspecialchars($username) . '.');
            $this->_redirect_by_role($user['role']);
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('login');
        }
    }

    // ─── REGISTRASI: Pelanggan Daftar Mandiri ────────────────────────────────

    /**
     * Tampilkan form registrasi pelanggan mandiri.
     */
    public function register()
    {
        if ($this->session->userdata('logged_in')) {
            $this->_redirect_by_role($this->session->userdata('role'));
        }

        $data['title'] = 'Pendaftaran Pelanggan Baru — LaundryKu';
        $this->load->view('auth/register', $data);
    }

    /**
     * Memproses data registrasi POST.
     */
    public function proses_register()
    {
        // Validasi input form
        $this->form_validation->set_rules('username',      'Username',           'required|trim|alpha_numeric|min_length[4]|max_length[50]|is_unique[users.username]', [
            'is_unique' => 'Username ini sudah terdaftar.'
        ]);
        $this->form_validation->set_rules('password',      'Password',           'required|min_length[4]|max_length[255]');
        $this->form_validation->set_rules('konf_password', 'Konfirmasi Password', 'required|matches[password]', [
            'matches' => 'Konfirmasi password tidak cocok.'
        ]);
        $this->form_validation->set_rules('nama',          'Nama Lengkap',       'required|trim|max_length[100]');
        $this->form_validation->set_rules('no_hp',         'Nomor HP',           'required|trim|numeric|min_length[8]|max_length[15]', [
            'numeric' => 'Nomor HP harus berupa angka.',
            'min_length' => 'Nomor HP minimal 8 angka.',
            'max_length' => 'Nomor HP maksimal 15 angka.'
        ]);
        $this->form_validation->set_rules('alamat',        'Alamat Lengkap',     'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = 'Pendaftaran Pelanggan Baru — LaundryKu';
            $this->load->view('auth/register', $data);
            return;
        }

        // 1. Mulai Transaksi Database manual
        $this->db->trans_start();

        // 2. Insert data ke tabel users
        $user_data = [
            'username' => $this->input->post('username', TRUE),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'role'     => 'user',
        ];
        $this->User_model->insert($user_data);
        $id_user = $this->db->insert_id(); // Dapatkan ID user yang baru dibuat

        // 3. Insert data ke tabel pelanggan
        $pelanggan_data = [
            'id_user' => $id_user,
            'nama'    => $this->input->post('nama', TRUE),
            'no_hp'   => $this->input->post('no_hp', TRUE),
            'alamat'  => $this->input->post('alamat', TRUE),
        ];
        $this->Pelanggan_model->insert($pelanggan_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Pendaftaran gagal. Terjadi kesalahan pada sistem.');
            redirect('register');
        } else {
            $this->session->set_flashdata('success', 'Registrasi sukses! Silakan login menggunakan akun baru Anda.');
            redirect('login');
        }
    }

    // ─── LOGOUT ────────────────────────────────────────────────────────────

    /**
     * Proses keluar dari sistem dan hapus semua data session.
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    // ─── HELPER PRIVATE ────────────────────────────────────────────────────

    /**
     * Redirect ke halaman panel masing-masing berdasarkan hak akses.
     */
    private function _redirect_by_role($role)
    {
        if ($role === 'admin') {
            redirect('admin/dashboard');
        } else {
            redirect('user/dashboard');
        }
    }
}
