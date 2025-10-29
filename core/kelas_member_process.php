<?php
// /core/kelas_member_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

// Fungsi helper untuk redirect kembali ke halaman detail kelas
function redirect_back($kelas_id, $message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_kelas_detail.php?id=" . $kelas_id);
    exit();
}

// Validasi dasar
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action']) || !isset($_POST['kelas_id'])) {
    header("Location: " . BASE_URL . "pages/admin_manage_kelas.php");
    exit();
}

$action = $_POST['action'];
$kelas_id = (int)$_POST['kelas_id'];

// Logika untuk Menambahkan Siswa ke Kelas
if ($action === 'add_student_to_class') {
    if (!isset($_POST['siswa_id']) || empty($_POST['siswa_id'])) {
        redirect_back($kelas_id, 'Anda harus memilih siswa.', 'danger');
    }
    $siswa_id = (int)$_POST['siswa_id'];

    // Update kelas_id untuk siswa yang dipilih
    $sql = "UPDATE siswa SET kelas_id = ? WHERE siswa_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ii", $kelas_id, $siswa_id);
        if ($stmt->execute()) {
            redirect_back($kelas_id, 'Siswa berhasil ditambahkan ke kelas.');
        } else {
            redirect_back($kelas_id, 'Gagal menambahkan siswa: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

// Logika untuk Mengeluarkan Siswa dari Kelas
elseif ($action === 'remove_student_from_class') {
    if (!isset($_POST['siswa_id']) || empty($_POST['siswa_id'])) {
        redirect_back($kelas_id, 'ID siswa tidak valid.', 'danger');
    }
    $siswa_id = (int)$_POST['siswa_id'];

    // Set kelas_id menjadi NULL untuk siswa yang dipilih
    $sql = "UPDATE siswa SET kelas_id = NULL WHERE siswa_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $siswa_id);
        if ($stmt->execute()) {
            redirect_back($kelas_id, 'Siswa berhasil dikeluarkan dari kelas.');
        } else {
            redirect_back($kelas_id, 'Gagal mengeluarkan siswa: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

$mysqli->close();
?>
