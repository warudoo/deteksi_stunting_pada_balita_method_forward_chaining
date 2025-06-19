<?php
// Mulai session dan panggil file koneksi & penjaga
session_start();
require 'config/database.php';

// 1. PENJAGA HALAMAN & VALIDASI INPUT
// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Pastikan ada id_balita di URL dan merupakan angka
if (!isset($_GET['id_balita']) || !is_numeric($_GET['id_balita'])) {
    header("Location: dashboard.php");
    exit();
}

$id_balita = (int)$_GET['id_balita'];
$id_user = $_SESSION['id_user'];

// 2. KEAMANAN: PASTIKAN BALITA INI MILIK USER YANG LOGIN
// Ini sangat penting agar user tidak bisa melihat riwayat anak orang lain dengan menebak ID
$query_cek = "SELECT nama_balita FROM balita WHERE id_balita = $id_balita AND id_user = $id_user";
$result_cek = mysqli_query($koneksi, $query_cek);
if (mysqli_num_rows($result_cek) == 0) {
    // Jika balita bukan milik user, tendang ke dashboard
    echo "Akses tidak sah.";
    header("refresh:2;url=dashboard.php");
    exit();
}
// Ambil nama balita untuk ditampilkan di judul
$data_balita = mysqli_fetch_assoc($result_cek);
$nama_balita = $data_balita['nama_balita'];


// 3. AMBIL DATA RIWAYAT DIAGNOSIS DARI DATABASE
// Urutkan berdasarkan tanggal diagnosis, dari yang paling baru (DESC)
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Diagnosis - <?php echo htmlspecialchars($nama_balita); ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .history-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .history-table th, .history-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .history-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .history-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn-detail {
            display: inline-block;
            padding: 5px 10px;
            font-size: 14px;
            text-decoration: none;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .btn-detail:hover {
            background-color: #2980b9;
        }
        .no-history {
            text-align: center;
            padding: 20px;
            background-color: #fcf8e3;
            border: 1px solid #faebcc;
            color: #8a6d3b;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="history-card">
            <h2>Riwayat Diagnosis</h2>
            <h3>Untuk Balita: <?php echo htmlspecialchars($nama_balita); ?></h3>
            <a href="dashboard.php" class="btn" style="display: inline-block; width: auto; margin-top: 10px; margin-bottom: 20px; padding: 10px 20px;">&larr; Kembali ke Dashboard</a>

            <?php 
            // Cek apakah ada data riwayat yang ditemukan
            if (mysqli_num_rows($result_riwayat) > 0) : 
            ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Diagnosis</th>
                            <th>Usia Saat Diagnosis</th>
                            <th>Hasil Diagnosis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $nomor = 1; // Variabel untuk penomoran
                        // Loop untuk menampilkan setiap baris data riwayat
                        while ($riwayat = mysqli_fetch_assoc($result_riwayat)): 
                        ?>
                        <tr>
                            <td><?php echo $nomor++; ?></td>
                            <td><?php echo date('d F Y, H:i', strtotime($riwayat['tanggal_diagnosis'])); ?></td>
                            <td><?php echo $riwayat['catatan_usia_saat_diagnosis']; ?> bulan</td>
                            <td><?php echo htmlspecialchars($riwayat['kesimpulan_akhir']); ?></td>
                            <td>
                                <a href="detail_diagnosis.php?id_hasil=<?php echo $riwayat['id_hasil']; ?>" class="btn-detail">Lihat Detail</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-history">
                    <p>Belum ada riwayat diagnosis untuk balita ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>