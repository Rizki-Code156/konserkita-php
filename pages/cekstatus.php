<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Tiket - KONSERKITA</title>
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
        .card-cek {
            border: none; border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
        }
        .form-control:focus {
            border-color: #6610f2;
            box-shadow: 0 0 0 0.2rem rgba(102,16,242,0.15);
        }
        .result-card {
            border-radius: 14px; border: none;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
        .ticket-detail { background: #f8f9fa; border-radius: 10px; padding: 16px; }
        .ticket-detail .label { font-size: 0.78rem; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
        .ticket-detail .value { font-weight: 600; font-size: 0.95rem; }
        footer { background: #0f0f1a; color: white; padding: 30px 0; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<!-- Hero -->
<div class="hero">
    <div class="container">
        <div class="fs-1 mb-2">🎫</div>
        <h1 class="fw-bold">Cek Status Tiket</h1>
        <p class="lead opacity-75">Masukkan kode booking untuk melihat status tiket kamu</p>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <!-- Form -->
            <div class="card card-cek p-4 mb-4">
                <h6 class="fw-bold mb-3">Masukkan Kode Booking</h6>
                <form method="POST">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">🔍</span>
                        <input type="text" name="kode" class="form-control border-start-0 ps-0"
                               placeholder="Contoh: KNSR1234"
                               value="<?= htmlspecialchars($_POST['kode'] ?? '') ?>"
                               style="text-transform:uppercase" required>
                        <button type="submit" class="btn btn-primary px-4">Cek</button>
                    </div>
                </form>
            </div>

            <!-- Hasil -->
            <?php
            require_once '../includes/db.php';

            if (isset($_POST['kode'])) {
                $kode = strtoupper(trim($_POST['kode']));

                $stmt = $pdo->prepare("
                    SELECT t.kode, t.kategori, t.harga, t.status,
                           k.nama AS konser, k.kota AS lokasi,
                           DATE_FORMAT(k.tanggal, '%d %b %Y') AS tanggal
                    FROM tiket t
                    LEFT JOIN konser k ON t.konser_id = k.id
                    WHERE t.kode = ?
                    LIMIT 1
                ");
                $stmt->execute([$kode]);
                $t = $stmt->fetch();

                if ($t) {
                    $isAktif   = $t['status'] === 'Aktif';
                    $isPending = $t['status'] === 'Menunggu Pembayaran';
                    $bgClass   = $isAktif ? 'bg-success' : ($isPending ? 'bg-warning text-dark' : 'bg-danger');
                    $icon      = $isAktif ? '✅' : ($isPending ? '⏳' : '❌');
                    echo '
                    <div class="card result-card p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="fs-2">'.$icon.'</span>
                            <div>
                                <span class="badge '.$bgClass.' mb-1">'.$t['status'].'</span>
                                <h6 class="fw-bold mb-0">'.htmlspecialchars($t['konser']).'</h6>
                            </div>
                        </div>
                        <div class="ticket-detail">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="label">Kode Booking</div>
                                    <div class="value"><code>'.htmlspecialchars($t['kode']).'</code></div>
                                </div>
                                <div class="col-6">
                                    <div class="label">Kategori</div>
                                    <div class="value">'.htmlspecialchars($t['kategori']).'</div>
                                </div>
                                <div class="col-6">
                                    <div class="label">Tanggal</div>
                                    <div class="value">📅 '.htmlspecialchars($t['tanggal']).'</div>
                                </div>
                                <div class="col-6">
                                    <div class="label">Harga</div>
                                    <div class="value" style="color:#6610f2">Rp '.number_format($t['harga'],0,',','.').'</div>
                                </div>
                                <div class="col-12">
                                    <div class="label">Lokasi</div>
                                    <div class="value">📍 '.htmlspecialchars($t['lokasi']).'</div>
                                </div>
                            </div>
                        </div>
                    </div>';
                } else {
                    echo '
                    <div class="card result-card p-4 text-center">
                        <div class="fs-1 mb-2">❌</div>
                        <h6 class="fw-bold">Kode Tidak Ditemukan</h6>
                        <p class="text-muted small mb-0">Pastikan kode booking <strong>'.htmlspecialchars($kode).'</strong> sudah benar dan coba lagi.</p>
                    </div>';
                }
            }
            ?>

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
