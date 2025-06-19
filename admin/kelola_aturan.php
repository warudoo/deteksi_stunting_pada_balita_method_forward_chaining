<?php
// 1. Panggil penjaga halaman admin dan koneksi database
require 'auth_admin.php'; 
require '../config/database.php';

$message = '';
$error = '';

// 2. LOGIKA PEMROSESAN FORM (POST REQUEST)
// Cek apakah ada form yang di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // A. JIKA TOMBOL "Tambah Pertanyaan" DITEKAN
    if (isset($_POST['tambah_pertanyaan'])) {
        $kode_gejala = mysqli_real_escape_string($koneksi, $_POST['kode_gejala']);
        $teks_pertanyaan = mysqli_real_escape_string($koneksi, $_POST['teks_pertanyaan']);

        if (!empty($kode_gejala) && !empty($teks_pertanyaan)) {
            $stmt = $koneksi->prepare("INSERT INTO pertanyaan (kode_gejala, teks_pertanyaan) VALUES (?, ?)");
            $stmt->bind_param("ss", $kode_gejala, $teks_pertanyaan);
            if ($stmt->execute()) {
                $message = "Pertanyaan baru berhasil ditambahkan.";
            } else {
                $error = "Gagal: Kode Gejala mungkin sudah ada. " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Kode Gejala dan Teks Pertanyaan tidak boleh kosong.";
        }
    }

    // B. JIKA TOMBOL "Tambah Aturan" DITEKAN
    if (isset($_POST['tambah_aturan'])) {
        $kode_aturan = mysqli_real_escape_string($koneksi, $_POST['kode_aturan']);
        $kondisi = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
        $kesimpulan = mysqli_real_escape_string($koneksi, $_POST['kesimpulan']);

        if (!empty($kode_aturan) && !empty($kondisi) && !empty($kesimpulan)) {
            $stmt = $koneksi->prepare("INSERT INTO aturan (kode_aturan, kondisi, kesimpulan) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $kode_aturan, $kondisi, $kesimpulan);
            if ($stmt->execute()) {
                $message = "Aturan baru berhasil ditambahkan.";
            } else {
                $error = "Gagal: Kode Aturan mungkin sudah ada. " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Semua kolom aturan tidak boleh kosong.";
        }
    }

    // C. JIKA TOMBOL "Hapus Pertanyaan" DITEKAN
    if (isset($_POST['hapus_pertanyaan'])) {
        $id_pertanyaan = (int)$_POST['id_pertanyaan'];
        $stmt = $koneksi->prepare("DELETE FROM pertanyaan WHERE id_pertanyaan = ?");
        $stmt->bind_param("i", $id_pertanyaan);
        if ($stmt->execute()) {
            $message = "Pertanyaan berhasil dihapus.";
        } else {
            $error = "Gagal menghapus pertanyaan: " . $stmt->error;
        }
        $stmt->close();
    }

    // D. JIKA TOMBOL "Hapus Aturan" DITEKAN
    if (isset($_POST['hapus_aturan'])) {
        $id_aturan = (int)$_POST['id_aturan'];
        $stmt = $koneksi->prepare("DELETE FROM aturan WHERE id_aturan = ?");
        $stmt->bind_param("i", $id_aturan);
        if ($stmt->execute()) {
            $message = "Aturan berhasil dihapus.";
        } else {
            $error = "Gagal menghapus aturan: " . $stmt->error;
        }
        $stmt->close();
    }
}


// 3. AMBIL SEMUA DATA DARI DATABASE UNTUK DITAMPILKAN
$result_pertanyaan = mysqli_query($koneksi, "SELECT * FROM pertanyaan ORDER BY kode_gejala ASC");
$result_aturan = mysqli_query($koneksi, "SELECT * FROM aturan ORDER BY kode_aturan ASC");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Aturan & Pertanyaan - Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .management-card { background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .management-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .management-table th, .management-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .management-table th { background-color: #f8f9fa; font-weight: 600; }
        .management-table tbody tr:nth-child(even) { background-color: #f2f2f2; }
        .delete-btn { background: #e74c3c; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; }
        .delete-btn:hover { background: #c0392b; }
    </style>
</head>
<body>
<div class="container">
    <a href="dashboard_admin.php" style="text-decoration: none; color: #3498db; margin-bottom: 20px; display:inline-block;">&larr; Kembali ke Dashboard Admin</a>
    <h1>Kelola Aturan & Pertanyaan</h1>
    <p>Di halaman ini, Anda bisa mengubah basis pengetahuan yang digunakan sistem untuk melakukan diagnosis.</p>

    <?php if(!empty($message)): ?><div class="alert success"><?php echo $message; ?></div><?php endif; ?>
    <?php if(!empty($error)): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>

    <div class="management-card">
        <h2>Manajemen Pertanyaan (Gejala)</h2>
        <fieldset>
            <legend>Tambah Pertanyaan Baru</legend>
            <form action="kelola_aturan.php" method="POST">
                <div class="form-group">
                    <label>Kode Gejala (Contoh: G01, G06, dst.)</label>
                    <input type="text" name="kode_gejala" required>
                </div>
                <div class="form-group">
                    <label>Teks Pertanyaan</label>
                    <textarea name="teks_pertanyaan" rows="2" required></textarea>
                </div>
                <button type="submit" name="tambah_pertanyaan" class="btn">Tambah Pertanyaan</button>
            </form>
        </fieldset>

        <h3 style="margin-top: 30px;">Daftar Pertanyaan Saat Ini</h3>
        <table class="management-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Teks Pertanyaan</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($p = mysqli_fetch_assoc($result_pertanyaan)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['kode_gejala']); ?></td>
                    <td><?php echo htmlspecialchars($p['teks_pertanyaan']); ?></td>
                    <td>
                        <form action="kelola_aturan.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?');">
                            <input type="hidden" name="id_pertanyaan" value="<?php echo $p['id_pertanyaan']; ?>">
                            <button type="submit" name="hapus_pertanyaan" class="delete-btn">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="management-card">
        <h2>Manajemen Aturan (Rules)</h2>
        <fieldset>
            <legend>Tambah Aturan Baru</legend>
            <form action="kelola_aturan.php" method="POST">
                <div class="form-group">
                    <label>Kode Aturan (Contoh: R01, R04, dst.)</label>
                    <input type="text" name="kode_aturan" required>
                </div>
                <div class="form-group">
                    <label>Kondisi (IF): Pisahkan kode gejala dengan " AND "</label>
                    <input type="text" name="kondisi" placeholder="Contoh: G01 AND G03 AND G05" required>
                </div>
                <div class="form-group">
                    <label>Kesimpulan (THEN): Masukkan kode gejala atau kode hasil</label>
                    <input type="text" name="kesimpulan" placeholder="Contoh: K01 atau G07" required>
                </div>
                <button type="submit" name="tambah_aturan" class="btn">Tambah Aturan</button>
            </form>
        </fieldset>

        <h3 style="margin-top: 30px;">Daftar Aturan Saat Ini</h3>
        <table class="management-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>IF (Kondisi)</th>
                    <th>THEN (Kesimpulan)</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($a = mysqli_fetch_assoc($result_aturan)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($a['kode_aturan']); ?></td>
                    <td><?php echo htmlspecialchars($a['kondisi']); ?></td>
                    <td><?php echo htmlspecialchars($a['kesimpulan']); ?></td>
                    <td>
                        <form action="kelola_aturan.php" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aturan ini?');">
                            <input type="hidden" name="id_aturan" value="<?php echo $a['id_aturan']; ?>">
                            <button type="submit" name="hapus_aturan" class="delete-btn">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>