<?php
// 1. Memanggil penjaga halaman admin dan koneksi database
require 'auth_admin.php'; 
require '../config/database.php';

// 2. Query untuk mengambil semua data laporan yang dibutuhkan
// Menggunakan JOIN untuk menggabungkan data dari 3 tabel:
// hasil_diagnosis, balita, dan users
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Diagnosis - Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .report-card { background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .report-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .report-table th, .report-table td { border: 1px solid #ddd; padding: 12px; text-align: left; font-size: 14px; }
        .report-table th { background-color: #f8f9fa; font-weight: 600; }
        .report-table tbody tr:nth-child(even) { background-color: #f2f2f2; }
        .export-btn {
            display: inline-block;
            background-color: #27ae60;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
            margin-bottom: 20px;
        }
        .export-btn:hover { background-color: #229954; }
    </style>
</head>
<body>
<div class="container">
    <div class="report-card">
        <a href="dashboard_admin.php" style="text-decoration: none; color: #3498db; margin-bottom: 20px; display:inline-block;">&larr; Kembali ke Dashboard Admin</a>
        <h2>Laporan Seluruh Diagnosis</h2>
        <p>Di bawah ini adalah daftar semua riwayat diagnosis yang tercatat dalam sistem.</p>
        
        <a href="export.php" class="export-btn">Ekspor ke CSV (Excel)</a>

        <table class="report-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal Diagnosis</th>
                    <th>Nama Balita</th>
                    <th>Nama Orang Tua</th>
                    <th>Usia Saat Diagnosis</th>
                    <th>Hasil Diagnosis</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result_laporan) > 0): ?>
                    <?php 
                    $nomor = 1;
                    while($row = mysqli_fetch_assoc($result_laporan)): 
                    ?>
                    <tr>
                        <td><?php echo $nomor++; ?></td>
                        <td><?php echo date('d M Y, H:i', strtotime($row['tanggal_diagnosis'])); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_balita']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_lengkap_user']); ?></td>
                        <td><?php echo $row['catatan_usia_saat_diagnosis']; ?> bulan</td>
                        <td><?php echo htmlspecialchars($row['kesimpulan_akhir']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">Belum ada data diagnosis untuk ditampilkan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>