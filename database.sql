-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 10:30 PM
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
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'Admin Utama', NULL, '2025-10-28 19:15:11'),
(2, 'guru.mapel', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Guru', 'Budi Santoso, S.Pd.', NULL, '2025-10-28 19:15:11'),
(3, 'siswa.contoh', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siswa', 'Ani Yudhoyono', NULL, '2025-10-28 19:15:11');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `siswa_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
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
  `user_id` int(11) DEFAULT NULL,
  `nip` varchar(30) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `kelas_id` int(11) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `wali_kelas_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `mapel_id` int(11) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_pelajaran`
--

CREATE TABLE `jadwal_pelajaran` (
  `jadwal_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qr_harian`
--

CREATE TABLE `qr_harian` (
  `qr_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `token` varchar(255) NOT NULL
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

ALTER TABLE `users` ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `username` (`username`);
ALTER TABLE `siswa` ADD PRIMARY KEY (`siswa_id`), ADD UNIQUE KEY `nis` (`nis`), ADD KEY `user_id` (`user_id`), ADD KEY `kelas_id` (`kelas_id`);
ALTER TABLE `guru` ADD PRIMARY KEY (`guru_id`), ADD UNIQUE KEY `nip` (`nip`), ADD KEY `user_id` (`user_id`);
ALTER TABLE `kelas` ADD PRIMARY KEY (`kelas_id`), ADD UNIQUE KEY `nama_kelas` (`nama_kelas`), ADD KEY `wali_kelas_id` (`wali_kelas_id`);
ALTER TABLE `mapel` ADD PRIMARY KEY (`mapel_id`), ADD UNIQUE KEY `nama_mapel` (`nama_mapel`);
ALTER TABLE `jadwal_pelajaran` ADD PRIMARY KEY (`jadwal_id`), ADD KEY `kelas_id` (`kelas_id`), ADD KEY `mapel_id` (`mapel_id`), ADD KEY `guru_id` (`guru_id`);
ALTER TABLE `qr_harian` ADD PRIMARY KEY (`qr_id`), ADD UNIQUE KEY `tanggal` (`tanggal`);
ALTER TABLE `bk_laporan_mood` ADD PRIMARY KEY (`laporan_id`), ADD KEY `siswa_id` (`siswa_id`);
ALTER TABLE `tabungan_saldo` ADD PRIMARY KEY (`saldo_id`), ADD UNIQUE KEY `siswa_id` (`siswa_id`);
ALTER TABLE `tabungan_transaksi` ADD PRIMARY KEY (`transaksi_id`), ADD KEY `siswa_id` (`siswa_id`), ADD KEY `petugas_user_id` (`petugas_user_id`);
ALTER TABLE `presensi_log_harian` ADD PRIMARY KEY (`log_id`), ADD KEY `siswa_id` (`siswa_id`);
ALTER TABLE `jurnal_harian` ADD PRIMARY KEY (`jurnal_id`), ADD KEY `guru_id` (`guru_id`), ADD KEY `mapel_id` (`mapel_id`), ADD KEY `kelas_id` (`kelas_id`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `users` MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `siswa` MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `guru` MODIFY `guru_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `kelas` MODIFY `kelas_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mapel` MODIFY `mapel_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `jadwal_pelajaran` MODIFY `jadwal_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `qr_harian` MODIFY `qr_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `bk_laporan_mood` MODIFY `laporan_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tabungan_saldo` MODIFY `saldo_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tabungan_transaksi` MODIFY `transaksi_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `presensi_log_harian` MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `jurnal_harian` MODIFY `jurnal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE SET NULL;
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`wali_kelas_id`) REFERENCES `guru` (`guru_id`) ON DELETE SET NULL;
ALTER TABLE `jadwal_pelajaran`
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_1` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_2` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`mapel_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_pelajaran_ibfk_3` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`guru_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
