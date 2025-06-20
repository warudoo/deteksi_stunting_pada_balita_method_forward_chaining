<?php
require 'auth_admin.php'; 
require '../config/database.php';

$total_users = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_user) as total FROM users WHERE role='user'"))['total'];
$total_balita = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_balita) as total FROM balita"))['total'];
$total_diagnosis = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(id_hasil) as total FROM hasil_diagnosis"))['total'];

$recent_diagnosis_query = mysqli_query($koneksi, 
    "SELECT h.tanggal_diagnosis, b.nama_balita, u.nama_lengkap_user, h.kesimpulan_akhir
     FROM hasil_diagnosis h
     JOIN balita b ON h.id_balita = b.id_balita
     JOIN users u ON b.id_user = u.id_user
     ORDER BY h.tanggal_diagnosis DESC
     LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<<<<<<< Updated upstream
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Sistem Diagnosis Stunting</title>
  <link rel="stylesheet" href="../src/output.css">
</head>
<body class="bg-gray-100 min-h-screen py-6 px-4">

  <div class="max-w-7xl mx-auto space-y-10">

    <!-- Header -->
    <header class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
      <div class="text-sm text-gray-700">
        Halo, <strong><?php echo htmlspecialchars($_SESSION['username']); ?>!</strong>
        <a href="../logout.php" class="ml-4 text-blue-600 hover:underline">Logout →</a>
      </div>
    </header>

    <!-- Summary Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg border-l-4 border-blue-500 shadow p-6 text-center hover:shadow-md transition">
        <div class="text-4xl font-bold text-gray-800"><?php echo $total_users; ?></div>
        <div class="text-gray-600 mt-2">Total Pengguna</div>
      </div>
      <div class="bg-white rounded-lg border-l-4 border-green-500 shadow p-6 text-center hover:shadow-md transition">
        <div class="text-4xl font-bold text-gray-800"><?php echo $total_balita; ?></div>
        <div class="text-gray-600 mt-2">Total Balita Terdaftar</div>
      </div>
      <div class="bg-white rounded-lg border-l-4 border-yellow-400 shadow p-6 text-center hover:shadow-md transition">
        <div class="text-4xl font-bold text-gray-800"><?php echo $total_diagnosis; ?></div>
        <div class="text-gray-600 mt-2">Total Diagnosis Dilakukan</div>
      </div>
    </section>

    <!-- Menu Navigasi -->
    <section class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Menu Manajemen</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="./kelola_pengguna.php" class="p-4 border rounded hover:shadow-md transition text-center text-gray-700">
          <div class="text-3xl mb-2">👤</div>
          <div class="font-semibold">Kelola Pengguna</div>
          <p class="text-sm text-gray-500">Tambah, edit, atau hapus data admin dan user.</p>
        </a>
        <a href="kelola_aturan.php" class="p-4 border rounded hover:shadow-md transition text-center text-gray-700">
          <div class="text-3xl mb-2">🧠</div>
          <div class="font-semibold">Kelola Aturan & Pertanyaan</div>
          <p class="text-sm text-gray-500">Ubah basis pengetahuan untuk sistem pakar.</p>
        </a>
        <a href="laporan_diagnosis.php" class="p-4 border rounded hover:shadow-md transition text-center text-gray-700">
          <div class="text-3xl mb-2">📋</div>
          <div class="font-semibold">Laporan Diagnosis</div>
=======
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin</title>
  <link href="../src/output.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen px-4 py-6">

  <div class="max-w-6xl mx-auto space-y-8">
    <!-- Header -->
    <header class="flex justify-between items-center">
      <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
      <div class="text-sm text-gray-600">
        Halo, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> &mdash; 
        <a href="../logout.php" class="text-blue-600 hover:underline ml-2">Logout &rarr;</a>
      </div>
    </header>

    <!-- Summary Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
      <div class="bg-white border-l-4 border-blue-500 shadow p-5 rounded">
        <div class="text-3xl font-bold text-gray-700"><?php echo $total_users; ?></div>
        <div class="text-sm text-gray-600">Total Pengguna</div>
      </div>
      <div class="bg-white border-l-4 border-green-500 shadow p-5 rounded">
        <div class="text-3xl font-bold text-gray-700"><?php echo $total_balita; ?></div>
        <div class="text-sm text-gray-600">Total Balita Terdaftar</div>
      </div>
      <div class="bg-white border-l-4 border-yellow-400 shadow p-5 rounded">
        <div class="text-3xl font-bold text-gray-700"><?php echo $total_diagnosis; ?></div>
        <div class="text-sm text-gray-600">Total Diagnosis Dilakukan</div>
      </div>
    </section>

    <!-- Navigation Cards -->
    <section class="bg-white rounded shadow p-6">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Menu Manajemen</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <a href="kelola_pengguna.php" class="p-5 border rounded hover:shadow hover:bg-blue-50 transition">
          <div class="text-3xl mb-2">👤</div>
          <div class="font-medium text-gray-700">Kelola Pengguna</div>
          <p class="text-sm text-gray-500">Tambah, edit, atau hapus data admin dan user.</p>
        </a>
        <a href="kelola_aturan.php" class="p-5 border rounded hover:shadow hover:bg-blue-50 transition">
          <div class="text-3xl mb-2">🧠</div>
          <div class="font-medium text-gray-700">Kelola Aturan & Pertanyaan</div>
          <p class="text-sm text-gray-500">Ubah basis pengetahuan untuk sistem pakar.</p>
        </a>
        <a href="laporan_diagnosis.php" class="p-5 border rounded hover:shadow hover:bg-blue-50 transition">
          <div class="text-3xl mb-2">📋</div>
          <div class="font-medium text-gray-700">Laporan Diagnosis</div>
>>>>>>> Stashed changes
          <p class="text-sm text-gray-500">Lihat semua riwayat diagnosis dan ekspor data.</p>
        </a>
      </div>
    </section>

<<<<<<< Updated upstream
    <!-- Aktivitas Terbaru -->
    <section class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Aktivitas Diagnosis Terbaru</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm border">
          <thead class="bg-gray-50">
            <tr class="text-left text-gray-600">
=======
    <!-- Recent Activity -->
    <section class="bg-white rounded shadow p-6">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Aktivitas Diagnosis Terbaru</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto text-sm border border-gray-200">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
>>>>>>> Stashed changes
              <th class="px-4 py-2 border-b">Tanggal</th>
              <th class="px-4 py-2 border-b">Nama Balita</th>
              <th class="px-4 py-2 border-b">Orang Tua</th>
              <th class="px-4 py-2 border-b">Hasil</th>
            </tr>
          </thead>
<<<<<<< Updated upstream
          <tbody>
            <?php if (mysqli_num_rows($recent_diagnosis_query) > 0): ?>
              <?php while($row = mysqli_fetch_assoc($recent_diagnosis_query)): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 border-b"><?php echo date('d M Y, H:i', strtotime($row['tanggal_diagnosis'])); ?></td>
                <td class="px-4 py-2 border-b"><?php echo htmlspecialchars($row['nama_balita']); ?></td>
                <td class="px-4 py-2 border-b"><?php echo htmlspecialchars($row['nama_lengkap_user']); ?></td>
                <td class="px-4 py-2 border-b"><?php echo htmlspecialchars($row['kesimpulan_akhir']); ?></td>
=======
          <tbody class="divide-y divide-gray-100">
            <?php if (mysqli_num_rows($recent_diagnosis_query) > 0): ?>
              <?php while($row = mysqli_fetch_assoc($recent_diagnosis_query)): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-2"><?php echo date('d M Y, H:i', strtotime($row['tanggal_diagnosis'])); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($row['nama_balita']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($row['nama_lengkap_user']); ?></td>
                <td class="px-4 py-2"><?php echo htmlspecialchars($row['kesimpulan_akhir']); ?></td>
>>>>>>> Stashed changes
              </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
<<<<<<< Updated upstream
                <td colspan="4" class="text-center py-4 text-gray-500">Belum ada aktivitas diagnosis.</td>
=======
                <td colspan="4" class="text-center px-4 py-4 text-gray-500">Belum ada aktivitas diagnosis.</td>
>>>>>>> Stashed changes
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
  </div>

</body>
</html>
