<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

// Cek apakah pengguna adalah admin
if ($_SESSION['role'] !== 'admin') {
    echo "Akses ditolak. Halaman ini hanya untuk admin.";
    header("refresh:2; url=../dashboard.php"); // redirect balik setelah 2 detik
    exit();
}
?>