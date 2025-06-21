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

$id_balita = $_GET['id_balita'];
$id_user = $_SESSION['id_user'];

$query_cek = "SELECT * FROM balita WHERE id_balita = $id_balita AND id_user = $id_user";
$result_cek = mysqli_query($koneksi, $query_cek);
if (mysqli_num_rows($result_cek) == 0) {
    echo "Akses tidak sah.";
    header("refresh:2;url=dashboard.php");
    exit();
}
$data_balita = mysqli_fetch_assoc($result_cek);

$query_pertanyaan = "SELECT id_pertanyaan, kode_gejala, teks_pertanyaan FROM pertanyaan ORDER BY id_pertanyaan";
$result_pertanyaan = mysqli_query($koneksi, $query_pertanyaan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mulai Diagnosis - Aplikasi Diagnosis Stunting</title>
    <link rel="stylesheet" href="src/output.css">
</head>
<body class="bg-gray-100 min-h-screen py-6 px-4">
    
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6 space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Diagnosis Stunting</h2>
            <p class="text-gray-600">Untuk Balita: <span class="font-semibold"><?php echo htmlspecialchars($data_balita['nama_balita']); ?></span></p>
            <p class="text-sm text-gray-500 mt-1">Jawab semua pertanyaan di bawah ini sesuai dengan kondisi balita.</p>
        </div>

        <form action="proses_diagnosis.php?id_balita=<?php echo $id_balita; ?>" method="POST" class="space-y-4">

            <?php while ($pertanyaan = mysqli_fetch_assoc($result_pertanyaan)) : ?>
                <div class="bg-gray-50 border border-gray-200 rounded-md p-4 shadow-sm">
                    <p class="font-medium text-gray-800 mb-2"><?php echo htmlspecialchars($pertanyaan['teks_pertanyaan']); ?></p>
                    <div class="flex space-x-6">
                        <label class="inline-flex items-center">
                            <input type="radio" name="jawaban[<?php echo $pertanyaan['kode_gejala']; ?>]" value="ya" required class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-gray-700">Ya</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="jawaban[<?php echo $pertanyaan['kode_gejala']; ?>]" value="tidak" required class="text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-gray-700">Tidak</span>
                        </label>
                    </div>
                </div>
            <?php endwhile; ?>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition">
                Lihat Hasil Diagnosis
            </button>
        </form>
    </div>

</body>
</html>
