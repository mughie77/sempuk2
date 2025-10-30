<?php
// /core/tahun_pelajaran_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_manage_tahun_pelajaran.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$action = $_POST['action'];

if ($action === 'add') {
    $tahun_ajaran = trim($_POST['tahun_ajaran']);
    if (empty($tahun_ajaran)) redirect_with_message('Tahun Ajaran tidak boleh kosong.', 'danger');

    $sql = "INSERT INTO tahun_pelajaran (tahun_ajaran) VALUES (?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $tahun_ajaran);
        if ($stmt->execute()) {
            redirect_with_message('Tahun Pelajaran baru berhasil ditambahkan.');
        } else {
            if ($mysqli->errno === 1062) redirect_with_message('Tahun Ajaran sudah ada.', 'danger');
            redirect_with_message('Gagal menambahkan data: ' . $stmt->error, 'danger');
        }
    }
}

elseif ($action === 'set_active') {
    $id = (int)$_POST['tahun_pelajaran_id'];

    $mysqli->begin_transaction();
    try {
        // 1. Set semua menjadi 'Tidak Aktif'
        $mysqli->query("UPDATE tahun_pelajaran SET status = 'Tidak Aktif'");

        // 2. Set yang dipilih menjadi 'Aktif'
        $stmt = $mysqli->prepare("UPDATE tahun_pelajaran SET status = 'Aktif' WHERE tahun_pelajaran_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $mysqli->commit();
        redirect_with_message('Tahun Pelajaran aktif telah berhasil diubah.');
    } catch (Exception $e) {
        $mysqli->rollback();
        redirect_with_message('Gagal mengubah status: ' . $e->getMessage(), 'danger');
    }
}

elseif ($action === 'delete') {
    $id = (int)$_POST['tahun_pelajaran_id'];
    // Validasi: jangan biarkan menghapus tahun ajaran yang aktif
    $check = $mysqli->query("SELECT status FROM tahun_pelajaran WHERE tahun_pelajaran_id = $id")->fetch_assoc();
    if ($check && $check['status'] === 'Aktif') {
        redirect_with_message('Tidak dapat menghapus tahun pelajaran yang sedang aktif.', 'danger');
    }

    $sql = "DELETE FROM tahun_pelajaran WHERE tahun_pelajaran_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            redirect_with_message('Tahun Pelajaran berhasil dihapus.');
        } else {
            redirect_with_message('Gagal menghapus data: ' . $stmt->error, 'danger');
        }
    }
}

$mysqli->close();
?>
