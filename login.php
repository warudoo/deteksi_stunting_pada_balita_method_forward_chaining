<?php
session_start();

if (isset($_SESSION['id_user'])) {
    header("Location: dashboard.php");
    exit();
}

require 'config/database.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan Password wajib diisi!";
    } else {
        $query = "SELECT id_user, username, password, nama_lengkap_user, role FROM users WHERE username = '$username'";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap_user'] = $user['nama_lengkap_user'];
                $_SESSION['role'] = $user['role'];
                if ($_SESSION['role'] === 'admin') {
                    header("Location: admin/dashboard_admin.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $error = "Password yang Anda masukkan salah.";
            }
        } else {
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
    <link rel="stylesheet" href="src/output.css"> <!-- pastikan ini hasil build Tailwind -->
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 space-y-6">
        <h2 class="text-2xl font-bold text-center text-gray-800">Login Akun</h2>
        <p class="text-center text-gray-500 text-sm">Silakan masuk untuk melanjutkan.</p>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 border border-red-300 rounded p-3 text-sm">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                Login
            </button>
        </form>

        <p class="text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="register.php" class="text-blue-600 hover:underline">Daftar di sini</a>
        </p>
    </div>
  </div>
</body>
</html>
