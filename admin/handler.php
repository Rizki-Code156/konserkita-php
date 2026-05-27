<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']); exit;
}

require_once '../includes/db.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

// ══════════════════════════════════════════════
//  KONSER
// ══════════════════════════════════════════════
if ($action === 'tambah_konser') {
    $stmt = $pdo->prepare("INSERT INTO konser (nama, kategori, kota, tanggal, harga, status) VALUES (?,?,?,?,?,?)");
    $stmt->execute([
        $_POST['nama'], $_POST['kategori'], $_POST['kota'],
        $_POST['tanggal'], (int)str_replace(['Rp ','.',',' ,''], '', $_POST['harga']),
        $_POST['status']
    ]);
    echo json_encode(['success' => true]);
}

elseif ($action === 'edit_konser') {
    $stmt = $pdo->prepare("UPDATE konser SET nama=?, kategori=?, kota=?, tanggal=?, harga=?, status=? WHERE id=?");
    $stmt->execute([
        $_POST['nama'], $_POST['kategori'], $_POST['kota'],
        $_POST['tanggal'], (int)str_replace(['Rp ','.',',' ,''], '', $_POST['harga']),
        $_POST['status'], (int)$_POST['id']
    ]);
    echo json_encode(['success' => true]);
}

elseif ($action === 'hapus_konser') {
    $stmt = $pdo->prepare("DELETE FROM konser WHERE id=?");
    $stmt->execute([(int)$_POST['id']]);
    echo json_encode(['success' => true]);
}

// ══════════════════════════════════════════════
//  TIKET
// ══════════════════════════════════════════════
elseif ($action === 'tambah_tiket') {
    // cari user_id dan konser_id berdasarkan nama
    $u = $pdo->prepare("SELECT id FROM users WHERE nama=? LIMIT 1");
    $u->execute([$_POST['user_nama']]);
    $uid = $u->fetchColumn();

    $k = $pdo->prepare("SELECT id FROM konser WHERE nama=? LIMIT 1");
    $k->execute([$_POST['konser_nama']]);
    $kid = $k->fetchColumn();

    if (!$uid || !$kid) { echo json_encode(['success'=>false,'message'=>'User atau konser tidak ditemukan']); exit; }

    $stmt = $pdo->prepare("INSERT INTO tiket (kode, user_id, konser_id, kategori, harga, status) VALUES (?,?,?,?,?,?)");
    $stmt->execute([
        strtoupper($_POST['kode']), $uid, $kid,
        $_POST['kategori'],
        (int)str_replace(['Rp ','.',',' ,''], '', $_POST['harga']),
        $_POST['status']
    ]);
    echo json_encode(['success' => true]);
}

elseif ($action === 'edit_tiket') {
    $stmt = $pdo->prepare("UPDATE tiket SET kategori=?, harga=?, status=? WHERE id=?");
    $stmt->execute([
        $_POST['kategori'],
        (int)str_replace(['Rp ','.',',' ,''], '', $_POST['harga']),
        $_POST['status'],
        (int)$_POST['id']
    ]);
    echo json_encode(['success' => true]);
}

elseif ($action === 'hapus_tiket') {
    $stmt = $pdo->prepare("DELETE FROM tiket WHERE id=?");
    $stmt->execute([(int)$_POST['id']]);
    echo json_encode(['success' => true]);
}

// ══════════════════════════════════════════════
//  PENGGUNA
// ══════════════════════════════════════════════
elseif ($action === 'tambah_pengguna') {
    // cek duplikat username
    $cek = $pdo->prepare("SELECT id FROM users WHERE username=?");
    $cek->execute([$_POST['username']]);
    if ($cek->fetch()) { echo json_encode(['success'=>false,'message'=>'Username sudah digunakan']); exit; }

    $stmt = $pdo->prepare("INSERT INTO users (nama, username, email, password, role) VALUES (?,?,?,?,?)");
    $stmt->execute([
        $_POST['nama'], $_POST['username'], $_POST['email'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['role']
    ]);
    echo json_encode(['success' => true]);
}

elseif ($action === 'edit_pengguna') {
    if (!empty($_POST['password'])) {
        $stmt = $pdo->prepare("UPDATE users SET nama=?, username=?, email=?, password=?, role=? WHERE id=?");
        $stmt->execute([
            $_POST['nama'], $_POST['username'], $_POST['email'],
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            $_POST['role'], (int)$_POST['id']
        ]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET nama=?, username=?, email=?, role=? WHERE id=?");
        $stmt->execute([
            $_POST['nama'], $_POST['username'], $_POST['email'],
            $_POST['role'], (int)$_POST['id']
        ]);
    }
    echo json_encode(['success' => true]);
}

elseif ($action === 'hapus_pengguna') {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=? AND role != 'admin'");
    $stmt->execute([(int)$_POST['id']]);
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success'=>false,'message'=>'Akun admin tidak bisa dihapus']);
    } else {
        echo json_encode(['success' => true]);
    }
}

else {
    echo json_encode(['success' => false, 'message' => 'Action tidak dikenal']);
}
