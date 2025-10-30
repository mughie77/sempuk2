<?php
// /core/download_template.php

// Panggil file library
require_once __DIR__ . '/../includes/lib/xlsxwriter.class.php';

// Definisikan header kolom sesuai dengan yang diharapkan oleh prosesor impor
$header = [
    "nis" => "string", "nama_lengkap" => "string", "jk" => "string", "nisn" => "string",
    "tempat_lahir" => "string", "tanggal_lahir" => "YYYY-MM-DD", "nik" => "string", "agama" => "string",
    "anak_ke" => "integer", "no_akta_lahir" => "string", "no_kk" => "string", "alamat" => "string",
    "rt" => "string", "rw" => "string", "dusun" => "string", "kelurahan" => "string", "kecamatan" => "string",
    "kode_pos" => "string", "jenis_tinggal" => "string", "alat_transportasi" => "string", "telepon" => "string",
    "hp" => "string", "email" => "string", "jarak_sekolah_km" => "integer", "berat_badan" => "integer",
    "tinggi_badan" => "integer", "lingkar_kepala" => "integer", "jml_saudara" => "integer", "kebutuhan_khusus" => "string",
    "sekolah_asal" => "string", "no_peserta_un" => "string", "no_seri_ijazah" => "string",
    "ayah_nama" => "string", "ayah_nik" => "string", "ayah_tahun_lahir" => "YYYY", "ayah_pendidikan" => "string",
    "ayah_pekerjaan" => "string", "ayah_penghasilan" => "string", "ibu_nama" => "string", "ibu_nik" => "string",
    "ibu_tahun_lahir" => "YYYY", "ibu_pendidikan" => "string", "ibu_pekerjaan" => "string", "ibu_penghasilan" => "string",
    "wali_nama" => "string"
];

// Inisialisasi writer
$writer = new XLSXWriter();
$writer->writeSheetHeader('Siswa', $header);

// Siapkan header untuk download file
$filename = "template_import_siswa.xlsx";
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

// Tulis file ke output stream
$writer->writeToStdOut();

exit(0);
