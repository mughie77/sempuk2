-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 12:25 AM
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

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Administrator','Guru','Siswa','Orang Tua','Petugas Tabungan','BK') NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `token_sesi_aktif` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `nama_lengkap`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'Admin Utama');

CREATE TABLE `program_keahlian` (
  `program_id` int(11) NOT NULL,
  `nama_program` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `konsentrasi_keahlian` (
  `konsentrasi_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `nama_konsentrasi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `kelas` (
  `kelas_id` int(11) NOT NULL,
  `konsentrasi_id` int(11) DEFAULT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `wali_kelas_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `guru` (
  `guru_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nip` varchar(30) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `mapel` (
  `mapel_id` int(11) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `jadwal_pelajaran` (
  `jadwal_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `qr_harian` (
  `qr_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

ALTER TABLE `users` ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `username` (`username`);
ALTER TABLE `program_keahlian` ADD PRIMARY KEY (`program_id`), ADD UNIQUE KEY `nama_program` (`nama_program`);
ALTER TABLE `konsentrasi_keahlian` ADD PRIMARY KEY (`konsentrasi_id`), ADD KEY `program_id` (`program_id`);
ALTER TABLE `kelas` ADD PRIMARY KEY (`kelas_id`), ADD UNIQUE KEY `nama_kelas` (`nama_kelas`), ADD KEY `wali_kelas_id` (`wali_kelas_id`), ADD KEY `konsentrasi_id` (`konsentrasi_id`);
ALTER TABLE `siswa` ADD PRIMARY KEY (`siswa_id`), ADD UNIQUE KEY `nis` (`nis`), ADD KEY `user_id` (`user_id`), ADD KEY `kelas_id` (`kelas_id`);
ALTER TABLE `guru` ADD PRIMARY KEY (`guru_id`), ADD UNIQUE KEY `nip` (`nip`), ADD KEY `user_id` (`user_id`);
ALTER TABLE `mapel` ADD PRIMARY KEY (`mapel_id`), ADD UNIQUE KEY `nama_mapel` (`nama_mapel`);
ALTER TABLE `jadwal_pelajaran` ADD PRIMARY KEY (`jadwal_id`), ADD KEY `kelas_id` (`kelas_id`), ADD KEY `mapel_id` (`mapel_id`), ADD KEY `guru_id` (`guru_id`);
ALTER TABLE `qr_harian` ADD PRIMARY KEY (`qr_id`), ADD UNIQUE KEY `tanggal` (`tanggal`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `users` MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `program_keahlian` MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `konsentrasi_keahlian` MODIFY `konsentrasi_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `kelas` MODIFY `kelas_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `siswa` MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `guru` MODIFY `guru_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mapel` MODIFY `mapel_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `jadwal_pelajaran` MODIFY `jadwal_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `qr_harian` MODIFY `qr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

ALTER TABLE `konsentrasi_keahlian` ADD CONSTRAINT `fk_program` FOREIGN KEY (`program_id`) REFERENCES `program_keahlian` (`program_id`) ON DELETE CASCADE;
ALTER TABLE `kelas` ADD CONSTRAINT `fk_wali_kelas` FOREIGN KEY (`wali_kelas_id`) REFERENCES `guru` (`guru_id`) ON DELETE SET NULL, ADD CONSTRAINT `fk_konsentrasi` FOREIGN KEY (`konsentrasi_id`) REFERENCES `konsentrasi_keahlian` (`konsentrasi_id`) ON DELETE SET NULL;
ALTER TABLE `siswa` ADD CONSTRAINT `fk_user_siswa` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL, ADD CONSTRAINT `fk_kelas_siswa` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE SET NULL;
ALTER TABLE `guru` ADD CONSTRAINT `fk_user_guru` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
ALTER TABLE `jadwal_pelajaran` ADD CONSTRAINT `fk_kelas_jadwal` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE, ADD CONSTRAINT `fk_mapel_jadwal` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`mapel_id`) ON DELETE CASCADE, ADD CONSTRAINT `fk_guru_jadwal` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`guru_id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
