<?php
require 'auth_admin.php';
require '../config/database.php';

// Validasi ID user
if (!isset($_GET['id_user']) || !is_numeric($_GET['id_user'])) {
    header("Location: kelola_pengguna.php");
    exit();
}

$id_user = (int)$_GET['id_user'];
$error = '';
$message = '';

// Ambil data user
$result = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = $id_user");
if (mysqli_num_rows($result) === 0) {
    header("Location: kelola_pengguna.php");
    exit();
}
$user = mysqli_fetch_assoc($result);

// Proses update saat form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap_user']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role = $_POST['role'];

    $update = mysqli_query($koneksi, "UPDATE users SET 
        nama_lengkap_user = '$nama',
        username = '$username',
        email = '$email',
        role = '$role'
        WHERE id_user = $id_user
    ");

    if ($update) {
        $message = "Data pengguna berhasil diperbarui.";
        $user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = $id_user")); // refresh data
    } else {
        $error = "Gagal memperbarui data pengguna: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pengguna</title>
  <link rel="stylesheet" href="../src/output.css">
</head>
<body class="bg-gray-100 py-8 px-4">
  <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Edit Pengguna</h2>
    <a href="kelola_pengguna.php" class="text-blue-600 hover:underline">&larr; Kembali</a>

    <?php if ($message): ?>
      <div class="bg-green-100 text-green-700 px-4 py-2 mt-4 rounded"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 mt-4 rounded"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4 mt-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
        <input type="text" name="nama_lengkap_user" value="<?php echo htmlspecialchars($user['nama_lengkap_user']); ?>" required class="w-full border border-gray-300 p-2 rounded">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="w-full border border-gray-300 p-2 rounded">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="w-full border border-gray-300 p-2 rounded">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Role</label>
        <select name="role" class="w-full border border-gray-300 p-2 rounded" required>
          <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>User</option>
          <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
        </select>
      </div>
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
    </form>
  </div>
</body>
</html>
