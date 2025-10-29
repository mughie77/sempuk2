<?php
// /core/auth_check.php

// 1. Mulai atau lanjutkan sesi yang ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Sertakan koneksi database
require_once __DIR__ . '/db_connect.php';

// 3. Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['token_sesi'])) {
    // Jika tidak, arahkan ke halaman login dengan pesan error
    header("Location: /login.php?error=denied");
    exit();
}

// 4. Implementasi Keamanan Single-Device Login
$user_id = $_SESSION['user_id'];
$token_sesi = $_SESSION['token_sesi'];

$sql = "SELECT token_sesi_aktif FROM users WHERE user_id = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($token_db);
    $stmt->fetch();
    $stmt->close();

    // Bandingkan token dari sesi dengan token di database
    if ($token_sesi !== $token_db || $token_db === null) {
        // Jika tidak cocok, berarti ada login dari perangkat lain
        // Hancurkan sesi saat ini
        session_unset();
        session_destroy();

        // Arahkan ke halaman login dengan pesan error multilogin
        header("Location: /login.php?error=multilogin");
        exit();
    }
} else {
    // Gagal menyiapkan query, hancurkan sesi sebagai tindakan pengamanan
    session_unset();
    session_destroy();
    header("Location: /login.php?error=denied");
    exit();
}


// 5. Fungsi untuk Role-Based Access Control (RBAC)
/**
 * Memeriksa apakah peran pengguna saat ini cocok dengan salah satu peran yang diizinkan.
 * Jika tidak cocok, skrip akan berhenti dan menampilkan pesan akses ditolak.
 *
 * @param array $allowed_roles Array yang berisi string peran yang diizinkan. Contoh: ['Administrator', 'Guru']
 */
function require_role(array $allowed_roles) {
    // Pastikan peran pengguna ada di sesi
    if (!isset($_SESSION['role'])) {
        http_response_code(403);
        die("<h1>Akses Ditolak</h1><p>Peran pengguna tidak ditemukan. Silakan login kembali.</p>");
    }

    $current_role = $_SESSION['role'];

    // Periksa apakah peran saat ini ada di dalam array peran yang diizinkan
    if (!in_array($current_role, $allowed_roles)) {
        // Jika tidak, tampilkan halaman akses ditolak
        http_response_code(403); // Set status 'Forbidden'

        // Tampilkan halaman error yang lebih ramah pengguna
        include(__DIR__ . '/../includes/header.php');
        echo '<div class="container-fluid px-4">';
        echo '  <div class="alert alert-danger mt-4">';
        echo '      <h4>Akses Ditolak</h4>';
        echo '      <p>Anda tidak memiliki izin untuk mengakses halaman ini. Peran Anda adalah <strong>' . htmlspecialchars($current_role) . '</strong>, ';
        echo '      sedangkan halaman ini hanya bisa diakses oleh: <strong>' . htmlspecialchars(implode(', ', $allowed_roles)) . '</strong>.</p>';
        echo '  </div>';
        echo '  <a href="/dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>';
        echo '</div>';
        include(__DIR__ . '/../includes/footer.php');
        exit(); // Hentikan eksekusi skrip
    }
}
?>
