<?php
// /index.php

// Mulai session untuk memeriksa status login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah pengguna sudah login (berdasarkan keberadaan 'user_id' di session)
if (isset($_SESSION['user_id'])) {
    // Jika sudah login, arahkan ke halaman dashboard
    header("Location: /dashboard.php");
    exit();
} else {
    // Jika belum login, arahkan ke halaman login
    header("Location: /login.php");
    exit();
}
?>
