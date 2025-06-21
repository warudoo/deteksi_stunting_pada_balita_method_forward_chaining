<?php
require 'config/database.php';

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $nama_lengkap_user = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap_user']);

    $nama_balita = mysqli_real_escape_string($koneksi, $_POST['nama_balita']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    if (empty($username) || empty($email) || empty($password) || empty($nama_lengkap_user) || empty($nama_balita) || empty($tanggal_lahir)) {
        $error = "Semua kolom yang ditandai * wajib diisi!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        mysqli_begin_transaction($koneksi);
        try {
            $query_user = "INSERT INTO users (username, email, password, nama_lengkap_user) 
                           VALUES ('$username', '$email', '$hashed_password', '$nama_lengkap_user')";
            if (mysqli_query($koneksi, $query_user)) {
                $id_user_baru = mysqli_insert_id($koneksi);
                $query_balita = "INSERT INTO balita (id_user, nama_balita, tanggal_lahir, jenis_kelamin, alamat)
                                 VALUES ('$id_user_baru', '$nama_balita', '$tanggal_lahir', '$jenis_kelamin', '$alamat')";
                if (mysqli_query($koneksi, $query_balita)) {
                    mysqli_commit($koneksi);
                    $message = "Registrasi berhasil! Anda akan diarahkan ke halaman login.";
                    header("refresh:3;url=login.php");
                } else {
                    mysqli_rollback($koneksi);
                    $error = "Terjadi kesalahan saat menyimpan data balita: " . mysqli_error($koneksi);
                }
            } else {
                mysqli_rollback($koneksi);
                if (mysqli_errno($koneksi) == 1062) {
                    $error = "Username atau Email sudah terdaftar. Silakan gunakan yang lain.";
                } else {
                    $error = "Terjadi kesalahan saat mendaftarkan user: " . mysqli_error($koneksi);
                }
            }
        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            $error = "Terjadi kesalahan pada database: " . $e->getMessage();
        }
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
  <link rel="stylesheet" href="src/output.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

  <div class="w-full max-w-2xl bg-white rounded-lg shadow p-6 space-y-6 mt-5 mb-5">
    <h2 class="text-2xl font-bold text-gray-800 text-center">Registrasi Akun</h2>
    <p class="text-center text-sm text-gray-500">Daftarkan akun Anda dan data balita Anda.</p>

    <?php if(!empty($message)): ?>
      <div class="bg-green-100 border border-green-300 text-green-800 text-sm px-4 py-3 rounded">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>
    <?php if(!empty($error)): ?>
      <div class="bg-red-100 border border-red-300 text-red-800 text-sm px-4 py-3 rounded">
        <?php echo $error; ?>
      </div>
    <?php endif; ?>

    <form action="register.php" method="POST" class="space-y-6">
      <fieldset class="border border-gray-300 rounded p-4 space-y-4">
        <legend class="text-sm font-semibold text-gray-700 px-2">Data Akun Pengguna (Orang Tua)</legend>
        
        <div>
          <label for="nama_lengkap_user" class="block text-sm text-gray-700">Nama Lengkap Anda *</label>
          <input type="text" id="nama_lengkap_user" name="nama_lengkap_user" required class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <div>
          <label for="username" class="block text-sm text-gray-700">Username *</label>
          <input type="text" id="username" name="username" required class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <div>
          <label for="email" class="block text-sm text-gray-700">Email *</label>
          <input type="email" id="email" name="email" required class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <div>
          <label for="password" class="block text-sm text-gray-700">Password *</label>
          <input type="password" id="password" name="password" required class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-300">
        </div>
      </fieldset>

      <fieldset class="border border-gray-300 rounded p-4 space-y-4">
        <legend class="text-sm font-semibold text-gray-700 px-2">Data Balita</legend>
        
        <div>
          <label for="nama_balita" class="block text-sm text-gray-700">Nama Balita *</label>
          <input type="text" id="nama_balita" name="nama_balita" required class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <div>
          <label for="tanggal_lahir" class="block text-sm text-gray-700">Tanggal Lahir *</label>
          <input type="date" id="tanggal_lahir" name="tanggal_lahir" required class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-300">
        </div>

        <div>
          <label for="jenis_kelamin" class="block text-sm text-gray-700">Jenis Kelamin *</label>
          <select id="jenis_kelamin" name="jenis_kelamin" required class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-300">
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
          </select>
        </div>

        <div>
          <label for="alamat" class="block text-sm text-gray-700">Alamat</label>
          <textarea id="alamat" name="alamat" rows="3" class="w-full border rounded px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-blue-300"></textarea>
        </div>
      </fieldset>

      <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition">
        Daftar
      </button>

      <p class="text-center text-sm text-gray-600">
        Sudah punya akun?
        <a href="login.php" class="text-blue-600 hover:underline">Login di sini</a>
      </p>
    </form>
  </div>
</body>
</html>
