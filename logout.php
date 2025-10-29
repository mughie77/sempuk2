<?php
// /logout.php

// Sertakan file inisialisasi untuk memulai session dan memuat config
require_once __DIR__ . '/core/init.php';

// 2. Hapus semua variabel session
$_SESSION = array();

// 3. Hancurkan session
if (session_destroy()) {
    // 4. Arahkan ke halaman login setelah logout berhasil
    header("Location: " . BASE_URL . "login.php");
    exit();
} else {
    // Jika ada masalah saat menghancurkan sesi
    echo "Error: Logout gagal.";
}
?>
