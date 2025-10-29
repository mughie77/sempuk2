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

    // Mulai transaksi
    $mysqli->begin_transaction();

    try {
        // 1. Buat akun di tabel 'users'
        $username = $nip;
        $password = $nip; // Password awal sama dengan NIP
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'Guru'; // Peran default bisa 'Guru' atau 'GuruMapel', sesuaikan

        $sql_user = "INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, ?, ?)";
        $stmt_user = $mysqli->prepare($sql_user);
        $stmt_user->bind_param("ssss", $username, $hashed_password, $role, $nama_lengkap);

        if (!$stmt_user->execute()) {
            if ($mysqli->errno === 1062) {
                throw new Exception('Gagal membuat akun: Username (NIP) sudah digunakan.');
            }
            throw new Exception('Gagal membuat akun pengguna: ' . $stmt_user->error);
        }

        $user_id = $stmt_user->insert_id;
        $stmt_user->close();

        // 2. Buat data di tabel 'guru'
        $sql_guru = "INSERT INTO guru (user_id, nip, nama_lengkap, alamat, telepon) VALUES (?, ?, ?, ?, ?)";
        $stmt_guru = $mysqli->prepare($sql_guru);
        $stmt_guru->bind_param("issss", $user_id, $nip, $nama_lengkap, $alamat, $telepon);

        if (!$stmt_guru->execute()) {
            if ($mysqli->errno === 1062) {
                 throw new Exception('Gagal menyimpan data: NIP sudah terdaftar.');
            }
            throw new Exception('Gagal menyimpan data guru: ' . $stmt_guru->error);
        }
        $stmt_guru->close();

        // Commit transaksi
        $mysqli->commit();
        redirect_with_message('Data guru baru dan akun login berhasil dibuat.');

    } catch (Exception $e) {
        // Rollback jika ada error
        $mysqli->rollback();
        redirect_with_message($e->getMessage(), 'danger');
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
