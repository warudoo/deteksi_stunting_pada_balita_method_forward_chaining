<?php
require 'auth_admin.php'; 
require '../config/database.php';

$query_laporan = "SELECT 
    h.id_hasil, 
    h.tanggal_diagnosis, 
    h.catatan_usia_saat_diagnosis, 
    h.kesimpulan_akhir,
    b.nama_balita,
    u.nama_lengkap_user
  FROM hasil_diagnosis h
  JOIN balita b ON h.id_balita = b.id_balita
  JOIN users u ON b.id_user = u.id_user
  ORDER BY h.tanggal_diagnosis DESC";

$result_laporan = mysqli_query($koneksi, $query_laporan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Diagnosis - Admin</title>
  <link href="../src/output.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 min-h-screen px-4 py-6">

  <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow space-y-6">
    <a href="dashboard_admin.php" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Dashboard Admin</a>
    
    <div class="space-y-1">
      <h2 class="text-2xl font-bold text-gray-800">Laporan Seluruh Diagnosis</h2>
      <p class="text-sm text-gray-600">Berikut adalah daftar seluruh riwayat diagnosis yang tercatat dalam sistem.</p>
    </div>

    <a href="export.php" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm font-medium">
      Ekspor ke CSV (Excel)
    </a>

    <div class="overflow-x-auto mt-4">
      <table class="min-w-full text-sm overflow-hidden">
        <thead class="bg-gray-100 text-center border border-gray-300 text-gray-700 font-semibold">
          <tr>
            <th class="px-4 py-2 border">No.</th>
            <th class="px-4 py-2 border">Tanggal Diagnosis</th>
            <th class="px-4 py-2 border">Nama Balita</th>
            <th class="px-4 py-2 border">Nama Orang Tua</th>
            <th class="px-4 py-2 border">Usia Saat Diagnosis</th>
            <th class="px-4 py-2 border">Hasil Diagnosis</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if (mysqli_num_rows($result_laporan) > 0): ?>
            <?php $no = 1; while($row = mysqli_fetch_assoc($result_laporan)): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 border text-center"><?php echo $no++; ?></td>
                <td class="px-4 py-2 border"><?php echo date('d M Y, H:i', strtotime($row['tanggal_diagnosis'])); ?></td>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['nama_balita']); ?></td>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['nama_lengkap_user']); ?></td>
                <td class="px-4 py-2 border"><?php echo $row['catatan_usia_saat_diagnosis']; ?> bulan</td>
                <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['kesimpulan_akhir']); ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center px-4 py-6 text-gray-500">Belum ada data diagnosis untuk ditampilkan.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
