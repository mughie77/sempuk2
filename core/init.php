<?php
// /core/init.php

// 1. Sertakan file konfigurasi (termasuk BASE_URL)
require_once __DIR__ . '/config.php';

// 2. Mulai session jika belum ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tambahkan konfigurasi atau inisialisasi lain di sini di masa depan
// Misalnya, pengaturan zona waktu, autoloader, dll.
// date_default_timezone_set('Asia/Jakarta');

?>
