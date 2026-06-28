<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * transaksi/excel.php
 * View ekspor data transaksi laundry ke format Excel (.xls).
 * Menggunakan format HTML Spreadsheet yang didukung penuh oleh MS Excel, Google Sheets, dll.
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
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            border: 1px solid #000000;
        }
        td {
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
            background-color: #f1f5f9;
        }
    </style>
</head>
<body>

    <div class="title">LAPORAN DATA TRANSAKSI LAUNDRY</div>
    <div class="subtitle">Sistem Informasi Manajemen Laundry — LaundryKu</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Jenis Layanan</th>
                <th>Berat (kg)</th>
                <th>Biaya Laundry</th>
                <th>Biaya Jemput</th>
                <th>Biaya Antar</th>
                <th>Total Tagihan</th>
                <th>Metode Pembayaran</th>
                <th>Status Pembayaran</th>
                <th>Status Cucian</th>
                <th>Tanggal Masuk</th>
                <th>Catatan Khusus</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            $total_berat = 0;
            $total_harga = 0;
            $total_laundry = 0;
            $total_jemput = 0;
            $total_antar = 0;

            foreach ($transaksi as $t): 
                $laundry_cost = $t['harga'] - $t['ongkir_jemput'] - $t['ongkir_antar'];
                $total_berat += $t['berat'];
                $total_harga += $t['harga'];
                $total_laundry += $laundry_cost;
                $total_jemput += $t['ongkir_jemput'];
                $total_antar += $t['ongkir_antar'];
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center">`<?= htmlspecialchars($t['kode_transaksi']) ?>`</td>
                    <td><?= htmlspecialchars($t['nama_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($t['jenis_layanan']) ?></td>
                    <td class="text-right"><?= number_format($t['berat'], 2, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($laundry_cost, 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($t['ongkir_jemput'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($t['ongkir_antar'], 0, ',', '.') ?></td>
                    <td class="text-right bold"><?= number_format($t['harga'], 0, ',', '.') ?></td>
                    <td class="text-center"><?= htmlspecialchars($t['metode_pembayaran']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($t['status_pembayaran']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($t['status']) ?></td>
                    <td class="text-center"><?= date('d-m-Y', strtotime($t['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($t['catatan'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
            
            <!-- Summary Row -->
            <tr class="bold bg-total">
                <td colspan="4" class="text-right">TOTAL REKAP:</td>
                <td class="text-right"><?= number_format($total_berat, 2, ',', '.') ?></td>
                <td class="text-right"><?= number_format($total_laundry, 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($total_jemput, 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($total_antar, 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($total_harga, 0, ',', '.') ?></td>
                <td colspan="5" class="bg-total"></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
