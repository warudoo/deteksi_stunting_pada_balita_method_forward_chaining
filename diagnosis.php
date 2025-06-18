<?php
session_start();
require 'config/database.php';

// Penjaga Halaman: Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Ambil id_balita dari URL
if (!isset($_GET['id_balita']) || !is_numeric($_GET['id_balita'])) {
    // Jika tidak ada id_balita, kembalikan ke dashboard
    header("Location: dashboard.php");
    exit();
}

$id_balita = $_GET['id_balita'];
$id_user = $_SESSION['id_user'];

// Keamanan: Pastikan balita yang akan didiagnosis adalah milik user yang login
$query_cek = "SELECT * FROM balita WHERE id_balita = $id_balita AND id_user = $id_user";
$result_cek = mysqli_query($koneksi, $query_cek);
if (mysqli_num_rows($result_cek) == 0) {
    // Jika balita bukan milik user, kembalikan ke dashboard
    echo "Akses tidak sah.";
    header("refresh:2;url=dashboard.php");
    exit();
}
$data_balita = mysqli_fetch_assoc($result_cek);


// Ambil semua pertanyaan dari database
$query_pertanyaan = "SELECT id_pertanyaan, kode_gejala, teks_pertanyaan FROM pertanyaan ORDER BY id_pertanyaan";
$result_pertanyaan = mysqli_query($koneksi, $query_pertanyaan);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mulai Diagnosis - Aplikasi Diagnosis Stunting</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .question-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .question-text {
            font-weight: 500;
            margin-bottom: 10px;
        }
        .radio-group label {
            margin-right: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-card">
        <h2>Diagnosis Stunting</h2>
        <p>Untuk Balita: <strong><?php echo htmlspecialchars($data_balita['nama_balita']); ?></strong></p>
        <p>Jawab semua pertanyaan di bawah ini sesuai dengan kondisi balita.</p>

        <form action="proses_diagnosis.php?id_balita=<?php echo $id_balita; ?>" method="POST">
            
            <?php 
            // Loop untuk menampilkan semua pertanyaan
            while ($pertanyaan = mysqli_fetch_assoc($result_pertanyaan)) : 
            ?>
            <div class="question-card">
                <p class="question-text"><?php echo htmlspecialchars($pertanyaan['teks_pertanyaan']); ?></p>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="jawaban[<?php echo $pertanyaan['kode_gejala']; ?>]" value="ya" required> Ya
                    </label>
                    <label>
                        <input type="radio" name="jawaban[<?php echo $pertanyaan['kode_gejala']; ?>]" value="tidak" required> Tidak
                    </label>
                </div>
            </div>
            <?php endwhile; ?>

            <button type="submit" class="btn">Lihat Hasil Diagnosis</button>
        </form>
    </div>
</div>
</body>
</html>