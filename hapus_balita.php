<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['id_user']) || !isset($_GET['id_balita'])) {
    header("Location: dashboard.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$id_balita = (int)$_GET['id_balita'];

// Cek apakah balita milik user yang sedang login
$cek = mysqli_query($koneksi, "SELECT id_balita FROM balita WHERE id_balita = $id_balita AND id_user = $id_user");
if (mysqli_num_rows($cek) === 0) {
    echo "Akses tidak sah!";
    header("refresh:2; url=dashboard.php");
    exit();
}

// Hapus data
mysqli_query($koneksi, "DELETE FROM balita WHERE id_balita = $id_balita");
header("Location: dashboard.php");
exit();
?>
