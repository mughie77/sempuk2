<?php
// /core/program_crud_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_manage_program.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$action = $_POST['action'];

if ($action === 'add_program') {
    $nama_program = trim($_POST['nama_program']);
    $deskripsi = trim($_POST['deskripsi']);
    if (empty($nama_program)) redirect_with_message('Nama Program Keahlian tidak boleh kosong.', 'danger');

    $sql = "INSERT INTO program_keahlian (nama_program, deskripsi) VALUES (?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ss", $nama_program, $deskripsi);
        if ($stmt->execute()) {
            redirect_with_message('Program keahlian baru berhasil ditambahkan.');
        } else {
            if ($mysqli->errno === 1062) redirect_with_message('Nama program keahlian sudah ada.', 'danger');
            redirect_with_message('Gagal menambahkan program: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'edit_program') {
    $program_id = $_POST['program_id'];
    $nama_program = trim($_POST['nama_program']);
    $deskripsi = trim($_POST['deskripsi']);
    if (empty($program_id) || empty($nama_program)) redirect_with_message('ID dan Nama Program tidak boleh kosong.', 'danger');

    $sql = "UPDATE program_keahlian SET nama_program = ?, deskripsi = ? WHERE program_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssi", $nama_program, $deskripsi, $program_id);
        if ($stmt->execute()) {
            redirect_with_message('Data program keahlian berhasil diperbarui.');
        } else {
            if ($mysqli->errno === 1062) redirect_with_message('Nama program sudah digunakan.', 'danger');
            redirect_with_message('Gagal memperbarui data: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'delete_program') {
    $program_id = $_POST['program_id'];
    $sql = "DELETE FROM program_keahlian WHERE program_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $program_id);
        if ($stmt->execute()) {
            redirect_with_message('Data program keahlian berhasil dihapus.');
        } else {
            redirect_with_message('Gagal menghapus data: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'get_program_details' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $program_id = $_GET['id'];
    $sql = "SELECT program_id, nama_program, deskripsi FROM program_keahlian WHERE program_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $program_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($program = $result->fetch_assoc()) {
            echo json_encode($program);
        } else {
            echo json_encode(['error' => 'Program not found']);
        }
        $stmt->close();
    }
    exit();
}

$mysqli->close();
?>
