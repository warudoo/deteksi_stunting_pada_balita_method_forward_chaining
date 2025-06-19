<?php
// 1. Panggil penjaga halaman admin dan koneksi database
require 'auth_admin.php'; 
require '../config/database.php';

$message = '';
$error = '';

// 2. LOGIKA UNTUK MENAMBAHKAN PENGGUNA BARU
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_user'])) {
    // Ambil data dari form
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $nama_lengkap_user = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap_user']);
    $role = $_POST['role']; // Ambil role dari form

    // Validasi sederhana
    if (empty($username) || empty($email) || empty($password) || empty($nama_lengkap_user)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        // Enkripsi password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query INSERT menggunakan prepared statement
        $stmt = $koneksi->prepare("INSERT INTO users (username, email, password, nama_lengkap_user, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $hashed_password, $nama_lengkap_user, $role);

        if ($stmt->execute()) {
            $message = "Pengguna baru berhasil ditambahkan!";
        } else {
            // Cek error duplikat
            if ($koneksi->errno == 1062) {
                $error = "Gagal menambahkan: Username atau Email sudah ada yang menggunakan.";
            } else {
                $error = "Gagal menambahkan pengguna: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

// 3. LOGIKA UNTUK MENGAMBIL DAFTAR SEMUA PENGGUNA
$query_users = "SELECT id_user, username, email, nama_lengkap_user, role FROM users ORDER BY role, username";
$result_users = mysqli_query($koneksi, $query_users);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .management-card { background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .user-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .user-table th, .user-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .user-table th { background-color: #f8f9fa; font-weight: 600; }
        .user-table tbody tr:nth-child(even) { background-color: #f2f2f2; }
        .badge { padding: 3px 8px; border-radius: 5px; color: white; font-size: 12px; }
        .badge-admin { background-color: #e74c3c; }
        .badge-user { background-color: #3498db; }
    </style>
</head>
<body>
    <div class="container">
        <div class="management-card">
            <a href="dashboard_admin.php" style="text-decoration: none; color: #3498db; margin-bottom: 20px; display:inline-block;">&larr; Kembali ke Dashboard Admin</a>
            <h2>Kelola Pengguna (Admin & User)</h2>

            <fieldset>
                <legend>Tambah Pengguna Baru</legend>
                <?php if(!empty($message)): ?><div class="alert success"><?php echo $message; ?></div><?php endif; ?>
                <?php if(!empty($error)): ?><div class="alert error"><?php echo $error; ?></div><?php endif; ?>

                <form action="kelola_pengguna.php" method="POST">
                    <div class="form-group">
                        <label for="nama_lengkap_user">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap_user" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="tambah_user" class="btn">Tambah Pengguna</button>
                </form>
            </fieldset>

            <h3 style="margin-top: 40px;">Daftar Pengguna Terdaftar</h3>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $nomor = 1;
                    while($user = mysqli_fetch_assoc($result_users)): 
                    ?>
                    <tr>
                        <td><?php echo $nomor++; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['nama_lengkap_user']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <?php if($user['role'] == 'admin'): ?>
                                <span class="badge badge-admin">Admin</span>
                            <?php else: ?>
                                <span class="badge badge-user">User</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="#">Edit</a> | <a href="#">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>