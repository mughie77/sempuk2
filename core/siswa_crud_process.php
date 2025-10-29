<?php
// /core/siswa_crud_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

// Fungsi helper untuk redirect dengan pesan flash
function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_manage_siswa.php");
    exit();
}

// Pastikan ini adalah request POST
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$action = $_POST['action'];

// Logika Tambah Siswa
if ($action === 'add_siswa') {
    $nis = trim($_POST['nis']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $alamat = trim($_POST['alamat']);
    $telepon = trim($_POST['telepon']);
    // $kelas_id = $_POST['kelas_id']; // Akan ditambahkan nanti
    // $user_id = $_POST['user_id']; // Akan ditambahkan nanti

    if (empty($nis) || empty($nama_lengkap)) {
        redirect_with_message('NIS dan Nama Lengkap wajib diisi.', 'danger');
    }

    $sql = "INSERT INTO siswa (nis, nama_lengkap, alamat, telepon) VALUES (?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssss", $nis, $nama_lengkap, $alamat, $telepon);
        if ($stmt->execute()) {
            redirect_with_message('Data siswa baru berhasil ditambahkan.');
        } else {
            if ($mysqli->errno === 1062) {
                redirect_with_message('NIS sudah terdaftar.', 'danger');
            }
            redirect_with_message('Gagal menambahkan data siswa: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

// Logika Edit Siswa
elseif ($action === 'edit_siswa') {
    $siswa_id = $_POST['siswa_id'];
    $nis = trim($_POST['nis']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $alamat = trim($_POST['alamat']);
    $telepon = trim($_POST['telepon']);

    if (empty($siswa_id) || empty($nis) || empty($nama_lengkap)) {
        redirect_with_message('ID, NIS, dan Nama Lengkap tidak boleh kosong.', 'danger');
    }

    $sql = "UPDATE siswa SET nis = ?, nama_lengkap = ?, alamat = ?, telepon = ? WHERE siswa_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssssi", $nis, $nama_lengkap, $alamat, $telepon, $siswa_id);
        if ($stmt->execute()) {
            redirect_with_message('Data siswa berhasil diperbarui.');
        } else {
             if ($mysqli->errno === 1062) {
                redirect_with_message('NIS sudah digunakan oleh siswa lain.', 'danger');
            }
            redirect_with_message('Gagal memperbarui data siswa: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

// Logika Hapus Siswa
elseif ($action === 'delete_siswa') {
    $siswa_id = $_POST['siswa_id'];

    $sql = "DELETE FROM siswa WHERE siswa_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $siswa_id);
        if ($stmt->execute()) {
            redirect_with_message('Data siswa berhasil dihapus.');
        } else {
            redirect_with_message('Gagal menghapus data siswa: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

// Logika Ambil Detail Siswa (untuk AJAX)
elseif ($action === 'get_siswa_details' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $siswa_id = $_GET['id'];
    $sql = "SELECT siswa_id, nis, nama_lengkap, alamat, telepon FROM siswa WHERE siswa_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $siswa_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($siswa = $result->fetch_assoc()) {
            echo json_encode($siswa);
        } else {
            echo json_encode(['error' => 'Siswa not found']);
        }
        $stmt->close();
    }
    exit();
}

$mysqli->close();
?>
