<?php
session_start();
require 'config/database.php';

// Penjaga Halaman
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit(); }
if (!isset($_GET['id_hasil'])) { header("Location: dashboard.php"); exit(); }

$id_hasil = (int)$_GET['id_hasil'];
$id_user = $_SESSION['id_user'];

// Query untuk mengambil detail hasil diagnosis dan data terkait
$query = "SELECT h.*, b.nama_balita, u.nama_lengkap_user 
          FROM hasil_diagnosis h
          JOIN balita b ON h.id_balita = b.id_balita
          JOIN users u ON b.id_user = u.id_user
          WHERE h.id_hasil = $id_hasil AND u.id_user = $id_user";

$result = mysqli_query($koneksi, $query);

// Keamanan: Cek apakah hasil ditemukan dan milik user yang login
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
    <title>Hasil Diagnosis - Aplikasi Diagnosis Stunting</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .result-card { text-align: center; }
        .result-conclusion {
            font-size: 28px;
            font-weight: 600;
            color: #e74c3c; /* Merah untuk penekanan */
            margin: 20px 0;
            padding: 20px;
            border: 2px dashed #e74c3c;
            border-radius: 8px;
            background-color: #fbeae9;
        }
        .result-details p { margin: 8px 0; color: #555; text-align: left;}
    </style>
</head>
<body>
<div class="container">
    <div class="form-card result-card">
        <h2>Hasil Diagnosis</h2>
        
        <div class="result-details">
            <p><strong>Nama Balita:</strong> <?php echo htmlspecialchars($hasil['nama_balita']); ?></p>
            <p><strong>Nama Orang Tua:</strong> <?php echo htmlspecialchars($hasil['nama_lengkap_user']); ?></p>
            <p><strong>Tanggal Diagnosis:</strong> <?php echo date('d F Y, H:i', strtotime($hasil['tanggal_diagnosis'])); ?></p>
            <p><strong>Usia Saat Diagnosis:</strong> <?php echo $hasil['catatan_usia_saat_diagnosis']; ?> bulan</p>
        </div>

        <p>Berdasarkan jawaban yang diberikan, sistem menyimpulkan bahwa kondisi balita adalah:</p>
        <div class="result-conclusion">
            <?php echo htmlspecialchars($hasil['kesimpulan_akhir']); ?>
        </div>
        <p style="text-align:center; font-size: 14px; color: #7f8c8d;">
            <strong>Disclaimer:</strong> Hasil ini adalah indikasi awal berdasarkan metode sistem pakar. Untuk diagnosis yang akurat dan penanganan lebih lanjut, sangat disarankan untuk berkonsultasi langsung dengan dokter atau ahli gizi.
        </p>

        <a href="dashboard.php" class="btn">Kembali ke Dashboard</a>
    </div>
</div>
</body>
</html>