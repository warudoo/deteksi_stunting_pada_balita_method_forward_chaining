<?php
// 1. Memanggil penjaga halaman admin dan koneksi database
require 'auth_admin.php'; 
require '../config/database.php';

// 2. MENGAMBIL DATA UNTUK KARTU RINGKASAN (SUMMARY CARDS)
// Mengambil total pengguna dengan role 'user'
$total_users_query = mysqli_query($koneksi, "SELECT COUNT(id_user) as total FROM users WHERE role='user'");
$total_users = mysqli_fetch_assoc($total_users_query)['total'];

// Mengambil total balita yang terdaftar
$total_balita_query = mysqli_query($koneksi, "SELECT COUNT(id_balita) as total FROM balita");
$total_balita = mysqli_fetch_assoc($total_balita_query)['total'];

// Mengambil total diagnosis yang pernah dilakukan
$total_diagnosis_query = mysqli_query($koneksi, "SELECT COUNT(id_hasil) as total FROM hasil_diagnosis");
$total_diagnosis = mysqli_fetch_assoc($total_diagnosis_query)['total'];

// 3. MENGAMBIL DATA UNTUK AKTIVITAS TERBARU
// Mengambil 5 diagnosis terakhir dengan data user dan balitanya
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sistem Diagnosis Stunting</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body { background-color: #f4f7f6; }
        .container { max-width: 1200px; }
        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .summary-grid, .nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .summary-card, .nav-card {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .summary-card:hover, .nav-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .summary-card .count {
            font-size: 48px;
            font-weight: 600;
            margin: 10px 0;
        }
        .summary-card .title { font-size: 16px; color: #555; }
        .summary-card.user { border-left: 5px solid #3498db; }
        .summary-card.balita { border-left: 5px solid #2ecc71; }
        .summary-card.diagnosis { border-left: 5px solid #f1c40f; }
        .nav-card { text-decoration: none; color: #333; }
        .nav-card .icon { font-size: 40px; margin-bottom: 10px; }
        .nav-card .title { font-size: 18px; font-weight: 500; }
        .content-section {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-top: 30px;
        }
        .activity-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .activity-table th, .activity-table td { border-bottom: 1px solid #eee; padding: 12px 8px; text-align: left; }
        .activity-table th { font-weight: 600; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        
        <header class="main-header">
            <h1>Admin Dashboard</h1>
            <div>
                <span>Halo, <strong><?php echo htmlspecialchars($_SESSION['username']); ?>!</strong></span>
                <a href="../logout.php" style="margin-left: 15px; text-decoration: none;">Logout &rarr;</a>
            </div>
        </header>

        <section class="summary-grid">
            <div class="summary-card user">
                <div class="count"><?php echo $total_users; ?></div>
                <div class="title">Total Pengguna</div>
            </div>
            <div class="summary-card balita">
                <div class="count"><?php echo $total_balita; ?></div>
                <div class="title">Total Balita Terdaftar</div>
            </div>
            <div class="summary-card diagnosis">
                <div class="count"><?php echo $total_diagnosis; ?></div>
                <div class="title">Total Diagnosis Dilakukan</div>
            </div>
        </section>

        <section class="content-section">
            <h2>Menu Manajemen</h2>
            <div class="nav-grid">
                <a href="kelola_pengguna.php" class="nav-card">
                    <div class="icon">👤</div>
                    <div class="title">Kelola Pengguna</div>
                    <p>Tambah, edit, atau hapus data admin dan user.</p>
                </a>
                <a href="kelola_aturan.php" class="nav-card">
                    <div class="icon">🧠</div>
                    <div class="title">Kelola Aturan & Pertanyaan</div>
                    <p>Ubah basis pengetahuan untuk sistem pakar.</p>
                </a>
                <a href="laporan_diagnosis.php" class="nav-card">
                    <div class="icon">📋</div>
                    <div class="title">Laporan Diagnosis</div>
                    <p>Lihat semua riwayat diagnosis dan ekspor data.</p>
                </a>
            </div>
        </section>

        <section class="content-section">
            <h2>Aktivitas Diagnosis Terbaru</h2>
            <table class="activity-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Balita</th>
                        <th>Orang Tua</th>
                        <th>Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($recent_diagnosis_query) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($recent_diagnosis_query)): ?>
                            <tr>
                                <td><?php echo date('d M Y, H:i', strtotime($row['tanggal_diagnosis'])); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_balita']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama_lengkap_user']); ?></td>
                                <td><?php echo htmlspecialchars($row['kesimpulan_akhir']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Belum ada aktivitas diagnosis.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

    </div>
</body>
</html>