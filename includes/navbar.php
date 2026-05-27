<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Deteksi base URL otomatis agar navbar bisa dipakai dari folder manapun
$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$depth  = substr_count(dirname($script), '/') - 1;
$base   = str_repeat('../', $depth);
if ($depth === 0) $base = '';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="<?= $base ?>index.php">KONSERKITA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="<?= $base ?>index.php">Beranda</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="kategoriDropdown" role="button" data-bs-toggle="dropdown">
                        Kategori Konser
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= $base ?>pages/kategori.php?kategori=pop">Pop</a></li>
                        <li><a class="dropdown-item" href="<?= $base ?>pages/kategori.php?kategori=rock">Rock</a></li>
                        <li><a class="dropdown-item" href="<?= $base ?>pages/kategori.php?kategori=jazz">Jazz</a></li>
                        <li><a class="dropdown-item" href="<?= $base ?>pages/kategori.php?kategori=kpop">K-Pop</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $base ?>pages/panduan.php">Panduan</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $base ?>pages/cekstatus.php">Cek Status Tiket</a>
                </li>

                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="<?= $base ?>admin/dashboard.php">Dashboard Admin</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-primary" href="<?= $base ?>user/dashboard.php">Dashboard</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-danger px-4" href="<?= $base ?>auth/logout.php">Keluar</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-primary px-4" href="<?= $base ?>auth/login.php">Masuk</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary px-4" href="<?= $base ?>auth/register.php">Daftar</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
