<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KonserKita - Tiket Konser Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }

        /* Navbar */
        .navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.08); }

        /* Hero Carousel */
        .carousel-item img { height: 520px; object-fit: cover; filter: brightness(55%); }
        .carousel-caption { bottom: 15%; }
        .carousel-caption h1 { text-shadow: 0 2px 8px rgba(0,0,0,0.4); }
        .hero-badge {
            display: inline-block;
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            color: white; font-size: 0.75rem;
            padding: 4px 14px; border-radius: 50px;
            margin-bottom: 12px; letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Buttons */
        .btn-primary { background: linear-gradient(135deg, #6610f2, #0d6efd); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, #59359a, #0b5ed7); border: none; }
        .btn-outline-primary { color: #6610f2; border-color: #6610f2; }
        .btn-outline-primary:hover { background: linear-gradient(135deg, #6610f2, #0d6efd); border-color: transparent; }

        /* Section header accent */
        .section-title { position: relative; display: inline-block; }
        .section-title::after {
            content: '';
            display: block;
            width: 50px; height: 4px;
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            border-radius: 2px; margin-top: 6px;
        }

        /* Kategori pills */
        .kategori-pill {
            display: inline-block;
            padding: 8px 22px; border-radius: 50px;
            background: white;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            font-size: 0.88rem; font-weight: 600;
            color: #444; text-decoration: none;
            transition: all 0.2s;
        }
        .kategori-pill:hover {
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            color: white; transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102,16,242,0.3);
        }

        /* Event cards */
        .card-event {
            border: none; border-radius: 16px; overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .card-event:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(102,16,242,0.15);
        }
        .card-event .card-img-top { height: 200px; object-fit: cover; }
        .card-event .price-tag { color: #6610f2; font-weight: 700; }

        /* Stats banner */
        .stats-banner {
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            color: white; padding: 50px 0;
        }
        .stat-item h2 { font-size: 2.4rem; font-weight: 700; margin-bottom: 4px; }

        /* CTA section */
        .cta-section {
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            color: white; padding: 70px 0; text-align: center;
        }

        /* Footer */
        .footer { background: #0f0f1a; color: white; padding: 50px 0 30px; }
        .footer a { color: rgba(255,255,255,0.65); text-decoration: none; transition: color 0.2s; }
        .footer a:hover { color: white; }
        .footer-brand { font-size: 1.4rem; font-weight: 700;
            background: linear-gradient(135deg, #a78bfa, #60a5fa);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<!-- ── WELCOME BANNER ─────────────────────────────── -->
<div style="background: linear-gradient(135deg, #0f0f1a, #1a0533); color: white; padding: 28px 0; text-align: center;">
    <div class="container">
        <p class="mb-1" style="font-size:0.8rem; letter-spacing:3px; text-transform:uppercase; color:rgba(167,139,250,0.8);">🎶 #1 Platform Tiket Konser di Cirebon</p>
        <h2 class="fw-bold mb-1" style="font-size:clamp(1.4rem,3vw,2rem); background: linear-gradient(135deg, #a78bfa, #60a5fa); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">
            Satu Tiket, Sejuta Kenangan
        </h2>
        <p class="mb-0" style="color:rgba(255,255,255,0.55); font-size:0.92rem;">Temukan & beli tiket konser favoritmu — cepat, aman, dan resmi.</p>
    </div>
</div>

<!-- ── HERO CAROUSEL ─────────────────────────────── -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&cs=tinysrgb&w=1400" class="d-block w-100" alt="Concert 1">
            <div class="carousel-caption d-none d-md-block">
                <span class="hero-badge">🎵 Live Concert</span>
                <h1 class="display-4 fw-bold">Dewa 19 All Stars</h1>
                <p class="lead mb-4">Alun-Alun Kejaksan Cirebon &mdash; 12 Agustus 2026</p>
                <a href="<?= isset($_SESSION['user']) ? 'pages/cekstatus.php' : 'auth/login.php' ?>" class="btn btn-primary btn-lg px-5 rounded-pill">Beli Tiket</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://images.pexels.com/photos/1190297/pexels-photo-1190297.jpeg?auto=compress&cs=tinysrgb&w=1400" class="d-block w-100" alt="Concert 2">
            <div class="carousel-caption d-none d-md-block">
                <span class="hero-badge">🎧 Electronic</span>
                <h1 class="display-4 fw-bold">Electronic Music Fest</h1>
                <p class="lead mb-4">Taman Ade Irma Cirebon &mdash; 20 September 2026</p>
                <a href="pages/kategori.php?kategori=pop" class="btn btn-primary btn-lg px-5 rounded-pill">Lihat Detail</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://images.pexels.com/photos/167636/pexels-photo-167636.jpeg?auto=compress&cs=tinysrgb&w=1400" class="d-block w-100" alt="Concert 3">
            <div class="carousel-caption d-none d-md-block">
                <span class="hero-badge">🎷 Jazz</span>
                <h1 class="display-4 fw-bold">Jazz Traffic Festival</h1>
                <p class="lead mb-4">Keraton Kanoman Cirebon &mdash; 5 Oktober 2026</p>
                <a href="pages/cekstatus.php" class="btn btn-primary btn-lg px-5 rounded-pill">Cek Kuota</a>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- ── KATEGORI PILLS ─────────────────────────────── -->
<div class="container my-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold section-title mx-auto" style="width:fit-content">Jelajahi Kategori</h2>
    </div>
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="pages/kategori.php?kategori=pop"     class="kategori-pill">🎤 Pop</a>
        <a href="pages/kategori.php?kategori=rock"    class="kategori-pill">🎸 Rock</a>
        <a href="pages/kategori.php?kategori=jazz"    class="kategori-pill">🎷 Jazz</a>
        <a href="pages/kategori.php?kategori=kpop"    class="kategori-pill">💜 K-Pop</a>
    </div>
</div>

<!-- ── KONSER TERPOPULER ──────────────────────────── -->
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h2 class="fw-bold section-title mb-0">Konser Terpopuler</h2>
        <a href="pages/kategori.php?kategori=pop" class="text-decoration-none" style="color:#6610f2">Lihat Semua &rarr;</a>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card card-event h-100">
                <img src="https://images.pexels.com/photos/1105666/pexels-photo-1105666.jpeg?auto=compress&cs=tinysrgb&w=600" class="card-img-top" alt="Event 1">
                <div class="card-body">
                    <span class="badge bg-success mb-2">Tersedia</span>
                    <h5 class="card-title fw-bold">Sheila On 7 "Tunggu Aku Di"</h5>
                    <p class="text-muted small mb-1">📍 Gedung Kesenian Cirebon</p>
                    <p class="text-muted small mb-3">📅 15 Juli 2026</p>
                    <p class="price-tag mb-0">Rp 450.000 &mdash; Rp 1.500.000</p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <a href="<?= isset($_SESSION['user']) ? 'pages/pembayaran.php?konser='.urlencode('Sheila On 7 "Tunggu Aku Di"').'&harga=450000&tanggal=15+Juli+2026&lokasi=Gedung+Kesenian+Cirebon' : 'auth/login.php' ?>" class="btn btn-outline-primary w-100 rounded-pill">Pesan Tiket</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-event h-100">
                <img src="https://images.pexels.com/photos/1540406/pexels-photo-1540406.jpeg?auto=compress&cs=tinysrgb&w=600" class="card-img-top" alt="Event 2">
                <div class="card-body">
                    <span class="badge bg-warning text-dark mb-2">Sisa Sedikit</span>
                    <h5 class="card-title fw-bold">Coldplay Live in Cirebon</h5>
                    <p class="text-muted small mb-1">📍 Stadion Bima Cirebon</p>
                    <p class="text-muted small mb-3">📅 20 Agustus 2026</p>
                    <p class="price-tag mb-0">Rp 1.200.000 &mdash; Rp 11.000.000</p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <a href="<?= isset($_SESSION['user']) ? 'pages/pembayaran.php?konser='.urlencode('Coldplay Live in Cirebon').'&harga=1200000&tanggal=20+Agustus+2026&lokasi=Stadion+Bima+Cirebon' : 'auth/login.php' ?>" class="btn btn-outline-primary w-100 rounded-pill">Pesan Tiket</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-event h-100">
                <img src="https://images.pexels.com/photos/995301/pexels-photo-995301.jpeg?auto=compress&cs=tinysrgb&w=600" class="card-img-top" alt="Event 3">
                <div class="card-body">
                    <span class="badge bg-danger mb-2">Sold Out</span>
                    <h5 class="card-title fw-bold">Tulus: Tur Manusia</h5>
                    <p class="text-muted small mb-1">📍 Taman Air Mancur Cirebon</p>
                    <p class="text-muted small mb-3">📅 10 September 2026</p>
                    <p class="price-tag mb-0">Rp 350.000 &mdash; Rp 1.000.000</p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <button class="btn btn-secondary w-100 rounded-pill" disabled>Habis Terjual</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── STATS BANNER ───────────────────────────────── -->
<div class="stats-banner">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3 stat-item">
                <h2>50+</h2>
                <p class="mb-0 opacity-75">Konser Tersedia</p>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <h2>10K+</h2>
                <p class="mb-0 opacity-75">Tiket Terjual</p>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <h2>5</h2>
                <p class="mb-0 opacity-75">Kategori Musik</p>
            </div>
            <div class="col-6 col-md-3 stat-item">
                <h2>100%</h2>
                <p class="mb-0 opacity-75">Tiket Resmi</p>
            </div>
        </div>
    </div>
</div>

<!-- ── CTA ────────────────────────────────────────── -->
<div class="cta-section">
    <div class="container">
        <h2 class="fw-bold mb-3">Siap Nonton Konser Favoritmu?</h2>
        <p class="lead opacity-75 mb-4">Daftar sekarang dan dapatkan akses ke ribuan tiket konser terbaik.</p>
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="auth/register.php" class="btn btn-light btn-lg px-5 rounded-pill fw-semibold me-2" style="color:#6610f2">Daftar Gratis</a>
            <a href="auth/login.php"    class="btn btn-outline-light btn-lg px-5 rounded-pill">Masuk</a>
        <?php else: ?>
            <a href="pages/kategori.php?kategori=pop" class="btn btn-light btn-lg px-5 rounded-pill fw-semibold" style="color:#6610f2">Lihat Semua Konser</a>
        <?php endif; ?>
    </div>
</div>

<!-- ── FOOTER ─────────────────────────────────────── -->
<footer class="footer">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="footer-brand mb-2">KONSERKITA</div>
                <p class="small opacity-50">Platform terpercaya untuk pembelian tiket konser musik legal dan aman di Cirebon.</p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold mb-3">Navigasi</h6>
                <div class="d-flex flex-column gap-2 small">
                    <a href="index.php">Beranda</a>
                    <a href="pages/kategori.php?kategori=pop">Kategori Konser</a>
                    <a href="pages/panduan.php">Panduan</a>
                    <a href="pages/cekstatus.php">Cek Status Tiket</a>
                </div>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold mb-3">Bantuan</h6>
                <div class="d-flex flex-column gap-2 small">
                    <a href="pages/panduan.php">FAQ</a>
                    <a href="pages/panduan.php">Syarat &amp; Ketentuan</a>
                    <a href="pages/cekstatus.php">Hubungi Kami</a>
                </div>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,0.1)">
        <p class="small text-center mb-0" style="color:rgba(255,255,255,0.35)">&copy; 2026 KonserKita System Informasi. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
