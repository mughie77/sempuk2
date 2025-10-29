<?php
// /index.php

// Sertakan file inisialisasi
require_once __DIR__ . '/core/init.php';

// Cek apakah pengguna sudah login (berdasarkan keberadaan 'user_id' di session)
if (isset($_SESSION['user_id'])) {
    // Jika sudah login, arahkan ke halaman dashboard
    header("Location: " . BASE_URL . "dashboard.php");
    exit();
} else {
    // Jika belum login, arahkan ke halaman login
    header("Location: " . BASE_URL . "login.php");
    exit();
}
?>
