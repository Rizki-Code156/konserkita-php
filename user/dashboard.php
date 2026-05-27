<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    header('Location: ../auth/login.php'); exit;
}

require_once '../includes/db.php';

$nama     = $_SESSION['nama'] ?? 'User';
$username = $_SESSION['user'] ?? '';

// Ambil user_id
$stmtU = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
$stmtU->execute([$username]);
$userRow = $stmtU->fetch();
$userId  = $userRow['id'] ?? 0;

// Ambil tiket milik user ini saja
$stmtT = $pdo->prepare("
    SELECT t.*, k.nama AS nama_konser,
           k.kota AS lokasi_konser,
           DATE_FORMAT(k.tanggal,'%d %b %Y') AS tgl_konser
    FROM tiket t
    LEFT JOIN konser k ON t.konser_id = k.id
    WHERE t.user_id = ?
    ORDER BY t.created_at DESC
");
$stmtT->execute([$userId]);
$tiket = $stmtT->fetchAll();

$totalTiket   = count($tiket);
$tiketAktif   = count(array_filter($tiket, fn($t) => $t['status'] === 'Aktif'));
$tiketPending = count(array_filter($tiket, fn($t) => $t['status'] === 'Menunggu Pembayaran'));

// Konser tersedia untuk rekomendasi
$konserRekomendasi = $pdo->query("SELECT * FROM konser WHERE status != 'Sold Out' ORDER BY tanggal ASC LIMIT 3")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - KONSERKITA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .hero-dashboard { background: linear-gradient(135deg, #6610f2, #0d6efd); color: white; padding: 50px 0 80px; }
        .card-stat { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); transition: transform 0.2s; }
        .card-stat:hover { transform: translateY(-4px); }
        .card-tiket { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.07); }
        .avatar { width:60px;height:60px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.6rem; }
        .section-offset { margin-top: -50px; }
        .btn-primary { background: linear-gradient(135deg,#6610f2,#0d6efd); border:none; }
        .btn-primary:hover { background: linear-gradient(135deg,#59359a,#0b5ed7); border:none; }
        .btn-outline-primary { color:#6610f2; border-color:#6610f2; }
        .btn-outline-primary:hover { background:linear-gradient(135deg,#6610f2,#0d6efd); border-color:transparent; color:white; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="hero-dashboard">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar">👤</div>
            <div>
                <h4 class="fw-bold mb-0">Halo, <?= htmlspecialchars($nama) ?>!</h4>
                <p class="mb-0 opacity-75 small">@<?= htmlspecialchars($username) ?> &mdash; Member KonserKita</p>
            </div>
        </div>
    </div>
</div>

<div class="container section-offset">

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card card-stat p-3 text-center">
                <div class="fs-2">🎟️</div>
                <h5 class="fw-bold mb-0"><?= $totalTiket ?></h5>
                <small class="text-muted">Total Tiket</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat p-3 text-center">
                <div class="fs-2">✅</div>
                <h5 class="fw-bold mb-0"><?= $tiketAktif ?></h5>
                <small class="text-muted">Tiket Aktif</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat p-3 text-center">
                <div class="fs-2">⏳</div>
                <h5 class="fw-bold mb-0"><?= $tiketPending ?></h5>
                <small class="text-muted">Menunggu Bayar</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat p-3 text-center">
                <div class="fs-2">🎵</div>
                <h5 class="fw-bold mb-0"><?= $pdo->query("SELECT COUNT(*) FROM konser WHERE status != 'Sold Out'")->fetchColumn() ?></h5>
                <small class="text-muted">Konser Tersedia</small>
            </div>
        </div>
    </div>

    <!-- Tiket Saya -->
    <div class="card card-tiket p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">🎟️ Tiket Saya</h5>
            <a href="../pages/cekstatus.php" class="btn btn-sm btn-outline-primary rounded-pill">Cek Status</a>
        </div>

        <?php if (empty($tiket)): ?>
            <div class="text-center py-4 text-muted">
                <div class="fs-1">🎫</div>
                <p class="mb-3">Kamu belum punya tiket. Yuk beli sekarang!</p>
                <a href="../index.php" class="btn btn-primary rounded-pill px-4">Lihat Konser</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" id="tabelTiketUser">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Konser</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tiket as $t): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($t['kode']) ?></code></td>
                            <td>
                                <span class="fw-semibold"><?= htmlspecialchars($t['nama_konser']) ?></span><br>
                                <small class="text-muted">📍 <?= htmlspecialchars($t['lokasi_konser']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($t['tgl_konser']) ?></td>
                            <td><?= htmlspecialchars($t['kategori']) ?></td>
                            <td>Rp <?= number_format($t['harga'], 0, ',', '.') ?></td>
                            <td>
                                <?php
                                $badgeClass = match($t['status']) {
                                    'Aktif'                => 'bg-success',
                                    'Menunggu Pembayaran'  => 'bg-warning text-dark',
                                    default                => 'bg-danger',
                                };
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $t['status'] ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Konser Rekomendasi dari DB -->
    <div class="card card-tiket p-4 mb-5">
        <h5 class="fw-bold mb-3">🎶 Konser Untukmu</h5>
        <?php if (!empty($konserRekomendasi)): ?>
        <div class="row g-3">
            <?php foreach ($konserRekomendasi as $k): ?>
            <div class="col-md-4">
                <div class="card border-0 bg-light rounded-3 p-3">
                    <span class="badge bg-success mb-2" style="width:fit-content"><?= $k['status'] ?></span>
                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($k['nama']) ?></h6>
                    <p class="text-muted small mb-1">📍 <?= $k['kota'] ?></p>
                    <p class="text-muted small mb-2">📅 <?= date('d M Y', strtotime($k['tanggal'])) ?></p>
                    <p class="fw-bold mb-2" style="color:#6610f2;font-size:0.9rem">Rp <?= number_format($k['harga'],0,',','.') ?></p>
                    <a href="../pages/pembayaran.php?konser=<?= urlencode($k['nama']) ?>&harga=<?= $k['harga'] ?>&tanggal=<?= urlencode(date('d M Y', strtotime($k['tanggal']))) ?>&lokasi=<?= urlencode($k['kota']) ?>"
                       class="btn btn-sm btn-primary rounded-pill">Beli Tiket</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="text-muted small">Tidak ada konser tersedia saat ini.</p>
        <?php endif; ?>
    </div>

</div>

<footer style="background:#0f0f1a;color:white;padding:30px 0;">
    <div class="container text-center">
        <p class="mb-0 small" style="color:rgba(255,255,255,0.35)">&copy; 2026 KonserKita. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#tabelTiketUser').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Tiket tidak ditemukan",
            paginate: { previous: "‹", next: "›" }
        }
    });
});
</script>
</body>
</html>
