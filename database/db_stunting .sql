-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Jun 2025 pada 18.35
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_stunting`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `aturan`
--

CREATE TABLE `aturan` (
  `id_aturan` int(11) NOT NULL,
  `kode_aturan` varchar(5) NOT NULL,
  `kondisi` varchar(255) NOT NULL,
  `kesimpulan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aturan`
--

INSERT INTO `aturan` (`id_aturan`, `kode_aturan`, `kondisi`, `kesimpulan`) VALUES
(1, 'R01', 'G01 AND G02', 'K01'),
(2, 'R02', 'G03 AND G04', 'K03'),
(3, 'R03', 'K01 AND G05', 'K02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `balita`
--

CREATE TABLE `balita` (
  `id_balita` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_balita` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `balita`
--

INSERT INTO `balita` (`id_balita`, `id_user`, `nama_balita`, `tanggal_lahir`, `jenis_kelamin`, `alamat`) VALUES
(3, 3, 'Hafidza', '2025-02-19', 'Perempuan', ''),
(4, 7, 'Hafidza', '2023-03-22', 'Perempuan', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil_diagnosis`
--

CREATE TABLE `hasil_diagnosis` (
  `id_hasil` int(11) NOT NULL,
  `id_balita` int(11) NOT NULL,
  `jawaban` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`jawaban`)),
  `kesimpulan_akhir` varchar(100) NOT NULL,
  `tanggal_diagnosis` timestamp NOT NULL DEFAULT current_timestamp(),
  `catatan_usia_saat_diagnosis` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hasil_diagnosis`
--

INSERT INTO `hasil_diagnosis` (`id_hasil`, `id_balita`, `jawaban`, `kesimpulan_akhir`, `tanggal_diagnosis`, `catatan_usia_saat_diagnosis`) VALUES
(4, 4, '{\"G01\":\"tidak\",\"G02\":\"tidak\",\"G03\":\"tidak\",\"G04\":\"tidak\",\"G05\":\"tidak\",\"g09\":\"tidak\"}', 'Normal / Tidak Terindikasi Masalah Stunting', '2025-06-19 15:41:02', 26),
(5, 4, '{\"G01\":\"ya\",\"G02\":\"ya\",\"G03\":\"tidak\",\"G04\":\"tidak\",\"G05\":\"ya\",\"g09\":\"ya\"}', 'Terindikasi Stunting', '2025-06-19 15:41:21', 26);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertanyaan`
--

CREATE TABLE `pertanyaan` (
  `id_pertanyaan` int(11) NOT NULL,
  `kode_gejala` varchar(5) NOT NULL,
  `teks_pertanyaan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pertanyaan`
--

INSERT INTO `pertanyaan` (`id_pertanyaan`, `kode_gejala`, `teks_pertanyaan`) VALUES
(1, 'G01', 'Apakah berat badan anak tidak naik secara signifikan dalam 3 bulan terakhir?'),
(2, 'G02', 'Apakah tinggi badan anak terlihat lebih pendek dibandingkan anak seusianya?'),
(3, 'G03', 'Apakah anak sering sakit atau mudah terkena infeksi (lebih dari 3 kali dalam setahun)?'),
(4, 'G04', 'Apakah anak terlihat lesu, tidak aktif, dan cenderung lebih pendiam?'),
(5, 'G05', 'Apakah perkembangan kemampuan bicara atau motorik anak tampak terlambat?'),
(7, 'g09', 'apakah bayi anda hitam?\\r\\n');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap_user` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `tanggal_registrasi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `nama_lengkap_user`, `role`, `tanggal_registrasi`) VALUES
(3, 'admin', 'warudssss@gmail.com', '$2y$10$Pb6TGtyEyK/z0wa0FvZHLuti6jfT0vDlU2ZM3IB1LrTtyJxCeqFGe', 'admin', 'admin', '2025-06-19 15:02:03'),
(6, 'Warud', 'ruhamaevent@gmail.com', '$2y$10$jJyB6OYHXbF0pbSXuRYVr.cL6WC0U3zhaUxsXKbeERWKZylj1zfBi', 'Warud', 'admin', '2025-06-19 15:22:25'),
(7, 'mpii', 'muhammadsalwarud@gmail.com', '$2y$10$hJWYL7mxu03rO4/Vy2DXXemcJBgAgp0GrRV7pJ3K4JdzvT4qVc9ve', 'mpii', 'user', '2025-06-19 15:40:41');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `aturan`
--
ALTER TABLE `aturan`
  ADD PRIMARY KEY (`id_aturan`),
  ADD UNIQUE KEY `kode_aturan` (`kode_aturan`);

--
-- Indeks untuk tabel `balita`
--
ALTER TABLE `balita`
  ADD PRIMARY KEY (`id_balita`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `hasil_diagnosis`
--
ALTER TABLE `hasil_diagnosis`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_balita` (`id_balita`);

--
-- Indeks untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD PRIMARY KEY (`id_pertanyaan`),
  ADD UNIQUE KEY `kode_gejala` (`kode_gejala`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aturan`
--
ALTER TABLE `aturan`
  MODIFY `id_aturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `balita`
--
ALTER TABLE `balita`
  MODIFY `id_balita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `hasil_diagnosis`
--
ALTER TABLE `hasil_diagnosis`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pertanyaan`
--
ALTER TABLE `pertanyaan`
  MODIFY `id_pertanyaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `balita`
--
ALTER TABLE `balita`
  ADD CONSTRAINT `balita_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `hasil_diagnosis`
--
ALTER TABLE `hasil_diagnosis`
  ADD CONSTRAINT `hasil_diagnosis_ibfk_1` FOREIGN KEY (`id_balita`) REFERENCES `balita` (`id_balita`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
