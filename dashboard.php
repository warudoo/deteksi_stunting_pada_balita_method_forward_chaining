<?php
session_start();
<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}
<<<<<<< Updated upstream

require 'config/database.php';
$id_user = $_SESSION['id_user'];

=======
require 'config/database.php';

$id_user = $_SESSION['id_user'];
>>>>>>> Stashed changes
$query_balita = "SELECT id_balita, nama_balita, tanggal_lahir, jenis_kelamin FROM balita WHERE id_user = $id_user";
$result_balita = mysqli_query($koneksi, $query_balita);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<<<<<<< Updated upstream
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aplikasi Diagnosis Stunting</title>
    <link rel="stylesheet" href="src/output.css"> <!-- File hasil build Tailwind -->
</head>
<body class="bg-gray-100 min-h-screen px-4 py-6">

    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Header -->
        <header class="bg-white shadow-md rounded-lg px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold text-gray-800">
                Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap_user']); ?>!
            </h1>
            <a href="logout.php" class="text-blue-600 hover:underline font-medium">Logout</a>
        </header>

        <!-- Main content -->
        <main>
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Data Balita Anda</h2>

            <?php if (mysqli_num_rows($result_balita) > 0): ?>
                <?php while ($balita = mysqli_fetch_assoc($result_balita)) :
                    $tgl_lahir = new DateTime($balita['tanggal_lahir']);
                    $hari_ini = new DateTime('today');
                    $umur = $hari_ini->diff($tgl_lahir);
                ?>
                <div class="bg-white rounded-lg shadow p-6 mb-4">
                    <h3 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($balita['nama_balita']); ?></h3>
                    <p class="text-gray-600 mt-1"><span class="font-semibold">Tanggal Lahir:</span> <?php echo date('d F Y', strtotime($balita['tanggal_lahir'])); ?></p>
                    <p class="text-gray-600"><span class="font-semibold">Jenis Kelamin:</span> <?php echo $balita['jenis_kelamin']; ?></p>
                    <p class="text-gray-600"><span class="font-semibold">Usia:</span> <?php echo $umur->y . ' tahun, ' . $umur->m . ' bulan, ' . $umur->d . ' hari'; ?></p>
                    <div class="mt-4 space-x-2">
                        <a href="diagnosis.php?id_balita=<?php echo $balita['id_balita']; ?>" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-medium">Mulai Diagnosis</a>
                        <a href="riwayat.php?id_balita=<?php echo $balita['id_balita']; ?>" class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-sm font-medium">Lihat Riwayat</a>
                    </div>
                </div>
                <?php endwhile; ?>

            <?php else: ?>
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-gray-600">Anda belum memiliki data balita. Silakan tambahkan data balita terlebih dahulu.</p>
                    <a href="tambah_balita.php" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-medium">Tambah Data Balita</a>
                </div>
            <?php endif; ?>

        </main>

    </div>

=======
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Pengguna</title>
  <link href="src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen px-4 py-6">
  <div class="max-w-4xl mx-auto">
    <header class="bg-white shadow p-6 rounded-lg flex justify-between items-center mb-6">
      <h1 class="text-xl font-bold text-gray-700">Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap_user']); ?>!</h1>
      <a href="logout.php" class="text-blue-600 hover:underline">Logout</a>
    </header>

    <main class="space-y-6">
      <h2 class="text-lg font-semibold text-gray-700">Data Balita Anda</h2>

      <?php if (mysqli_num_rows($result_balita) > 0): ?>
        <?php while ($balita = mysqli_fetch_assoc($result_balita)):
          $tgl_lahir = new DateTime($balita['tanggal_lahir']);
          $hari_ini = new DateTime('today');
          $umur = $hari_ini->diff($tgl_lahir);
        ?>
          <div class="bg-white shadow p-4 rounded-lg">
            <h3 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($balita['nama_balita']); ?></h3>
            <p class="text-sm text-gray-600"><strong>Tanggal Lahir:</strong> <?php echo date('d F Y', strtotime($balita['tanggal_lahir'])); ?></p>
            <p class="text-sm text-gray-600"><strong>Jenis Kelamin:</strong> <?php echo $balita['jenis_kelamin']; ?></p>
            <p class="text-sm text-gray-600"><strong>Usia:</strong> <?php echo $umur->y . ' tahun, ' . $umur->m . ' bulan, ' . $umur->d . ' hari'; ?></p>
            <div class="mt-4 space-x-2">
              <a href="diagnosis.php?id_balita=<?php echo $balita['id_balita']; ?>" class="inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">Mulai Diagnosis</a>
              <a href="riwayat.php?id_balita=<?php echo $balita['id_balita']; ?>" class="inline-block bg-gray-500 text-white text-sm px-4 py-2 rounded hover:bg-gray-600">Lihat Riwayat</a>
            </div>
          </div>
        <?php endwhile; ?>

      <?php else: ?>
        <div class="bg-white p-4 rounded shadow text-gray-700">
          <p>Anda belum memiliki data balita. Silakan tambahkan data balita terlebih dahulu.</p>
          <a href="tambah_balita.php" class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 text-sm rounded hover:bg-blue-700">Tambah Data Balita</a>
        </div>
      <?php endif; ?>
    </main>
  </div>
>>>>>>> Stashed changes
</body>
</html>
