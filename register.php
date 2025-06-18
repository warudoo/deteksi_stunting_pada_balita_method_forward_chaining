<?php
// Memasukkan file koneksi database
require 'config/database.php';

// Variabel untuk menyimpan pesan error atau sukses
$message = '';
$error = '';

// Memeriksa apakah form telah di-submit menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Mengambil data dari form dan melindunginya dari SQL Injection
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $nama_lengkap_user = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap_user']);
    
    $nama_balita = mysqli_real_escape_string($koneksi, $_POST['nama_balita']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    // Validasi sederhana (pastikan kolom yang wajib diisi tidak kosong)
    if (empty($username) || empty($email) || empty($password) || empty($nama_lengkap_user) || empty($nama_balita) || empty($tanggal_lahir)) {
        $error = "Semua kolom yang ditandai * wajib diisi!";
    } else {
        // Enkripsi password sebelum disimpan ke database untuk keamanan
        // Ini adalah langkah yang SANGAT PENTING
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Memulai transaksi database
        // Ini untuk memastikan data user dan balita dimasukkan bersamaan
        mysqli_begin_transaction($koneksi);

        try {
            // Query untuk memasukkan data ke tabel 'users'
            $query_user = "INSERT INTO users (username, email, password, nama_lengkap_user) 
                           VALUES ('$username', '$email', '$hashed_password', '$nama_lengkap_user')";
            
            // Eksekusi query user
            if (mysqli_query($koneksi, $query_user)) {
                // Ambil ID user yang baru saja dibuat
                $id_user_baru = mysqli_insert_id($koneksi);

                // Query untuk memasukkan data ke tabel 'balita'
                $query_balita = "INSERT INTO balita (id_user, nama_balita, tanggal_lahir, jenis_kelamin, alamat)
                                 VALUES ('$id_user_baru', '$nama_balita', '$tanggal_lahir', '$jenis_kelamin', '$alamat')";
                
                // Eksekusi query balita
                if (mysqli_query($koneksi, $query_balita)) {
                    // Jika kedua query berhasil, commit transaksi
                    mysqli_commit($koneksi);
                    $message = "Registrasi berhasil! Anda akan diarahkan ke halaman login.";
                    // Arahkan ke halaman login setelah 3 detik
                    header("refresh:3;url=login.php");
                } else {
                    // Jika query balita gagal, batalkan transaksi (rollback)
                    mysqli_rollback($koneksi);
                    $error = "Terjadi kesalahan saat menyimpan data balita: " . mysqli_error($koneksi);
                }
            } else {
                // Jika query user gagal, batalkan transaksi (rollback)
                mysqli_rollback($koneksi);
                // Cek apakah error karena duplikat username atau email
                if(mysqli_errno($koneksi) == 1062) {
                    $error = "Username atau Email sudah terdaftar. Silakan gunakan yang lain.";
                } else {
                    $error = "Terjadi kesalahan saat mendaftarkan user: " . mysqli_error($koneksi);
                }
            }
        } catch (Exception $e) {
            // Tangkap exception lain dan batalkan transaksi
            mysqli_rollback($koneksi);
            $error = "Terjadi kesalahan pada database: " . $e->getMessage();
        }

        // Menutup koneksi database
        mysqli_close($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Aplikasi Diagnosis Stunting</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <div class="container">
        <form action="register.php" method="POST" class="form-card">
            <h2>Registrasi Akun</h2>
            <p>Daftarkan akun Anda dan data balita Anda.</p>

            <?php if(!empty($message)): ?>
                <div class="alert success"><?php echo $message; ?></div>
            <?php endif; ?>
            <?php if(!empty($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <fieldset>
                <legend>Data Akun Pengguna (Orang Tua)</legend>
                <div class="form-group">
                    <label for="nama_lengkap_user">Nama Lengkap Anda *</label>
                    <input type="text" id="nama_lengkap_user" name="nama_lengkap_user" required>
                </div>
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required>
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Data Balita</legend>
                <div class="form-group">
                    <label for="nama_balita">Nama Balita *</label>
                    <input type="text" id="nama_balita" name="nama_balita" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir *</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" required>
                </div>
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin *</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3"></textarea>
                </div>
            </fieldset>

            <button type="submit" class="btn">Daftar</button>
            <p class="center-text">Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </form>
    </div>

</body>
</html>