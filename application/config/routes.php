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
$route['default_controller']   = 'auth/login';
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
$route['admin/user/(:num)']        = 'user/index';
$route['admin/user/tambah']        = 'user/tambah';
$route['admin/user/simpan']        = 'user/simpan';
$route['admin/user/edit/(:num)']   = 'user/edit/$1';
$route['admin/user/update/(:num)'] = 'user/update/$1';
$route['admin/user/hapus/(:num)']  = 'user/hapus/$1';

// ── RUTE ADMIN: CRUD PELANGGAN ───────────────────────────────────────────
$route['admin/pelanggan']               = 'pelanggan/index';
$route['admin/pelanggan/(:num)']        = 'pelanggan/index';
$route['admin/pelanggan/tambah']        = 'pelanggan/tambah';
$route['admin/pelanggan/simpan']        = 'pelanggan/simpan';
$route['admin/pelanggan/edit/(:num)']   = 'pelanggan/edit/$1';
$route['admin/pelanggan/update/(:num)'] = 'pelanggan/update/$1';
$route['admin/pelanggan/hapus/(:num)']  = 'pelanggan/hapus/$1';

// ── RUTE ADMIN: CRUD TRANSAKSI ───────────────────────────────────────────
$route['admin/transaksi']               = 'transaksi/index';
$route['admin/transaksi/(:num)']        = 'transaksi/index';
$route['admin/transaksi/tambah']        = 'transaksi/tambah';
$route['admin/transaksi/simpan']        = 'transaksi/simpan';
$route['admin/transaksi/edit/(:num)']   = 'transaksi/edit/$1';
$route['admin/transaksi/update/(:num)'] = 'transaksi/update/$1';
$route['admin/transaksi/status/(:num)'] = 'transaksi/update_status/$1';
$route['admin/transaksi/hapus/(:num)']  = 'transaksi/hapus/$1';

// ── RUTE ADMIN: LAPORAN ──────────────────────────────────────────────────
$route['admin/laporan']         = 'laporan/index';

// ── RUTE USER / PELANGGAN ────────────────────────────────────────────────
$route['user/dashboard']        = 'user/dashboard';
$route['user/riwayat']          = 'user/riwayat';
$route['user/profil']           = 'user/profil';
$route['user/profil/update']    = 'user/update_profil';
$route['user/detail/(:num)']    = 'user/detail/$1';

// ── RUTE CEK STATUS TANPA LOGIN (PUBLIK) ─────────────────────────────────
$route['cek-status']            = 'status/index';
$route['cek-status/cari']       = 'status/cari';
$route['status/cari']           = 'status/cari'; // Fallback link form cek
