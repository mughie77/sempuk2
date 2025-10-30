-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 06:00 PM
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
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `siswa_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nis` varchar(20) NOT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `anak_ke` int(2) DEFAULT NULL,
  `no_akta_lahir` varchar(50) DEFAULT NULL,
  `no_kk` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `rt` varchar(5) DEFAULT NULL,
  `rw` varchar(5) DEFAULT NULL,
  `dusun` varchar(50) DEFAULT NULL,
  `kelurahan` varchar(50) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `jenis_tinggal` varchar(50) DEFAULT NULL,
  `alat_transportasi` varchar(50) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `lintang` varchar(50) DEFAULT NULL,
  `bujur` varchar(50) DEFAULT NULL,
  `jarak_sekolah_km` int(3) DEFAULT NULL,
  `berat_badan` int(3) DEFAULT NULL,
  `tinggi_badan` int(3) DEFAULT NULL,
  `lingkar_kepala` int(3) DEFAULT NULL,
  `jml_saudara` int(2) DEFAULT NULL,
  `kebutuhan_khusus` varchar(100) DEFAULT NULL,
  `sekolah_asal` varchar(100) DEFAULT NULL,
  `no_peserta_un` varchar(50) DEFAULT NULL,
  `no_seri_ijazah` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `orang_tua`
--

CREATE TABLE `orang_tua` (
  `orang_tua_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tipe` enum('Ayah','Ibu','Wali') NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tahun_lahir` year(4) DEFAULT NULL,
  `pendidikan` varchar(50) DEFAULT NULL,
  `pekerjaan` varchar(50) DEFAULT NULL,
  `penghasilan` varchar(50) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- (Struktur tabel lain tetap sama)
-- ...

--
-- Indexes for dumped tables
--

ALTER TABLE `siswa`
  ADD PRIMARY KEY (`siswa_id`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kelas_id` (`kelas_id`);

ALTER TABLE `orang_tua`
  ADD PRIMARY KEY (`orang_tua_id`),
  ADD KEY `siswa_id` (`siswa_id`);

-- ... (Indexes untuk tabel lain)

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `siswa`
  MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `orang_tua`
  MODIFY `orang_tua_id` int(11) NOT NULL AUTO_INCREMENT;

-- ... (AUTO_INCREMENT untuk tabel lain)

--
-- Constraints for dumped tables
--

ALTER TABLE `siswa`
  ADD CONSTRAINT `fk_user_siswa` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_kelas_siswa` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE SET NULL;

ALTER TABLE `orang_tua`
  ADD CONSTRAINT `fk_siswa_ortu` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;

-- ... (Constraints untuk tabel lain)

COMMIT;
