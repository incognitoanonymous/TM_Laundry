<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| Laporan_model.php — Model Laporan Keuangan LaundryKu
| -------------------------------------------------------------------
| Menyediakan metode rekap statistik transaksi & pendapatan dengan filter waktu.
*/

class Laporan_model extends CI_Model {

    /**
     * Terapkan filter tanggal/waktu pada query database transaksi.
     */
    private function _apply_filter($tanggal = NULL, $bulan = NULL, $tahun = NULL, $tgl_mulai = NULL, $tgl_akhir = NULL)
    {
        if (!empty($tanggal)) {
            $this->db->where('tanggal', $tanggal);
        } elseif (!empty($bulan) && !empty($tahun)) {
            $this->db->where('MONTH(tanggal)', $bulan);
            $this->db->where('YEAR(tanggal)', $tahun);
        } elseif (!empty($tgl_mulai) && !empty($tgl_akhir)) {
            $this->db->where('tanggal >=', $tgl_mulai);
            $this->db->where('tanggal <=', $tgl_akhir);
        }
    }

    /**
     * Ambil daftar transaksi berdasarkan filter.
     */
    public function get_transaksi($tanggal = NULL, $bulan = NULL, $tahun = NULL, $tgl_mulai = NULL, $tgl_akhir = NULL)
    {
        $this->db->select('transaksi.*, pelanggan.nama AS nama_pelanggan');
        $this->db->from('transaksi');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');
        $this->_apply_filter($tanggal, $bulan, $tahun, $tgl_mulai, $tgl_akhir);
        $this->db->order_by('tanggal', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil rangkuman statistik (Total Transaksi, Total Pendapatan, dsb) berdasarkan filter.
     */
    public function get_statistik($tanggal = NULL, $bulan = NULL, $tahun = NULL, $tgl_mulai = NULL, $tgl_akhir = NULL)
    {
        // 1. Total Transaksi & Pendapatan
        $this->db->select('COUNT(id_transaksi) AS total_transaksi, SUM(harga) AS total_pendapatan');
        $this->db->from('transaksi');
        $this->_apply_filter($tanggal, $bulan, $tahun, $tgl_mulai, $tgl_akhir);
        $res = $this->db->get()->row_array();

        $total_transaksi = $res['total_transaksi'] ?? 0;
        $total_pendapatan = $res['total_pendapatan'] ?? 0;

        // 2. Laundry Selesai (berdasarkan filter)
        $this->db->where('status', 'Selesai');
        $this->_apply_filter($tanggal, $bulan, $tahun, $tgl_mulai, $tgl_akhir);
        $laundry_selesai = $this->db->count_all_results('transaksi');

        // 3. Laundry Diambil (berdasarkan filter)
        $this->db->where('status', 'Diambil');
        $this->_apply_filter($tanggal, $bulan, $tahun, $tgl_mulai, $tgl_akhir);
        $laundry_diambil = $this->db->count_all_results('transaksi');

        // 4. Total Pelanggan (akumulatif, tidak terpengaruh filter tanggal transaksi)
        $total_pelanggan = $this->db->count_all('pelanggan');

        return [
            'total_transaksi' => $total_transaksi,
            'total_pendapatan' => $total_pendapatan,
            'total_pelanggan' => $total_pelanggan,
            'laundry_selesai' => $laundry_selesai,
            'laundry_diambil' => $laundry_diambil
        ];
    }
}
