<?php
session_start();
if (isset($_SESSION['user'])) { header('Location: ../index.php'); exit; }

require_once '../includes/db.php';

// Generate captcha baru
if (empty($_SESSION['captcha_reg'])) {
    $a = rand(1, 9); $b = rand(1, 9);
    $_SESSION['captcha_reg'] = ['a' => $a, 'b' => $b, 'ans' => $a + $b];
}

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama']     ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';
    $konfirm  = $_POST['konfirm']       ?? '';
    $captcha  = (int)($_POST['captcha'] ?? -1);

    if ($captcha !== $_SESSION['captcha_reg']['ans']) {
        $error = 'Jawaban captcha salah.';
        $a = rand(1, 9); $b = rand(1, 9);
        $_SESSION['captcha_reg'] = ['a' => $a, 'b' => $b, 'ans' => $a + $b];
    } elseif (empty($nama) || empty($username) || empty($email) || empty($password)) {
        $error = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif ($password !== $konfirm) {
        $error = 'Konfirmasi password tidak cocok.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = 'Username hanya boleh huruf, angka, dan underscore.';
    } else {
        $cek = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $cek->execute([$username, $email]);
        if ($cek->fetch()) {
            $error = 'Username atau email sudah digunakan.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (nama, username, email, password, role) VALUES (?, ?, ?, ?, 'user')");
            $stmt->execute([$nama, $username, $email, password_hash($password, PASSWORD_DEFAULT)]);
            unset($_SESSION['captcha_reg']);
            $success = 'Pendaftaran berhasil! Silakan <a href="login.php">masuk</a>.';
        }
    }
}
$cap = $_SESSION['captcha_reg'] ?? (function(){ $a=rand(1,9);$b=rand(1,9);$_SESSION['captcha_reg']=['a'=>$a,'b'=>$b,'ans'=>$a+$b];return $_SESSION['captcha_reg']; })();
?>
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - KONSERKITA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Poppins',sans-serif; background: linear-gradient(135deg, #0d6efd, #6610f2); min-height: 100vh; display: flex; align-items: center; }
        .card { border-radius: 16px; }
        .btn-primary { background: linear-gradient(135deg,#6610f2,#0d6efd); border:none; }
        .btn-primary:hover { background: linear-gradient(135deg,#59359a,#0b5ed7); border:none; }
        .form-control:focus { border-color:#6610f2; box-shadow:0 0 0 0.2rem rgba(102,16,242,0.15); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow p-4">
                <h4 class="fw-bold text-center mb-1">KONSERKITA</h4>
                <p class="text-center text-muted mb-4">Buat akun baru</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="konfirm" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Verifikasi: Berapa hasil dari <strong><?= $cap['a'] ?> + <?= $cap['b'] ?></strong>?</label>
                        <input type="number" name="captcha" class="form-control" placeholder="Jawaban..." required>
                    </div>
                    <button class="btn btn-primary w-100 rounded-pill">Daftar</button>
                </form>

                <p class="text-center mt-3 mb-0 small">Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
