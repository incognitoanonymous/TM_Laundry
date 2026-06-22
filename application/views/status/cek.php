<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LaundryKu Premium Care - Solusi jasa laundry kiloan dan satuan terbaik. Bersih, wangi, higienis, dan cepat dengan sistem pelacakan online real-time.">
    <title><?= $title ?? 'LaundryKu Premium Care — Jasa Laundry Terbaik & Terpercaya' ?></title>
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #eff6ff;
            --secondary: #0ea5e9;
            --success: #10b981;
            --success-dark: #0f9669;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-600: #475569;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --white: #ffffff;
            --radius: 12px;
            --radius-lg: 20px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            --shadow-lg: 0 10px 25px -5px rgba(37,99,235,0.1), 0 8px 10px -6px rgba(37,99,235,0.05);
            --shadow-card: 0 20px 25px -5px rgba(0,0,0,0.02), 0 10px 10px -5px rgba(0,0,0,0.01);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-800);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ── NAVBAR ── */
        .navbar {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--gray-900);
            letter-spacing: -0.5px;
        }

        .navbar-brand .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .navbar-menu a {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--gray-600);
            transition: var(--transition);
        }

        .navbar-menu a:hover {
            color: var(--primary);
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: var(--transition);
            font-family: inherit;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--white);
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-secondary {
            background-color: var(--gray-100);
            color: var(--gray-800);
        }

        .btn-secondary:hover {
            background-color: var(--gray-200);
            transform: translateY(-2px);
        }

        .btn-outline {
            background-color: transparent;
            border: 1.5px solid var(--gray-200);
            color: var(--gray-600);
        }

        .btn-outline:hover {
            background-color: var(--gray-100);
            color: var(--gray-900);
            border-color: var(--gray-300);
        }

        /* ── HERO SECTION ── */
        .hero {
            background: radial-gradient(circle at 80% 20%, rgba(14, 165, 233, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 10% 80%, rgba(37, 99, 235, 0.1) 0%, transparent 50%);
            padding: 80px 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 60px;
            align-items: center;
        }

        .hero-content h2 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            color: var(--gray-900);
            letter-spacing: -1.5px;
            margin-bottom: 20px;
        }

        .hero-content h2 span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: var(--gray-600);
            margin-bottom: 36px;
            max-width: 560px;
        }

        .hero-badges {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .hero-badge-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray-600);
            background-color: var(--white);
            padding: 8px 16px;
            border-radius: 999px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
        }

        /* ── TRACKING CARD ── */
        .tracking-card {
            background-color: var(--white);
            border-radius: var(--radius-lg);
            padding: 36px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-200);
        }

        .tracking-card h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .tracking-card p {
            font-size: 0.85rem;
            color: var(--gray-600);
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .tracking-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 0.9rem;
            font-family: inherit;
            color: var(--gray-800);
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .form-control::placeholder {
            color: var(--gray-300);
        }

        /* ── FEATURES SECTION ── */
        .features {
            padding: 80px 24px;
            background-color: var(--white);
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 56px;
        }

        .section-header h3 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.5px;
            margin-bottom: 12px;
        }

        .section-header p {
            font-size: 0.95rem;
            color: var(--gray-600);
        }

        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 32px;
        }

        .feature-card {
            background-color: var(--gray-50);
            border-radius: var(--radius-lg);
            padding: 32px;
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            background-color: var(--white);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background-color: var(--primary-light);
            color: var(--primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .feature-card h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 10px;
        }

        .feature-card p {
            font-size: 0.85rem;
            color: var(--gray-600);
            line-height: 1.5;
        }

        /* ── PRICING SECTION ── */
        .pricing {
            padding: 80px 24px;
            border-bottom: 1px solid var(--gray-200);
        }

        .pricing-grid {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 32px;
            justify-content: center;
        }

        .pricing-card {
            background-color: var(--white);
            border-radius: var(--radius-lg);
            padding: 40px;
            border: 1.5px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: var(--transition);
            position: relative;
        }

        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .pricing-card.featured {
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
        }

        .pricing-card.featured::before {
            content: "PALING POPULER";
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 999px;
            letter-spacing: 0.5px;
        }

        .pricing-header h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 6px;
        }

        .pricing-header p {
            font-size: 0.8rem;
            color: var(--gray-600);
            margin-bottom: 24px;
        }

        .pricing-price {
            margin-bottom: 28px;
        }

        .pricing-price .amount {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--gray-900);
        }

        .pricing-price .unit {
            font-size: 0.9rem;
            color: var(--gray-600);
            font-weight: 500;
        }

        .pricing-features {
            list-style: none;
            margin-bottom: 36px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .pricing-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: var(--gray-600);
        }

        .pricing-features li .check-icon {
            color: var(--success);
            font-weight: bold;
        }

        /* ── RESULT / TIMELINE SECTION ── */
        .result-section {
            padding: 80px 24px;
            background-color: var(--gray-100);
            border-bottom: 1px solid var(--gray-200);
        }

        .result-card {
            max-width: 750px;
            margin: 0 auto;
            background-color: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            border: 1px solid var(--gray-200);
        }

        .result-header-box {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            padding: 24px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .result-header-box .trx-code {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .result-header-box .trx-date {
            font-size: 0.85rem;
            opacity: 0.85;
        }

        .result-details-grid {
            padding: 32px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            border-bottom: 1px solid var(--gray-100);
        }

        .detail-item span {
            display: block;
            font-size: 0.75rem;
            color: var(--gray-600);
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
            letter-spacing: 0.2px;
        }

        .detail-item strong {
            font-size: 1.05rem;
            color: var(--gray-900);
            font-weight: 600;
        }

        .result-timeline {
            padding: 32px;
            border-bottom: 1px solid var(--gray-100);
        }

        .result-timeline h4 {
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--gray-600);
            margin-bottom: 20px;
            letter-spacing: 0.5px;
            font-weight: 700;
        }

        .timeline-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
            background-color: var(--gray-50);
            padding: 24px;
            border-radius: var(--radius);
            border: 1px solid var(--gray-200);
        }

        .timeline-step {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .timeline-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            background-color: var(--gray-200);
            color: var(--gray-400);
            transition: var(--transition);
        }

        .timeline-step.active .timeline-circle {
            background-color: var(--primary);
            color: var(--white);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .timeline-step.passed .timeline-circle {
            background-color: var(--success);
            color: var(--white);
        }

        .timeline-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-400);
        }

        .timeline-step.active .timeline-label {
            color: var(--primary);
            font-weight: 700;
        }

        .timeline-step.passed .timeline-label {
            color: var(--gray-800);
        }

        .current-badge {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--primary-dark);
            background-color: var(--primary-light);
            padding: 2px 10px;
            border-radius: 999px;
            margin-left: 8px;
            font-style: italic;
        }

        .status-alert {
            padding: 16px 32px;
        }

        .alert {
            padding: 14px 20px;
            border-radius: var(--radius);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid;
            line-height: 1.5;
        }

        .alert-info {
            background-color: var(--primary-light);
            color: var(--primary-dark);
            border-color: var(--primary);
        }

        .alert-warning {
            background-color: #fffbeb;
            color: #92400e;
            border-color: var(--warning);
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border-color: var(--success);
        }

        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border-color: var(--danger);
        }

        /* ── FOOTER ── */
        .footer {
            background-color: var(--gray-900);
            color: var(--gray-300);
            padding: 60px 24px 30px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr 1fr;
            gap: 60px;
            margin-bottom: 48px;
        }

        .footer-brand h4 {
            color: var(--white);
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .footer-brand p {
            font-size: 0.85rem;
            color: var(--gray-300);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .footer-links h5, .footer-contact h5 {
            color: var(--white);
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .footer-links ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links a {
            font-size: 0.85rem;
            color: var(--gray-300);
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary);
            padding-left: 4px;
        }

        .footer-contact p {
            font-size: 0.85rem;
            line-height: 1.6;
            margin-bottom: 12px;
            display: flex;
            align-items: start;
            gap: 10px;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: var(--gray-600);
        }

        /* Responsive Layout */
        @media (max-width: 992px) {
            .hero-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .footer-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .hero-content h2 {
                font-size: 2.25rem;
            }
        }

        @media (max-width: 768px) {
            .navbar-menu {
                display: none;
            }
            .result-details-grid {
                grid-template-columns: 1fr;
            }
            .navbar-container {
                padding: 12px 16px;
            }
        }
    </style>
</head>
<body>

    <!-- ── NAVBAR ── -->
    <header class="navbar">
        <div class="navbar-container">
            <a href="<?= base_url() ?>" class="navbar-brand">
                <div class="logo-icon">🧺</div>
                <span>LaundryKu</span>
            </a>
            <ul class="navbar-menu">
                <li><a href="#beranda">Beranda</a></li>
                <li><a href="#keunggulan">Kenapa Kami</a></li>
                <li><a href="#tarif">Layanan & Tarif</a></li>
                <li><a href="#lacak-cucian-section">Lacak Cucian</a></li>
                <li><a href="#kontak">Hubungi Kami</a></li>
            </ul>
            <div class="navbar-actions">
                <a href="<?= base_url('login') ?>" class="btn btn-outline">Masuk</a>
                <a href="<?= base_url('register') ?>" class="btn btn-primary">Daftar</a>
            </div>
        </div>
    </header>

    <!-- ── HERO SECTION ── -->
    <section class="hero" id="beranda">
        <div class="hero-container">
            <div class="hero-content">
                <h2>Cucian <span>Bersih, Wangi & Cepat</span> Tanpa Repot</h2>
                <p>Kami melayani jasa laundry premium kiloan dan satuan dengan kualitas cuci terbaik, deterjen ramah lingkungan, setrika uap, dan layanan lacak cucian online pertama yang terpercaya.</p>
                
                <div class="hero-badges">
                    <div class="hero-badge-item">⚡ Layanan Express 24 Jam</div>
                    <div class="hero-badge-item">🧼 Deterjen Anti-Bakteri</div>
                    <div class="hero-badge-item">🛡️ 100% Garansi Bersih</div>
                </div>
            </div>

            <!-- CARD LACAK CUCIAN -->
            <div class="tracking-card" id="lacak-cucian-section">
                <h3>🔍 Lacak Nota Cucian</h3>
                <p>Masukkan Kode Transaksi yang tertera pada nota fisik cucian Anda untuk memantau proses laundry secara real-time.</p>
                
                <form action="<?= base_url('status/cari') ?>" method="post" class="tracking-form" id="form-cek-status">
                    <!-- Native CodeIgniter 3 CSRF input -->
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="kode_transaksi" 
                            id="kode_transaksi" 
                            class="form-control" 
                            placeholder="Contoh: TRX-20260520-001" 
                            value="<?= isset($kode_cari) ? htmlspecialchars($kode_cari) : '' ?>"
                            style="text-transform: uppercase;"
                            required
                            autocomplete="off">
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; height: 46px;">
                        Cek Status Sekarang
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- ── HASIL PENCARIAN STATUS ── -->
    <?php if ($tidak_ada || $hasil): ?>
        <section class="result-section" id="hasil-pencarian-lacak">
            
            <?php if ($tidak_ada): ?>
                <div class="result-card" style="padding: 40px; text-align: center;">
                    <span style="font-size: 3.5rem; display: block; margin-bottom: 16px;">❌</span>
                    <h3 style="font-size: 1.25rem; font-weight:800; color: var(--gray-900); margin-bottom: 8px;">Kode Nota Tidak Ditemukan</h3>
                    <p style="font-size: 0.9rem; color: var(--gray-600); max-width: 500px; margin: 0 auto 24px;">
                        Kode transaksi <strong><?= htmlspecialchars($kode_cari ?? '') ?></strong> tidak terdaftar di sistem kami. Pastikan Anda memasukkan kode persis seperti yang tertulis pada nota belanja laundry.
                    </p>
                    <a href="#lacak-cucian-section" class="btn btn-secondary btn-sm">Ulangi Pencarian</a>
                </div>
            <?php elseif ($hasil): ?>
                <?php 
                $t = $hasil;
                $badge_class = 'proses';
                if ($t['status'] === 'Selesai') {
                    $badge_class = 'selesai';
                } elseif ($t['status'] === 'Diambil') {
                    $badge_class = 'diambil';
                }
                ?>
                <div class="result-card">
                    <div class="result-header-box">
                        <span class="trx-code">📋 Nota: <?= htmlspecialchars($t['kode_transaksi']) ?></span>
                        <span class="trx-date">Tanggal Masuk: <?= date('d M Y', strtotime($t['tanggal'])) ?></span>
                    </div>
                    
                    <div class="result-details-grid">
                        <div class="detail-item">
                            <span>Nama Pelanggan</span>
                            <strong><?= htmlspecialchars($t['nama_pelanggan']) ?></strong>
                        </div>
                        <div class="detail-item">
                            <span>Jenis Layanan</span>
                            <strong><?= htmlspecialchars($t['jenis_layanan']) ?></strong>
                        </div>
                        <div class="detail-item">
                            <span>Berat Cucian</span>
                            <strong><?= number_format($t['berat'], 2) ?> kg</strong>
                        </div>
                        <div class="detail-item">
                            <span>Total Tagihan</span>
                            <strong style="color: var(--primary); font-size:1.15rem; font-weight:700;">
                                Rp <?= number_format($t['harga'], 0, ',', '.') ?>
                            </strong>
                        </div>
                    </div>

                    <!-- TIMELINE -->
                    <div class="result-timeline">
                        <h4>Tahapan Operasional Cucian</h4>
                        <div class="timeline-container">
                            <?php 
                            $status_flow = ['Menunggu', 'Dicuci', 'Dikeringkan', 'Disetrika', 'Selesai', 'Diambil'];
                            $current_index = array_search($t['status'], $status_flow);
                            if ($current_index === FALSE) $current_index = 0;

                            foreach ($status_flow as $idx => $step):
                                $is_passed = $idx <= $current_index;
                                $is_current = $idx === $current_index;
                                $step_class = $is_current ? 'active' : ($is_passed ? 'passed' : '');
                            ?>
                                <div class="timeline-step <?= $step_class ?>">
                                    <div class="timeline-circle">
                                        <?= $is_passed ? '✓' : $idx + 1 ?>
                                    </div>
                                    <span class="timeline-label">
                                        <?= htmlspecialchars($step) ?>
                                        <?= $is_current ? '<span class="current-badge">Sedang diproses</span>' : '' ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- ALERT NOTIFIKASI -->
                    <div class="status-alert">
                        <?php if ($t['status'] === 'Menunggu'): ?>
                            <div class="alert alert-info">
                                ⏳ Cucian Anda telah terdaftar dan sedang dalam **antrean antrean** masuk mesin cuci.
                            </div>
                        <?php elseif (in_array($t['status'], ['Dicuci', 'Dikeringkan', 'Disetrika'])): ?>
                            <div class="alert alert-warning">
                                ⚙️ Pakaian Anda **sedang dikerjakan** oleh staf kami di ruang cuci/gosok.
                            </div>
                        <?php elseif ($t['status'] === 'Selesai'): ?>
                            <div class="alert alert-success">
                                🎉 Hore! Cucian Anda **sudah bersih, rapi, dan siap diambil** di toko kami.
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info" style="background-color: var(--gray-100); color: var(--gray-600); border-color: var(--gray-300);">
                                📦 Cucian **sudah diserahkan** ke pelanggan. Terima kasih telah mencuci di LaundryKu!
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endif; ?>

        </section>
    <?php endif; ?>

    <!-- ── KEUNGGULAN SECTION ── -->
    <section class="features" id="keunggulan">
        <div class="section-header">
            <h3>Mengapa Memilih Kami?</h3>
            <p>Kami berkomitmen memberikan standar kebersihan tertinggi untuk pakaian Anda.</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🧼</div>
                <h4>Higienis & Anti-Bakteri</h4>
                <p>Setiap pelanggan dicuci terpisah (tidak dicampur) menggunakan deterjen bermutu tinggi dan anti-bakteri.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🔌</div>
                <h4>Setrika Uap Vertikal</h4>
                <p>Menghaluskan pakaian dengan uap panas bersuhu aman, menjaga serat kain tetap awet dan anti-kusut.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h4>Layanan Cepat</h4>
                <p>Butuh pakaian bersih secepatnya? Nikmati Paket Express dengan garansi selesai dalam waktu 24 jam.</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">🔍</div>
                <h4>Lacak Online 24/7</h4>
                <p>Pantau status pengerjaan pakaian Anda kapan saja dan di mana saja langsung melalui web pelacak kami.</p>
            </div>
        </div>
    </section>

    <!-- ── LAYANAN & TARIF SECTION ── -->
    <section class="pricing" id="tarif">
        <div class="section-header">
            <h3>Layanan & Tarif Laundry</h3>
            <p>Harga terjangkau dengan hasil maksimal. Tarif kiloan sudah termasuk cuci, setrika, dan wangi.</p>
        </div>
        
        <div class="pricing-grid">
            
            <!-- REGULER -->
            <div class="pricing-card">
                <div class="pricing-header">
                    <h4>Cuci Reguler</h4>
                    <p>Paket standar hemat berkualitas</p>
                </div>
                <div class="pricing-price">
                    <span class="amount">Rp 7.000</span>
                    <span class="unit">/ kg</span>
                </div>
                <ul class="pricing-features">
                    <li><span class="check-icon">✓</span> Estimasi selesai 2-3 hari</li>
                    <li><span class="check-icon">✓</span> Cuci bersih terpisah</li>
                    <li><span class="check-icon">✓</span> Setrika halus rapi</li>
                    <li><span class="check-icon">✓</span> Pewangi premium</li>
                </ul>
                <a href="#lacak-cucian-section" class="btn btn-outline" style="width: 100%;">Gunakan Layanan</a>
            </div>

            <!-- EXPRESS -->
            <div class="pricing-card featured">
                <div class="pricing-header">
                    <h4>Cuci Express</h4>
                    <p>Pilihan tepat untuk kebutuhan mendesak</p>
                </div>
                <div class="pricing-price">
                    <span class="amount">Rp 10.000</span>
                    <span class="unit">/ kg</span>
                </div>
                <ul class="pricing-features">
                    <li><span class="check-icon">✓</span> Garansi selesai 24 jam</li>
                    <li><span class="check-icon">✓</span> Cuci bersih terpisah</li>
                    <li><span class="check-icon">✓</span> Setrika halus rapi</li>
                    <li><span class="check-icon">✓</span> Pewangi premium</li>
                </ul>
                <a href="#lacak-cucian-section" class="btn btn-primary" style="width: 100%;">Gunakan Layanan</a>
            </div>

            <!-- CUCI + SETRIKA -->
            <div class="pricing-card">
                <div class="pricing-header">
                    <h4>Cuci + Setrika Uap</h4>
                    <p>Layanan komplit ekstra rapi</p>
                </div>
                <div class="pricing-price">
                    <span class="amount">Rp 12.000</span>
                    <span class="unit">/ kg</span>
                </div>
                <ul class="pricing-features">
                    <li><span class="check-icon">✓</span> Estimasi selesai 2 hari</li>
                    <li><span class="check-icon">✓</span> Setrika Uap Profesional</li>
                    <li><span class="check-icon">✓</span> Pakaian wangi & higienis</li>
                    <li><span class="check-icon">✓</span> Cuci bersih terpisah</li>
                </ul>
                <a href="#lacak-cucian-section" class="btn btn-outline" style="width: 100%;">Gunakan Layanan</a>
            </div>

        </div>
    </section>

    <!-- ── FOOTER / KONTAK ── -->
    <footer class="footer" id="kontak">
        <div class="footer-container">
            <div class="footer-brand">
                <h4>🧺 LaundryKu Premium</h4>
                <p>LaundryKu Premium Care berkomitmen menghadirkan layanan cucian bersih, wangi, rapi, dan cepat dengan harga terjangkau untuk menunjang produktivitas harian Anda.</p>
            </div>
            
            <div class="footer-links">
                <h5>Navigasi</h5>
                <ul>
                    <li><a href="#beranda">Beranda</a></li>
                    <li><a href="#keunggulan">Kenapa Kami</a></li>
                    <li><a href="#tarif">Layanan & Tarif</a></li>
                    <li><a href="<?= base_url('login') ?>">Masuk Akun</a></li>
                    <li><a href="<?= base_url('register') ?>">Daftar Pelanggan</a></li>
                </ul>
            </div>
            
            <div class="footer-contact">
                <h5>Hubungi Kami</h5>
                <p>📍 Jl. Raya Laundry No. 101, Jakarta Pusat, Indonesia</p>
                <p>📞 Phone/WhatsApp: +62 812-3456-7890</p>
                <p>✉️ Email: support@laundrykupremium.com</p>
                <p>🕰️ Jam Buka: Setiap Hari (07:00 - 21:00)</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> LaundryKu Premium Care. All rights reserved.</p>
            <p>Jasa Laundry Kiloan & Satuan Terpercaya</p>
        </div>
    </footer>

    <script>
        // Auto uppercase input kode transaksi
        document.getElementById('kode_transaksi').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Auto-scroll ke hasil pencarian jika hasil lacak sedang dimuat
        document.addEventListener("DOMContentLoaded", function() {
            const hasilSection = document.getElementById('hasil-pencarian-lacak');
            if (hasilSection) {
                hasilSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    </script>
</body>
</html>
