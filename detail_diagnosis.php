<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id_hasil']) || !is_numeric($_GET['id_hasil'])) {
    header("Location: dashboard.php");
    exit();
}

$id_hasil = (int)$_GET['id_hasil'];
$id_user = $_SESSION['id_user'];

$query = "SELECT h.*, b.nama_balita, u.nama_lengkap_user 
          FROM hasil_diagnosis h
          JOIN balita b ON h.id_balita = b.id_balita
          JOIN users u ON b.id_user = u.id_user
          WHERE h.id_hasil = $id_hasil AND u.id_user = $id_user";

$result = mysqli_query($koneksi, $query);
if (mysqli_num_rows($result) === 0) {
    header("Location: dashboard.php");
    exit();
}

$hasil = mysqli_fetch_assoc($result);
$jawaban = json_decode($hasil['jawaban'], true);

$query_gejala = mysqli_query($koneksi, "SELECT kode_gejala, teks_pertanyaan FROM pertanyaan");
$pertanyaan_map = [];
while ($g = mysqli_fetch_assoc($query_gejala)) {
    $pertanyaan_map[$g['kode_gejala']] = $g['teks_pertanyaan'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Diagnosis</title>
  <link href="src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen px-4 py-6">
  <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6 space-y-6">
    <div class="space-y-1">
      <h2 class="text-2xl font-bold text-gray-800">Detail Hasil Diagnosis</h2>
      <p class="text-sm text-gray-600">Nama Balita: <span class="font-medium text-gray-800"><?php echo htmlspecialchars($hasil['nama_balita']); ?></span></p>
      <p class="text-sm text-gray-600">Orang Tua: <span class="font-medium text-gray-800"><?php echo htmlspecialchars($hasil['nama_lengkap_user']); ?></span></p>
      <p class="text-sm text-gray-600">Tanggal Diagnosis: <span class="text-gray-700"><?php echo date('d F Y, H:i', strtotime($hasil['tanggal_diagnosis'])); ?></span></p>
      <p class="text-sm text-gray-600">Usia Saat Diagnosis: <span class="text-gray-700"><?php echo $hasil['catatan_usia_saat_diagnosis']; ?> bulan</span></p>
    </div>

    <div class="mt-4">
      <h3 class="text-lg font-semibold text-gray-700 mb-2">Jawaban Pengguna:</h3>
      <ul class="space-y-2">
        <?php foreach ($jawaban as $kode => $isi): ?>
          <li class="border px-4 py-2 rounded bg-gray-50">
            <span class="font-medium text-gray-700"><?php echo $pertanyaan_map[$kode] ?? $kode; ?>:</span>
            <span class="text-gray-800"> <?php echo ucfirst($isi); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="mt-6">
      <h3 class="text-lg font-semibold text-gray-700">Kesimpulan Akhir:</h3>
      <div class="mt-2 px-4 py-3 border border-red-300 bg-red-50 text-red-700 rounded">
        <?php echo htmlspecialchars($hasil['kesimpulan_akhir']); ?>
      </div>
    </div>

    <a href="riwayat.php?id_balita=<?php echo $hasil['id_balita']; ?>" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
      &larr; Kembali ke Riwayat
    </a>
  </div>
</body>
</html>
