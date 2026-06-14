<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| ---------------------------------------------------------------
| Status.php  —  Controller Cek Status (Publik)
| ---------------------------------------------------------------
| Halaman cek status laundry yang bisa diakses TANPA login.
| User cukup memasukkan kode transaksi untuk cek status cucian.
*/

class Status extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
    }

    // ─── Form Cek Status ───────────────────────────────────────────────────

    /**
     * Tampilkan form cek status (halaman publik).
     */
    public function index()
    {
        $data['title']     = 'Cek Status Laundry — LaundryKu';
        $data['hasil']     = NULL;
        $data['tidak_ada'] = FALSE;

        $this->load->view('status/cek', $data);
    }

    // ─── Proses Pencarian ──────────────────────────────────────────────────

    /**
     * Proses cari transaksi berdasarkan kode yang diinput.
     */
    public function cari()
    {
        $kode = $this->input->post('kode_transaksi', TRUE);

        $data['title']     = 'Cek Status Laundry — LaundryKu';
        $data['tidak_ada'] = FALSE;
        $data['hasil']     = NULL;

        if (empty($kode)) {
            $this->session->set_flashdata('error', 'Kode transaksi tidak boleh kosong.');
            redirect('cek-status');
            return;
        }

        $transaksi = $this->Transaksi_model->get_by_kode($kode);

        if ($transaksi) {
            $data['hasil'] = $transaksi;
        } else {
            $data['tidak_ada'] = TRUE;
        }

        $data['kode_cari'] = htmlspecialchars($kode);

        $this->load->view('status/cek', $data);
    }
}
