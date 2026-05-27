<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengujian Black Box - KONSERKITA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f1f3f5; }
        .header { background: linear-gradient(135deg, #0f0f1a, #1a0533); color: white; padding: 32px 0; }
        .card-form { border: none; border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .table th { font-size: 0.8rem; background: #0f0f1a; color: white; }
        .table td { font-size: 0.85rem; vertical-align: middle; }
        .badge-pass { background: #198754; }
        .badge-fail { background: #dc3545; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .card-form { box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="header no-print">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-0">🧪 Form Pengujian Black Box</h4>
            <small class="opacity-50">KONSERKITA — Sistem Informasi Tiket Konser</small>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-light btn-sm" onclick="tambahBaris()">+ Tambah Baris</button>
            <button class="btn btn-light btn-sm" onclick="window.print()">🖨️ Cetak / Export PDF</button>
        </div>
    </div>
</div>

<div class="container my-4">

    <!-- Info Pengujian -->
    <div class="card card-form p-4 mb-4 no-print">
        <h6 class="fw-bold mb-3">Informasi Pengujian</h6>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Nama Penguji</label>
                <input type="text" id="namaPenguji" class="form-control" placeholder="Nama lengkap">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Tanggal Pengujian</label>
                <input type="date" id="tanggalUji" class="form-control" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Versi Aplikasi</label>
                <input type="text" id="versiApp" class="form-control" placeholder="v1.0.0">
            </div>
        </div>
    </div>

    <!-- Tabel Pengujian -->
    <div class="card card-form p-4">

        <!-- Header print only -->
        <div class="d-none d-print-block mb-4">
            <h5 class="fw-bold">FORM PENGUJIAN BLACK BOX</h5>
            <p class="mb-1 small">Aplikasi: KONSERKITA — Sistem Informasi Tiket Konser</p>
            <p class="mb-1 small">Penguji: <span id="printPenguji"></span></p>
            <p class="mb-1 small">Tanggal: <span id="printTanggal"></span></p>
            <p class="mb-0 small">Versi: <span id="printVersi"></span></p>
            <hr>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <h6 class="fw-bold mb-0">Tabel Uji</h6>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-success" id="totalPass">Pass: 0</span>
                <span class="badge bg-danger" id="totalFail">Fail: 0</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="tabelUji">
                <thead>
                    <tr>
                        <th style="width:40px">No</th>
                        <th style="width:140px">Modul</th>
                        <th style="width:160px">Skenario Uji</th>
                        <th>Data Input</th>
                        <th>Hasil yang Diharapkan</th>
                        <th>Hasil Aktual</th>
                        <th style="width:90px">Status</th>
                        <th style="width:40px" class="no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bodyUji">
                    <!-- Baris default -->
                </tbody>
            </table>
        </div>

        <div class="no-print mt-2">
            <button class="btn btn-primary btn-sm rounded-pill px-4" onclick="tambahBaris()">+ Tambah Baris</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const modulOptions = [
    'Login','Register','Logout',
    'Beli Tiket','Pembayaran','Cek Status Tiket',
    'Dashboard User','Dashboard Admin',
    'Kelola Konser','Kelola Tiket','Kelola Pengguna'
];

const defaultRows = [
    ['Login', 'Login dengan data valid', 'Username: admin, Password: admin123', 'Berhasil masuk ke dashboard admin', '', 'Pass'],
    ['Login', 'Login dengan password salah', 'Username: admin, Password: salah123', 'Muncul pesan "Username atau password salah"', '', 'Fail'],
    ['Register', 'Daftar akun baru', 'Isi semua field dengan data valid', 'Akun berhasil dibuat, redirect ke login', '', 'Pass'],
    ['Beli Tiket', 'Beli tiket tanpa login', 'Klik tombol Pesan Tiket', 'Redirect ke halaman login', '', 'Pass'],
    ['Pembayaran', 'Submit form pembayaran', 'Isi semua field, pilih metode bayar', 'Tiket tersimpan dengan status Menunggu Pembayaran', '', 'Pass'],
    ['Cek Status Tiket', 'Cek kode tiket valid', 'Masukkan kode booking yang ada', 'Muncul detail tiket', '', 'Pass'],
];

let counter = 0;

function buatBaris(data = ['','','','','','Pass']) {
    counter++;
    const [modul, skenario, input, harapan, aktual, status] = data;
    const modulOpts = modulOptions.map(m =>
        `<option ${m === modul ? 'selected' : ''}>${m}</option>`
    ).join('');

    const tr = document.createElement('tr');
    tr.id = 'row-' + counter;
    tr.innerHTML = `
        <td class="text-center text-muted small">${counter}</td>
        <td><select class="form-select form-select-sm modul-sel" onchange="hitungStatus()">${modulOpts}</select></td>
        <td><input type="text" class="form-control form-control-sm" value="${skenario}" placeholder="Skenario..."></td>
        <td><input type="text" class="form-control form-control-sm" value="${input}" placeholder="Data input..."></td>
        <td><input type="text" class="form-control form-control-sm" value="${harapan}" placeholder="Hasil diharapkan..."></td>
        <td><input type="text" class="form-control form-control-sm" value="${aktual}" placeholder="Hasil aktual..."></td>
        <td>
            <select class="form-select form-select-sm status-sel" onchange="updateBadge(this); hitungStatus()">
                <option ${status==='Pass'?'selected':''} value="Pass">✅ Pass</option>
                <option ${status==='Fail'?'selected':''} value="Fail">❌ Fail</option>
            </select>
        </td>
        <td class="text-center no-print">
            <button class="btn btn-sm btn-outline-danger" onclick="hapusBaris('row-${counter}')">🗑</button>
        </td>
    `;
    document.getElementById('bodyUji').appendChild(tr);
    updateBadge(tr.querySelector('.status-sel'));
    hitungStatus();
}

function tambahBaris() { buatBaris(); }

function hapusBaris(id) {
    document.getElementById(id)?.remove();
    hitungStatus();
}

function updateBadge(sel) {
    sel.className = 'form-select form-select-sm status-sel';
    if (sel.value === 'Pass') sel.style.cssText = 'background:#d1fae5;color:#065f46;font-weight:600';
    else sel.style.cssText = 'background:#fee2e2;color:#991b1b;font-weight:600';
}

function hitungStatus() {
    const sels = document.querySelectorAll('.status-sel');
    let pass = 0, fail = 0;
    sels.forEach(s => s.value === 'Pass' ? pass++ : fail++);
    document.getElementById('totalPass').textContent = 'Pass: ' + pass;
    document.getElementById('totalFail').textContent = 'Fail: ' + fail;
}

// Sinkron info ke print header
window.addEventListener('beforeprint', () => {
    document.getElementById('printPenguji').textContent = document.getElementById('namaPenguji').value || '-';
    document.getElementById('printTanggal').textContent = document.getElementById('tanggalUji').value || '-';
    document.getElementById('printVersi').textContent   = document.getElementById('versiApp').value || '-';
});

// Load default rows
defaultRows.forEach(r => buatBaris(r));
</script>
</body>
</html>
