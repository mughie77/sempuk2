<?php
// /core/siswa_crud_process.php

require_once __DIR__ . '/init.php';
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
        $stmt_siswa->close();

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
}
// TODO: Add logic for 'edit_siswa' and 'delete_siswa' in the future.
?>
