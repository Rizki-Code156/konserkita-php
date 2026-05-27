<?php
$host = 'localhost';
$db   = 'konserkita';
$user = 'root';
$pass = ''; // sesuaikan password MySQL kamu

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('<div style="font-family:sans-serif;padding:20px;color:red">
        <strong>Koneksi database gagal.</strong><br>
        Pastikan MySQL aktif dan kredensial di <code>includes/db.php</code> sudah benar.<br><br>
        <small>' . htmlspecialchars($e->getMessage()) . '</small>
    </div>');
}
