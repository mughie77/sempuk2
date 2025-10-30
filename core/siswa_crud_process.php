<?php
// /core/siswa_crud_process.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/db_connect.php';

// ... (fungsi redirect)

if ($action === 'add_siswa' || $action === 'edit_siswa') {

    // ... (pengumpulan data siswa)

    $mysqli->begin_transaction();
    try {
        // ... (logika tambah/edit siswa utama)

        // Hapus data lama dari tabel terkait
        // ... (logika hapus)

        // Insert data orang tua
        $ortu_types = ['ayah', 'ibu', 'wali'];
        $stmt_ortu = $mysqli->prepare("INSERT INTO orang_tua (siswa_id, tipe, nama, tempat_lahir, tanggal_lahir, agama, kewarganegaraan, pendidikan, pekerjaan, pengeluaran_perbulan, alamat, status_hidup) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($ortu_types as $type) {
            if (!empty($_POST[$type.'_nama'])) {
                $tipe_enum = ucfirst($type);
                $stmt_ortu->bind_param("isssssssssss", $siswa_id, $tipe_enum, $_POST[$type.'_nama'], $_POST[$type.'_tempat_lahir'], $_POST[$type.'_tanggal_lahir'], $_POST[$type.'_agama'], $_POST[$type.'_kewarganegaraan'], $_POST[$type.'_pendidikan'], $_POST[$type.'_pekerjaan'], $_POST[$type.'_pengeluaran_perbulan'], $_POST[$type.'_alamat'], $_POST[$type.'_status_hidup']);
                $stmt_ortu->execute();
            }
        }
        $stmt_ortu->close();

        // Insert data lain
        $stmt_pendidikan = $mysqli->prepare("INSERT INTO pendidikan_sebelumnya (siswa_id, nama_sekolah, lama_belajar, tgl_ijazah, no_ijazah, tgl_skhun, no_skhun) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_pendidikan->bind_param("issssss", $siswa_id, $_POST['pendidikan_nama_sekolah'], $_POST['pendidikan_lama_belajar'], $_POST['pendidikan_tgl_ijazah'], $_POST['pendidikan_no_ijazah'], $_POST['pendidikan_tgl_skhun'], $_POST['pendidikan_no_skhun']);
        $stmt_pendidikan->execute();
        $stmt_pendidikan->close();

        // ... (dan seterusnya untuk tabel-tabel lain)

        $mysqli->commit();
        redirect_with_message('Data siswa berhasil disimpan.');

    } catch (Exception $e) {
        // ... (blok catch)
    }
}
?>
