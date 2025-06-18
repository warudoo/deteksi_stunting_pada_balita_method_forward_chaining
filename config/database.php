<?php
/*
 * File ini berfungsi untuk menghubungkan aplikasi dengan database MySQL.
 * Variabel di bawah ini adalah konfigurasi untuk koneksi database.
 */

// Konfigurasi Database
$db_host = 'localhost';     // Nama host database, biasanya 'localhost'
$db_user = 'root';          // Username database, default XAMPP adalah 'root'
$db_pass = '';              // Password database, default XAMPP kosong
$db_name = 'db_stunting';   // Nama database yang sudah kita buat

// Membuat koneksi ke database menggunakan mysqli
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Memeriksa apakah koneksi berhasil atau gagal
if (!$koneksi) {
    // Jika koneksi gagal, hentikan script dan tampilkan pesan error
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Jika koneksi berhasil, script akan lanjut berjalan tanpa menampilkan apa-apa.
?>