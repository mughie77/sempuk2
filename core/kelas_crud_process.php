<?php
// /core/kelas_crud_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_manage_kelas.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$action = $_POST['action'];

if ($action === 'add_kelas') {
    $nama_kelas = trim($_POST['nama_kelas']);
    $konsentrasi_id = $_POST['konsentrasi_id'];
    if (empty($nama_kelas) || empty($konsentrasi_id)) redirect_with_message('Semua field wajib diisi.', 'danger');

    $sql = "INSERT INTO kelas (nama_kelas, konsentrasi_id) VALUES (?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("si", $nama_kelas, $konsentrasi_id);
        if ($stmt->execute()) {
            redirect_with_message('Kelas baru berhasil ditambahkan.');
        } else {
            if ($mysqli->errno === 1062) redirect_with_message('Nama kelas sudah ada.', 'danger');
            redirect_with_message('Gagal menambahkan kelas: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'edit_kelas') {
    $kelas_id = $_POST['kelas_id'];
    $nama_kelas = trim($_POST['nama_kelas']);
    $konsentrasi_id = $_POST['konsentrasi_id'];
    if (empty($kelas_id) || empty($nama_kelas) || empty($konsentrasi_id)) redirect_with_message('Semua field tidak boleh kosong.', 'danger');

    $sql = "UPDATE kelas SET nama_kelas = ?, konsentrasi_id = ? WHERE kelas_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("sii", $nama_kelas, $konsentrasi_id, $kelas_id);
        if ($stmt->execute()) {
            redirect_with_message('Data kelas berhasil diperbarui.');
        } else {
            if ($mysqli->errno === 1062) redirect_with_message('Nama kelas sudah digunakan.', 'danger');
            redirect_with_message('Gagal memperbarui data kelas: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'delete_kelas') {
    $kelas_id = $_POST['kelas_id'];
    $sql = "DELETE FROM kelas WHERE kelas_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $kelas_id);
        if ($stmt->execute()) {
            redirect_with_message('Data kelas berhasil dihapus.');
        } else {
            redirect_with_message('Gagal menghapus data kelas: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'get_kelas_details' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $kelas_id = $_GET['id'];
    $sql = "SELECT kelas_id, nama_kelas, konsentrasi_id FROM kelas WHERE kelas_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $kelas_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($kelas = $result->fetch_assoc()) {
            echo json_encode($kelas);
        } else {
            echo json_encode(['error' => 'Kelas not found']);
        }
        $stmt->close();
    }
    exit();
}

$mysqli->close();
?>
