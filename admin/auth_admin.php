<?php
// File ini akan menjadi penjaga untuk semua halaman admin

// Mulai session
session_start();

// Cek dua hal:
// 1. Apakah user sudah login (ada session id_user)
// 2. Apakah role user tersebut adalah 'admin'
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    // Jika salah satu kondisi tidak terpenuhi, user tidak berhak mengakses.
    // Kirim pesan error dan arahkan kembali ke halaman login.
    echo "Akses ditolak. Anda harus login sebagai admin untuk mengakses halaman ini.";
    // Arahkan ke login.php di folder utama (../) setelah 2 detik
    header("refresh:2;url=../login.php"); 
    exit();
}
?>