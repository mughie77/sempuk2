-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 09:30 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sempu`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Administrator','Guru','Siswa','Orang Tua','Petugas Tabungan','BK') NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `token_sesi_aktif` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `nama_lengkap`, `token_sesi_aktif`, `created_at`) VALUES
(1, 'admin', '$2y$10$examplehashedpassword1', 'Administrator', 'Admin Utama', NULL, '2025-10-28 19:15:11'),
(2, 'guru.mapel', '$2y$10$examplehashedpassword2', 'Guru', 'Budi Santoso, S.Pd.', NULL, '2025-10-28 19:15:11'),
(3, 'siswa.contoh', '$2y$10$examplehashedpassword3', 'Siswa', 'Ani Yudhoyono', NULL, '2025-10-28 19:15:11'),
(4, 'ortu.contoh', '$2y$10$examplehashedpassword4', 'Orang Tua', 'Bapak Ani', NULL, '2025-10-28 19:15:11'),
(5, 'petugas.tabungan', '$2y$10$examplehashedpassword5', 'Petugas Tabungan', 'Citra Lestari', NULL, '2025-10-28 19:15:11'),
(6, 'guru.bk', '$2y$10$examplehashedpassword6', 'BK', 'Dewi Anggraini, S.Psi.', NULL, '2025-10-28 19:15:11');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `siswa_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `guru_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bk_laporan_mood`
--

CREATE TABLE `bk_laporan_mood` (
  `laporan_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `mood` enum('Senang','Biasa','Sedih','Marah','Cemas') NOT NULL,
  `catatan` text DEFAULT NULL,
  `tanggal_lapor` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabungan_saldo`
--

CREATE TABLE `tabungan_saldo` (
  `saldo_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `saldo` decimal(15,2) NOT NULL DEFAULT 0.00,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabungan_transaksi`
--

CREATE TABLE `tabungan_transaksi` (
  `transaksi_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `petugas_user_id` int(11) NOT NULL,
  `tipe_transaksi` enum('Setor','Tarik') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `saldo_sebelum` decimal(15,2) NOT NULL,
  `saldo_sesudah` decimal(15,2) NOT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presensi_log_harian`
--

CREATE TABLE `presensi_log_harian` (
  `log_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status_kehadiran` enum('Hadir','Izin','Sakit','Alpa') NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_harian`
--

CREATE TABLE `jurnal_harian` (
  `jurnal_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `materi` text NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`siswa_id`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`guru_id`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bk_laporan_mood`
--
ALTER TABLE `bk_laporan_mood`
  ADD PRIMARY KEY (`laporan_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indexes for table `tabungan_saldo`
--
ALTER TABLE `tabungan_saldo`
  ADD PRIMARY KEY (`saldo_id`),
  ADD UNIQUE KEY `siswa_id` (`siswa_id`);

--
-- Indexes for table `tabungan_transaksi`
--
ALTER TABLE `tabungan_transaksi`
  ADD PRIMARY KEY (`transaksi_id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `petugas_user_id` (`petugas_user_id`);

--
-- Indexes for table `presensi_log_harian`
--
ALTER TABLE `presensi_log_harian`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `siswa_id` (`siswa_id`);

--
-- Indexes for table `jurnal_harian`
--
ALTER TABLE `jurnal_harian`
  ADD PRIMARY KEY (`jurnal_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `guru_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bk_laporan_mood`
--
ALTER TABLE `bk_laporan_mood`
  MODIFY `laporan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabungan_saldo`
--
ALTER TABLE `tabungan_saldo`
  MODIFY `saldo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabungan_transaksi`
--
ALTER TABLE `tabungan_transaksi`
  MODIFY `transaksi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensi_log_harian`
--
ALTER TABLE `presensi_log_harian`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurnal_harian`
--
ALTER TABLE `jurnal_harian`
  MODIFY `jurnal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `bk_laporan_mood`
--
ALTER TABLE `bk_laporan_mood`
  ADD CONSTRAINT `bk_laporan_mood_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;

--
-- Constraints for table `tabungan_saldo`
--
ALTER TABLE `tabungan_saldo`
  ADD CONSTRAINT `tabungan_saldo_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;

--
-- Constraints for table `tabungan_transaksi`
--
ALTER TABLE `tabungan_transaksi`
  ADD CONSTRAINT `tabungan_transaksi_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tabungan_transaksi_ibfk_2` FOREIGN KEY (`petugas_user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
