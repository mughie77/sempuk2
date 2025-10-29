<?php
// /core/db_connect.php

// Konfigurasi Database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Ganti dengan username database Anda
define('DB_PASSWORD', ''); // Ganti dengan password database Anda
define('DB_NAME', 'sempu');

// Buat koneksi menggunakan MySQLi
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Periksa koneksi
if ($mysqli->connect_errno) {
    // Jika koneksi gagal, hentikan skrip dan tampilkan pesan error
    // Sebaiknya tidak menampilkan error detail di lingkungan produksi
    die("ERROR: Gagal terhubung ke database. " . $mysqli->connect_error);
}

// Atur character set ke utf8mb4 untuk mendukung karakter internasional
$mysqli->set_charset("utf8mb4");
?>
