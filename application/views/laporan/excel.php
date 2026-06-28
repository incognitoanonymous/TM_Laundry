<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * laporan/excel.php
 * View ekspor laporan keuangan dan transaksi ke format Excel (.xls).
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 10pt;
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }
        .stats-table {
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .stats-table td {
            border: 1px solid #cccccc;
            padding: 8px 12px;
        }
        .stats-header {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        table.data-table {
            border-collapse: collapse;
            width: 100%;
        }
        table.data-table th {
            background-color: #16a34a;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            border: 1px solid #000000;
        }
        table.data-table td {
            padding: 8px;
            border: 1px solid #000000;
            vertical-align: middle;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .bg-total {
            background-color: #f0fdf4;
        }
    </style>
</head>
<body>

    <div class="title">LAPORAN KEUANGAN & PENDAPATAN LAUNDRY</div>
    <div class="subtitle">Sistem Informasi Manajemen Laundry — LaundryKu</div>

    <!-- Info Filter -->
    <p>
        <strong>Filter Periode:</strong> 
        <?php 
        if ($filter_type === 'harian') {
            echo "Harian (" . date('d-m-Y', strtotime($tanggal)) . ")";
        } elseif ($filter_type === 'bulanan') {
            $indo_months = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            echo "Bulanan (" . $indo_months[$bulan] . " " . $tahun . ")";
        } elseif ($filter_type === 'rentang') {
            echo "Rentang Tanggal (" . date('d-m-Y', strtotime($tgl_mulai)) . " s.d " . date('d-m-Y', strtotime($tgl_akhir)) . ")";
        }
        ?>
    </p>

    <!-- Ringkasan Statistik -->
    <h3>📊 Ringkasan Statistik Pendapatan</h3>
    <table class="stats-table">
        <tr class="stats-header">
            <td>Total Pendapatan</td>
            <td>Total Transaksi</td>
            <td>Total Pelanggan</td>
            <td>Cucian Selesai</td>
            <td>Cucian Diambil</td>
        </tr>
        <tr>
            <td class="bold">Rp <?= number_format($statistik['total_pendapatan'] ?? 0, 0, ',', '.') ?></td>
            <td><?= $statistik['total_transaksi'] ?? 0 ?></td>
            <td><?= $statistik['total_pelanggan'] ?? 0 ?></td>
            <td><?= $statistik['laundry_selesai'] ?? 0 ?></td>
            <td><?= $statistik['laundry_diambil'] ?? 0 ?></td>
        </tr>
    </table>

    <!-- Rincian Item Transaksi -->
    <h3>🧾 Rincian Transaksi Terkait</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Jenis Layanan</th>
                <th>Berat (kg)</th>
                <th>Total Harga</th>
                <th>Tanggal Masuk</th>
                <th>Status Cucian</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            $total_berat = 0;
            $total_harga = 0;
            foreach ($laporan_transaksi as $t): 
                $total_berat += $t['berat'];
                $total_harga += $t['harga'];
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center">`<?= htmlspecialchars($t['kode_transaksi']) ?>`</td>
                    <td><?= htmlspecialchars($t['nama_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($t['jenis_layanan']) ?></td>
                    <td class="text-right"><?= number_format($t['berat'], 2, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($t['harga'], 0, ',', '.') ?></td>
                    <td class="text-center"><?= date('d-m-Y', strtotime($t['tanggal'])) ?></td>
                    <td class="text-center"><?= htmlspecialchars($t['status']) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="bold bg-total">
                <td colspan="4" class="text-right">TOTAL REKAP:</td>
                <td class="text-right"><?= number_format($total_berat, 2, ',', '.') ?> kg</td>
                <td class="text-right">Rp <?= number_format($total_harga, 0, ',', '.') ?></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
