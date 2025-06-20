<?php
session_start();
require 'config/database.php';

<<<<<<< Updated upstream
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['id_hasil'])) {
    header("Location: dashboard.php");
    exit();
}
=======
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit(); }
if (!isset($_GET['id_hasil'])) { header("Location: dashboard.php"); exit(); }
>>>>>>> Stashed changes

$id_hasil = (int)$_GET['id_hasil'];
$id_user = $_SESSION['id_user'];

$query = "SELECT h.*, b.nama_balita, u.nama_lengkap_user 
          FROM hasil_diagnosis h
          JOIN balita b ON h.id_balita = b.id_balita
          JOIN users u ON b.id_user = u.id_user
          WHERE h.id_hasil = $id_hasil AND u.id_user = $id_user";

$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: dashboard.php");
    exit();
}

$hasil = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< Updated upstream
  <title>Hasil Diagnosis - Aplikasi Diagnosis Stunting</title>
  <link rel="stylesheet" href="src/output.css"> <!-- Tailwind CSS -->
</head>
<body class="bg-gray-100 min-h-screen py-6 px-4">

  <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md space-y-6 text-center">
    <h2 class="text-2xl font-bold text-gray-800">Hasil Diagnosis</h2>

    <div class="text-left space-y-2 text-gray-700">
      <p><span class="font-medium">Nama Balita:</span> <?php echo htmlspecialchars($hasil['nama_balita']); ?></p>
      <p><span class="font-medium">Nama Orang Tua:</span> <?php echo htmlspecialchars($hasil['nama_lengkap_user']); ?></p>
      <p><span class="font-medium">Tanggal Diagnosis:</span> <?php echo date('d F Y, H:i', strtotime($hasil['tanggal_diagnosis'])); ?></p>
      <p><span class="font-medium">Usia Saat Diagnosis:</span> <?php echo $hasil['catatan_usia_saat_diagnosis']; ?> bulan</p>
    </div>

    <p class="text-gray-600 mt-4">
      Berdasarkan jawaban yang diberikan, sistem menyimpulkan bahwa kondisi balita adalah:
    </p>

    <div class="text-red-700 bg-red-100 border-2 border-dashed border-red-400 rounded-md px-6 py-4 text-xl font-semibold">
      <?php echo htmlspecialchars($hasil['kesimpulan_akhir']); ?>
    </div>

    <p class="text-xs text-gray-500 leading-relaxed mt-4">
      <strong>Disclaimer:</strong> Hasil ini adalah indikasi awal berdasarkan metode sistem pakar.<br>
      Untuk diagnosis yang akurat dan penanganan lebih lanjut, sangat disarankan untuk berkonsultasi langsung dengan dokter atau ahli gizi.
    </p>

    <a href="dashboard.php" class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
      Kembali ke Dashboard
    </a>
  </div>

=======
  <title>Hasil Diagnosis</title>
  <link href="src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen px-4 py-6">
  <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow space-y-6">
    <h2 class="text-2xl font-bold text-gray-800 text-center">Hasil Diagnosis</h2>

    <div class="text-sm text-gray-700 space-y-2">
      <p><strong>Nama Balita:</strong> <?php echo htmlspecialchars($hasil['nama_balita']); ?></p>
      <p><strong>Nama Orang Tua:</strong> <?php echo htmlspecialchars($hasil['nama_lengkap_user']); ?></p>
      <p><strong>Tanggal Diagnosis:</strong> <?php echo date('d F Y, H:i', strtotime($hasil['tanggal_diagnosis'])); ?></p>
      <p><strong>Usia Saat Diagnosis:</strong> <?php echo $hasil['catatan_usia_saat_diagnosis']; ?> bulan</p>
    </div>

    <div class="text-center">
      <p class="text-sm text-gray-700 mt-4">Berdasarkan jawaban yang diberikan, sistem menyimpulkan bahwa kondisi balita adalah:</p>
      <div class="mt-3 p-4 border-2 border-dashed border-red-400 bg-red-50 text-red-600 font-semibold text-xl rounded">
        <?php echo htmlspecialchars($hasil['kesimpulan_akhir']); ?>
      </div>
    </div>

    <p class="text-xs text-gray-500 text-center">
      <strong>Disclaimer:</strong> Hasil ini adalah indikasi awal berdasarkan metode sistem pakar. Untuk diagnosis yang akurat dan penanganan lebih lanjut, sangat disarankan untuk berkonsultasi langsung dengan dokter atau ahli gizi.
    </p>

    <div class="text-center mt-6">
      <a href="dashboard.php" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-medium">Kembali ke Dashboard</a>
    </div>
  </div>
>>>>>>> Stashed changes
</body>
</html>
