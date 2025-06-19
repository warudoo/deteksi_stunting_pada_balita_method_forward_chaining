<?php
session_start();
require 'config/database.php';

// Penjaga Halaman dan validasi dasar
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit(); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: dashboard.php"); exit(); }
if (!isset($_GET['id_balita']) || !isset($_POST['jawaban'])) { header("Location: dashboard.php"); exit(); }

$id_balita = (int)$_GET['id_balita'];
$id_user = $_SESSION['id_user'];
$jawaban_user = $_POST['jawaban'];

// Keamanan: Cek lagi kepemilikan balita
$query_cek = "SELECT tanggal_lahir FROM balita WHERE id_balita = $id_balita AND id_user = $id_user";
$result_cek = mysqli_query($koneksi, $query_cek);
if (mysqli_num_rows($result_cek) == 0) { header("Location: dashboard.php"); exit(); }
$data_balita = mysqli_fetch_assoc($result_cek);


// --- LOGIKA FORWARD CHAINING DIMULAI ---

// 1. Ambil semua aturan dari database
$aturan = [];
$query_aturan = "SELECT kondisi, kesimpulan FROM aturan";
$result_aturan = mysqli_query($koneksi, $query_aturan);
while ($row = mysqli_fetch_assoc($result_aturan)) {
    $aturan[] = $row;
}

// 2. Inisialisasi Fakta Awal
// Fakta awal adalah semua gejala yang dijawab "ya" oleh user
$fakta = [];
foreach ($jawaban_user as $kode_gejala => $jawaban) {
    if ($jawaban == 'ya') {
        $fakta[] = $kode_gejala;
    }
}

// 3. Proses Inferensi (Loop hingga tidak ada fakta baru)
$fakta_baru_ditemukan = true;
while ($fakta_baru_ditemukan) {
    $fakta_baru_ditemukan = false;
    foreach ($aturan as $rule) {
        // Cek apakah kesimpulan dari aturan ini sudah ada di fakta
        if (!in_array($rule['kesimpulan'], $fakta)) {
            // Pecah kondisi menjadi array (misal: "G01 AND G02" -> ["G01", "G02"])
            $kondisi_list = explode(' AND ', $rule['kondisi']);
            
            // Cek apakah semua kondisi untuk aturan ini terpenuhi oleh fakta yang ada
            $semua_kondisi_terpenuhi = true;
            foreach ($kondisi_list as $kondisi) {
                if (!in_array($kondisi, $fakta)) {
                    $semua_kondisi_terpenuhi = false;
                    break;
                }
            }

            // Jika semua kondisi terpenuhi, tambahkan kesimpulan ke daftar fakta
            if ($semua_kondisi_terpenuhi) {
                $fakta[] = $rule['kesimpulan'];
                $fakta_baru_ditemukan = true; // Set flag karena ada fakta baru
            }
        }
    }
}
// --- LOGIKA FORWARD CHAINING SELESAI ---


// 4. Tentukan Hasil Akhir
// Mapping kode kesimpulan ke teks yang mudah dibaca
$kamus_hasil = [
    'K01' => 'Berisiko Stunting',
    'K02' => 'Terindikasi Stunting',
    'K03' => 'Waspada Gizi Buruk'
];

$kesimpulan_akhir = 'Normal / Tidak Terindikasi Masalah Stunting'; // Default
foreach ($fakta as $f) {
    // Ambil kesimpulan dengan prioritas tertinggi (misal K02 lebih tinggi dari K01)
    if (isset($kamus_hasil[$f])) {
        // Logika sederhana: hasil terakhir yang ditemukan akan menjadi kesimpulan.
        // Anda bisa membuat logika prioritas jika diperlukan.
        $kesimpulan_akhir = $kamus_hasil[$f];
    }
}

// 5. Simpan Hasil Diagnosis ke Database
// Hitung usia dalam bulan
$tgl_lahir = new DateTime($data_balita['tanggal_lahir']);
$hari_ini = new DateTime('today');
$umur_bulan = $hari_ini->diff($tgl_lahir)->y * 12 + $hari_ini->diff($tgl_lahir)->m;

// Format jawaban user menjadi JSON untuk disimpan
$jawaban_json = json_encode($jawaban_user);

// Query Insert menggunakan Prepared Statement untuk keamanan
$stmt = $koneksi->prepare("INSERT INTO hasil_diagnosis (id_balita, jawaban, kesimpulan_akhir, catatan_usia_saat_diagnosis) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issi", $id_balita, $jawaban_json, $kesimpulan_akhir, $umur_bulan);

if ($stmt->execute()) {
    // Jika berhasil, ambil ID hasil yang baru saja dibuat
    $id_hasil_baru = $stmt->insert_id;
    // Arahkan ke halaman hasil
    header("Location: hasil.php?id_hasil=" . $id_hasil_baru);
    exit();
} else {
    echo "Terjadi kesalahan saat menyimpan hasil diagnosis.";
    // Handle error
}

$stmt->close();
$koneksi->close();

?>