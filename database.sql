-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 07:50 PM
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

CREATE TABLE `orang_tua` (
  `orang_tua_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tipe` enum('Ayah','Ibu','Wali') NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `kewarganegaraan` varchar(50) DEFAULT NULL,
  `pendidikan` varchar(50) DEFAULT NULL,
  `pekerjaan` varchar(50) DEFAULT NULL,
  `pengeluaran_perbulan` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `status_hidup` varchar(20) DEFAULT 'Masih Hidup'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pendidikan_sebelumnya` (
  `pendidikan_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tingkat` varchar(50) NOT NULL,
  `nama_sekolah` varchar(100) DEFAULT NULL,
  `tgl_ijazah` date DEFAULT NULL,
  `no_ijazah` varchar(50) DEFAULT NULL,
  `tgl_skhun` date DEFAULT NULL,
  `no_skhun` varchar(50) DEFAULT NULL,
  `lama_belajar` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `riwayat_pindahan` (
  `pindahan_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `dari_sekolah` varchar(100) DEFAULT NULL,
  `alasan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `kegemaran_siswa` (
  `kegemaran_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `kesenian` varchar(100) DEFAULT NULL,
  `olah_raga` varchar(100) DEFAULT NULL,
  `organisasi` varchar(100) DEFAULT NULL,
  `lain_lain` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `perkembangan_siswa` (
  `perkembangan_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `beasiswa` varchar(100) DEFAULT NULL,
  `tgl_meninggalkan_sekolah` date DEFAULT NULL,
  `alasan_meninggalkan_sekolah` text DEFAULT NULL,
  `lulus` tinyint(1) DEFAULT 0,
  `tgl_ijazah_akhir` date DEFAULT NULL,
  `no_ijazah_akhir` varchar(50) DEFAULT NULL,
  `tgl_skhun_akhir` date DEFAULT NULL,
  `no_skhun_akhir` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `info_setelah_lulus` (
  `info_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `melanjutkan_ke` varchar(100) DEFAULT NULL,
  `bekerja_di` varchar(100) DEFAULT NULL,
  `tgl_mulai_bekerja` date DEFAULT NULL,
  `penghasilan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- (Struktur tabel lain tetap sama)

--
-- Indexes for dumped tables
--

ALTER TABLE `siswa` ADD PRIMARY KEY (`siswa_id`), ADD UNIQUE KEY `nis` (`nis`), ADD KEY `user_id` (`user_id`), ADD KEY `kelas_id` (`kelas_id`);
ALTER TABLE `orang_tua` ADD PRIMARY KEY (`orang_tua_id`), ADD KEY `siswa_id` (`siswa_id`);
ALTER TABLE `pendidikan_sebelumnya` ADD PRIMARY KEY (`pendidikan_id`), ADD KEY `siswa_id` (`siswa_id`);
ALTER TABLE `riwayat_pindahan` ADD PRIMARY KEY (`pindahan_id`), ADD KEY `siswa_id` (`siswa_id`);
ALTER TABLE `kegemaran_siswa` ADD PRIMARY KEY (`kegemaran_id`), ADD KEY `siswa_id` (`siswa_id`);
ALTER TABLE `perkembangan_siswa` ADD PRIMARY KEY (`perkembangan_id`), ADD KEY `siswa_id` (`siswa_id`);
ALTER TABLE `info_setelah_lulus` ADD PRIMARY KEY (`info_id`), ADD KEY `siswa_id` (`siswa_id`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `siswa` MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `orang_tua` MODIFY `orang_tua_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pendidikan_sebelumnya` MODIFY `pendidikan_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `riwayat_pindahan` MODIFY `pindahan_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `kegemaran_siswa` MODIFY `kegemaran_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `perkembangan_siswa` MODIFY `perkembangan_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `info_setelah_lulus` MODIFY `info_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

ALTER TABLE `orang_tua` ADD CONSTRAINT `fk_siswa_ortu` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `pendidikan_sebelumnya` ADD CONSTRAINT `fk_siswa_pendidikan` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `riwayat_pindahan` ADD CONSTRAINT `fk_siswa_pindahan` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `kegemaran_siswa` ADD CONSTRAINT `fk_siswa_kegemaran` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `perkembangan_siswa` ADD CONSTRAINT `fk_siswa_perkembangan` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `info_setelah_lulus` ADD CONSTRAINT `fk_siswa_lulus` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;

-- (Sisa file database.sql)
COMMIT;
