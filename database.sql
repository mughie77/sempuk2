-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 08:30 PM
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

CREATE TABLE `tahun_pelajaran` (
  `tahun_pelajaran_id` int(11) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Tidak Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `siswa` (
  `siswa_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nama_panggilan` varchar(50) DEFAULT NULL,
  `nis` varchar(20) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `kewarganegaraan` varchar(50) DEFAULT NULL,
  `anak_ke` int(2) DEFAULT NULL,
  `jml_saudara_kandung` int(2) DEFAULT NULL,
  `jml_saudara_tiri` int(2) DEFAULT NULL,
  `jml_saudara_angkat` int(2) DEFAULT NULL,
  `status_yatim` varchar(20) DEFAULT NULL,
  `bahasa_sehari_hari` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `tinggal_dengan` varchar(50) DEFAULT NULL,
  `jarak_ke_sekolah` varchar(50) DEFAULT NULL,
  `golongan_darah` varchar(5) DEFAULT NULL,
  `penyakit_diderita` varchar(255) DEFAULT NULL,
  `kelainan_jasmani` varchar(255) DEFAULT NULL,
  `tinggi_badan` int(3) DEFAULT NULL,
  `berat_badan` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- (dan seterusnya untuk semua tabel lain)

--
-- Indexes for dumped tables
--

ALTER TABLE `users` ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `username` (`username`);
ALTER TABLE `tahun_pelajaran` ADD PRIMARY KEY (`tahun_pelajaran_id`), ADD UNIQUE KEY `tahun_ajaran` (`tahun_ajaran`);
ALTER TABLE `siswa` ADD PRIMARY KEY (`siswa_id`), ADD UNIQUE KEY `nis` (`nis`), ADD KEY `user_id` (`user_id`), ADD KEY `kelas_id` (`kelas_id`);
-- (dan seterusnya untuk semua tabel lain)

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `users` MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tahun_pelajaran` MODIFY `tahun_pelajaran_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `siswa` MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT;
-- (dan seterusnya untuk semua tabel lain)

--
-- Constraints for dumped tables
--

-- (semua constraint)

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
