<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Konser - KONSERKITA</title>
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
        .btn-outline-primary { color: #6610f2; border-color: #6610f2; }
        .btn-outline-primary:hover { background: linear-gradient(135deg, #6610f2, #0d6efd); border-color: transparent; color: white; }
        .kategori-pill {
            display: inline-block; padding: 8px 22px; border-radius: 50px;
            background: white; box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            font-size: 0.88rem; font-weight: 600; color: #444;
            text-decoration: none; transition: all 0.2s;
        }
        .kategori-pill:hover, .kategori-pill.active {
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            color: white; transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102,16,242,0.3);
        }
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
        .price-tag { color: #6610f2; font-weight: 700; }
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

<?php
$kategori = $_GET['kategori'] ?? 'pop';
$judulMap = ['pop'=>'Pop','rock'=>'Rock','jazz'=>'Jazz','kpop'=>'K-Pop'];
$judul = $judulMap[$kategori] ?? 'Pop';

$emojiMap = ['pop'=>'🎤','rock'=>'🎸','jazz'=>'🎷','kpop'=>'💜'];
$emoji = $emojiMap[$kategori] ?? '🎵';

$data = [
    "pop"  => [
        ["nama"=>"Konser Raisa",          "kota"=>"Gedung Kesenian Cirebon",   "tanggal"=>"10 Jul 2026","harga"=>"Rp 500.000",  "status"=>"Tersedia",    "img"=>"https://images.pexels.com/photos/1105666/pexels-photo-1105666.jpeg?auto=compress&cs=tinysrgb&w=600"],
        ["nama"=>"Konser Tulus",          "kota"=>"Taman Air Mancur Cirebon",  "tanggal"=>"15 Jul 2026","harga"=>"Rp 450.000",  "status"=>"Tersedia",    "img"=>"https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&cs=tinysrgb&w=600"],
        ["nama"=>"Sheila On 7",           "kota"=>"Gedung Kesenian Cirebon",   "tanggal"=>"15 Jul 2026","harga"=>"Rp 1.500.000","status"=>"Tersedia",    "img"=>"https://images.pexels.com/photos/1540406/pexels-photo-1540406.jpeg?auto=compress&cs=tinysrgb&w=600"],
        ["nama"=>"Coldplay Live Cirebon", "kota"=>"Stadion Bima Cirebon",      "tanggal"=>"20 Agu 2026","harga"=>"Rp 1.200.000","status"=>"Sisa Sedikit","img"=>"https://images.pexels.com/photos/1190297/pexels-photo-1190297.jpeg?auto=compress&cs=tinysrgb&w=600"],
    ],
    "rock" => [
        ["nama"=>"Konser Slank",          "kota"=>"Lapangan Kebumen Cirebon",  "tanggal"=>"22 Jul 2026","harga"=>"Rp 350.000",  "status"=>"Sisa Sedikit","img"=>"https://images.pexels.com/photos/167636/pexels-photo-167636.jpeg?auto=compress&cs=tinysrgb&w=600"],
        ["nama"=>"Konser Noah",           "kota"=>"GOR Bima Cirebon",          "tanggal"=>"1 Agu 2026", "harga"=>"Rp 400.000",  "status"=>"Tersedia",    "img"=>"https://images.pexels.com/photos/995301/pexels-photo-995301.jpeg?auto=compress&cs=tinysrgb&w=600"],
        ["nama"=>"Dewa 19 All Stars",     "kota"=>"Alun-Alun Kejaksan Cirebon","tanggal"=>"12 Agu 2026","harga"=>"Rp 800.000",  "status"=>"Tersedia",    "img"=>"https://images.pexels.com/photos/1105666/pexels-photo-1105666.jpeg?auto=compress&cs=tinysrgb&w=600"],
    ],
    "jazz" => [
        ["nama"=>"Java Jazz Festival",    "kota"=>"Keraton Kasepuhan Cirebon", "tanggal"=>"5 Agu 2026", "harga"=>"Rp 750.000",  "status"=>"Tersedia",    "img"=>"https://images.pexels.com/photos/1763075/pexels-photo-1763075.jpeg?auto=compress&cs=tinysrgb&w=600"],
        ["nama"=>"Jazz Night Cirebon",    "kota"=>"Alun-Alun Kejaksan Cirebon","tanggal"=>"12 Agu 2026","harga"=>"Rp 600.000",  "status"=>"Sold Out",    "img"=>"https://images.pexels.com/photos/167636/pexels-photo-167636.jpeg?auto=compress&cs=tinysrgb&w=600"],
        ["nama"=>"Jazz Traffic Festival", "kota"=>"Keraton Kanoman Cirebon",   "tanggal"=>"5 Okt 2026", "harga"=>"Rp 650.000",  "status"=>"Tersedia",    "img"=>"https://images.pexels.com/photos/1540406/pexels-photo-1540406.jpeg?auto=compress&cs=tinysrgb&w=600"],
    ],
    "kpop" => [
        ["nama"=>"BTS Live Concert",      "kota"=>"GOR Bima Cirebon",          "tanggal"=>"20 Agu 2026","harga"=>"Rp 2.000.000","status"=>"Sold Out",    "img"=>"https://images.pexels.com/photos/1190297/pexels-photo-1190297.jpeg?auto=compress&cs=tinysrgb&w=600"],
        ["nama"=>"Blackpink World Tour",  "kota"=>"Stadion Bima Cirebon",      "tanggal"=>"28 Agu 2026","harga"=>"Rp 1.800.000","status"=>"Sisa Sedikit","img"=>"https://images.pexels.com/photos/995301/pexels-photo-995301.jpeg?auto=compress&cs=tinysrgb&w=600"],
    ],
];

$badgeMap = ["Tersedia"=>"success","Sisa Sedikit"=>"warning","Sold Out"=>"danger"];
$konserList = $data[$kategori] ?? [];
?>

<!-- Hero -->
<div class="hero">
    <div class="container">
        <div class="fs-1 mb-2"><?= $emoji ?></div>
        <h1 class="fw-bold">Konser <?= $judul ?></h1>
        <p class="lead opacity-75">Temukan konser <?= $judul ?> terbaik untukmu</p>
    </div>
</div>

<!-- Filter Kategori -->
<div class="container my-4">
    <div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="?kategori=pop"  class="kategori-pill <?= $kategori==='pop'  ?'active':'' ?>">🎤 Pop</a>
        <a href="?kategori=rock" class="kategori-pill <?= $kategori==='rock' ?'active':'' ?>">🎸 Rock</a>
        <a href="?kategori=jazz" class="kategori-pill <?= $kategori==='jazz' ?'active':'' ?>">🎷 Jazz</a>
        <a href="?kategori=kpop" class="kategori-pill <?= $kategori==='kpop' ?'active':'' ?>">💜 K-Pop</a>
    </div>
</div>

<!-- Daftar Konser -->
<div class="container mb-5">
    <h4 class="fw-bold section-title mb-4"><?= count($konserList) ?> Konser Ditemukan</h4>
    <?php if (!empty($konserList)): ?>
    <div class="row g-4">
        <?php foreach ($konserList as $k):
            $b = $badgeMap[$k['status']] ?? 'secondary'; ?>
        <div class="col-md-4">
            <div class="card card-event h-100">
                <img src="<?= $k['img'] ?>" class="card-img-top" alt="<?= htmlspecialchars($k['nama']) ?>">
                <div class="card-body">
                    <span class="badge bg-<?= $b ?> mb-2"><?= $k['status'] ?></span>
                    <h5 class="card-title fw-bold"><?= htmlspecialchars($k['nama']) ?></h5>
                    <p class="text-muted small mb-1">📍 <?= $k['kota'] ?></p>
                    <p class="text-muted small mb-3">📅 <?= $k['tanggal'] ?></p>
                    <p class="price-tag mb-0"><?= $k['harga'] ?></p>
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <?php if ($k['status'] === 'Sold Out'): ?>
                        <button class="btn btn-secondary w-100 rounded-pill" disabled>Habis Terjual</button>
                    <?php else: ?>
                        <?php
                        $urlBayar = 'pembayaran.php?konser=' . urlencode($k['nama'])
                            . '&harga=' . preg_replace('/[^0-9]/', '', $k['harga'])
                            . '&tanggal=' . urlencode($k['tanggal'])
                            . '&lokasi=' . urlencode($k['kota']);
                        ?>
                        <a href="<?= isset($_SESSION['user']) ? $urlBayar : 'login.php' ?>" class="btn btn-outline-primary w-100 rounded-pill">Pesan Tiket</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-5 text-muted">
        <div class="fs-1">🎵</div>
        <p>Belum ada konser untuk kategori ini.</p>
        <a href="index.php" class="btn btn-primary rounded-pill px-4">Kembali ke Beranda</a>
    </div>
    <?php endif; ?>
</div>

<footer>
    <div class="container text-center">
        <p class="mb-0 small" style="color:rgba(255,255,255,0.35)">&copy; 2026 KonserKita. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
