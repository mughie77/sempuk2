<?php
// /core/login_process.php

// 1. Mulai Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Sertakan file koneksi database
require_once 'db_connect.php';

// 3. Pastikan request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 4. Ambil dan bersihkan input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // 5. Query menggunakan Prepared Statement untuk keamanan SQL Injection
    $sql = "SELECT user_id, username, password, role, nama_lengkap FROM users WHERE username = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variabel ke prepared statement
        $stmt->bind_param("s", $username);

        // Eksekusi statement
        if ($stmt->execute()) {
            // Simpan hasil
            $stmt->store_result();

            // Cek jika username ada
            if ($stmt->num_rows == 1) {
                // Bind hasil ke variabel
                $stmt->bind_result($user_id, $username, $hashed_password, $role, $nama_lengkap);

                if ($stmt->fetch()) {
                    // 6. Verifikasi password
                    if (password_verify($password, $hashed_password)) {

                        // 7. Jika password benar, mulai proses sesi

                        // Hancurkan sesi lama dan buat yang baru (mencegah session fixation)
                        session_destroy();
                        session_start();
                        session_regenerate_id(true);

                        // Buat token unik untuk keamanan Single-Device Login
                        $token = bin2hex(random_bytes(32));

                        // Simpan informasi pengguna ke dalam session
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['username'] = $username;
                        $_SESSION['nama_lengkap'] = $nama_lengkap;
                        $_SESSION['role'] = $role;
                        $_SESSION['token_sesi'] = $token;

                        // Simpan token ke database
                        $update_sql = "UPDATE users SET token_sesi_aktif = ? WHERE user_id = ?";
                        if ($update_stmt = $mysqli->prepare($update_sql)) {
                            $update_stmt->bind_param("si", $token, $user_id);
                            $update_stmt->execute();
                            $update_stmt->close();
                        }

                        // Arahkan ke dashboard
                        header("location: /dashboard.php");
                        exit();

                    } else {
                        // Password salah
                        header("location: /login.php?error=invalid");
                        exit();
                    }
                }
            } else {
                // Username tidak ditemukan
                header("location: /login.php?error=invalid");
                exit();
            }
        } else {
            // Gagal eksekusi query
            echo "Terjadi kesalahan. Silakan coba lagi nanti.";
        }
        // Tutup statement
        $stmt->close();
    }
    // Tutup koneksi
    $mysqli->close();
} else {
    // Jika bukan POST, arahkan ke halaman login
    header("location: /login.php");
    exit();
}
?>
