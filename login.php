<?php
// Memulai session di PHP. Ini harus ada di paling atas halaman.
session_start();

// Jika user sudah login, arahkan langsung ke dashboard
if (isset($_SESSION['id_user'])) {
    header("Location: dashboard.php");
    exit(); // Pastikan script berhenti setelah redirect
}

// Memasukkan file koneksi database
require 'config/database.php';

// Variabel untuk menyimpan pesan error
$error = '';

// Memeriksa apakah form telah di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan bersihkan input dari user
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Username dan Password wajib diisi!";
    } else {
        // Query untuk mencari user berdasarkan username
        $query = "SELECT id_user, username, password, nama_lengkap_user, role FROM users WHERE username = '$username'";
        $result = mysqli_query($koneksi, $query);

        // Periksa apakah user ditemukan
        if (mysqli_num_rows($result) == 1) {
            // Ambil data user dari hasil query
            $user = mysqli_fetch_assoc($result);

            // Verifikasi password yang diinput dengan hash password di database
            // Ini adalah cara yang aman untuk memeriksa password
            if (password_verify($password, $user['password'])) {
                // Jika password cocok, simpan informasi user ke dalam session
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap_user'] = $user['nama_lengkap_user'];
                $_SESSION['role'] = $user['role'];

                // Arahkan user ke halaman dashboard
                header("Location: dashboard.php");
                exit(); // Hentikan script
            } else {
                // Jika password salah
                $error = "Password yang Anda masukkan salah.";
            }
        } else {
            // Jika username tidak ditemukan
            $error = "Username tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Diagnosis Stunting</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <div class="container">
        <form action="login.php" method="POST" class="form-card">
            <h2>Login Akun</h2>
            <p>Silakan masuk untuk melanjutkan.</p>

            <?php if(!empty($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login</button>
            <p class="center-text">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </form>
    </div>

</body>
</html>