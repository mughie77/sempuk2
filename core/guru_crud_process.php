<?php
// /core/guru_crud_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_manage_guru.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$action = $_POST['action'];

if ($action === 'add_guru') {
    $nip = trim($_POST['nip']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $alamat = trim($_POST['alamat']);
    $telepon = trim($_POST['telepon']);

    if (empty($nip) || empty($nama_lengkap)) {
        redirect_with_message('NIP dan Nama Lengkap wajib diisi.', 'danger');
    }

    $sql = "INSERT INTO guru (nip, nama_lengkap, alamat, telepon) VALUES (?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssss", $nip, $nama_lengkap, $alamat, $telepon);
        if ($stmt->execute()) {
            redirect_with_message('Data guru baru berhasil ditambahkan.');
        } else {
            if ($mysqli->errno === 1062) redirect_with_message('NIP sudah terdaftar.', 'danger');
            redirect_with_message('Gagal menambahkan data guru: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'edit_guru') {
    $guru_id = $_POST['guru_id'];
    $nip = trim($_POST['nip']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $alamat = trim($_POST['alamat']);
    $telepon = trim($_POST['telepon']);

    if (empty($guru_id) || empty($nip) || empty($nama_lengkap)) {
        redirect_with_message('ID, NIP, dan Nama Lengkap tidak boleh kosong.', 'danger');
    }

    $sql = "UPDATE guru SET nip = ?, nama_lengkap = ?, alamat = ?, telepon = ? WHERE guru_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssssi", $nip, $nama_lengkap, $alamat, $telepon, $guru_id);
        if ($stmt->execute()) {
            redirect_with_message('Data guru berhasil diperbarui.');
        } else {
            if ($mysqli->errno === 1062) redirect_with_message('NIP sudah digunakan oleh guru lain.', 'danger');
            redirect_with_message('Gagal memperbarui data guru: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'delete_guru') {
    $guru_id = $_POST['guru_id'];
    $sql = "DELETE FROM guru WHERE guru_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $guru_id);
        if ($stmt->execute()) {
            redirect_with_message('Data guru berhasil dihapus.');
        } else {
            redirect_with_message('Gagal menghapus data guru: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'get_guru_details' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $guru_id = $_GET['id'];
    $sql = "SELECT guru_id, nip, nama_lengkap, alamat, telepon FROM guru WHERE guru_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $guru_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($guru = $result->fetch_assoc()) {
            echo json_encode($guru);
        } else {
            echo json_encode(['error' => 'Guru not found']);
        }
        $stmt->close();
    }
    exit();
}

$mysqli->close();
?>
