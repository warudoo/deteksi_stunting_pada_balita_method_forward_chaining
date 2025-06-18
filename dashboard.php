<?php
// Mulai session
session_start();

// --- PENJAGA HALAMAN ---
// Cek apakah user sudah login atau belum.
// Jika tidak ada session id_user, maka user belum login
if (!isset($_SESSION['id_user'])) {
    // Arahkan ke halaman login
    header("Location: login.php");
    exit(); // Hentikan script
}

// Masukkan file koneksi database
require 'config/database.php';

// Ambil ID user dari session
$id_user = $_SESSION['id_user'];

// Query untuk mengambil semua data balita milik user yang sedang login
$query_balita = "SELECT id_balita, nama_balita, tanggal_lahir, jenis_kelamin FROM balita WHERE id_user = $id_user";
$result_balita = mysqli_query($koneksi, $query_balita);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aplikasi Diagnosis Stunting</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .header {
            background-color: #fff;
            padding: 15px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .header a {
            text-decoration: none;
            color: #3498db;
            font-weight: 500;
        }
        .balita-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .balita-card h3 {
            margin-top: 0;
            color: #34495e;
        }
        .balita-card p {
            margin: 5px 0;
            color: #555;
            text-align: left;
        }
        .btn-group {
            margin-top: 15px;
        }
        .btn-small {
            padding: 8px 15px;
            font-size: 14px;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <header class="header">
            <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap_user']); ?>!</h1>
            <a href="logout.php">Logout</a>
        </header>

        <main>
            <h2>Data Balita Anda</h2>

            <?php 
            // Cek apakah user memiliki data balita
            if (mysqli_num_rows($result_balita) > 0) :
                // Jika ada, loop dan tampilkan setiap data balita
                while ($balita = mysqli_fetch_assoc($result_balita)) :
                    // Menghitung umur balita dari tanggal lahir
                    $tgl_lahir = new DateTime($balita['tanggal_lahir']);
                    $hari_ini = new DateTime('today');
                    $umur = $hari_ini->diff($tgl_lahir);
            ?>

                <div class="balita-card">
                    <h3><?php echo htmlspecialchars($balita['nama_balita']); ?></h3>
                    <p><strong>Tanggal Lahir:</strong> <?php echo date('d F Y', strtotime($balita['tanggal_lahir'])); ?></p>
                    <p><strong>Jenis Kelamin:</strong> <?php echo $balita['jenis_kelamin']; ?></p>
                    <p><strong>Usia:</strong> <?php echo $umur->y . ' tahun, ' . $umur->m . ' bulan, ' . $umur->d . ' hari'; ?></p>
                    <div class="btn-group">
                        <a href="diagnosis.php?id_balita=<?php echo $balita['id_balita']; ?>" class="btn btn-small">Mulai Diagnosis</a>
                        <a href="riwayat.php?id_balita=<?php echo $balita['id_balita']; ?>" class="btn btn-small" style="background-color: #95a5a6;">Lihat Riwayat</a>
                    </div>
                </div>

            <?php 
                endwhile;
            else: 
                // Jika tidak ada data balita
            ?>
                <div class="balita-card">
                    <p>Anda belum memiliki data balita. Silakan tambahkan data balita terlebih dahulu.</p>
                    <a href="tambah_balita.php" class="btn btn-small">Tambah Data Balita</a>
                </div>
            <?php endif; ?>

        </main>

    </div>
    
</body>
</html>