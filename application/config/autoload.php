<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| ---------------------------------------------------------------
| autoload.php — Autoload library, helper, model
| ---------------------------------------------------------------
| Library dan helper yang di-load otomatis di semua controller.
*/

// ── Packages ────────────────────────────────────────────────────
$autoload['packages'] = [];

// ── Libraries ───────────────────────────────────────────────────
// session   → untuk session login
// database  → koneksi database otomatis
$autoload['libraries'] = ['database', 'session', 'form_validation'];

// ── Drivers ─────────────────────────────────────────────────────
$autoload['drivers'] = [];

// ── Helpers ─────────────────────────────────────────────────────
// url    → base_url(), site_url(), redirect()
// form   → form_open(), form_close(), set_value()
$autoload['helper'] = ['url', 'form', 'auth'];

// ── Config ──────────────────────────────────────────────────────
$autoload['config'] = [];

// ── Language ────────────────────────────────────────────────────
$autoload['language'] = [];

// ── Models ──────────────────────────────────────────────────────
// Model tidak di-autoload agar lebih efisien.
// Setiap controller akan load model yang dibutuhkan saja.
$autoload['model'] = [];
