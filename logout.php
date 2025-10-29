<?php
// /logout.php

// 1. Selalu mulai session untuk mengaksesnya
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Hapus semua variabel session
$_SESSION = array();

// 3. Hancurkan session
if (session_destroy()) {
    // 4. Arahkan ke halaman login setelah logout berhasil
    header("Location: /login.php");
    exit();
} else {
    // Jika ada masalah saat menghancurkan sesi
    echo "Error: Logout gagal.";
}
?>
