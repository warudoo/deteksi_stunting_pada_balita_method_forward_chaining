<?php
require 'auth_admin.php'; 
require '../config/database.php';

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['tambah_pertanyaan'])) {
        $kode_gejala = mysqli_real_escape_string($koneksi, $_POST['kode_gejala']);
        $teks_pertanyaan = mysqli_real_escape_string($koneksi, $_POST['teks_pertanyaan']);

        if (!empty($kode_gejala) && !empty($teks_pertanyaan)) {
            $stmt = $koneksi->prepare("INSERT INTO pertanyaan (kode_gejala, teks_pertanyaan) VALUES (?, ?)");
            $stmt->bind_param("ss", $kode_gejala, $teks_pertanyaan);
            $message = $stmt->execute() ? "Pertanyaan baru berhasil ditambahkan." : "Gagal: Kode Gejala mungkin sudah ada. " . $stmt->error;
            $stmt->close();
        } else {
            $error = "Kode Gejala dan Teks Pertanyaan tidak boleh kosong.";
        }
    }

    if (isset($_POST['tambah_aturan'])) {
        $kode_aturan = mysqli_real_escape_string($koneksi, $_POST['kode_aturan']);
        $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
        $kesimpulan = mysqli_real_escape_string($koneksi, $_POST['kesimpulan']);

        if (!empty($kode_aturan) && !empty($kondisi) && !empty($kesimpulan)) {
            $stmt = $koneksi->prepare("INSERT INTO aturan (kode_aturan, kondisi, kesimpulan) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $kode_aturan, $kondisi, $kesimpulan);
            $message = $stmt->execute() ? "Aturan baru berhasil ditambahkan." : "Gagal: Kode Aturan mungkin sudah ada. " . $stmt->error;
            $stmt->close();
        } else {
            $error = "Semua kolom aturan tidak boleh kosong.";
        }
    }

    if (isset($_POST['hapus_pertanyaan'])) {
        $id_pertanyaan = (int)$_POST['id_pertanyaan'];
        $stmt = $koneksi->prepare("DELETE FROM pertanyaan WHERE id_pertanyaan = ?");
        $stmt->bind_param("i", $id_pertanyaan);
        $message = $stmt->execute() ? "Pertanyaan berhasil dihapus." : "Gagal menghapus pertanyaan: " . $stmt->error;
        $stmt->close();
    }

    if (isset($_POST['hapus_aturan'])) {
        $id_aturan = (int)$_POST['id_aturan'];
        $stmt = $koneksi->prepare("DELETE FROM aturan WHERE id_aturan = ?");
        $stmt->bind_param("i", $id_aturan);
        $message = $stmt->execute() ? "Aturan berhasil dihapus." : "Gagal menghapus aturan: " . $stmt->error;
        $stmt->close();
    }
}

$result_pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan ORDER BY kode_gejala ASC");
$result_aturan = mysqli_query($koneksi, "SELECT * FROM aturan ORDER BY id_aturan ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Aturan & Pertanyaan - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen px-4 py-8">

  <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow space-y-8">
    <a href="dashboard_admin.php" class="text-blue-600 hover:underline text-sm">&larr; Kembali ke Dashboard Admin</a>
    <h1 class="text-2xl font-bold text-gray-800">Kelola Aturan & Pertanyaan</h1>
    <p class="text-sm text-gray-600 mb-4">Ubah basis pengetahuan yang digunakan sistem untuk diagnosis.</p>

    <?php if($message): ?>
      <div class="bg-green-100 text-green-800 text-sm px-4 py-2 rounded"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if($error): ?>
      <div class="bg-red-100 text-red-800 text-sm px-4 py-2 rounded"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Pertanyaan -->
    <section class="space-y-6">
      <h2 class="text-xl font-semibold text-gray-700">Manajemen Pertanyaan (Gejala)</h2>
      <form method="POST" class="space-y-4 border border-gray-300 p-4 rounded">
        <input type="hidden" name="tambah_pertanyaan" value="1">
        <div>
          <label class="block text-sm text-gray-600">Kode Gejala</label>
          <input type="text" name="kode_gejala" required class="w-full border px-3 py-2 rounded mt-1">
        </div>
        <div>
          <label class="block text-sm text-gray-600">Teks Pertanyaan</label>
          <textarea name="teks_pertanyaan" rows="2" required class="w-full border px-3 py-2 rounded mt-1"></textarea>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" type="submit">Tambah Pertanyaan</button>
      </form>

      <table class="w-full border text-sm mt-4">
        <thead class="bg-gray-100 text-left">
          <tr>
            <th class="px-4 py-2">Kode</th>
            <th class="px-4 py-2">Teks Pertanyaan</th>
            <th class="px-4 py-2">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <?php while($p = mysqli_fetch_assoc($result_pertanyaan)): ?>
          <tr>
            <td class="px-4 py-2"><?php echo htmlspecialchars($p['kode_gejala']); ?></td>
            <td class="px-4 py-2"><?php echo htmlspecialchars($p['teks_pertanyaan']); ?></td>
            <td class="px-4 py-2">
              <form method="POST" onsubmit="return confirm('Yakin ingin menghapus pertanyaan ini?')">
                <input type="hidden" name="id_pertanyaan" value="<?php echo $p['id_pertanyaan']; ?>">
                <button type="submit" name="hapus_pertanyaan" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Hapus</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </section>

    <!-- Aturan -->
    <section class="space-y-6">
      <h2 class="text-xl font-semibold text-gray-700">Manajemen Aturan (Rules)</h2>
      <form method="POST" class="space-y-4 border border-gray-300 p-4 rounded">
        <input type="hidden" name="tambah_aturan" value="1">
        <div>
          <label class="block text-sm text-gray-600">Kode Aturan</label>
          <input type="text" name="kode_aturan" required class="w-full border px-3 py-2 rounded mt-1">
        </div>
        <div>
          <label class="block text-sm text-gray-600">Kondisi (Pisahkan dengan AND)</label>
          <input type="text" name="kondisi" placeholder="Contoh: G01 AND G03" required class="w-full border px-3 py-2 rounded mt-1">
        </div>
        <div>
          <label class="block text-sm text-gray-600">Kesimpulan</label>
          <input type="text" name="kesimpulan" placeholder="Contoh: G07 atau K01" required class="w-full border px-3 py-2 rounded mt-1">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" type="submit">Tambah Aturan</button>
      </form>

      <table class="w-full border text-sm mt-4">
        <thead class="bg-gray-100 text-left">
          <tr>
            <th class="px-4 py-2">Kode</th>
            <th class="px-4 py-2">Kondisi</th>
            <th class="px-4 py-2">Kesimpulan</th>
            <th class="px-4 py-2">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <?php while($a = mysqli_fetch_assoc($result_aturan)): ?>
          <tr>
            <td class="px-4 py-2"><?php echo htmlspecialchars($a['kode_aturan']); ?></td>
            <td class="px-4 py-2"><?php echo htmlspecialchars($a['kondisi']); ?></td>
            <td class="px-4 py-2"><?php echo htmlspecialchars($a['kesimpulan']); ?></td>
            <td class="px-4 py-2">
              <form method="POST" onsubmit="return confirm('Yakin ingin menghapus aturan ini?')">
                <input type="hidden" name="id_aturan" value="<?php echo $a['id_aturan']; ?>">
                <button type="submit" name="hapus_aturan" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Hapus</button>
              </form>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </section>
  </div>

</body>
</html>
