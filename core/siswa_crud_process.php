<?php
// /core/siswa_crud_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action'])) {
    header("Location: " . BASE_URL . "pages/admin_manage_siswa.php");
    exit();
}

$action = $_POST['action'];

// Helper function for validation
function validate_input($data) {
    $errors = [];
    if (empty(trim($data['nama_lengkap']))) $errors[] = "Nama lengkap wajib diisi.";
    if (empty(trim($data['nis']))) $errors[] = "NIS wajib diisi.";
    if (empty(trim($data['nisn']))) $errors[] = "NISN wajib diisi.";
    return $errors;
}

if ($action === 'add_siswa') {
    $errors = validate_input($_POST);
    if (!empty($errors)) {
        $_SESSION['flash_message'] = ['message' => implode('<br>', $errors), 'type' => 'danger'];
        header("Location: " . BASE_URL . "pages/admin_siswa_form.php");
        exit();
    }

    $mysqli->begin_transaction();

    try {
        // 1. Create the user account using NISN
        $username = trim($_POST['nisn']);
        $password = password_hash($username, PASSWORD_DEFAULT); // Default password is the NISN
        $role = 'Siswa';
        $nama_lengkap = trim($_POST['nama_lengkap']);

        $stmt_user = $mysqli->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, ?, ?)");
        $stmt_user->bind_param("ssss", $username, $password, $role, $nama_lengkap);
        $stmt_user->execute();
        $user_id = $stmt_user->insert_id;
        $stmt_user->close();

        // 2. Insert into 'siswa' table
        $stmt_siswa = $mysqli->prepare("INSERT INTO siswa (user_id, nama_lengkap, nama_panggilan, nis, nisn, jk, tempat_lahir, tanggal_lahir, agama, kewarganegaraan, anak_ke, jml_saudara_kandung, bahasa_sehari_hari, alamat, telepon, tinggal_dengan, jarak_ke_sekolah, golongan_darah, penyakit_diderita, kelainan_jasmani, tinggi_badan, berat_badan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt_siswa->bind_param(
            "isssssssssiisssssssssi",
            $user_id,
            $_POST['nama_lengkap'],
            $_POST['nama_panggilan'],
            $_POST['nis'],
            $_POST['nisn'],
            $_POST['jk'],
            $_POST['tempat_lahir'],
            $_POST['tanggal_lahir'],
            $_POST['agama'],
            $_POST['kewarganegaraan'],
            $_POST['anak_ke'],
            $_POST['jml_saudara_kandung'],
            $_POST['bahasa_sehari_hari'],
            $_POST['alamat'],
            $_POST['telepon'],
            $_POST['tinggal_dengan'],
            $_POST['jarak_ke_sekolah'],
            $_POST['golongan_darah'],
            $_POST['penyakit_diderita'],
            $_POST['kelainan_jasmani'],
            $_POST['tinggi_badan'],
            $_POST['berat_badan']
        );
        $stmt_siswa->execute();
        $siswa_id = $stmt_siswa->insert_id;
        $stmt_siswa->close();

        // 3. Insert into 'kegemaran_siswa' table
        $stmt_kegemaran = $mysqli->prepare("INSERT INTO kegemaran_siswa (siswa_id, kesenian, olahraga, kemasyarakatan, lain_lain) VALUES (?, ?, ?, ?, ?)");
        $stmt_kegemaran->bind_param(
            "issss",
            $siswa_id,
            $_POST['kegemaran_kesenian'],
            $_POST['kegemaran_olah_raga'],
            $_POST['kegemaran_organisasi'],
            $_POST['kegemaran_lain_lain']
        );
        $stmt_kegemaran->execute();
        $stmt_kegemaran->close();

        // 4. Insert into 'perkembangan_siswa' table
        $stmt_perkembangan = $mysqli->prepare("INSERT INTO perkembangan_siswa (siswa_id, beasiswa_nama, beasiswa_tahun, beasiswa_dari, meninggalkan_sekolah_tanggal, meninggalkan_sekolah_alasan, akhir_pendidikan_tanggal, akhir_pendidikan_no_ijazah, akhir_pendidikan_no_skhun) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_perkembangan->bind_param(
            "issssssss",
            $siswa_id,
            $_POST['perkembangan_beasiswa_nama'],
            $_POST['perkembangan_beasiswa_tahun'],
            $_POST['perkembangan_beasiswa_dari'],
            $_POST['perkembangan_meninggalkan_tanggal'],
            $_POST['perkembangan_meninggalkan_alasan'],
            $_POST['perkembangan_akhir_tanggal'],
            $_POST['perkembangan_akhir_no_ijazah'],
            $_POST['perkembangan_akhir_no_skhun']
        );
        $stmt_perkembangan->execute();
        $stmt_perkembangan->close();

        // 5. Insert into 'info_setelah_lulus' table
        $stmt_lulus = $mysqli->prepare("INSERT INTO info_setelah_lulus (siswa_id, melanjutkan_ke, bekerja_di, bekerja_tanggal_mulai, bekerja_nama_perusahaan, bekerja_penghasilan) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_lulus->bind_param(
            "isssss",
            $siswa_id,
            $_POST['lulus_melanjutkan_ke'],
            $_POST['lulus_bekerja_di'],
            $_POST['lulus_bekerja_tanggal'],
            $_POST['lulus_bekerja_nama_perusahaan'],
            $_POST['lulus_bekerja_penghasilan']
        );
        $stmt_lulus->execute();
        $stmt_lulus->close();

        $mysqli->commit();
        $_SESSION['flash_message'] = ['message' => 'Data siswa dan akun login berhasil ditambahkan.', 'type' => 'success'];

    } catch (mysqli_sql_exception $e) {
        $mysqli->rollback();
        // Check for duplicate entry error (code 1062)
        if ($e->getCode() == 1062) {
             $_SESSION['flash_message'] = ['message' => 'Gagal menambahkan siswa. NIS atau NISN sudah ada yang menggunakan.', 'type' => 'danger'];
        } else {
             $_SESSION['flash_message'] = ['message' => 'Terjadi kesalahan pada database: ' . $e->getMessage(), 'type' => 'danger'];
        }
    }

    header("Location: " . BASE_URL . "pages/admin_manage_siswa.php");
    exit();
} elseif ($action === 'edit_siswa') {
    if (!isset($_POST['siswa_id']) || !is_numeric($_POST['siswa_id'])) {
        $_SESSION['flash_message'] = ['message' => 'ID Siswa tidak valid.', 'type' => 'danger'];
        header("Location: " . BASE_URL . "pages/admin_manage_siswa.php");
        exit();
    }
    $siswa_id = (int)$_POST['siswa_id'];

    $mysqli->begin_transaction();

    try {
        // 1. Update 'siswa' table
        $stmt_siswa = $mysqli->prepare("UPDATE siswa SET nama_lengkap = ?, nama_panggilan = ?, nis = ?, nisn = ?, jk = ?, tempat_lahir = ?, tanggal_lahir = ?, agama = ?, kewarganegaraan = ?, anak_ke = ?, jml_saudara_kandung = ?, bahasa_sehari_hari = ?, alamat = ?, telepon = ?, tinggal_dengan = ?, jarak_ke_sekolah = ?, golongan_darah = ?, penyakit_diderita = ?, kelainan_jasmani = ?, tinggi_badan = ?, berat_badan = ? WHERE siswa_id = ?");
        $stmt_siswa->bind_param(
            "sssssssssiisssssssssii",
            $_POST['nama_lengkap'], $_POST['nama_panggilan'], $_POST['nis'], $_POST['nisn'], $_POST['jk'],
            $_POST['tempat_lahir'], $_POST['tanggal_lahir'], $_POST['agama'], $_POST['kewarganegaraan'],
            $_POST['anak_ke'], $_POST['jml_saudara_kandung'], $_POST['bahasa_sehari_hari'], $_POST['alamat'],
            $_POST['telepon'], $_POST['tinggal_dengan'], $_POST['jarak_ke_sekolah'], $_POST['golongan_darah'],
            $_POST['penyakit_diderita'], $_POST['kelainan_jasmani'], $_POST['tinggi_badan'], $_POST['berat_badan'],
            $siswa_id
        );
        $stmt_siswa->execute();
        $stmt_siswa->close();

        // 2. Update 'kegemaran_siswa' table (Insert or Update)
        $stmt_kegemaran = $mysqli->prepare("INSERT INTO kegemaran_siswa (siswa_id, kesenian, olahraga, kemasyarakatan, lain_lain) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE kesenian=VALUES(kesenian), olahraga=VALUES(olahraga), kemasyarakatan=VALUES(kemasyarakatan), lain_lain=VALUES(lain_lain)");
        $stmt_kegemaran->bind_param("issss", $siswa_id, $_POST['kegemaran_kesenian'], $_POST['kegemaran_olah_raga'], $_POST['kegemaran_organisasi'], $_POST['kegemaran_lain_lain']);
        $stmt_kegemaran->execute();
        $stmt_kegemaran->close();

        // 3. Update 'perkembangan_siswa' table (Insert or Update)
        $stmt_perkembangan = $mysqli->prepare("INSERT INTO perkembangan_siswa (siswa_id, beasiswa_nama, beasiswa_tahun, beasiswa_dari, meninggalkan_sekolah_tanggal, meninggalkan_sekolah_alasan, akhir_pendidikan_tanggal, akhir_pendidikan_no_ijazah, akhir_pendidikan_no_skhun) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE beasiswa_nama=VALUES(beasiswa_nama), beasiswa_tahun=VALUES(beasiswa_tahun), beasiswa_dari=VALUES(beasiswa_dari), meninggalkan_sekolah_tanggal=VALUES(meninggalkan_sekolah_tanggal), meninggalkan_sekolah_alasan=VALUES(meninggalkan_sekolah_alasan), akhir_pendidikan_tanggal=VALUES(akhir_pendidikan_tanggal), akhir_pendidikan_no_ijazah=VALUES(akhir_pendidikan_no_ijazah), akhir_pendidikan_no_skhun=VALUES(akhir_pendidikan_no_skhun)");
        $stmt_perkembangan->bind_param("issssssss", $siswa_id, $_POST['perkembangan_beasiswa_nama'], $_POST['perkembangan_beasiswa_tahun'], $_POST['perkembangan_beasiswa_dari'], $_POST['perkembangan_meninggalkan_tanggal'], $_POST['perkembangan_meninggalkan_alasan'], $_POST['perkembangan_akhir_tanggal'], $_POST['perkembangan_akhir_no_ijazah'], $_POST['perkembangan_akhir_no_skhun']);
        $stmt_perkembangan->execute();
        $stmt_perkembangan->close();

        // 4. Update 'info_setelah_lulus' table (Insert or Update)
        $stmt_lulus = $mysqli->prepare("INSERT INTO info_setelah_lulus (siswa_id, melanjutkan_ke, bekerja_di, bekerja_tanggal_mulai, bekerja_nama_perusahaan, bekerja_penghasilan) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE melanjutkan_ke=VALUES(melanjutkan_ke), bekerja_di=VALUES(bekerja_di), bekerja_tanggal_mulai=VALUES(bekerja_tanggal_mulai), bekerja_nama_perusahaan=VALUES(bekerja_nama_perusahaan), bekerja_penghasilan=VALUES(bekerja_penghasilan)");
        $stmt_lulus->bind_param("isssss", $siswa_id, $_POST['lulus_melanjutkan_ke'], $_POST['lulus_bekerja_di'], $_POST['lulus_bekerja_tanggal'], $_POST['lulus_bekerja_nama_perusahaan'], $_POST['lulus_bekerja_penghasilan']);
        $stmt_lulus->execute();
        $stmt_lulus->close();

        $mysqli->commit();
        $_SESSION['flash_message'] = ['message' => 'Data siswa berhasil diperbarui.', 'type' => 'success'];

    } catch (mysqli_sql_exception $e) {
        $mysqli->rollback();
        $_SESSION['flash_message'] = ['message' => 'Gagal memperbarui data siswa: ' . $e->getMessage(), 'type' => 'danger'];
    }

    header("Location: " . BASE_URL . "pages/admin_manage_siswa.php");
    exit();
}
?>
