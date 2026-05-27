KonserKita 🎵
Platform sistem informasi pembelian tiket konser musik berbasis web untuk wilayah Cirebon.

Tech Stack
Backend: PHP Native
Database: MySQL (PDO)
Frontend: Bootstrap 5, DataTables, jQuery
Server: XAMPP (Apache + MySQL)

Struktur Folder

konserkita/

├── admin/

│   ├── dashboard.php       # Panel admin (kelola konser, tiket, pengguna)

│   └── handler.php         # Handler AJAX untuk operasi CRUD

├── auth/

│   ├── login.php           # Halaman login + math captcha

│   ├── register.php        # Halaman registrasi + math captcha

│   └── logout.php          # Proses logout

├── includes/

│   ├── db.php              # Koneksi database PDO

│   └── navbar.php          # Komponen navbar global

├── pages/

│   ├── kategori.php        # Halaman konser per kategori

│   ├── pembayaran.php      # Form pembelian tiket

│   ├── cekstatus.php       # Cek status tiket via kode booking

│   └── panduan.php         # Panduan penggunaan

├── user/

│   └── dashboard.php       # Dashboard pengguna

├── testing/

│   └── testing.php         # Form pengujian black box

├── index.php               # Halaman utama

└── konserkita.sql          # File database

Cara Instalasi
Clone atau copy folder konserkita ke C:\xampp\htdocs\
Jalankan XAMPP, aktifkan Apache dan MySQL
Buka phpMyAdmin → tab Import → pilih file konserkita.sql → klik Go
Buka browser dan akses http://localhost/konserkita

Akun Default
Role	Username	Password
Admin	admin	admin123
User	user1	admin123
User	siti	admin123
Password plain text otomatis di-hash bcrypt saat pertama kali login.

Fitur
Publik

Beranda dengan carousel konser unggulan
Filter konser berdasarkan kategori (Pop, Rock, Jazz, K-Pop)
Cek status tiket via kode booking
User (Member)

Registrasi & login dengan math captcha
Dashboard pribadi dengan riwayat tiket
Pembelian tiket dengan pilihan kategori (Regular, VIP, SVIP, Festival)
Konfirmasi pembayaran via Transfer Bank, GoPay, OVO, DANA
Admin

Kelola konser (tambah, edit, hapus)
Kelola tiket & konfirmasi pembayaran
Kelola data pengguna
Tabel dengan search, pagination, dan show entries
Lokasi Venue (Cirebon)
Seluruh konser berlokasi di area Cirebon, antara lain: Gedung Kesenian, Stadion Bima, GOR Bima, Alun-Alun Kejaksan, Keraton Kasepuhan, Keraton Kanoman, Taman Air Mancur, dan Taman Ade Irma.

Pengujian
Akses halaman pengujian black box di http://localhost/konserkita/testing/testing.php

