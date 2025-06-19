<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id_balita']) || !is_numeric($_GET['id_balita'])) {
    header("Location: dashboard.php");
    exit();
}

$id_balita = (int)$_GET['id_balita'];
$id_user = $_SESSION['id_user'];

$query_cek = "SELECT nama_balita FROM balita WHERE id_balita = $id_balita AND id_user = $id_user";
$result_cek = mysqli_query($koneksi, $query_cek);
if (mysqli_num_rows($result_cek) == 0) {
    echo "Akses tidak sah.";
    header("refresh:2;url=dashboard.php");
    exit();
}

$data_balita = mysqli_fetch_assoc($result_cek);
$nama_balita = $data_balita['nama_balita'];

$query_riwayat = "SELECT id_hasil, tanggal_diagnosis, catatan_usia_saat_diagnosis, kesimpulan_akhir 
                  FROM hasil_diagnosis 
                  WHERE id_balita = $id_balita 
                  ORDER BY tanggal_diagnosis DESC";
$result_riwayat = mysqli_query($koneksi, $query_riwayat);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Riwayat Diagnosis - <?php echo htmlspecialchars($nama_balita); ?></title>
  <link rel="stylesheet" href="src/output.css">
</head>
<body class="bg-gray-100 min-h-screen px-4 py-6">

  <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6 space-y-6">
    <div class="space-y-2">
      <h2 class="text-2xl font-bold text-gray-800">Riwayat Diagnosis</h2>
      <h3 class="text-lg font-medium text-gray-600">Untuk Balita: <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($nama_balita); ?></span></h3>
      <a href="dashboard.php" class="inline-block mt-2 text-sm bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        &larr; Kembali ke Dashboard
      </a>
    </div>

    <?php if (mysqli_num_rows($result_riwayat) > 0): ?>
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto text-sm border border-gray-200">
        <thead class="bg-gray-50 text-gray-700">
          <tr>
            <th class="px-4 py-2 border-b">No.</th>
            <th class="px-4 py-2 border-b">Tanggal Diagnosis</th>
            <th class="px-4 py-2 border-b">Usia Saat Diagnosis</th>
            <th class="px-4 py-2 border-b">Hasil Diagnosis</th>
            <th class="px-4 py-2 border-b">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php 
          $nomor = 1;
          while ($riwayat = mysqli_fetch_assoc($result_riwayat)): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-2"><?php echo $nomor++; ?></td>
              <td class="px-4 py-2"><?php echo date('d F Y, H:i', strtotime($riwayat['tanggal_diagnosis'])); ?></td>
              <td class="px-4 py-2"><?php echo $riwayat['catatan_usia_saat_diagnosis']; ?> bulan</td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($riwayat['kesimpulan_akhir']); ?></td>
              <td class="px-4 py-2">
                <a href="detail_diagnosis.php?id_hasil=<?php echo $riwayat['id_hasil']; ?>" class="inline-block bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition">
                  Lihat Detail
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <?php else: ?>
    <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-4 rounded text-center text-sm">
      Belum ada riwayat diagnosis untuk balita ini.
    </div>
    <?php endif; ?>
  </div>

</body>
</html>
