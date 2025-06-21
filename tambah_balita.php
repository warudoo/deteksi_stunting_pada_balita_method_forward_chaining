<?php
session_start();
require 'config/database.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_balita = mysqli_real_escape_string($koneksi, $_POST['nama_balita']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    if (empty($nama_balita) || empty($tanggal_lahir) || empty($jenis_kelamin)) {
        $error = "Semua kolom bertanda * wajib diisi.";
    } else {
        $query = "INSERT INTO balita (id_user, nama_balita, tanggal_lahir, jenis_kelamin, alamat)
                  VALUES ('$id_user', '$nama_balita', '$tanggal_lahir', '$jenis_kelamin', '$alamat')";
        if (mysqli_query($koneksi, $query)) {
            $message = "Data balita berhasil ditambahkan!";
            header("refresh:2; url=dashboard.php");
        } else {
            $error = "Gagal menambahkan data balita: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Data Balita</title>
  <link rel="stylesheet" href="src/output.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4 py-8">
  <div class="w-full max-w-xl bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4 text-gray-800">Tambah Data Balita</h2>

    <?php if (!empty($message)): ?>
      <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4 text-sm"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="tambah_balita.php" method="POST" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama Balita *</label>
        <input type="text" name="nama_balita" required class="w-full border border-gray-300 p-2 rounded mt-1">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir *</label>
        <input type="date" name="tanggal_lahir" required class="w-full border border-gray-300 p-2 rounded mt-1">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin *</label>
        <select name="jenis_kelamin" required class="w-full border border-gray-300 p-2 rounded mt-1">
          <option value="">-- Pilih --</option>
          <option value="Laki-laki">Laki-laki</option>
          <option value="Perempuan">Perempuan</option>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Alamat (Opsional)</label>
        <textarea name="alamat" rows="3" class="w-full border border-gray-300 p-2 rounded mt-1"></textarea>
      </div>

      <div class="flex justify-between items-center mt-6">
        <a href="dashboard.php" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Dashboard</a>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">Simpan Data</button>
      </div>
    </form>
  </div>
</body>
</html>
