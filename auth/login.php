<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['user'])) { header('Location: ../index.php'); exit; }

require_once '../includes/db.php';

// Generate captcha baru
if (empty($_SESSION['captcha_login'])) {
    $a = rand(1, 9); $b = rand(1, 9);
    $_SESSION['captcha_login'] = ['a' => $a, 'b' => $b, 'ans' => $a + $b];
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password']      ?? '';
    $captcha  = (int)($_POST['captcha'] ?? -1);

    if ($captcha !== $_SESSION['captcha_login']['ans']) {
        $error = 'Jawaban captcha salah.';
        $a = rand(1, 9); $b = rand(1, 9);
        $_SESSION['captcha_login'] = ['a' => $a, 'b' => $b, 'ans' => $a + $b];
    } else {
        unset($_SESSION['captcha_login']);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        $passwordValid = false;

        if (str_starts_with($user['password'] ?? '', '$2y$')) {
            $passwordValid = $user && password_verify($password, $user['password']);
        } else {
            $passwordValid = $user && $password === $user['password'];
            if ($passwordValid) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$newHash, $user['id']]);
            }
        }

        if ($passwordValid) {
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama'];
            header('Location: ' . ($user['role'] === 'admin' ? '../admin/dashboard.php' : '../user/dashboard.php'));
            exit;
        } else {
            $error = 'Username atau password salah.';
            $a = rand(1, 9); $b = rand(1, 9);
            $_SESSION['captcha_login'] = ['a' => $a, 'b' => $b, 'ans' => $a + $b];
        }
    }
}
$cap = $_SESSION['captcha_login'];
?>
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - KONSERKITA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Poppins',sans-serif; background: linear-gradient(135deg, #6610f2, #0d6efd); min-height: 100vh; display: flex; align-items: center; }
        .card { border-radius: 16px; }
        .btn-primary { background: linear-gradient(135deg,#6610f2,#0d6efd); border:none; }
        .btn-primary:hover { background: linear-gradient(135deg,#59359a,#0b5ed7); border:none; }
        .form-control:focus { border-color:#6610f2; box-shadow:0 0 0 0.2rem rgba(102,16,242,0.15); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow p-4">
                <h4 class="fw-bold text-center mb-1">KONSERKITA</h4>
                <p class="text-center text-muted mb-4">Masuk ke akun kamu</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Verifikasi: Berapa hasil dari <strong><?= $cap['a'] ?> + <?= $cap['b'] ?></strong>?</label>
                        <input type="number" name="captcha" class="form-control" placeholder="Jawaban..." required>
                    </div>
                    <button class="btn btn-primary w-100 rounded-pill">Masuk</button>
                </form>

                <p class="text-center mt-3 mb-0 small">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
                <p class="text-center mt-2 mb-0 small"><a href="../index.php">← Kembali ke Beranda</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
