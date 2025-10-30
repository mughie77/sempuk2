<?php
// /core/download_template.php

require_once __DIR__ . '/../includes/lib/xlsxwriter.class.php';

// Definisikan semua header kolom yang baru
$header = [
    "nis" => "string", "nama_lengkap" => "string", "nama_panggilan" => "string", "jk" => "string", "nisn" => "string",
    "tempat_lahir" => "string", "tanggal_lahir" => "YYYY-MM-DD", "agama" => "string", "kewarganegaraan" => "string",
    "anak_ke" => "integer", "jml_saudara_kandung" => "integer", "jml_saudara_tiri" => "integer", "jml_saudara_angkat" => "integer",
    "status_yatim" => "string", "bahasa_sehari_hari" => "string", "alamat" => "string", "telepon" => "string",
    "tinggal_dengan" => "string", "jarak_ke_sekolah" => "string", "golongan_darah" => "string",
    "penyakit_diderita" => "string", "kelainan_jasmani" => "string", "tinggi_badan" => "integer", "berat_badan" => "integer",
    "ayah_nama" => "string", "ayah_tempat_lahir" => "string", "ayah_tanggal_lahir" => "YYYY-MM-DD", "ayah_agama" => "string",
    "ayah_kewarganegaraan" => "string", "ayah_pendidikan" => "string", "ayah_pekerjaan" => "string",
    "ayah_pengeluaran_perbulan" => "string", "ayah_alamat" => "string", "ayah_status_hidup" => "string",
    "ibu_nama" => "string", "ibu_tempat_lahir" => "string", "ibu_tanggal_lahir" => "YYYY-MM-DD", "ibu_agama" => "string",
    "ibu_kewarganegaraan" => "string", "ibu_pendidikan" => "string", "ibu_pekerjaan" => "string",
    "ibu_pengeluaran_perbulan" => "string", "ibu_alamat" => "string", "ibu_status_hidup" => "string",
    "wali_nama" => "string"
];

$writer = new XLSXWriter();
$writer->writeSheetHeader('Siswa', $header);

$filename = "template_import_siswa_lengkap.xlsx";
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer->writeToStdOut();

exit(0);
