<?php
// /core/jadwal_upload_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

$upload_dir = __DIR__ . '/../assets/schedules/';

function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_manage_jadwal.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$action = $_POST['action'];
$kelas_id = isset($_POST['kelas_id']) ? (int)$_POST['kelas_id'] : 0;

if ($action === 'upload_jadwal') {
    if ($kelas_id <= 0 || !isset($_FILES['jadwal_file']) || $_FILES['jadwal_file']['error'] !== UPLOAD_ERR_OK) {
        redirect_with_message('Silakan pilih kelas dan file yang valid.', 'danger');
    }

    $file = $_FILES['jadwal_file'];
    $original_filename = basename($file['name']);
    $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

    // Validasi tipe file
    $allowed_types = ['pdf', 'xls', 'xlsx'];
    if (!in_array($file_extension, $allowed_types)) {
        redirect_with_message('Hanya file PDF, XLS, dan XLSX yang diizinkan.', 'danger');
    }

    // Buat nama file unik
    $new_filename = 'jadwal_' . $kelas_id . '_' . time() . '.' . $file_extension;
    $target_path = $upload_dir . $new_filename;

    // Mulai transaksi
    $mysqli->begin_transaction();
    try {
        // Cek apakah sudah ada jadwal untuk kelas ini dan hapus yang lama
        $sql_select = "SELECT file_path FROM jadwal_files WHERE kelas_id = ?";
        $stmt_select = $mysqli->prepare($sql_select);
        $stmt_select->bind_param("i", $kelas_id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        if ($old_file = $result->fetch_assoc()) {
            if (file_exists($upload_dir . $old_file['file_path'])) {
                unlink($upload_dir . $old_file['file_path']);
            }
        }
        $stmt_select->close();

        // Hapus entri lama dari DB
        $sql_delete = "DELETE FROM jadwal_files WHERE kelas_id = ?";
        $stmt_delete = $mysqli->prepare($sql_delete);
        $stmt_delete->bind_param("i", $kelas_id);
        $stmt_delete->execute();
        $stmt_delete->close();

        // Pindahkan file baru
        if (!move_uploaded_file($file['tmp_name'], $target_path)) {
            throw new Exception('Gagal memindahkan file yang diunggah.');
        }

        // Simpan info file baru ke DB
        $sql_insert = "INSERT INTO jadwal_files (kelas_id, file_path, original_filename) VALUES (?, ?, ?)";
        $stmt_insert = $mysqli->prepare($sql_insert);
        // Simpan hanya nama file, bukan path lengkap
        $stmt_insert->bind_param("iss", $kelas_id, $new_filename, $original_filename);
        if (!$stmt_insert->execute()) {
            throw new Exception('Gagal menyimpan informasi file ke database.');
        }
        $stmt_insert->close();

        $mysqli->commit();
        redirect_with_message('Jadwal berhasil diunggah.');

    } catch (Exception $e) {
        $mysqli->rollback();
        if (file_exists($target_path)) unlink($target_path); // Hapus file jika Gagal DB
        redirect_with_message('Terjadi kesalahan: ' . $e->getMessage(), 'danger');
    }
}

elseif ($action === 'delete_jadwal') {
    if ($kelas_id <= 0) redirect_with_message('ID Kelas tidak valid.', 'danger');

    $mysqli->begin_transaction();
    try {
        $sql_select = "SELECT file_path FROM jadwal_files WHERE kelas_id = ?";
        $stmt_select = $mysqli->prepare($sql_select);
        $stmt_select->bind_param("i", $kelas_id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        if ($file_to_delete = $result->fetch_assoc()) {
            if (file_exists($upload_dir . $file_to_delete['file_path'])) {
                unlink($upload_dir . $file_to_delete['file_path']);
            }
        }
        $stmt_select->close();

        $sql_delete = "DELETE FROM jadwal_files WHERE kelas_id = ?";
        $stmt_delete = $mysqli->prepare($sql_delete);
        $stmt_delete->bind_param("i", $kelas_id);
        $stmt_delete->execute();
        $stmt_delete->close();

        $mysqli->commit();
        redirect_with_message('Jadwal berhasil dihapus.');

    } catch (Exception $e) {
        $mysqli->rollback();
        redirect_with_message('Gagal menghapus jadwal: ' . $e->getMessage(), 'danger');
    }
}

$mysqli->close();
?>
