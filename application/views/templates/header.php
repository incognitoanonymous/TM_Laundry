<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * templates/header.php
 * Template bagian atas dokumen HTML, memuat Inter font dan stylesheet LaundryKu.
 */
$title_page = $title ?? 'LaundryKu — Sistem Informasi Manajemen Laundry';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title_page) ?></title>
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
<div class="app-wrapper">
