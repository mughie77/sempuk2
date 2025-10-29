<?php
// /core/jadwal_crud_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_manage_jadwal.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$action = $_POST['action'];

if ($action === 'add_jadwal') {
    $kelas_id = $_POST['kelas_id'];
    $mapel_id = $_POST['mapel_id'];
    $guru_id = $_POST['guru_id'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    if (empty($kelas_id) || empty($mapel_id) || empty($guru_id) || empty($hari) || empty($jam_mulai) || empty($jam_selesai)) {
        redirect_with_message('Semua field wajib diisi.', 'danger');
    }

    $sql = "INSERT INTO jadwal_pelajaran (kelas_id, mapel_id, guru_id, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("iiisss", $kelas_id, $mapel_id, $guru_id, $hari, $jam_mulai, $jam_selesai);
        if ($stmt->execute()) {
            redirect_with_message('Jadwal baru berhasil ditambahkan.');
        } else {
            redirect_with_message('Gagal menambahkan jadwal: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'edit_jadwal') {
    $jadwal_id = $_POST['jadwal_id'];
    $kelas_id = $_POST['kelas_id'];
    $mapel_id = $_POST['mapel_id'];
    $guru_id = $_POST['guru_id'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    if (empty($jadwal_id) || empty($kelas_id) || empty($mapel_id) || empty($guru_id) || empty($hari) || empty($jam_mulai) || empty($jam_selesai)) {
        redirect_with_message('Semua field tidak boleh kosong.', 'danger');
    }

    $sql = "UPDATE jadwal_pelajaran SET kelas_id = ?, mapel_id = ?, guru_id = ?, hari = ?, jam_mulai = ?, jam_selesai = ? WHERE jadwal_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("iiisssi", $kelas_id, $mapel_id, $guru_id, $hari, $jam_mulai, $jam_selesai, $jadwal_id);
        if ($stmt->execute()) {
            redirect_with_message('Data jadwal berhasil diperbarui.');
        } else {
            redirect_with_message('Gagal memperbarui data jadwal: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'delete_jadwal') {
    $jadwal_id = $_POST['jadwal_id'];
    $sql = "DELETE FROM jadwal_pelajaran WHERE jadwal_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $jadwal_id);
        if ($stmt->execute()) {
            redirect_with_message('Data jadwal berhasil dihapus.');
        } else {
            redirect_with_message('Gagal menghapus data jadwal: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

elseif ($action === 'get_jadwal_details' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $jadwal_id = $_GET['id'];
    $sql = "SELECT jadwal_id, kelas_id, mapel_id, guru_id, hari, jam_mulai, jam_selesai FROM jadwal_pelajaran WHERE jadwal_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $jadwal_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($jadwal = $result->fetch_assoc()) {
            echo json_encode($jadwal);
        } else {
            echo json_encode(['error' => 'Jadwal not found']);
        }
        $stmt->close();
    }
    exit();
}

$mysqli->close();
?>
