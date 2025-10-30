<?php
// /core/generate_pdf_buku_induk.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../includes/lib/fpdf/fpdf.php';

// Function to fetch all data for a single student
function get_student_details($mysqli, $siswa_id) {
    $data = [];
    $stmt = $mysqli->prepare("
        SELECT s.*, k.nama_kelas, kk.nama_konsentrasi, pk.nama_program
        FROM siswa s
        LEFT JOIN kelas k ON s.kelas_id = k.kelas_id
        LEFT JOIN konsentrasi_keahlian kk ON k.konsentrasi_id = kk.konsentrasi_id
        LEFT JOIN program_keahlian pk ON kk.program_id = pk.program_id
        WHERE s.siswa_id = ?
    ");
    $stmt->bind_param("i", $siswa_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data['siswa'] = $result->fetch_assoc();
    $stmt->close();

    if (!$data['siswa']) return null;

    // Fetch other related data...
    $data['orang_tua'] = [];
    $stmt_ortu = $mysqli->prepare("SELECT * FROM orang_tua WHERE siswa_id = ?");
    $stmt_ortu->bind_param("i", $siswa_id);
    $stmt_ortu->execute();
    $result_ortu = $stmt_ortu->get_result();
    while ($row = $result_ortu->fetch_assoc()) {
        $data['orang_tua'][$row['tipe']] = $row;
    }
    $stmt_ortu->close();

    $stmt_pendidikan = $mysqli->prepare("SELECT * FROM pendidikan_sebelumnya WHERE siswa_id = ?");
    $stmt_pendidikan->bind_param("i", $siswa_id);
    $stmt_pendidikan->execute();
    $data['pendidikan'] = $stmt_pendidikan->get_result()->fetch_assoc();
    $stmt_pendidikan->close();

    $stmt_kegemaran = $mysqli->prepare("SELECT * FROM kegemaran_siswa WHERE siswa_id = ?");
    $stmt_kegemaran->bind_param("i", $siswa_id);
    $stmt_kegemaran->execute();
    $data['kegemaran'] = $stmt_kegemaran->get_result()->fetch_assoc();
    $stmt_kegemaran->close();

    $stmt_perkembangan = $mysqli->prepare("SELECT * FROM perkembangan_siswa WHERE siswa_id = ?");
    $stmt_perkembangan->bind_param("i", $siswa_id);
    $stmt_perkembangan->execute();
    $data['perkembangan'] = $stmt_perkembangan->get_result()->fetch_assoc();
    $stmt_perkembangan->close();

    $stmt_lulus = $mysqli->prepare("SELECT * FROM info_setelah_lulus WHERE siswa_id = ?");
    $stmt_lulus->bind_param("i", $siswa_id);
    $stmt_lulus->execute();
    $data['lulus'] = $stmt_lulus->get_result()->fetch_assoc();
    $stmt_lulus->close();

    return $data;
}

$siswa_ids = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['siswa_ids'])) {
    $siswa_ids = (array)$_POST['siswa_ids'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['siswa_id'])) {
    $siswa_ids[] = (int)$_GET['siswa_id'];
}

if (empty($siswa_ids)) {
    die("Tidak ada siswa yang dipilih.");
}

class PDF extends FPDF
{
    private $nomor_induk;

    function SetNomorInduk($nomor_induk) {
        $this->nomor_induk = $nomor_induk;
    }

    function Header() {
        $this->SetFont('Times', 'B', 12);
        $this->Cell(0, 6, 'BUKU INDUK PESERTA DIDIK', 0, 1, 'C');
        $this->SetFont('Times', '', 10);
        $this->Cell(0, 5, 'TAHUN PELAJARAN: ' . date('Y') . '/' . (date('Y') + 1), 0, 1, 'C');
        $this->Ln(5);
        $this->SetFont('Times', 'B', 10);
        $this->Cell(40, 6, 'NOMOR INDUK', 1, 0, 'C');
        $this->Cell(150, 6, $this->nomor_induk, 1, 1, 'C');
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Times', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function ChapterTitle($num, $label) {
        $this->SetFont('Times', 'B', 10);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(10, 6, $num, 1, 0, 'C', true);
        $this->Cell(180, 6, $label, 1, 1, 'L', true);
        $this->Ln(2);
    }

    function ContentRow($num, $label, $value, $indent = false) {
        $this->SetFont('Times', '', 10);
        $this->Cell(10, 6, $num, 'LR', 0, 'R');
        $x = $this->GetX();
        $this->SetX($x + ($indent ? 5 : 0));
        $this->Cell(70, 6, $label, 0, 0, 'L');
        $this->Cell(5, 6, ':', 0, 0, 'C');
        $this->MultiCell(0, 6, $value, 'R', 'L');
        $this->Cell(10, 0, '', 'L', 1); // Penutup bawah
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();

foreach ($siswa_ids as $siswa_id) {
    $data = get_student_details($mysqli, $siswa_id);
    if ($data) {
        $s = $data['siswa'];
        $pdf->SetNomorInduk($s['nis'] ?? '');
        $pdf->AddPage();

        // A. KETERANGAN PRIBADI SISWA
        $pdf->ChapterTitle('A.', 'KETERANGAN PRIBADI SISWA');
        $pdf->ContentRow('1.', 'Nama Lengkap', $s['nama_lengkap'] ?? '');
        $pdf->ContentRow('2.', 'Nama Panggilan', $s['nama_panggilan'] ?? '');
        $pdf->ContentRow('3.', 'Jenis Kelamin', ($s['jk'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan');
        $pdf->ContentRow('4.', 'Tempat dan Tanggal Lahir', ($s['tempat_lahir'] ?? '') . ', ' . ($s['tanggal_lahir'] ?? ''));
        $pdf->ContentRow('5.', 'Agama', $s['agama'] ?? '');
        $pdf->ContentRow('6.', 'Kewarganegaraan', $s['kewarganegaraan'] ?? '');
        $pdf->ContentRow('7.', 'Anak ke-', $s['anak_ke'] ?? '');
        $pdf->ContentRow('8.', 'Jumlah Saudara Kandung', $s['jml_saudara_kandung'] ?? '');
        $pdf->ContentRow('9.', 'Bahasa Sehari-hari', $s['bahasa_sehari_hari'] ?? '');

        // B. KETERANGAN TEMPAT TINGGAL
        $pdf->ChapterTitle('B.', 'KETERANGAN TEMPAT TINGGAL');
        $pdf->ContentRow('10.', 'Alamat Peserta Didik', $s['alamat'] ?? '');
        $pdf->ContentRow('11.', 'Nomor Telepon/HP', $s['telepon'] ?? '');
        $pdf->ContentRow('12.', 'Tinggal Dengan', $s['tinggal_dengan'] ?? '');
        $pdf->ContentRow('13.', 'Jarak Tempat Tinggal ke Sekolah', $s['jarak_ke_sekolah'] ?? '');

        // C. KETERANGAN KESEHATAN
        $pdf->ChapterTitle('C.', 'KETERANGAN KESEHATAN');
        $pdf->ContentRow('14.', 'Golongan Darah', $s['golongan_darah'] ?? '');
        $pdf->ContentRow('15.', 'Penyakit yang Pernah Diderita', $s['penyakit_diderita'] ?? '');
        $pdf->ContentRow('16.', 'Kelainan Jasmani', $s['kelainan_jasmani'] ?? '');
        $pdf->ContentRow('17.', 'Tinggi dan Berat Badan', 'Tinggi: ' . ($s['tinggi_badan'] ?? '') . ' cm, Berat: ' . ($s['berat_badan'] ?? '') . ' kg');

        // D. KETERANGAN PENDIDIKAN SEBELUMNYA
        $pend = $data['pendidikan'];
        $pdf->ChapterTitle('D.', 'KETERANGAN PENDIDIKAN SEBELUMNYA');
        $pdf->ContentRow('18.', 'Lulusan Dari', $pend['nama_sekolah'] ?? '');
        $pdf->ContentRow('', 'Tanggal dan Nomor STTB', ($pend['tahun_sttb'] ?? '') . ' / ' . ($pend['nomor_sttb'] ?? ''));

        // E. KETERANGAN ORANG TUA KANDUNG
        $ayah = $data['orang_tua']['Ayah'] ?? [];
        $ibu = $data['orang_tua']['Ibu'] ?? [];
        $pdf->ChapterTitle('E.', 'KETERANGAN ORANG TUA KANDUNG');
        $pdf->ContentRow('19.', 'Nama Orang Tua', '');
        $pdf->ContentRow('', 'a. Ayah', $ayah['nama_lengkap'] ?? '', true);
        $pdf->ContentRow('', 'b. Ibu', $ibu['nama_lengkap'] ?? '', true);
        $pdf->ContentRow('20.', 'Pekerjaan', '');
        $pdf->ContentRow('', 'a. Ayah', $ayah['pekerjaan'] ?? '', true);
        $pdf->ContentRow('', 'b. Ibu', $ibu['pekerjaan'] ?? '', true);

        // F. KETERANGAN WALI
        $wali = $data['orang_tua']['Wali'] ?? [];
        $pdf->ChapterTitle('F.', 'KETERANGAN WALI');
        $pdf->ContentRow('21.', 'Nama Wali', $wali['nama_lengkap'] ?? '');
        $pdf->ContentRow('22.', 'Pekerjaan Wali', $wali['pekerjaan'] ?? '');
        $pdf->ContentRow('23.', 'Alamat Wali', $wali['alamat'] ?? '');

        // G. KEGEMARAN SISWA
        $keg = $data['kegemaran'];
        $pdf->ChapterTitle('G.', 'KEGEMARAN SISWA');
        $pdf->ContentRow('24.', 'Kesenian', $keg['kesenian'] ?? '');
        $pdf->ContentRow('25.', 'Olah Raga', $keg['olahraga'] ?? '');
        $pdf->ContentRow('26.', 'Kemasyarakatan / Organisasi', $keg['kemasyarakatan'] ?? '');
        $pdf->ContentRow('27.', 'Lain-lain', $keg['lain_lain'] ?? '');

        // H. KETERANGAN PERKEMBANGAN SISWA
        $perk = $data['perkembangan'];
        $pdf->ChapterTitle('H.', 'KETERANGAN PERKEMBANGAN SISWA');
        $pdf->ContentRow('28.', 'Menerima Beasiswa', ($perk['beasiswa_nama'] ?? '') . ' tahun ' . ($perk['beasiswa_tahun'] ?? ''));
        $pdf->ContentRow('29.', 'Meninggalkan Sekolah', '');
        $pdf->ContentRow('', 'Tanggal', $perk['meninggalkan_sekolah_tanggal'] ?? '', true);
        $pdf->ContentRow('', 'Alasan', $perk['meninggalkan_sekolah_alasan'] ?? '', true);
        $pdf->ContentRow('30.', 'Akhir Pendidikan', '');
        $pdf->ContentRow('', 'Lulus Tanggal', $perk['akhir_pendidikan_tanggal'] ?? '', true);
        $pdf->ContentRow('', 'Nomor Ijazah', $perk['akhir_pendidikan_no_ijazah'] ?? '', true);

        // I. KETERANGAN SETELAH SELESAI PENDIDIKAN
        $lulus = $data['lulus'];
        $pdf->ChapterTitle('I.', 'KETERANGAN SETELAH SELESAI PENDIDIKAN');
        $pdf->ContentRow('31.', 'Melanjutkan ke', $lulus['melanjutkan_ke'] ?? '');
        $pdf->ContentRow('32.', 'Bekerja', '');
        $pdf->ContentRow('', 'Tanggal Mulai', $lulus['bekerja_tanggal_mulai'] ?? '', true);
        $pdf->ContentRow('', 'Nama Perusahaan/DU/DI', $lulus['bekerja_nama_perusahaan'] ?? '', true);
        $pdf->ContentRow('', 'Penghasilan', $lulus['bekerja_penghasilan'] ?? '', true);
    }
}

$pdf->Output('I', 'Buku_Induk_Siswa.pdf');
$mysqli->close();
?>
