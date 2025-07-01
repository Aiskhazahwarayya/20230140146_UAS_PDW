-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 01, 2025 at 04:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simprakt_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `modul_id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `file_laporan` varchar(255) NOT NULL,
  `tanggal_kumpul` timestamp NOT NULL DEFAULT current_timestamp(),
  `nilai` int(3) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `status` enum('Terkumpul','Dinilai') NOT NULL DEFAULT 'Terkumpul'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `modul_id`, `mahasiswa_id`, `file_laporan`, `tanggal_kumpul`, `nilai`, `feedback`, `status`) VALUES
(1, 2, 1, 'laporan_1_2_1751378853_LaporanPelanggan-1.pdf', '2025-07-01 14:07:33', 100, 'Mantap', 'Dinilai'),
(2, 4, 1, 'laporan_1_4_1751381154_LaporanPelanggan-1.pdf', '2025-07-01 14:45:54', NULL, NULL, 'Terkumpul');

-- --------------------------------------------------------

--
-- Table structure for table `mata_praktikum`
--

CREATE TABLE `mata_praktikum` (
  `id` int(11) NOT NULL,
  `nama_praktikum` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mata_praktikum`
--

INSERT INTO `mata_praktikum` (`id`, `nama_praktikum`, `deskripsi`) VALUES
(2, 'PABD', 'Pengembangan Aplikasi Basis Data'),
(3, 'PDW', 'Pengembangan Desain Web'),
(5, 'KMS', 'Pembelajaran Cyber Security');

-- --------------------------------------------------------

--
-- Table structure for table `modul`
--

CREATE TABLE `modul` (
  `id` int(11) NOT NULL,
  `praktikum_id` int(11) NOT NULL,
  `nama_modul` varchar(150) NOT NULL,
  `file_materi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modul`
--

INSERT INTO `modul` (`id`, `praktikum_id`, `nama_modul`, `file_materi`, `created_at`) VALUES
(2, 2, 'Modul 2', '1751378462_laporan pdw kel-7[1].docx', '2025-07-01 14:01:02'),
(3, 3, 'Tugas Pertemuan 16', '1751381026_UJIAN AKHIR SEMESTER PDW.pdf', '2025-07-01 14:43:46'),
(4, 5, 'Challange PicoCTF', '1751381058_Challenge PicoCTF_Dadakan.pdf', '2025-07-01 14:44:18');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_praktikum`
--

CREATE TABLE `pendaftaran_praktikum` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `praktikum_id` int(11) NOT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftaran_praktikum`
--

INSERT INTO `pendaftaran_praktikum` (`id`, `mahasiswa_id`, `praktikum_id`, `tanggal_daftar`) VALUES
(1, 1, 2, '2025-07-01 13:52:48'),
(2, 1, 3, '2025-07-01 14:36:39'),
(3, 1, 5, '2025-07-01 14:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','asisten') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Aiskha', 'aiskha@gmail.com', '$2y$10$.Z5bnNVu0yP11KQxe3zhtuBlT3/9fQ8uNJco4rl894IIRuH6jzr4e', 'mahasiswa', '2025-07-01 13:39:42'),
(2, 'ZahwaR', 'zahwaR@gmail.com', '$2y$10$16fZ0XshMCpwWpPVRSEQUOX1D8Wfhoy/XE38K5BkbeVRwheAkzuqm', 'asisten', '2025-07-01 13:43:07'),
(3, 'Rayya13', 'rayya13@gmail.com', '$2y$10$Zn11pBWqfNayqEBVLNImnO7dPovK/tFh1kKpexPj5iibLHtkXPdiq', 'mahasiswa', '2025-07-01 14:21:47'),
(4, 'Rindu', 'rindu@gmail.com', '$2y$10$MsJIax6IaNAatj7p0k96I.7t2/1tv1KaaJKRum6BS6i2Dtlj6xWrm', 'mahasiswa', '2025-07-01 14:23:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modul_id` (`modul_id`),
  ADD KEY `mahasiswa_id_laporan` (`mahasiswa_id`);

--
-- Indexes for table `mata_praktikum`
--
ALTER TABLE `mata_praktikum`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`id`),
  ADD KEY `praktikum_id` (`praktikum_id`);

--
-- Indexes for table `pendaftaran_praktikum`
--
ALTER TABLE `pendaftaran_praktikum`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mahasiswa_praktikum` (`mahasiswa_id`,`praktikum_id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `praktikum_id_pendaftaran` (`praktikum_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mata_praktikum`
--
ALTER TABLE `mata_praktikum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `modul`
--
ALTER TABLE `modul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pendaftaran_praktikum`
--
ALTER TABLE `pendaftaran_praktikum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`modul_id`) REFERENCES `modul` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_ibfk_2` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `modul`
--
ALTER TABLE `modul`
  ADD CONSTRAINT `modul_ibfk_1` FOREIGN KEY (`praktikum_id`) REFERENCES `mata_praktikum` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pendaftaran_praktikum`
--
ALTER TABLE `pendaftaran_praktikum`
  ADD CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_ibfk_2` FOREIGN KEY (`praktikum_id`) REFERENCES `mata_praktikum` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
