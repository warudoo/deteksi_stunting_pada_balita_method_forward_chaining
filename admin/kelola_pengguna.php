<?php
require 'auth_admin.php';
require '../config/database.php';

$message = '';
$error = '';

// Tambah pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_user'])) {
  $username = mysqli_real_escape_string($koneksi, $_POST['username']);
  $email = mysqli_real_escape_string($koneksi, $_POST['email']);
  $password = $_POST['password'];
  $nama_lengkap_user = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap_user']);
  $role = $_POST['role'];

  if (empty($username) || empty($email) || empty($password) || empty($nama_lengkap_user)) {
    $error = "Semua kolom wajib diisi!";
  } else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $koneksi->prepare("INSERT INTO users (username, email, password, nama_lengkap_user, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $nama_lengkap_user, $role);

    if ($stmt->execute()) {
      $message = "Pengguna baru berhasil ditambahkan!";
    } else {
      $error = ($koneksi->errno == 1062)
        ? "Gagal menambahkan: Username atau Email sudah digunakan."
        : "Gagal menambahkan pengguna: " . $stmt->error;
    }
    $stmt->close();
  }
}

$query_users = "SELECT id_user, username, email, nama_lengkap_user, role FROM users ORDER BY role, username";
$result_users = mysqli_query($koneksi, $query_users);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelola Pengguna - Admin Dashboard</title>
  <link rel="stylesheet" href="../src/output.css">
</head>

<body class="bg-gray-100 py-8 px-4">

  <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <a href="dashboard_admin.php" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Kembali ke Dashboard
      Admin</a>
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Kelola Pengguna</h2>

    <!-- Alert -->
    <?php if (!empty($message)): ?>
      <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4 text-sm"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Form Tambah User -->
    <fieldset class="border border-gray-300 p-4 rounded mb-6">
      <legend class="font-medium text-gray-700 px-2">Tambah Pengguna Baru</legend>
      <form method="POST" class="space-y-4 mt-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
          <input type="text" name="nama_lengkap_user" required class="w-full border border-gray-300 p-2 rounded mt-1">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Username</label>
          <input type="text" name="username" required class="w-full border border-gray-300 p-2 rounded mt-1">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" required class="w-full border border-gray-300 p-2 rounded mt-1">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" name="password" required class="w-full border border-gray-300 p-2 rounded mt-1">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Role</label>
          <select name="role" class="w-full border border-gray-300 p-2 rounded mt-1" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <button type="submit" name="tambah_user" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Tambah Pengguna
        </button>
      </form>
    </fieldset>

    <!-- Tabel Daftar Pengguna -->
    <h3 class="text-lg font-semibold text-gray-700 mb-3">Daftar Pengguna Terdaftar</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm border border-gray-200 divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-center font-medium text-gray-600">No.</th>
            <th class="px-4 py-2 text-center font-medium text-gray-600">Username</th>
            <th class="px-4 py-2 text-center font-medium text-gray-600">Nama Lengkap</th>
            <th class="px-4 py-2 text-center font-medium text-gray-600">Email</th>
            <th class="px-4 py-2 text-center font-medium text-gray-600">Role</th>
            <th class="px-4 py-2 text-center font-medium text-gray-600">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
          <?php $nomor = 1;
          while ($user = mysqli_fetch_assoc($result_users)): ?>
            <tr class="bg-white hover:bg-gray-50">
              <td class="px-4 py-2 text-center"><?php echo $nomor++; ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($user['username']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($user['nama_lengkap_user']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($user['email']); ?></td>
              <td class="px-4 py-2 text-center align-middle">
                <span class="block w-full px-2 py-1 text-center align-middle text-sm rounded 
    <?php echo $user['role'] == 'admin'
      ? 'bg-red-500 text-white'
      : 'bg-blue-500 text-white'; ?>">
                  <?php echo ucfirst($user['role']); ?>
                </span>
              </td>
              <td class="px-4 py-2 text-blue-600 space-x-2">
                <a href="edit_pengguna.php?id_user=<?= $user['id_user']; ?>" class="text-blue-600 hover:underline">Edit</a>
                <a href="delete_pengguna.php?id_user=<?= $user['id_user']; ?>" class="text-red-600 hover:underline">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>