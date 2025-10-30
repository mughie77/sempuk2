<?php
// /core/siswa_crud_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_manage_siswa.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['action'])) {
    redirect_with_message('Akses tidak sah.', 'danger');
}

$action = $_POST['action'];

if ($action === 'add_siswa' || $action === 'edit_siswa') {
    // Kumpulkan semua data dari form
    $siswa_fields = [
        'nama_lengkap', 'nis', 'jk', 'nisn', 'tempat_lahir', 'tanggal_lahir', 'nik', 'agama',
        'anak_ke', 'no_akta_lahir', 'no_kk', 'alamat', 'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan',
        'kode_pos', 'jenis_tinggal', 'alat_transportasi', 'telepon', 'hp', 'email', 'lintang', 'bujur',
        'jarak_sekolah_km', 'berat_badan', 'tinggi_badan', 'lingkar_kepala', 'jml_saudara', 'kebutuhan_khusus',
        'sekolah_asal', 'no_peserta_un', 'no_seri_ijazah'
    ];
    $siswa_data = [];
    foreach($siswa_fields as $field) {
        $siswa_data[$field] = !empty($_POST[$field]) ? $_POST[$field] : NULL;
    }

    $ortu_fields = ['nama', 'nik', 'tahun_lahir', 'pendidikan', 'pekerjaan', 'penghasilan'];
    $ayah_data = [];
    foreach($ortu_fields as $field) $ayah_data[$field] = !empty($_POST['ayah_'.$field]) ? $_POST['ayah_'.$field] : NULL;
    $ibu_data = [];
    foreach($ortu_fields as $field) $ibu_data[$field] = !empty($_POST['ibu_'.$field]) ? $_POST['ibu_'.$field] : NULL;

    // Mulai transaksi
    $mysqli->begin_transaction();

    try {
        if ($action === 'add_siswa') {
            // Logika Tambah Siswa
            if (empty($siswa_data['nis']) || empty($siswa_data['nama_lengkap'])) {
                throw new Exception('NIS dan Nama Lengkap wajib diisi.');
            }

            // 1. Buat akun user
            $hashed_password = password_hash($siswa_data['nis'], PASSWORD_DEFAULT);
            $stmt_user = $mysqli->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, 'Siswa', ?)");
            $stmt_user->bind_param("sss", $siswa_data['nis'], $hashed_password, $siswa_data['nama_lengkap']);
            if (!$stmt_user->execute()) {
                if ($mysqli->errno === 1062) throw new Exception('Username (NIS) sudah ada.');
                throw new Exception('Gagal membuat akun user: ' . $stmt_user->error);
            }
            $user_id = $stmt_user->insert_id;
            $stmt_user->close();

            // 2. Simpan data siswa
            $siswa_data['user_id'] = $user_id;
            $keys = implode(', ', array_keys($siswa_data));
            $placeholders = implode(', ', array_fill(0, count($siswa_data), '?'));
            $stmt_siswa = $mysqli->prepare("INSERT INTO siswa ($keys) VALUES ($placeholders)");
            $stmt_siswa->bind_param(str_repeat('s', count($siswa_data)), ...array_values($siswa_data));
             if (!$stmt_siswa->execute()) {
                if ($mysqli->errno === 1062) throw new Exception('NIS sudah terdaftar.');
                throw new Exception('Gagal menyimpan data siswa: ' . $stmt_siswa->error);
            }
            $siswa_id = $stmt_siswa->insert_id;
            $stmt_siswa->close();

        } else { // Edit Siswa
            $siswa_id = (int)$_POST['siswa_id'];
            if (empty($siswa_id)) throw new Exception('ID Siswa tidak valid.');

            // Update data siswa
            $update_fields = [];
            foreach($siswa_data as $key => $value) $update_fields[] = "$key = ?";
            $update_query = implode(', ', $update_fields);
            $stmt_siswa = $mysqli->prepare("UPDATE siswa SET $update_query WHERE siswa_id = ?");
            $params = array_values($siswa_data);
            $params[] = $siswa_id;
            $stmt_siswa->bind_param(str_repeat('s', count($siswa_data)) . 'i', ...$params);
            if (!$stmt_siswa->execute()) {
                if ($mysqli->errno === 1062) throw new Exception('NIS sudah digunakan siswa lain.');
                throw new Exception('Gagal update data siswa: ' . $stmt_siswa->error);
            }
            $stmt_siswa->close();
        }

        // 3. Simpan/Update data orang tua
        // Hapus data ortu lama (untuk edit) agar mudah
        if ($action === 'edit_siswa') {
            $stmt_delete_ortu = $mysqli->prepare("DELETE FROM orang_tua WHERE siswa_id = ?");
            $stmt_delete_ortu->bind_param("i", $siswa_id);
            $stmt_delete_ortu->execute();
            $stmt_delete_ortu->close();
        }

        $stmt_ortu = $mysqli->prepare("INSERT INTO orang_tua (siswa_id, tipe, nama, nik, tahun_lahir, pendidikan, pekerjaan, penghasilan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!empty($ayah_data['nama'])) {
            $tipe = 'Ayah';
            $stmt_ortu->bind_param("isssssss", $siswa_id, $tipe, ...array_values($ayah_data));
            $stmt_ortu->execute();
        }
        if (!empty($ibu_data['nama'])) {
            $tipe = 'Ibu';
            $stmt_ortu->bind_param("isssssss", $siswa_id, $tipe, ...array_values($ibu_data));
            $stmt_ortu->execute();
        }
        $stmt_ortu->close();

        $mysqli->commit();
        redirect_with_message($action === 'add_siswa' ? 'Data siswa berhasil ditambahkan.' : 'Data siswa berhasil diperbarui.');

    } catch (Exception $e) {
        $mysqli->rollback();
        $_SESSION['flash_message'] = ['message' => $e->getMessage(), 'type' => 'danger'];
        // Redirect kembali ke form dengan data yang sudah diisi
        header("Location: " . BASE_URL . "pages/admin_siswa_form.php" . ($action === 'edit_siswa' ? '?edit_id='.$siswa_id : ''));
        exit();
    }
}

elseif ($action === 'delete_siswa') {
    // Logika Hapus Siswa (beserta akun user dan data ortu)
    $siswa_id = (int)$_POST['siswa_id'];
    $mysqli->begin_transaction();
    try {
        $stmt_get_user = $mysqli->prepare("SELECT user_id FROM siswa WHERE siswa_id = ?");
        $stmt_get_user->bind_param("i", $siswa_id);
        $stmt_get_user->execute();
        $user = $stmt_get_user->get_result()->fetch_assoc();
        $stmt_get_user->close();

        // Hapus dari siswa (otomatis akan menghapus dari orang_tua via CASCADE)
        $stmt_siswa = $mysqli->prepare("DELETE FROM siswa WHERE siswa_id = ?");
        $stmt_siswa->bind_param("i", $siswa_id);
        $stmt_siswa->execute();
        $stmt_siswa->close();

        // Hapus dari users
        if ($user && $user['user_id']) {
            $stmt_user = $mysqli->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt_user->bind_param("i", $user['user_id']);
            $stmt_user->execute();
            $stmt_user->close();
        }

        $mysqli->commit();
        redirect_with_message('Data siswa berhasil dihapus.');
    } catch (Exception $e) {
        $mysqli->rollback();
        redirect_with_message('Gagal menghapus siswa: ' . $e->getMessage(), 'danger');
    }
}

$mysqli->close();
?>
