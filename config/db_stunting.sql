-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
<<<<<<< Updated upstream:config/db_stunting.sql
-- Generation Time: Jun 19, 2025 at 03:47 PM
=======
-- Generation Time: Jun 19, 2025 at 07:24 PM
>>>>>>> Stashed changes:database/db_stunting.sql
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.4.5

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
-- Table structure for table `aturan`
--

CREATE TABLE `aturan` (
  `id_aturan` int(11) NOT NULL,
  `kode_aturan` varchar(5) NOT NULL,
  `kondisi` varchar(255) NOT NULL,
  `kesimpulan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aturan`
--

INSERT INTO `aturan` (`id_aturan`, `kode_aturan`, `kondisi`, `kesimpulan`) VALUES
(1, 'R01', 'G01 AND G02', 'K01'),
(2, 'R02', 'G03 AND G04', 'K03'),
(3, 'R03', 'K01 AND G05', 'K02'),
(4, 'R04', 'G01 AND G08', 'K01');

-- --------------------------------------------------------

--
-- Table structure for table `balita`
--

CREATE TABLE `balita` (
  `id_balita` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_balita` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `balita`
--

INSERT INTO `balita` (`id_balita`, `id_user`, `nama_balita`, `tanggal_lahir`, `jenis_kelamin`, `alamat`) VALUES
<<<<<<< Updated upstream:config/db_stunting.sql
(1, 2, 'Tiara', '2025-06-19', 'Perempuan', 'Jl. Pamulang'),
(2, 3, 'Indhira', '2020-12-16', 'Perempuan', 'Jl. Populis');
=======
(1, 2, 'Tiara', '2024-02-12', 'Perempuan', 'Jl. Pamulang Raya');
>>>>>>> Stashed changes:database/db_stunting.sql

-- --------------------------------------------------------

--
-- Table structure for table `hasil_diagnosis`
--

CREATE TABLE `hasil_diagnosis` (
  `id_hasil` int(11) NOT NULL,
  `id_balita` int(11) NOT NULL,
  `jawaban` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`jawaban`)),
  `kesimpulan_akhir` varchar(100) NOT NULL,
  `tanggal_diagnosis` timestamp NOT NULL DEFAULT current_timestamp(),
  `catatan_usia_saat_diagnosis` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
<<<<<<< Updated upstream:config/db_stunting.sql

--
-- Dumping data for table `hasil_diagnosis`
--

INSERT INTO `hasil_diagnosis` (`id_hasil`, `id_balita`, `jawaban`, `kesimpulan_akhir`, `tanggal_diagnosis`, `catatan_usia_saat_diagnosis`) VALUES
(1, 1, '{\"G01\":\"ya\",\"G02\":\"ya\",\"G03\":\"ya\",\"G04\":\"ya\",\"G05\":\"ya\"}', 'Terindikasi Stunting', '2025-06-17 21:45:46', 0),
(2, 2, '{\"G01\":\"ya\",\"G02\":\"ya\",\"G03\":\"ya\",\"G04\":\"ya\",\"G05\":\"ya\"}', 'Terindikasi Stunting', '2025-06-17 21:57:12', 0),
(3, 3, '{\"G01\":\"tidak\",\"G02\":\"tidak\",\"G03\":\"tidak\",\"G04\":\"tidak\",\"G05\":\"tidak\"}', 'Normal / Tidak Terindikasi Masalah Stunting', '2025-06-17 21:57:28', 0),
(4, 4, '{\"G01\":\"tidak\",\"G02\":\"tidak\",\"G03\":\"ya\",\"G04\":\"tidak\",\"G05\":\"ya\"}', 'Normal / Tidak Terindikasi Masalah Stunting', '2025-06-19 04:08:39', 12),
(5, 1, '{\"G01\":\"ya\",\"G02\":\"ya\",\"G03\":\"ya\",\"G04\":\"ya\",\"G05\":\"ya\"}', 'Terindikasi Stunting', '2025-06-19 13:36:07', 0),
(6, 1, '{\"G01\":\"tidak\",\"G02\":\"tidak\",\"G03\":\"tidak\",\"G04\":\"tidak\",\"G05\":\"tidak\"}', 'Normal / Tidak Terindikasi Masalah Stunting', '2025-06-19 13:36:41', 0),
(7, 2, '{\"G01\":\"ya\",\"G02\":\"ya\",\"G03\":\"ya\",\"G04\":\"ya\",\"G05\":\"ya\"}', 'Terindikasi Stunting', '2025-06-19 13:38:36', 54),
(8, 2, '{\"G01\":\"tidak\",\"G02\":\"tidak\",\"G03\":\"tidak\",\"G04\":\"tidak\",\"G05\":\"tidak\"}', 'Normal / Tidak Terindikasi Masalah Stunting', '2025-06-19 13:38:56', 54),
(9, 2, '{\"G01\":\"ya\",\"G02\":\"ya\",\"G03\":\"tidak\",\"G04\":\"tidak\",\"G05\":\"ya\"}', 'Terindikasi Stunting', '2025-06-19 13:41:26', 54);

-- --------------------------------------------------------
=======
>>>>>>> Stashed changes:database/db_stunting.sql

--
-- Table structure for table `pertanyaan`
--

CREATE TABLE `pertanyaan` (
  `id_pertanyaan` int(11) NOT NULL,
  `kode_gejala` varchar(5) NOT NULL,
  `teks_pertanyaan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pertanyaan`
--

INSERT INTO `pertanyaan` (`id_pertanyaan`, `kode_gejala`, `teks_pertanyaan`) VALUES
(1, 'G01', 'Apakah berat badan anak tidak naik secara signifikan dalam 3 bulan terakhir?'),
(2, 'G02', 'Apakah tinggi badan anak terlihat lebih pendek dibandingkan anak seusianya?'),
(3, 'G03', 'Apakah anak sering sakit atau mudah terkena infeksi (lebih dari 3 kali dalam setahun)?'),
(4, 'G04', 'Apakah anak terlihat lesu, tidak aktif, dan cenderung lebih pendiam?'),
(5, 'G05', 'Apakah perkembangan kemampuan bicara atau motorik anak tampak terlambat?'),
(7, 'G08', 'HITAM ?');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap_user` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `tanggal_registrasi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `nama_lengkap_user`, `role`, `tanggal_registrasi`) VALUES
<<<<<<< Updated upstream:config/db_stunting.sql
(1, 'admin', 'admin@gmail.com', '$2a$10$QGyMYQKhcRgqZDPO1aqp3O5/mgYCnsCZHo4tFKcW/KLcF55gXRjaG', 'Admin', 'admin', '2025-06-19 13:30:21'),
(2, 'user', 'user@gmail.com', '$2y$12$JvbeOo9zKo9RoasGEgJiFOhBAQWsqM3VGjIVAxz.0yHvM8sLYwipa', 'user', 'user', '2025-06-19 13:32:39'),
(3, 'jasmine', 'jasmine@gmail.com', '$2y$12$cSvmjCz6a1BB9uP9tGshauWnAZh4arcI/W2Cq.D7QO.rkU4cayqNC', 'Jasmine', 'user', '2025-06-19 13:38:14');
=======
(1, 'admin', 'admin@gmail.com', '$2y$12$tWZ.YRVEHq2kxsXQhpFUfueJtVPXvLvrE5zthOeVHkY6lN2ABamt2', 'admin', 'admin', '2025-06-19 17:02:01'),
(2, 'user', 'user@gmail.com', '$2y$12$hrni5haZcgpt.DIehEzvcO8IFljbXDbers0jHmUZLUGVEj27BFZFe', 'user', 'user', '2025-06-19 17:02:49');
>>>>>>> Stashed changes:database/db_stunting.sql

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aturan`
--
ALTER TABLE `aturan`
  ADD PRIMARY KEY (`id_aturan`),
  ADD UNIQUE KEY `kode_aturan` (`kode_aturan`);

--
-- Indexes for table `balita`
--
ALTER TABLE `balita`
  ADD PRIMARY KEY (`id_balita`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `hasil_diagnosis`
--
ALTER TABLE `hasil_diagnosis`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `id_balita` (`id_balita`);

--
-- Indexes for table `pertanyaan`
--
ALTER TABLE `pertanyaan`
  ADD PRIMARY KEY (`id_pertanyaan`),
  ADD UNIQUE KEY `kode_gejala` (`kode_gejala`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aturan`
--
ALTER TABLE `aturan`
  MODIFY `id_aturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `balita`
--
ALTER TABLE `balita`
  MODIFY `id_balita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hasil_diagnosis`
--
ALTER TABLE `hasil_diagnosis`
<<<<<<< Updated upstream:config/db_stunting.sql
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
=======
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
>>>>>>> Stashed changes:database/db_stunting.sql

--
-- AUTO_INCREMENT for table `pertanyaan`
--
ALTER TABLE `pertanyaan`
  MODIFY `id_pertanyaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
<<<<<<< Updated upstream:config/db_stunting.sql
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
=======
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balita`
--
ALTER TABLE `balita`
  ADD CONSTRAINT `balita_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hasil_diagnosis`
--
ALTER TABLE `hasil_diagnosis`
  ADD CONSTRAINT `hasil_diagnosis_ibfk_1` FOREIGN KEY (`id_balita`) REFERENCES `balita` (`id_balita`) ON DELETE CASCADE ON UPDATE CASCADE;
>>>>>>> Stashed changes:database/db_stunting.sql
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
