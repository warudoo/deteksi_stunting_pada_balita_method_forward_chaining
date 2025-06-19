<?php
// 1. Panggil penjaga halaman admin dan koneksi database
require 'auth_admin.php'; 
require '../config/database.php';

// 2. Atur Header HTTP untuk memicu unduhan file
// Menentukan nama file yang akan diunduh
$filename = "laporan_diagnosis_" . date('Y-m-d') . ".csv";

// Memberitahu browser bahwa ini adalah file CSV
header('Content-Type: text/csv; charset=utf-g');
// Memberitahu browser untuk mengunduh file dengan nama yang telah ditentukan
header('Content-Disposition: attachment; filename="' . $filename . '"');

// 3. Buat "penulis" file ke output browser
// php://output adalah stream yang memungkinkan kita menulis langsung ke body response
$output = fopen('php://output', 'w');

// 4. Tulis baris header (judul kolom) untuk file CSV
fputcsv($output, array('ID Hasil', 'Tanggal Diagnosis', 'ID Balita', 'Nama Balita', 'Usia Saat Diagnosis (Bulan)', 'ID User', 'Nama Orang Tua', 'Kesimpulan Akhir', 'Jawaban Detail (JSON)'));

// 5. Query data yang sama dengan halaman laporan
$query_export = "SELECT 
                    h.id_hasil, 
                    h.tanggal_diagnosis, 
                    h.id_balita,
                    b.nama_balita,
                    h.catatan_usia_saat_diagnosis, 
                    u.id_user,
                    u.nama_lengkap_user,
                    h.kesimpulan_akhir,
                    h.jawaban
                  FROM hasil_diagnosis h
                  JOIN balita b ON h.id_balita = b.id_balita
                  JOIN users u ON b.id_user = u.id_user
                  ORDER BY h.tanggal_diagnosis ASC"; // Urutkan dari yang terlama agar laporan runut

$result_export = mysqli_query($koneksi, $query_export);

// 6. Loop melalui data dan tulis setiap baris ke file CSV
if (mysqli_num_rows($result_export) > 0) {
    while ($row = mysqli_fetch_assoc($result_export)) {
        fputcsv($output, $row);
    }
}

// Tutup stream
fclose($output);
// Hentikan script
exit();
?>