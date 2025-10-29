<?php
// /core/config.php

// --- PENGATURAN URL DASAR APLIKASI ---

// 1. Tentukan protokol (http atau https)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";

// 2. Tentukan nama host (domain)
$host = $_SERVER['HTTP_HOST'];

// 3. Tentukan path subdirektori (jika ada) secara dinamis
// Dapatkan path dari root dokumen server ke direktori di mana file ini berada (/core)
$script_path = str_replace('\\', '/', __DIR__); // Menggunakan __DIR__ untuk path absolut yang andal
$document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

// Hapus path root dokumen dari path skrip untuk mendapatkan path relatif
$base_path = str_replace($document_root, '', $script_path);

// Hapus '/core' dari path untuk mendapatkan direktori root proyek
$base_path = str_replace('/core', '', $base_path);

// Tambahkan slash di akhir jika belum ada
$base_path = rtrim($base_path, '/') . '/';

// 4. Gabungkan semuanya untuk membuat URL dasar
$base_url = $protocol . "://" . $host . $base_path;

// 5. Definisikan konstanta BASE_URL untuk digunakan di seluruh aplikasi
define('BASE_URL', $base_url);

/*
 * Contoh penggunaan:
 * echo BASE_URL;
 * // Output (jika di localhost dalam folder 'sempu'): http://localhost/sempu/
 *
 * echo BASE_URL . 'assets/css/style.css';
 * // Output: http://localhost/sempu/assets/css/style.css
 */
?>
