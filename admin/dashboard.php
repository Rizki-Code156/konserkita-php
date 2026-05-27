<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php'); exit;
}

require_once '../includes/db.php';

$page = $_GET['page'] ?? 'dashboard';

// Ambil data dari DB
$konserList   = $pdo->query("SELECT * FROM konser ORDER BY tanggal ASC")->fetchAll();
$tiketList    = $pdo->query("
    SELECT t.*, u.nama AS user_nama, k.nama AS konser_nama
    FROM tiket t
    JOIN users u ON t.user_id = u.id
    JOIN konser k ON t.konser_id = k.id
    ORDER BY t.created_at DESC
")->fetchAll();
$penggunaList = $pdo->query("SELECT * FROM users ORDER BY created_at ASC")->fetchAll();

$totalKonser   = count($konserList);
$totalTiket    = count($tiketList);
$totalPengguna = count($penggunaList);
$totalKategori = count(array_unique(array_column($konserList, 'kategori')));

$badgeStatus = [
    'Tersedia'=>'success','Sisa Sedikit'=>'warning','Sold Out'=>'danger',
    'Aktif'=>'success','Menunggu Pembayaran'=>'warning','Dibatalkan'=>'danger'
];
$titles = ['dashboard'=>'Dashboard','konser'=>'Kelola Konser','tiket'=>'Data Tiket','pengguna'=>'Data Pengguna'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - KONSERKITA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f1f3f5; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #6610f2, #0d6efd);
            width: 240px; position: fixed; top: 0; left: 0;
            padding: 24px 16px; z-index: 100;
        }
        .sidebar a {
            color: rgba(255,255,255,0.85); text-decoration: none;
            display: block; padding: 10px 14px; border-radius: 8px;
            margin-bottom: 4px; font-size: 0.9rem;
        }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); color: white; }
        .main-content { margin-left: 240px; padding: 32px; }
        .stat-card  { border-radius: 14px; border: none; }
        .card-section { border: none; border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,0.07); }
        .table th { font-size: 0.82rem; }
        .table td { font-size: 0.88rem; vertical-align: middle; }
    </style>
</head>
<body>

<div class="sidebar">
    <h5 class="fw-bold mb-0 text-white">KONSERKITA</h5>
    <small class="opacity-50 text-white">Panel Admin</small>
    <hr class="border-white opacity-25 my-3">
    <a href="?page=dashboard" class="<?= $page==='dashboard' ?'active':'' ?>">🏠 Dashboard</a>
    <a href="?page=konser"    class="<?= $page==='konser'    ?'active':'' ?>">🎵 Kelola Konser</a>
    <a href="?page=tiket"     class="<?= $page==='tiket'     ?'active':'' ?>">🎫 Data Tiket</a>
    <a href="?page=pengguna"  class="<?= $page==='pengguna'  ?'active':'' ?>">👥 Data Pengguna</a>
    <hr class="border-white opacity-25 my-3">
    <a href="../auth/logout.php">🚪 Keluar</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0"><?= $titles[$page] ?? 'Dashboard' ?></h4>
            <small class="text-muted">Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?></small>
        </div>
        <a href="../auth/logout.php" class="btn btn-outline-danger btn-sm">Keluar</a>
    </div>

<?php if ($page === 'dashboard'): ?>
    <div class="row g-3 mb-4">
        <div class="col-md-3"><div class="card stat-card p-3 bg-primary text-white">
            <div class="fs-2 fw-bold"><?= $totalKategori ?></div><div>Kategori Konser</div>
        </div></div>
        <div class="col-md-3"><div class="card stat-card p-3 bg-success text-white">
            <div class="fs-2 fw-bold"><?= $totalKonser ?></div><div>Total Konser</div>
        </div></div>
        <div class="col-md-3"><div class="card stat-card p-3 bg-warning text-dark">
            <div class="fs-2 fw-bold"><?= $totalTiket ?></div><div>Tiket Terjual</div>
        </div></div>
        <div class="col-md-3"><div class="card stat-card p-3 bg-info text-white">
            <div class="fs-2 fw-bold"><?= $totalPengguna ?></div><div>Pengguna Terdaftar</div>
        </div></div>
    </div>
    <div class="row g-3">
        <div class="col-md-4"><div class="card card-section p-4 text-center">
            <div class="fs-1 mb-2">🎵</div><h6 class="fw-bold">Kelola Konser</h6>
            <p class="text-muted small">Tambah, edit, atau hapus data konser</p>
            <a href="?page=konser" class="btn btn-primary btn-sm">Buka</a>
        </div></div>
        <div class="col-md-4"><div class="card card-section p-4 text-center">
            <div class="fs-1 mb-2">🎫</div><h6 class="fw-bold">Data Tiket</h6>
            <p class="text-muted small">Lihat dan kelola semua transaksi tiket</p>
            <a href="?page=tiket" class="btn btn-primary btn-sm">Buka</a>
        </div></div>
        <div class="col-md-4"><div class="card card-section p-4 text-center">
            <div class="fs-1 mb-2">👥</div><h6 class="fw-bold">Data Pengguna</h6>
            <p class="text-muted small">Kelola akun pengguna terdaftar</p>
            <a href="?page=pengguna" class="btn btn-primary btn-sm">Buka</a>
        </div></div>
    </div>

<?php elseif ($page === 'konser'): ?>
    <div class="card card-section p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Daftar Konser</h6>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKonser">+ Tambah Konser</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" id="tabelKonser">
                <thead class="table-dark">
                    <tr><th>#</th><th>Nama Konser</th><th>Kategori</th><th>Kota</th><th>Tanggal</th><th>Harga</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php foreach ($konserList as $i => $k):
                    $b = $badgeStatus[$k['status']] ?? 'secondary'; ?>
                <tr id="konser-row-<?= $k['id'] ?>">
                    <td><?= $i+1 ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($k['nama']) ?></td>
                    <td><?= $k['kategori'] ?></td>
                    <td><?= $k['kota'] ?></td>
                    <td><?= date('d M Y', strtotime($k['tanggal'])) ?></td>
                    <td>Rp <?= number_format($k['harga'],0,',','.') ?></td>
                    <td><span class="badge bg-<?= $b ?>"><?= $k['status'] ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditKonser"
                            onclick="editKonser(<?= $k['id'] ?>,'<?= addslashes($k['nama']) ?>','<?= $k['kategori'] ?>','<?= addslashes($k['kota']) ?>','<?= $k['tanggal'] ?>',<?= $k['harga'] ?>,'<?= $k['status'] ?>')">Edit</button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus"
                            onclick="setHapus('konser',<?= $k['id'] ?>,'<?= addslashes($k['nama']) ?>')">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif ($page === 'tiket'): ?>
    <div class="card card-section p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Data Tiket</h6>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTiket">+ Tambah Tiket</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" id="tabelTiket">
                <thead class="table-dark">
                    <tr><th>#</th><th>Kode</th><th>Pengguna</th><th>Konser</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php foreach ($tiketList as $i => $t):
                    $b = $badgeStatus[$t['status']] ?? 'secondary'; ?>
                <tr id="tiket-row-<?= $t['id'] ?>">
                    <td><?= $i+1 ?></td>
                    <td><code><?= $t['kode'] ?></code></td>
                    <td><?= htmlspecialchars($t['user_nama']) ?></td>
                    <td><?= htmlspecialchars($t['konser_nama']) ?></td>
                    <td><?= $t['kategori'] ?></td>
                    <td>Rp <?= number_format($t['harga'],0,',','.') ?></td>
                    <td><span class="badge bg-<?= $b ?>"><?= $t['status'] ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditTiket"
                            onclick="editTiket(<?= $t['id'] ?>,'<?= $t['kode'] ?>','<?= $t['kategori'] ?>',<?= $t['harga'] ?>,'<?= $t['status'] ?>')">Edit</button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus"
                            onclick="setHapus('tiket',<?= $t['id'] ?>,'<?= $t['kode'] ?>')">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif ($page === 'pengguna'): ?>
    <div class="card card-section p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Data Pengguna</h6>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPengguna">+ Tambah Pengguna</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" id="tabelPengguna">
                <thead class="table-dark">
                    <tr><th>#</th><th>Nama</th><th>Username</th><th>Email</th><th>Role</th><th>Bergabung</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php foreach ($penggunaList as $i => $p): ?>
                <tr id="pengguna-row-<?= $p['id'] ?>">
                    <td><?= $i+1 ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($p['nama']) ?></td>
                    <td>@<?= $p['username'] ?></td>
                    <td><?= $p['email'] ?></td>
                    <td><span class="badge <?= $p['role']==='admin'?'bg-danger':'bg-secondary' ?>"><?= $p['role'] ?></span></td>
                    <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditPengguna"
                            onclick="editPengguna(<?= $p['id'] ?>,'<?= addslashes($p['nama']) ?>','<?= $p['username'] ?>','<?= $p['email'] ?>','<?= $p['role'] ?>')">Edit</button>
                        <?php if ($p['role'] !== 'admin'): ?>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalHapus"
                            onclick="setHapus('pengguna',<?= $p['id'] ?>,'<?= addslashes($p['nama']) ?>')">Hapus</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
</div>

<!-- MODAL TAMBAH KONSER -->
<div class="modal fade" id="modalTambahKonser" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold">Tambah Konser</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <form id="fTambahKonser">
        <div class="mb-3"><label class="form-label">Nama Konser</label><input type="text" name="nama" class="form-control" required></div>
        <div class="row g-2 mb-3">
          <div class="col"><label class="form-label">Kategori</label>
            <select name="kategori" class="form-select"><option>Pop</option><option>Rock</option><option>Jazz</option><option>K-Pop</option></select>
          </div>
          <div class="col"><label class="form-label">Kota</label><input type="text" name="kota" class="form-control" required></div>
        </div>
        <div class="row g-2 mb-3">
          <div class="col"><label class="form-label">Tanggal</label><input type="date" name="tanggal" class="form-control" required></div>
          <div class="col"><label class="form-label">Harga (Rp)</label><input type="number" name="harga" class="form-control" required></div>
        </div>
        <div class="mb-3"><label class="form-label">Status</label>
          <select name="status" class="form-select"><option>Tersedia</option><option>Sisa Sedikit</option><option>Sold Out</option></select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      <button class="btn btn-primary" onclick="submitForm('fTambahKonser','tambah_konser')">Simpan</button>
    </div>
  </div></div>
</div>

<!-- MODAL EDIT KONSER -->
<div class="modal fade" id="modalEditKonser" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold">Edit Konser</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <form id="fEditKonser">
        <input type="hidden" name="id" id="eKId">
        <div class="mb-3"><label class="form-label">Nama Konser</label><input type="text" name="nama" id="eKNama" class="form-control" required></div>
        <div class="row g-2 mb-3">
          <div class="col"><label class="form-label">Kategori</label>
            <select name="kategori" id="eKKategori" class="form-select"><option>Pop</option><option>Rock</option><option>Jazz</option><option>K-Pop</option></select>
          </div>
          <div class="col"><label class="form-label">Kota</label><input type="text" name="kota" id="eKKota" class="form-control" required></div>
        </div>
        <div class="row g-2 mb-3">
          <div class="col"><label class="form-label">Tanggal</label><input type="date" name="tanggal" id="eKTanggal" class="form-control"></div>
          <div class="col"><label class="form-label">Harga (Rp)</label><input type="number" name="harga" id="eKHarga" class="form-control"></div>
        </div>
        <div class="mb-3"><label class="form-label">Status</label>
          <select name="status" id="eKStatus" class="form-select"><option>Tersedia</option><option>Sisa Sedikit</option><option>Sold Out</option></select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      <button class="btn btn-primary" onclick="submitForm('fEditKonser','edit_konser')">Simpan Perubahan</button>
    </div>
  </div></div>
</div>

<!-- MODAL TAMBAH TIKET -->
<div class="modal fade" id="modalTambahTiket" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold">Tambah Tiket</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <form id="fTambahTiket">
        <div class="mb-3"><label class="form-label">Kode Booking</label><input type="text" name="kode" class="form-control" placeholder="KNSR0000" required></div>
        <div class="mb-3"><label class="form-label">Nama Pengguna</label><input type="text" name="user_nama" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Nama Konser</label><input type="text" name="konser_nama" class="form-control" required></div>
        <div class="row g-2 mb-3">
          <div class="col"><label class="form-label">Kategori</label>
            <select name="kategori" class="form-select"><option>Regular</option><option>VIP</option><option>SVIP</option><option>Festival</option></select>
          </div>
          <div class="col"><label class="form-label">Harga (Rp)</label><input type="number" name="harga" class="form-control" required></div>
        </div>
        <div class="mb-3"><label class="form-label">Status</label>
          <select name="status" class="form-select"><option>Aktif</option><option>Menunggu Pembayaran</option><option>Dibatalkan</option></select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      <button class="btn btn-primary" onclick="submitForm('fTambahTiket','tambah_tiket')">Simpan</button>
    </div>
  </div></div>
</div>

<!-- MODAL EDIT TIKET -->
<div class="modal fade" id="modalEditTiket" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold">Edit Tiket</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <form id="fEditTiket">
        <input type="hidden" name="id" id="eTId">
        <div class="mb-3"><label class="form-label">Kode Booking</label><input type="text" id="eTKode" class="form-control" readonly></div>
        <div class="row g-2 mb-3">
          <div class="col"><label class="form-label">Kategori</label>
            <select name="kategori" id="eTKategori" class="form-select"><option>Regular</option><option>VIP</option><option>SVIP</option><option>Festival</option></select>
          </div>
          <div class="col"><label class="form-label">Harga (Rp)</label><input type="number" name="harga" id="eTHarga" class="form-control"></div>
        </div>
        <div class="mb-3"><label class="form-label">Status</label>
          <select name="status" id="eTStatus" class="form-select"><option>Aktif</option><option>Menunggu Pembayaran</option><option>Dibatalkan</option></select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      <button class="btn btn-primary" onclick="submitForm('fEditTiket','edit_tiket')">Simpan Perubahan</button>
    </div>
  </div></div>
</div>

<!-- MODAL TAMBAH PENGGUNA -->
<div class="modal fade" id="modalTambahPengguna" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold">Tambah Pengguna</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <form id="fTambahPengguna">
        <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" name="nama" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Username</label><input type="text" name="username" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Role</label>
          <select name="role" class="form-select"><option value="user">User</option><option value="admin">Admin</option></select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      <button class="btn btn-primary" onclick="submitForm('fTambahPengguna','tambah_pengguna')">Simpan</button>
    </div>
  </div></div>
</div>

<!-- MODAL EDIT PENGGUNA -->
<div class="modal fade" id="modalEditPengguna" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title fw-bold">Edit Pengguna</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <form id="fEditPengguna">
        <input type="hidden" name="id" id="ePId">
        <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" name="nama" id="ePNama" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Username</label><input type="text" name="username" id="ePUsername" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" id="ePEmail" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label><input type="password" name="password" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Role</label>
          <select name="role" id="ePRole" class="form-select"><option value="user">User</option><option value="admin">Admin</option></select>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      <button class="btn btn-primary" onclick="submitForm('fEditPengguna','edit_pengguna')">Simpan Perubahan</button>
    </div>
  </div></div>
</div>

<!-- MODAL HAPUS -->
<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog modal-sm"><div class="modal-content">
    <div class="modal-header border-0 pb-0"><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body text-center pt-0">
      <div class="fs-1 mb-2">🗑️</div>
      <h6 class="fw-bold">Hapus Data?</h6>
      <p class="text-muted small mb-0">Yakin hapus <strong id="hapusNama"></strong>? Tidak bisa dibatalkan.</p>
    </div>
    <div class="modal-footer border-0 justify-content-center">
      <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
      <button class="btn btn-danger btn-sm" id="btnHapusOk">Ya, Hapus</button>
    </div>
  </div></div>
</div>

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
  <div id="toastNotif" class="toast align-items-center text-white border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body" id="toastMsg"></div>
      <button class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    const dtOpts = {
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data tidak ditemukan",
            paginate: { previous: "‹", next: "›" }
        },
        columnDefs: [{ orderable: false, targets: -1 }]
    };
    $('#tabelKonser').DataTable(dtOpts);
    $('#tabelTiket').DataTable(dtOpts);
    $('#tabelPengguna').DataTable(dtOpts);
});
</script>
<script>
const HANDLER = 'handler.php';

function showToast(msg, ok = true) {
    const el = document.getElementById('toastNotif');
    el.classList.remove('bg-success','bg-danger');
    el.classList.add(ok ? 'bg-success' : 'bg-danger');
    document.getElementById('toastMsg').textContent = msg;
    new bootstrap.Toast(el, {delay: 2800}).show();
}

function setSelect(id, val) {
    const el = document.getElementById(id);
    if (!el) return;
    [...el.options].forEach(o => o.selected = (o.value === val || o.text === val));
}

// Kirim form via AJAX
function submitForm(formId, action) {
    const form = document.getElementById(formId);
    if (!form.checkValidity()) { form.reportValidity(); return; }
    const data = new FormData(form);
    data.append('action', action);

    fetch(HANDLER, { method: 'POST', body: data })
        .then(r => r.json())
        .then(res => {
            document.querySelectorAll('.modal.show').forEach(m => bootstrap.Modal.getInstance(m)?.hide());
            if (res.success) {
                showToast('Berhasil disimpan!');
                setTimeout(() => location.reload(), 900);
            } else {
                showToast(res.message || 'Gagal menyimpan.', false);
            }
        })
        .catch(() => showToast('Terjadi kesalahan.', false));
}

// Hapus
let hapusPayload = {};
function setHapus(tipe, id, nama) {
    hapusPayload = { tipe, id };
    document.getElementById('hapusNama').textContent = nama;
}
document.getElementById('btnHapusOk').addEventListener('click', () => {
    const { tipe, id } = hapusPayload;
    const data = new FormData();
    data.append('action', 'hapus_' + tipe);
    data.append('id', id);

    fetch(HANDLER, { method: 'POST', body: data })
        .then(r => r.json())
        .then(res => {
            bootstrap.Modal.getInstance(document.getElementById('modalHapus'))?.hide();
            if (res.success) {
                document.getElementById(tipe + '-row-' + id)?.remove();
                showToast('Data berhasil dihapus!');
            } else {
                showToast(res.message || 'Gagal menghapus.', false);
            }
        })
        .catch(() => showToast('Terjadi kesalahan.', false));
});

// Isi modal edit
function editKonser(id, nama, kategori, kota, tanggal, harga, status) {
    document.getElementById('eKId').value     = id;
    document.getElementById('eKNama').value   = nama;
    document.getElementById('eKKota').value   = kota;
    document.getElementById('eKTanggal').value= tanggal;
    document.getElementById('eKHarga').value  = harga;
    setSelect('eKKategori', kategori);
    setSelect('eKStatus', status);
}
function editTiket(id, kode, kategori, harga, status) {
    document.getElementById('eTId').value      = id;
    document.getElementById('eTKode').value    = kode;
    document.getElementById('eTHarga').value   = harga;
    setSelect('eTKategori', kategori);
    setSelect('eTStatus', status);
}
function editPengguna(id, nama, username, email, role) {
    document.getElementById('ePId').value       = id;
    document.getElementById('ePNama').value     = nama;
    document.getElementById('ePUsername').value = username;
    document.getElementById('ePEmail').value    = email;
    setSelect('ePRole', role);
}
</script>
</body>
</html>
