<?php
// /core/user_crud_process.php

// 1. Sertakan file auth_check.php dan batasi akses hanya untuk Administrator
require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);

// 2. Sertakan koneksi database
require_once __DIR__ . '/db_connect.php';

// Fungsi untuk mengarahkan kembali dengan pesan status
function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];
    header("Location: " . BASE_URL . "pages/admin_manage_users.php");
    exit();
}

// Pastikan request adalah POST
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$action = $_POST['action'];

// 3. Logika untuk Menambah Pengguna Baru
if ($action === 'add_user') {
    // Ambil data dari form
    $username = trim($_POST['username']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Validasi dasar
    if (empty($username) || empty($nama_lengkap) || empty($role) || empty($password)) {
        redirect_with_message('Semua field wajib diisi.', 'danger');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query menggunakan prepared statement
    $sql = "INSERT INTO users (username, nama_lengkap, role, password) VALUES (?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssss", $username, $nama_lengkap, $role, $hashed_password);
        if ($stmt->execute()) {
            redirect_with_message('Pengguna baru berhasil ditambahkan.');
        } else {
            // Cek jika username sudah ada
            if ($mysqli->errno === 1062) {
                 redirect_with_message('Username sudah digunakan. Silakan pilih username lain.', 'danger');
            }
            redirect_with_message('Gagal menambahkan pengguna: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

// 4. Logika untuk Memperbarui Pengguna
elseif ($action === 'edit_user') {
    // Ambil data dari form
    $user_id = $_POST['user_id'];
    $username = trim($_POST['username']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Validasi dasar
    if (empty($user_id) || empty($username) || empty($nama_lengkap) || empty($role)) {
        redirect_with_message('Field ID, Username, Nama, dan Role tidak boleh kosong.', 'danger');
    }

    // Cek apakah password diisi atau tidak
    if (!empty($password)) {
        // Jika password diisi, update password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = ?, nama_lengkap = ?, role = ?, password = ? WHERE user_id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ssssi", $username, $nama_lengkap, $role, $hashed_password, $user_id);
        }
    } else {
        // Jika password kosong, jangan update password
        $sql = "UPDATE users SET username = ?, nama_lengkap = ?, role = ? WHERE user_id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sssi", $username, $nama_lengkap, $role, $user_id);
        }
    }

    if (isset($stmt)) {
        if ($stmt->execute()) {
            redirect_with_message('Data pengguna berhasil diperbarui.');
        } else {
            if ($mysqli->errno === 1062) {
                 redirect_with_message('Username sudah digunakan oleh pengguna lain.', 'danger');
            }
            redirect_with_message('Gagal memperbarui data: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

// 5. Logika untuk Menghapus Pengguna
elseif ($action === 'delete_user') {
    $user_id = $_POST['user_id'];

    // Jangan biarkan admin menghapus dirinya sendiri
    if ($user_id == $_SESSION['user_id']) {
        redirect_with_message('Anda tidak dapat menghapus akun Anda sendiri.', 'danger');
    }

    $sql = "DELETE FROM users WHERE user_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            redirect_with_message('Pengguna berhasil dihapus.');
        } else {
            redirect_with_message('Gagal menghapus pengguna: ' . $stmt->error, 'danger');
        }
        $stmt->close();
    }
}

// 6. Logika untuk Mengambil Data Pengguna (untuk form edit via AJAX)
elseif ($action === 'get_user_details' && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $user_id = $_GET['id'];
    $sql = "SELECT user_id, username, nama_lengkap, role FROM users WHERE user_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
        $stmt->close();
    }
    exit(); // Hentikan eksekusi setelah mengirim JSON
}


// Tutup koneksi
$mysqli->close();
?>
