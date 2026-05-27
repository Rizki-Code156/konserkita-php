<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan - KONSERKITA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .hero {
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            color: white; padding: 60px 0; text-align: center;
        }
        .btn-primary { background: linear-gradient(135deg, #6610f2, #0d6efd); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, #59359a, #0b5ed7); border: none; }
        .card-panduan {
            border: none; border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        }
        .step-number {
            width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            color: white; font-weight: 700; font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
        }
        .faq-item {
            border: none; border-radius: 12px; margin-bottom: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .faq-item .accordion-button {
            font-weight: 600; border-radius: 12px;
        }
        .faq-item .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            color: white; box-shadow: none;
        }
        .faq-item .accordion-button:not(.collapsed)::after { filter: brightness(10); }
        .section-title { position: relative; display: inline-block; }
        .section-title::after {
            content: ''; display: block; width: 50px; height: 4px;
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            border-radius: 2px; margin-top: 6px;
        }
        footer { background: #0f0f1a; color: white; padding: 30px 0; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<!-- Hero -->
<div class="hero">
    <div class="container">
        <div class="fs-1 mb-2">📖</div>
        <h1 class="fw-bold">Panduan Pembelian Tiket</h1>
        <p class="lead opacity-75">Ikuti langkah mudah berikut untuk membeli tiket konser favoritmu</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4">

        <!-- Langkah-langkah -->
        <div class="col-lg-7">
            <h4 class="fw-bold section-title mb-4">Cara Pembelian</h4>
            <div class="card card-panduan p-4">
                <div class="d-flex gap-3 align-items-start mb-4">
                    <div class="step-number">1</div>
                    <div>
                        <h6 class="fw-bold mb-1">Pilih Konser</h6>
                        <p class="text-muted small mb-0">Jelajahi konser di halaman Beranda atau pilih berdasarkan kategori musik favoritmu.</p>
                    </div>
                </div>
                <div class="d-flex gap-3 align-items-start mb-4">
                    <div class="step-number">2</div>
                    <div>
                        <h6 class="fw-bold mb-1">Login / Daftar Akun</h6>
                        <p class="text-muted small mb-0">Masuk ke akun KonserKita atau buat akun baru secara gratis untuk melanjutkan pemesanan.</p>
                    </div>
                </div>
                <div class="d-flex gap-3 align-items-start mb-4">
                    <div class="step-number">3</div>
                    <div>
                        <h6 class="fw-bold mb-1">Pilih Kategori Tiket</h6>
                        <p class="text-muted small mb-0">Pilih kategori tiket sesuai budget dan preferensimu: Regular, VIP, SVIP, atau Festival.</p>
                    </div>
                </div>
                <div class="d-flex gap-3 align-items-start mb-4">
                    <div class="step-number">4</div>
                    <div>
                        <h6 class="fw-bold mb-1">Lakukan Pembayaran</h6>
                        <p class="text-muted small mb-0">Selesaikan pembayaran melalui metode yang tersedia. Pembayaran aman dan terenkripsi.</p>
                    </div>
                </div>
                <div class="d-flex gap-3 align-items-start">
                    <div class="step-number">5</div>
                    <div>
                        <h6 class="fw-bold mb-1">Cek Status Tiket</h6>
                        <p class="text-muted small mb-0">Gunakan kode booking yang dikirim ke email untuk mengecek status tiket kapan saja.</p>
                    </div>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <a href="../index.php" class="btn btn-primary rounded-pill px-4">Lihat Konser</a>
                <a href="cekstatus.php" class="btn btn-outline-primary rounded-pill px-4">Cek Status Tiket</a>
            </div>
        </div>

        <!-- FAQ -->
        <div class="col-lg-5">
            <h4 class="fw-bold section-title mb-4">FAQ</h4>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item faq-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            Apakah tiket bisa dikembalikan?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body small text-muted">
                            Tiket yang sudah dibeli tidak dapat dikembalikan kecuali konser dibatalkan oleh penyelenggara.
                        </div>
                    </div>
                </div>
                <div class="accordion-item faq-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            Bagaimana cara mendapatkan kode booking?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body small text-muted">
                            Kode booking akan dikirimkan ke email kamu setelah pembayaran berhasil dikonfirmasi.
                        </div>
                    </div>
                </div>
                <div class="accordion-item faq-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Metode pembayaran apa saja yang tersedia?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body small text-muted">
                            Tersedia transfer bank, dompet digital (GoPay, OVO, Dana), dan kartu kredit/debit.
                        </div>
                    </div>
                </div>
                <div class="accordion-item faq-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Apakah tiket bisa dipindahtangankan?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body small text-muted">
                            Tiket tidak dapat dipindahtangankan. Nama pemegang tiket harus sesuai dengan identitas saat masuk venue.
                        </div>
                    </div>
                </div>
                <div class="accordion-item faq-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                            Berapa lama proses konfirmasi pembayaran?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body small text-muted">
                            Konfirmasi pembayaran biasanya membutuhkan waktu 1x24 jam pada hari kerja.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<footer>
    <div class="container text-center">
        <p class="mb-0 small" style="color:rgba(255,255,255,0.35)">&copy; 2026 KonserKita. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
