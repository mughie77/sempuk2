<?php
// /core/mapel_crud_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_manage_mapel.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$action = $_POST['action'];

if ($action === 'add_mapel') {
    $nama_mapel = trim($_POST['nama_mapel']);
    if (empty($nama_mapel)) redirect_with_message('Nama Mata Pelajaran tidak boleh kosong.', 'danger');

    $sql = "INSERT INTO mapel (nama_mapel) VALUES (?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $nama_mapel);
        if ($stmt->execute()) {
            redirect_with_message('Mata pelajaran baru berhasil ditambahkan.');
        } else {
            if ($mysqli->errno === 1062) redirect_with_message('Nama mata pelajaran sudah ada.', 'danger');
            redirect_with_message('Gagal menambahkan mata pelajaran: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'edit_mapel') {
    $mapel_id = $_POST['mapel_id'];
    $nama_mapel = trim($_POST['nama_mapel']);
    if (empty($mapel_id) || empty($nama_mapel)) redirect_with_message('ID dan Nama Mata Pelajaran tidak boleh kosong.', 'danger');

    $sql = "UPDATE mapel SET nama_mapel = ? WHERE mapel_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("si", $nama_mapel, $mapel_id);
        if ($stmt->execute()) {
            redirect_with_message('Data mata pelajaran berhasil diperbarui.');
        } else {
            if ($mysqli->errno === 1062) redirect_with_message('Nama mata pelajaran sudah digunakan.', 'danger');
            redirect_with_message('Gagal memperbarui data: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'delete_mapel') {
    $mapel_id = $_POST['mapel_id'];
    $sql = "DELETE FROM mapel WHERE mapel_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $mapel_id);
        if ($stmt->execute()) {
            redirect_with_message('Data mata pelajaran berhasil dihapus.');
        } else {
            redirect_with_message('Gagal menghapus data: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'get_mapel_details' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $mapel_id = $_GET['id'];
    $sql = "SELECT mapel_id, nama_mapel FROM mapel WHERE mapel_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $mapel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($mapel = $result->fetch_assoc()) {
            echo json_encode($mapel);
        } else {
            echo json_encode(['error' => 'Mapel not found']);
        }
        $stmt->close();
    }
    exit();
}

$mysqli->close();
?>
