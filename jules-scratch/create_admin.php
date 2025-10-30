<?php
// jules-scratch/create_admin.php

// This script is for one-time use to create an admin user.
// Delete it after use.

require_once __DIR__ . '/../core/db_connect.php';

$username = 'admin';
$password = 'admin123';
$role = 'Administrator';
$nama_lengkap = 'Admin SEMPU';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute the SQL statement
$stmt = $mysqli->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    die("Error preparing statement: " . $mysqli->error);
}

$stmt->bind_param("ssss", $username, $hashed_password, $role, $nama_lengkap);

if ($stmt->execute()) {
    echo "Admin user '$username' created successfully.\n";
} else {
    echo "Error creating admin user: " . $stmt->error . "\n";
}

$stmt->close();
$mysqli->close();
?>