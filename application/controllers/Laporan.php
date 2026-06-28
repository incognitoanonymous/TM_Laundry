<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Laporan.php — Controller Laporan Keuangan LaundryKu
| -------------------------------------------------------------------
| Memungkinkan Administrator menyaring rekapitulasi data transaksi & keuangan
| berdasarkan harian, bulanan, atau rentang tanggal tertentu.
*/

class Laporan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('auth');
        // Proteksi admin
        is_admin();

        $this->load->model(['Laporan_model']);
        $this->load->library(['session']);
        $this->load->helper(['url', 'form']);
    }

    /**
     * Halaman index laporan dengan berbagai filter penyaringan.
     */
    public function index()
    {
        $data['title']  = 'Laporan Transaksi & Keuangan — LaundryKu';
        $data['active'] = 'laporan';

        // Ambil filter dari GET
        $filter_type = $this->input->get('filter_type', TRUE);
        $tanggal     = $this->input->get('tanggal', TRUE);
        $bulan       = $this->input->get('bulan', TRUE);
        $tahun       = $this->input->get('tahun', TRUE);
        $tgl_mulai   = $this->input->get('tgl_mulai', TRUE);
        $tgl_akhir   = $this->input->get('tgl_akhir', TRUE);

        // Jika tidak ada filter yang diset, default-kan ke Bulanan (Bulan & Tahun ini)
        if (empty($filter_type)) {
            $filter_type = 'bulanan';
            $bulan       = date('m');
            $tahun       = date('Y');
        }

        // Simpan variabel filter saat ini ke view
        $data['filter_type'] = $filter_type;
        $data['tanggal']     = $tanggal;
        $data['bulan']       = $bulan;
        $data['tahun']       = $tahun;
        $data['tgl_mulai']   = $tgl_mulai;
        $data['tgl_akhir']   = $tgl_akhir;

        // Ambil data laporan dan statistik dari Laporan_model berdasarkan filter
        if ($filter_type === 'harian') {
            $data['laporan_transaksi'] = $this->Laporan_model->get_transaksi($tanggal);
            $data['statistik']         = $this->Laporan_model->get_statistik($tanggal);
        } elseif ($filter_type === 'bulanan') {
            $data['laporan_transaksi'] = $this->Laporan_model->get_transaksi(NULL, $bulan, $tahun);
            $data['statistik']         = $this->Laporan_model->get_statistik(NULL, $bulan, $tahun);
        } elseif ($filter_type === 'rentang') {
            $data['laporan_transaksi'] = $this->Laporan_model->get_transaksi(NULL, NULL, NULL, $tgl_mulai, $tgl_akhir);
            $data['statistik']         = $this->Laporan_model->get_statistik(NULL, NULL, NULL, $tgl_mulai, $tgl_akhir);
        } else {
            // Pengaman fallback
            $data['laporan_transaksi'] = [];
            $data['statistik']         = [
                'total_transaksi' => 0,
                'total_pendapatan' => 0,
                'total_pelanggan' => 0,
                'laundry_selesai' => 0,
                'laundry_diambil' => 0
            ];
        }

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/navbar', $data);
        $this->load->view('laporan/index', $data);
        $this->load->view('templates/footer', $data);
    }

    /**
     * Ekspor data laporan transaksi dan pendapatan ke format Excel (.xls).
     */
    public function export_excel()
    {
        $filter_type = $this->input->get('filter_type', TRUE);
        $tanggal     = $this->input->get('tanggal', TRUE);
        $bulan       = $this->input->get('bulan', TRUE);
        $tahun       = $this->input->get('tahun', TRUE);
        $tgl_mulai   = $this->input->get('tgl_mulai', TRUE);
        $tgl_akhir   = $this->input->get('tgl_akhir', TRUE);

        if (empty($filter_type)) {
            $filter_type = 'bulanan';
            $bulan       = date('m');
            $tahun       = date('Y');
        }

        $data['filter_type'] = $filter_type;
        $data['tanggal']     = $tanggal;
        $data['bulan']       = $bulan;
        $data['tahun']       = $tahun;
        $data['tgl_mulai']   = $tgl_mulai;
        $data['tgl_akhir']   = $tgl_akhir;

        // Ambil data laporan dan statistik dari Laporan_model berdasarkan filter
        if ($filter_type === 'harian') {
            $data['laporan_transaksi'] = $this->Laporan_model->get_transaksi($tanggal);
            $data['statistik']         = $this->Laporan_model->get_statistik($tanggal);
        } elseif ($filter_type === 'bulanan') {
            $data['laporan_transaksi'] = $this->Laporan_model->get_transaksi(NULL, $bulan, $tahun);
            $data['statistik']         = $this->Laporan_model->get_statistik(NULL, $bulan, $tahun);
        } elseif ($filter_type === 'rentang') {
            $data['laporan_transaksi'] = $this->Laporan_model->get_transaksi(NULL, NULL, NULL, $tgl_mulai, $tgl_akhir);
            $data['statistik']         = $this->Laporan_model->get_statistik(NULL, NULL, NULL, $tgl_mulai, $tgl_akhir);
        } else {
            $data['laporan_transaksi'] = [];
            $data['statistik']         = [
                'total_transaksi' => 0,
                'total_pendapatan' => 0,
                'total_pelanggan' => 0,
                'laundry_selesai' => 0,
                'laundry_diambil' => 0
            ];
        }

        // Atur header download Excel (.xls)
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Keuangan_LaundryKu_" . date('Ymd_His') . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('laporan/excel', $data);
    }
}
