<?php
// /core/konsentrasi_crud_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_manage_konsentrasi.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$action = $_POST['action'];

if ($action === 'add_konsentrasi') {
    $program_id = $_POST['program_id'];
    $nama_konsentrasi = trim($_POST['nama_konsentrasi']);
    if (empty($program_id) || empty($nama_konsentrasi)) redirect_with_message('Semua field wajib diisi.', 'danger');

    $sql = "INSERT INTO konsentrasi_keahlian (program_id, nama_konsentrasi) VALUES (?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("is", $program_id, $nama_konsentrasi);
        if ($stmt->execute()) {
            redirect_with_message('Konsentrasi keahlian baru berhasil ditambahkan.');
        } else {
            if ($mysqli->errno === 1062) redirect_with_message('Nama konsentrasi keahlian sudah ada.', 'danger');
            redirect_with_message('Gagal menambahkan data: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'edit_konsentrasi') {
    $konsentrasi_id = $_POST['konsentrasi_id'];
    $program_id = $_POST['program_id'];
    $nama_konsentrasi = trim($_POST['nama_konsentrasi']);
    if (empty($konsentrasi_id) || empty($program_id) || empty($nama_konsentrasi)) redirect_with_message('Semua field tidak boleh kosong.', 'danger');

    $sql = "UPDATE konsentrasi_keahlian SET program_id = ?, nama_konsentrasi = ? WHERE konsentrasi_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("isi", $program_id, $nama_konsentrasi, $konsentrasi_id);
        if ($stmt->execute()) {
            redirect_with_message('Data konsentrasi keahlian berhasil diperbarui.');
        } else {
            if ($mysqli->errno === 1062) redirect_with_message('Nama konsentrasi sudah digunakan.', 'danger');
            redirect_with_message('Gagal memperbarui data: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'delete_konsentrasi') {
    $konsentrasi_id = $_POST['konsentrasi_id'];
    $sql = "DELETE FROM konsentrasi_keahlian WHERE konsentrasi_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $konsentrasi_id);
        if ($stmt->execute()) {
            redirect_with_message('Data konsentrasi keahlian berhasil dihapus.');
        } else {
            redirect_with_message('Gagal menghapus data: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'get_konsentrasi_details' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $konsentrasi_id = $_GET['id'];
    $sql = "SELECT konsentrasi_id, program_id, nama_konsentrasi FROM konsentrasi_keahlian WHERE konsentrasi_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $konsentrasi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($data = $result->fetch_assoc()) {
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Data not found']);
        }
        $stmt->close();
    }
    exit();
}

$mysqli->close();
?>
