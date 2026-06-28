<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| Rute URL lengkap Sistem Informasi Manajemen LaundryKu.
| Mengarahkan URL cantik localhost ke Controller murni MVC CodeIgniter 3.
*/

// Default landing page diarahkan ke form masuk (Auth login)
$route['default_controller']   = 'status/index';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;

// ── RUTE AUTHENTICATION ──────────────────────────────────────────────────
$route['login']                 = 'auth/login';
$route['register']              = 'auth/register';
$route['logout']                = 'auth/logout';
$route['auth/proses_login']     = 'auth/proses_login';
$route['auth/proses_register']  = 'auth/proses_register';

// ── RUTE ADMIN: DASHBOARD ────────────────────────────────────────────────
$route['admin/dashboard']       = 'admin/dashboard';

// ── RUTE ADMIN: CRUD USER ────────────────────────────────────────────────
$route['admin/user']               = 'user/index';
$route['admin/user/(:num)']        = 'user/index/$1';
$route['admin/user/tambah']        = 'user/tambah';
$route['admin/user/simpan']        = 'user/simpan';
$route['admin/user/edit/(:num)']   = 'user/edit/$1';
$route['admin/user/update/(:num)'] = 'user/update/$1';
$route['admin/user/hapus/(:num)']  = 'user/hapus/$1';

// ── RUTE ADMIN: CRUD PELANGGAN ───────────────────────────────────────────
$route['admin/pelanggan']               = 'pelanggan/index';
$route['admin/pelanggan/(:num)']        = 'pelanggan/index/$1';
$route['admin/pelanggan/tambah']        = 'pelanggan/tambah';
$route['admin/pelanggan/simpan']        = 'pelanggan/simpan';
$route['admin/pelanggan/edit/(:num)']   = 'pelanggan/edit/$1';
$route['admin/pelanggan/update/(:num)'] = 'pelanggan/update/$1';
$route['admin/pelanggan/hapus/(:num)']  = 'pelanggan/hapus/$1';

// ── RUTE ADMIN: CRUD TRANSAKSI ───────────────────────────────────────────
$route['admin/transaksi']               = 'transaksi/index';
$route['admin/transaksi/(:num)']        = 'transaksi/index/$1';
$route['admin/transaksi/tambah']        = 'transaksi/tambah';
$route['admin/transaksi/simpan']        = 'transaksi/simpan';
$route['admin/transaksi/edit/(:num)']   = 'transaksi/edit/$1';
$route['admin/transaksi/update/(:num)'] = 'transaksi/update/$1';
$route['admin/transaksi/status/(:num)'] = 'transaksi/update_status/$1';
$route['admin/transaksi/verifikasi_bayar/(:num)'] = 'transaksi/verifikasi_bayar/$1';
$route['admin/transaksi/export_excel']  = 'transaksi/export_excel';
$route['admin/transaksi/hapus/(:num)']  = 'transaksi/hapus/$1';

// ── RUTE ADMIN: LAPORAN ──────────────────────────────────────────────────
$route['admin/laporan']              = 'laporan/index';
$route['admin/laporan/export_excel'] = 'laporan/export_excel';

// ── RUTE USER / PELANGGAN ────────────────────────────────────────────────
$route['user/dashboard']        = 'user/dashboard';
$route['user/riwayat']          = 'user/riwayat';
$route['user/profil']           = 'user/profil';
$route['user/profil/update']    = 'user/update_profil';
$route['user/detail/(:num)']    = 'user/detail/$1';
$route['user/pesan']            = 'user/pesan';
$route['user/pesan/proses']     = 'user/proses_pesan';
$route['user/pesan/konfirmasi_jemput/(:num)'] = 'user/konfirmasi_jemput/$1';
$route['user/pesan/konfirmasi_antar/(:num)']  = 'user/konfirmasi_antar/$1';
$route['user/rate_transaksi/(:num)']          = 'user/rate_transaksi/$1';
$route['user/upload_bukti/(:num)']            = 'user/upload_bukti/$1';
$route['user/pesan/batalkan/(:num)']          = 'user/batalkan_pesan/$1';

// ── RUTE CEK STATUS TANPA LOGIN (PUBLIK) ─────────────────────────────────
$route['cek-status']            = 'status/index';
$route['cek-status/cari']       = 'status/cari';
$route['status/cari']           = 'status/cari'; // Fallback link form cek
