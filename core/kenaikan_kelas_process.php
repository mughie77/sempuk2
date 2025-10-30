<?php
// /core/kenaikan_kelas_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

function redirect_with_message($message, $type = 'success', $kelas_asal_id = null) {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    $url = BASE_URL . "pages/admin_kenaikan_kelas.php";
    if ($kelas_asal_id) {
        $url .= "?kelas_id=" . $kelas_asal_id;
    }
    header("Location: " . $url);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action']) || $_POST['action'] !== 'process_kenaikan') {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$siswa_ids = $_POST['siswa_ids'] ?? [];
$kelas_tujuan_id = $_POST['kelas_tujuan_id'] ?? null;
$kelas_asal_id = (int)$_POST['kelas_asal_id'];

if (empty($siswa_ids) || empty($kelas_tujuan_id)) {
    redirect_with_message('Anda harus memilih setidaknya satu siswa dan satu kelas tujuan atau aksi.', 'danger', $kelas_asal_id);
}

// Tentukan nilai kelas_id yang baru
$new_kelas_id = ($kelas_tujuan_id === 'lulus') ? NULL : (int)$kelas_tujuan_id;

// Buat placeholder untuk query IN ()
$placeholders = implode(',', array_fill(0, count($siswa_ids), '?'));
// Buat tipe data untuk bind_param (semua integer)
$types = str_repeat('i', count($siswa_ids));

$sql = "UPDATE siswa SET kelas_id = ? WHERE siswa_id IN ($placeholders)";

if ($stmt = $mysqli->prepare($sql)) {
    // Tambahkan $new_kelas_id ke awal array parameter
    $params = array_merge([$new_kelas_id], $siswa_ids);
    // Tambahkan tipe data ke awal array parameter
    array_unshift($params, 'i' . $types);

    // Panggil bind_param dengan call_user_func_array
    call_user_func_array([$stmt, 'bind_param'], $params);

    if ($stmt->execute()) {
        $count = $stmt->affected_rows;
        $action_text = ($kelas_tujuan_id === 'lulus') ? 'diluluskan' : 'dipindahkan';
        redirect_with_message("$count siswa berhasil $action_text.", 'success', $kelas_asal_id);
    } else {
        redirect_with_message('Gagal memproses kenaikan kelas: ' . $stmt->error, 'danger', $kelas_asal_id);
    }
    $stmt->close();
} else {
    redirect_with_message('Gagal mempersiapkan query: ' . $mysqli->error, 'danger', $kelas_asal_id);
}

$mysqli->close();
?>
