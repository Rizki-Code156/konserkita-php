<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php?redirect=pembayaran.php&konser=' . urlencode($_GET['konser'] ?? ''));
    exit;
}

require_once '../includes/db.php';

$namaKonser = $_GET['konser'] ?? 'Konser';
$hargaBase  = (int) ($_GET['harga'] ?? 0);
$tanggal    = $_GET['tanggal'] ?? '-';
$lokasi     = $_GET['lokasi']  ?? '-';

$hargaKategori = [
    'Regular' => $hargaBase,
    'VIP'     => $hargaBase * 2,
    'SVIP'    => $hargaBase * 3,
    'Festival'=> $hargaBase + 200000,
];

$success     = false;
$kodeBooking = '';
$error       = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori   = $_POST['kategori']    ?? 'Regular';
    $hargaFinal = (int) ($_POST['harga_final'] ?? $hargaBase);
    $metode     = $_POST['metode']      ?? '';

    // Ambil data dari POST juga sebagai fallback (form submit kehilangan GET params)
    $namaKonser = $_POST['konser_nama'] ?? $namaKonser;
    $lokasi     = $_POST['lokasi']      ?? $lokasi;
    $tanggal    = $_POST['tanggal']     ?? $tanggal;

    try {
        // Cari user_id dari session
        $stmtU = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $stmtU->execute([$_SESSION['user']]);
        $userRow = $stmtU->fetch();

        if (!$userRow) {
            $error = 'Akun tidak ditemukan. Silakan login ulang.';
        } else {
            // Cari konser_id
            $stmtK = $pdo->prepare("SELECT id FROM konser WHERE nama LIKE ? LIMIT 1");
            $stmtK->execute(['%' . $namaKonser . '%']);
            $konserRow = $stmtK->fetch();

            if (!$konserRow) {
                $error = 'Konser tidak ditemukan. Silakan kembali dan pilih konser yang valid.';
            } else {
                $konserId    = $konserRow['id'];
                $kodeBooking = 'KNSR' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

                $stmtT = $pdo->prepare("
                    INSERT INTO tiket (kode, user_id, konser_id, kategori, harga, status)
                    VALUES (?, ?, ?, ?, ?, 'Menunggu Pembayaran')
                ");
                $stmtT->execute([
                    $kodeBooking,
                    $userRow['id'],
                    $konserId,
                    $kategori,
                    $hargaFinal,
                ]);
                $success = true;
            }
            $success = true;
        }
    } catch (PDOException $e) {
        $error = 'Gagal menyimpan tiket: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - KONSERKITA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .hero {
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            color: white; padding: 50px 0; text-align: center;
        }
        .btn-primary { background: linear-gradient(135deg, #6610f2, #0d6efd); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, #59359a, #0b5ed7); border: none; }
        .btn-outline-primary { color: #6610f2; border-color: #6610f2; }
        .btn-outline-primary:hover { background: linear-gradient(135deg, #6610f2, #0d6efd); border-color: transparent; color: white; }
        .card-pay {
            border: none; border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .konser-info {
            background: linear-gradient(135deg, rgba(102,16,242,0.06), rgba(13,110,253,0.06));
            border-radius: 12px; padding: 16px;
            border-left: 4px solid #6610f2;
        }
        .kategori-option {
            cursor: pointer; border: 2px solid #e9ecef;
            border-radius: 12px; padding: 14px 16px;
            transition: all 0.2s; user-select: none;
        }
        .kategori-option:hover { border-color: #6610f2; background: rgba(102,16,242,0.03); }
        .kategori-option.selected { border-color: #6610f2; background: rgba(102,16,242,0.06); }
        .kategori-option input[type=radio] { accent-color: #6610f2; }
        .metode-option {
            cursor: pointer; border: 2px solid #e9ecef;
            border-radius: 12px; padding: 12px 16px;
            transition: all 0.2s; user-select: none;
        }
        .metode-option:hover { border-color: #6610f2; }
        .metode-option.selected { border-color: #6610f2; background: rgba(102,16,242,0.04); }
        .metode-option input[type=radio] { accent-color: #6610f2; }
        .total-box {
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            color: white; border-radius: 12px; padding: 16px 20px;
        }
        .step-badge {
            width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #6610f2, #0d6efd);
            color: white; font-size: 0.8rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }
        /* Sukses */
        .success-card {
            border: none; border-radius: 20px;
            box-shadow: 0 8px 32px rgba(102,16,242,0.15);
            animation: popIn 0.4s ease;
        }
        @keyframes popIn { from { opacity:0; transform:scale(0.95); } to { opacity:1; transform:scale(1); } }
        .kode-box {
            background: #f8f9fa; border-radius: 10px;
            padding: 14px 20px; font-size: 1.5rem;
            font-weight: 700; letter-spacing: 3px;
            color: #6610f2; font-family: monospace;
        }
        footer { background: #0f0f1a; color: white; padding: 30px 0; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="hero">
    <div class="container">
        <div class="fs-1 mb-2">💳</div>
        <h1 class="fw-bold">Pembayaran Tiket</h1>
        <p class="lead opacity-75">Selesaikan pembelian tiket konsermu</p>
    </div>
</div>

<div class="container my-5">
<?php if ($success): ?>
    <!-- ── SUKSES ── -->
    <div class="row justify-content-center">
        <div class="col-lg-5 text-center">
            <div class="card success-card p-5">
                <div class="fs-1 mb-3">🎉</div>
                <h4 class="fw-bold mb-1">Pembayaran Berhasil!</h4>
                <p class="text-muted mb-4">Tiket kamu sudah aktif. Simpan kode booking berikut.</p>
                <div class="kode-box mb-4"><?= $kodeBooking ?></div>
                <p class="text-muted small mb-4">Kode booking ini digunakan untuk masuk ke venue. Tunjukkan ke petugas saat hari konser.</p>
                <div class="d-grid gap-2">
                    <a href="../user/dashboard.php" class="btn btn-primary rounded-pill">Lihat Tiket Saya</a>
                    <a href="../index.php" class="btn btn-outline-primary rounded-pill">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($error): ?>
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="alert alert-danger rounded-3">
                <strong>❌ Terjadi Kesalahan</strong><br>
                <?= htmlspecialchars($error) ?>
                <hr class="my-2">
                <a href="javascript:history.back()" class="btn btn-sm btn-outline-danger rounded-pill">← Kembali</a>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- ── FORM PEMBAYARAN ── -->
    <div class="row g-4 justify-content-center">

        <!-- Kiri: Form -->
        <div class="col-lg-7">
            <form method="POST" id="formBayar">

                <!-- Info Konser -->
                <div class="card card-pay p-4 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="step-badge">1</div>
                        <h6 class="fw-bold mb-0">Detail Konser</h6>
                    </div>
                    <div class="konser-info">
                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($namaKonser) ?></h5>
                        <p class="text-muted small mb-1">📍 <?= htmlspecialchars($lokasi) ?></p>
                        <p class="text-muted small mb-0">📅 <?= htmlspecialchars($tanggal) ?></p>
                    </div>
                </div>

                <!-- Pilih Kategori -->
                <div class="card card-pay p-4 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="step-badge">2</div>
                        <h6 class="fw-bold mb-0">Pilih Kategori Tiket</h6>
                    </div>
                    <div class="d-flex flex-column gap-2" id="kategoriGroup">
                        <?php foreach ($hargaKategori as $kat => $hrg): ?>
                        <label class="kategori-option d-flex justify-content-between align-items-center"
                               onclick="pilihKategori(this, <?= $hrg ?>)">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="kategori" value="<?= $kat ?>" <?= $kat==='Regular'?'checked':'' ?> required>
                                <span class="fw-semibold"><?= $kat ?></span>
                            </div>
                            <span style="color:#6610f2;font-weight:700">Rp <?= number_format($hrg,0,',','.') ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Data Pemesan -->
                <div class="card card-pay p-4 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="step-badge">3</div>
                        <h6 class="fw-bold mb-0">Data Pemesan</h6>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama_pemesan" class="form-control"
                               value="<?= htmlspecialchars($_SESSION['nama']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@kamu.com" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-semibold">Nomor HP</label>
                        <input type="tel" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="card card-pay p-4 mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="step-badge">4</div>
                        <h6 class="fw-bold mb-0">Metode Pembayaran</h6>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <label class="metode-option d-flex align-items-center gap-3" onclick="pilihMetode(this)">
                            <input type="radio" name="metode" value="transfer_bca" required>
                            <span class="fs-5">🏦</span>
                            <div>
                                <div class="fw-semibold small">Transfer Bank BCA</div>
                                <div class="text-muted" style="font-size:0.78rem">No. Rek: 1234567890 a/n KonserKita</div>
                            </div>
                        </label>
                        <label class="metode-option d-flex align-items-center gap-3" onclick="pilihMetode(this)">
                            <input type="radio" name="metode" value="gopay">
                            <span class="fs-5">💚</span>
                            <div>
                                <div class="fw-semibold small">GoPay</div>
                                <div class="text-muted" style="font-size:0.78rem">0812-3456-7890</div>
                            </div>
                        </label>
                        <label class="metode-option d-flex align-items-center gap-3" onclick="pilihMetode(this)">
                            <input type="radio" name="metode" value="ovo">
                            <span class="fs-5">💜</span>
                            <div>
                                <div class="fw-semibold small">OVO</div>
                                <div class="text-muted" style="font-size:0.78rem">0812-3456-7890</div>
                            </div>
                        </label>
                        <label class="metode-option d-flex align-items-center gap-3" onclick="pilihMetode(this)">
                            <input type="radio" name="metode" value="dana">
                            <span class="fs-5">🔵</span>
                            <div>
                                <div class="fw-semibold small">DANA</div>
                                <div class="text-muted" style="font-size:0.78rem">0812-3456-7890</div>
                            </div>
                        </label>
                    </div>
                </div>

                <input type="hidden" name="konser_nama"  value="<?= htmlspecialchars($namaKonser) ?>">
                <input type="hidden" name="lokasi"       value="<?= htmlspecialchars($lokasi) ?>">
                <input type="hidden" name="tanggal"      value="<?= htmlspecialchars($tanggal) ?>">
                <input type="hidden" name="konser"       value="<?= htmlspecialchars($namaKonser) ?>">
                <input type="hidden" name="harga_final"  id="hargaFinal" value="<?= reset($hargaKategori) ?>">

            </form>
        </div>

        <!-- Kanan: Ringkasan -->
        <div class="col-lg-4">
            <div class="card card-pay p-4 sticky-top" style="top:80px">
                <h6 class="fw-bold mb-3">Ringkasan Pesanan</h6>
                <div class="mb-3 pb-3 border-bottom">
                    <p class="fw-semibold mb-1 small"><?= htmlspecialchars($namaKonser) ?></p>
                    <p class="text-muted small mb-0">📅 <?= htmlspecialchars($tanggal) ?></p>
                    <p class="text-muted small mb-0">📍 <?= htmlspecialchars($lokasi) ?></p>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">Kategori</span>
                    <span class="fw-semibold" id="summaryKategori">Regular</span>
                </div>
                <div class="d-flex justify-content-between small mb-3">
                    <span class="text-muted">Harga Tiket</span>
                    <span class="fw-semibold" id="summaryHarga">Rp <?= number_format(reset($hargaKategori),0,',','.') ?></span>
                </div>
                <div class="total-box d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-semibold">Total</span>
                    <span class="fw-bold fs-5" id="summaryTotal">Rp <?= number_format(reset($hargaKategori),0,',','.') ?></span>
                </div>
                <button type="submit" form="formBayar" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
                    Bayar Sekarang 🔒
                </button>
                <p class="text-muted text-center mt-2 mb-0" style="font-size:0.75rem">
                    Pembayaran aman &amp; terenkripsi
                </p>
            </div>
        </div>

    </div>
<?php endif; ?>
</div>

<footer>
    <div class="container text-center">
        <p class="mb-0 small" style="color:rgba(255,255,255,0.35)">&copy; 2026 KonserKita. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function pilihKategori(el, harga) {
    document.querySelectorAll('.kategori-option').forEach(e => e.classList.remove('selected'));
    el.classList.add('selected');
    const label = el.querySelector('span.fw-semibold').textContent;
    const fmt = 'Rp ' + harga.toLocaleString('id-ID');
    document.getElementById('summaryKategori').textContent = label;
    document.getElementById('summaryHarga').textContent    = fmt;
    document.getElementById('summaryTotal').textContent    = fmt;
    document.getElementById('hargaFinal').value            = harga;
}

function pilihMetode(el) {
    document.querySelectorAll('.metode-option').forEach(e => e.classList.remove('selected'));
    el.classList.add('selected');
}

// Set default selected style
document.querySelector('.kategori-option')?.classList.add('selected');
</script>
</body>
</html>
