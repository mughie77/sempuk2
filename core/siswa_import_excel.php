<?php
// /core/siswa_import_excel.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/../includes/lib/simplexlsx.class.php';

function redirect_with_message($message, $type = 'success') {
    $_SESSION['flash_message'] = ['message' => $message, 'type' => $type];
    header("Location: " . BASE_URL . "pages/admin_manage_siswa.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
    redirect_with_message('Silakan pilih file Excel untuk diunggah.', 'danger');
}

$file = $_FILES['excel_file'];
$allowed_mimes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
if (!in_array($file['type'], $allowed_mimes)) {
    redirect_with_message('Hanya file .xlsx yang diizinkan.', 'danger');
}

if ($xlsx = SimpleXLSX::parse($file['tmp_name'])) {
    $rows = $xlsx->rows();
    $header = array_shift($rows);
    $success_count = 0;
    $error_count = 0;
    $errors = [];

    $mysqli->begin_transaction();
    try {
        foreach ($rows as $i => $data) {
            $row_num = $i + 2;
            $row = array_combine($header, $data);

            $nis = $row['nis'] ?? null;
            $nama_lengkap = $row['nama_lengkap'] ?? null;

            if (empty($nis) || empty($nama_lengkap)) {
                $errors[] = "Baris $row_num: NIS atau Nama Lengkap kosong.";
                $error_count++;
                continue;
            }

            try {
                // 1. Buat akun user
                $hashed_password = password_hash((string)$nis, PASSWORD_DEFAULT);
                $stmt_user = $mysqli->prepare("INSERT INTO users (username, password, role, nama_lengkap) VALUES (?, ?, 'Siswa', ?)");
                $stmt_user->bind_param("sss", $nis, $hashed_password, $nama_lengkap);
                if (!$stmt_user->execute()) {
                    if ($mysqli->errno === 1062) throw new Exception("Username (NIS) '$nis' sudah ada.");
                    throw new Exception("Gagal membuat akun untuk NIS '$nis'.");
                }
                $user_id = $stmt_user->insert_id;
                $stmt_user->close();

                // 2. Siapkan dan simpan data siswa
                $row['user_id'] = $user_id;
                $allowed_siswa_cols = array_flip(['user_id', 'nis', 'nama_lengkap', 'jk', 'nisn', 'tempat_lahir', 'tanggal_lahir', 'nik', 'agama', 'anak_ke', 'no_akta_lahir', 'no_kk', 'alamat', 'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'kode_pos', 'jenis_tinggal', 'alat_transportasi', 'telepon', 'hp', 'email', 'jarak_sekolah_km', 'berat_badan', 'tinggi_badan', 'lingkar_kepala', 'jml_saudara', 'kebutuhan_khusus', 'sekolah_asal', 'no_peserta_un', 'no_seri_ijazah']);
                $siswa_insert_data = array_intersect_key($row, $allowed_siswa_cols);

                $keys = implode(', ', array_keys($siswa_insert_data));
                $placeholders = implode(', ', array_fill(0, count($siswa_insert_data), '?'));
                $stmt_siswa = $mysqli->prepare("INSERT INTO siswa ($keys) VALUES ($placeholders)");
                $stmt_siswa->bind_param(str_repeat('s', count($siswa_insert_data)), ...array_values($siswa_insert_data));
                 if (!$stmt_siswa->execute()) {
                    throw new Exception("Gagal menyimpan data siswa untuk NIS '$nis'.");
                }
                $siswa_id = $stmt_siswa->insert_id;
                $stmt_siswa->close();

                // 3. Simpan data orang tua
                $ortu_types = ['ayah', 'ibu', 'wali'];
                $stmt_ortu = $mysqli->prepare("INSERT INTO orang_tua (siswa_id, tipe, nama, nik, tahun_lahir, pendidikan, pekerjaan, penghasilan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                foreach($ortu_types as $type) {
                    if (!empty($row[$type.'_nama'])) {
                        $ortu_tipe = ucfirst($type);
                        $stmt_ortu->bind_param("isssssss", $siswa_id, $ortu_tipe, $row[$type.'_nama'], $row[$type.'_nik'], $row[$type.'_tahun_lahir'], $row[$type.'_pendidikan'], $row[$type.'_pekerjaan'], $row[$type.'_penghasilan']);
                        $stmt_ortu->execute();
                    }
                }
                $stmt_ortu->close();
                $success_count++;
            } catch (Exception $e) {
                $errors[] = "Baris $row_num: " . $e->getMessage();
                $error_count++;
            }
        }

        if ($error_count > 0) {
            throw new Exception("Proses impor selesai dengan beberapa kesalahan.");
        }

        $mysqli->commit();
        redirect_with_message("$success_count data siswa berhasil diimpor.");

    } catch (Exception $e) {
        $mysqli->rollback();
        $error_summary = implode('<br>', array_slice($errors, 0, 5));
        if (count($errors) > 5) $error_summary .= '<br>...dan lainnya.';
        redirect_with_message("Impor gagal: $success_count berhasil, $error_count gagal.<br>Contoh Error:<br>$error_summary", 'danger');
    }
} else {
    redirect_with_message('Gagal membaca file Excel: ' . SimpleXLSX::parseError(), 'danger');
}

$mysqli->close();
?>
