-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 09:15 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

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
-- Password for admin is "admin123"
INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `nama_lengkap`, `token_sesi_aktif`, `created_at`) VALUES
(1, 'admin', '$2y$10$If6sH88c2yVd2aLd4E/mreNAtvFw2J5SVTz./sTwA8ANc81/t2ygi', 'Administrator', 'Admin SEMPU', NULL, '2025-10-29 14:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `guru_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nip` varchar(50) NOT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_keahlian`
--

CREATE TABLE `program_keahlian` (
  `program_id` int(11) NOT NULL,
  `nama_program` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `konsentrasi_keahlian`
--

CREATE TABLE `konsentrasi_keahlian` (
  `konsentrasi_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `nama_konsentrasi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `kelas_id` int(11) NOT NULL,
  `konsentrasi_id` int(11) NOT NULL,
  `wali_kelas_id` int(11) DEFAULT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `tingkat` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

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
  `berat_badan` int(3) DEFAULT NULL,
  `status_siswa` enum('Aktif','Lulus','Pindah','Keluar') NOT NULL DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orang_tua`
--

CREATE TABLE `orang_tua` (
  `orang_tua_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tipe` enum('Ayah','Ibu','Wali') NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `kewarganegaraan` varchar(50) DEFAULT NULL,
  `pendidikan_terakhir` varchar(50) DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `penghasilan_per_bulan` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendidikan_sebelumnya`
--

CREATE TABLE `pendidikan_sebelumnya` (
  `pendidikan_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tingkat` enum('TK','SD','SMP') NOT NULL,
  `nama_sekolah` varchar(100) DEFAULT NULL,
  `tahun_sttb` varchar(10) DEFAULT NULL,
  `nomor_sttb` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tahun_pelajaran`
--

CREATE TABLE `tahun_pelajaran` (
  `tahun_pelajaran_id` int(11) NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL DEFAULT 'Tidak Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `mapel_id` int(11) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `kelompok` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_pelajaran`
--

CREATE TABLE `jadwal_pelajaran` (
  `jadwal_id` int(11) NOT NULL,
  `tahun_pelajaran_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presensi_log_harian`
--

CREATE TABLE `presensi_log_harian` (
  `presensi_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `status_kehadiran_sekolah` enum('Hadir','Sakit','Izin','Alpha') NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `keterangan_sekolah` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_harian`
--

CREATE TABLE `jurnal_harian` (
  `jurnal_id` int(11) NOT NULL,
  `jadwal_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `materi_pembahasan` text NOT NULL,
  `catatan_guru` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `absensi_mapel`
--

CREATE TABLE `absensi_mapel` (
  `absensi_mapel_id` int(11) NOT NULL,
  `jurnal_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `status_kehadiran_mapel` enum('Hadir','Sakit','Izin','Cabut') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bk_laporan_mood`
--

CREATE TABLE `bk_laporan_mood` (
  `mood_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `mood` varchar(50) NOT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_perundungan`
--

CREATE TABLE `laporan_perundungan` (
  `laporan_id` int(11) NOT NULL,
  `pelapor_siswa_id` int(11) DEFAULT NULL,
  `tanggal_laporan` timestamp NOT NULL DEFAULT current_timestamp(),
  `kronologi` text NOT NULL,
  `bukti_file` varchar(255) DEFAULT NULL,
  `status_laporan` enum('Baru','Diproses','Selesai') NOT NULL DEFAULT 'Baru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poin_pelanggaran`
--

CREATE TABLE `poin_pelanggaran` (
  `poin_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `guru_pencatat_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan_pelanggaran` text NOT NULL,
  `jumlah_poin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabungan_saldo`
--

CREATE TABLE `tabungan_saldo` (
  `saldo_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `total_saldo` decimal(15,2) NOT NULL DEFAULT 0.00,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
-- Table structure for table `kegemaran_siswa`
--

CREATE TABLE `kegemaran_siswa` (
  `kegemaran_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `kesenian` varchar(255) DEFAULT NULL,
  `olahraga` varchar(255) DEFAULT NULL,
  `kemasyarakatan` varchar(255) DEFAULT NULL,
  `lain_lain` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perkembangan_siswa`
--

CREATE TABLE `perkembangan_siswa` (
  `perkembangan_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `beasiswa_nama` varchar(100) DEFAULT NULL,
  `beasiswa_tahun` varchar(10) DEFAULT NULL,
  `beasiswa_dari` varchar(100) DEFAULT NULL,
  `meninggalkan_sekolah_tanggal` date DEFAULT NULL,
  `meninggalkan_sekolah_alasan` varchar(255) DEFAULT NULL,
  `akhir_pendidikan_tanggal` date DEFAULT NULL,
  `akhir_pendidikan_no_ijazah` varchar(100) DEFAULT NULL,
  `akhir_pendidikan_no_skhun` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `info_setelah_lulus`
--

CREATE TABLE `info_setelah_lulus` (
  `info_lulus_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `melanjutkan_ke` varchar(100) DEFAULT NULL,
  `bekerja_di` varchar(100) DEFAULT NULL,
  `bekerja_tanggal_mulai` date DEFAULT NULL,
  `bekerja_nama_perusahaan` varchar(100) DEFAULT NULL,
  `bekerja_penghasilan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

ALTER TABLE `users` ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `username` (`username`);
ALTER TABLE `guru` ADD PRIMARY KEY (`guru_id`), ADD UNIQUE KEY `nip` (`nip`), ADD KEY `user_id` (`user_id`);
ALTER TABLE `program_keahlian` ADD PRIMARY KEY (`program_id`);
ALTER TABLE `konsentrasi_keahlian` ADD PRIMARY KEY (`konsentrasi_id`), ADD KEY `program_id` (`program_id`);
ALTER TABLE `kelas` ADD PRIMARY KEY (`kelas_id`), ADD KEY `konsentrasi_id` (`konsentrasi_id`), ADD KEY `wali_kelas_id` (`wali_kelas_id`);
ALTER TABLE `siswa` ADD PRIMARY KEY (`siswa_id`), ADD UNIQUE KEY `nis` (`nis`), ADD UNIQUE KEY `nisn` (`nisn`), ADD KEY `user_id` (`user_id`), ADD KEY `kelas_id` (`kelas_id`);
ALTER TABLE `orang_tua` ADD PRIMARY KEY (`orang_tua_id`), ADD KEY `siswa_id` (`siswa_id`);
ALTER TABLE `pendidikan_sebelumnya` ADD PRIMARY KEY (`pendidikan_id`), ADD KEY `siswa_id` (`siswa_id`);
ALTER TABLE `tahun_pelajaran` ADD PRIMARY KEY (`tahun_pelajaran_id`), ADD UNIQUE KEY `tahun_ajaran` (`tahun_ajaran`);
ALTER TABLE `mapel` ADD PRIMARY KEY (`mapel_id`);
ALTER TABLE `jadwal_pelajaran` ADD PRIMARY KEY (`jadwal_id`), ADD KEY `tahun_pelajaran_id` (`tahun_pelajaran_id`), ADD KEY `kelas_id` (`kelas_id`), ADD KEY `mapel_id` (`mapel_id`), ADD KEY `guru_id` (`guru_id`);
ALTER TABLE `presensi_log_harian` ADD PRIMARY KEY (`presensi_id`), ADD UNIQUE KEY `siswa_tanggal` (`siswa_id`,`tanggal`);
ALTER TABLE `jurnal_harian` ADD PRIMARY KEY (`jurnal_id`), ADD KEY `jadwal_id` (`jadwal_id`);
ALTER TABLE `absensi_mapel` ADD PRIMARY KEY (`absensi_mapel_id`), ADD KEY `jurnal_id` (`jurnal_id`), ADD KEY `siswa_id` (`siswa_id`);
ALTER TABLE `bk_laporan_mood` ADD PRIMARY KEY (`mood_id`), ADD KEY `siswa_id` (`siswa_id`);
ALTER TABLE `laporan_perundungan` ADD PRIMARY KEY (`laporan_id`), ADD KEY `pelapor_siswa_id` (`pelapor_siswa_id`);
ALTER TABLE `poin_pelanggaran` ADD PRIMARY KEY (`poin_id`), ADD KEY `siswa_id` (`siswa_id`), ADD KEY `guru_pencatat_id` (`guru_pencatat_id`);
ALTER TABLE `tabungan_saldo` ADD PRIMARY KEY (`saldo_id`), ADD UNIQUE KEY `siswa_id` (`siswa_id`);
ALTER TABLE `tabungan_transaksi` ADD PRIMARY KEY (`transaksi_id`), ADD KEY `siswa_id` (`siswa_id`), ADD KEY `petugas_user_id` (`petugas_user_id`);
ALTER TABLE `kegemaran_siswa` ADD PRIMARY KEY (`kegemaran_id`), ADD UNIQUE KEY `siswa_id` (`siswa_id`);
ALTER TABLE `perkembangan_siswa` ADD PRIMARY KEY (`perkembangan_id`), ADD UNIQUE KEY `siswa_id` (`siswa_id`);
ALTER TABLE `info_setelah_lulus` ADD PRIMARY KEY (`info_lulus_id`), ADD UNIQUE KEY `siswa_id` (`siswa_id`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `users` MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `guru` MODIFY `guru_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `program_keahlian` MODIFY `program_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `konsentrasi_keahlian` MODIFY `konsentrasi_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `kelas` MODIFY `kelas_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `siswa` MODIFY `siswa_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `orang_tua` MODIFY `orang_tua_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pendidikan_sebelumnya` MODIFY `pendidikan_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tahun_pelajaran` MODIFY `tahun_pelajaran_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mapel` MODIFY `mapel_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `jadwal_pelajaran` MODIFY `jadwal_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `presensi_log_harian` MODIFY `presensi_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `jurnal_harian` MODIFY `jurnal_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `absensi_mapel` MODIFY `absensi_mapel_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `bk_laporan_mood` MODIFY `mood_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `laporan_perundungan` MODIFY `laporan_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `poin_pelanggaran` MODIFY `poin_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tabungan_saldo` MODIFY `saldo_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tabungan_transaksi` MODIFY `transaksi_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `kegemaran_siswa` MODIFY `kegemaran_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `perkembangan_siswa` MODIFY `perkembangan_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `info_setelah_lulus` MODIFY `info_lulus_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

ALTER TABLE `guru` ADD CONSTRAINT `guru_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
ALTER TABLE `konsentrasi_keahlian` ADD CONSTRAINT `konsentrasi_keahlian_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `program_keahlian` (`program_id`) ON DELETE CASCADE;
ALTER TABLE `kelas` ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`konsentrasi_id`) REFERENCES `konsentrasi_keahlian` (`konsentrasi_id`) ON DELETE CASCADE, ADD CONSTRAINT `kelas_ibfk_2` FOREIGN KEY (`wali_kelas_id`) REFERENCES `guru` (`guru_id`) ON DELETE SET NULL;
ALTER TABLE `siswa` ADD CONSTRAINT `siswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL, ADD CONSTRAINT `siswa_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE SET NULL;
ALTER TABLE `orang_tua` ADD CONSTRAINT `orang_tua_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `pendidikan_sebelumnya` ADD CONSTRAINT `pendidikan_sebelumnya_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `jadwal_pelajaran` ADD CONSTRAINT `jadwal_pelajaran_ibfk_1` FOREIGN KEY (`tahun_pelajaran_id`) REFERENCES `tahun_pelajaran` (`tahun_pelajaran_id`) ON DELETE CASCADE, ADD CONSTRAINT `jadwal_pelajaran_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`kelas_id`) ON DELETE CASCADE, ADD CONSTRAINT `jadwal_pelajaran_ibfk_3` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`mapel_id`) ON DELETE CASCADE, ADD CONSTRAINT `jadwal_pelajaran_ibfk_4` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`guru_id`) ON DELETE CASCADE;
ALTER TABLE `presensi_log_harian` ADD CONSTRAINT `presensi_log_harian_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `jurnal_harian` ADD CONSTRAINT `jurnal_harian_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_pelajaran` (`jadwal_id`) ON DELETE CASCADE;
ALTER TABLE `absensi_mapel` ADD CONSTRAINT `absensi_mapel_ibfk_1` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal_harian` (`jurnal_id`) ON DELETE CASCADE, ADD CONSTRAINT `absensi_mapel_ibfk_2` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `bk_laporan_mood` ADD CONSTRAINT `bk_laporan_mood_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `laporan_perundungan` ADD CONSTRAINT `laporan_perundungan_ibfk_1` FOREIGN KEY (`pelapor_siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE SET NULL;
ALTER TABLE `poin_pelanggaran` ADD CONSTRAINT `poin_pelanggaran_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE, ADD CONSTRAINT `poin_pelanggaran_ibfk_2` FOREIGN KEY (`guru_pencatat_id`) REFERENCES `guru` (`guru_id`) ON DELETE CASCADE;
ALTER TABLE `tabungan_saldo` ADD CONSTRAINT `tabungan_saldo_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `tabungan_transaksi` ADD CONSTRAINT `tabungan_transaksi_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE, ADD CONSTRAINT `tabungan_transaksi_ibfk_2` FOREIGN KEY (`petugas_user_id`) REFERENCES `users` (`user_id`);
ALTER TABLE `kegemaran_siswa` ADD CONSTRAINT `kegemaran_siswa_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `perkembangan_siswa` ADD CONSTRAINT `perkembangan_siswa_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;
ALTER TABLE `info_setelah_lulus` ADD CONSTRAINT `info_setelah_lulus_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`siswa_id`) ON DELETE CASCADE;

COMMIT;
