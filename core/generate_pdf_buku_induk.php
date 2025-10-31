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
    protected $y0;

    function SetNomorInduk($nomor_induk) {
        $this->nomor_induk = $nomor_induk;
    }

    function Header() {
        // Arial bold 12
        $this->SetFont('Times', 'B', 12);
        // Judul
        $this->Cell(0, 6, 'BUKU INDUK PESERTA DIDIK', 0, 1, 'C');
        $this->SetFont('Times', '', 10);
        $this->Cell(0, 5, 'TAHUN PELAJARAN: ' . date('Y') . '/' . (date('Y') + 1), 0, 1, 'C');
        $this->Ln(5);

        // Nomor Induk Box
        $this->SetFont('Times', 'B', 10);
        $this->Cell(237, 6, '', 0, 0, 'L'); // Spacer
        $this->Cell(40, 6, 'NOMOR INDUK', 1, 1, 'C');
        $this->Cell(237, 6, '', 0, 0, 'L'); // Spacer
        $this->Cell(40, 6, $this->nomor_induk, 1, 1, 'C');

        // Set y0 for columns
        $this->y0 = $this->GetY();
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Times', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function SetCol($col) {
        // Set position at a given column
        $this->col = $col;
        $x = 10 + $col * 140; // 10mm margin, 140mm column width
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    function AcceptPageBreak() {
        // Method automatically called when page break occurs
        if ($this->col < 1) { // We're in the first column
            $this->SetCol(1); // Go to next column
            $this->SetY($this->y0); // Set Y to the top of the column
            return false; // Suppress page break
        } else { // We're in the second column
            $this->SetCol(0); // Go back to first column
            return true; // Perform page break
        }
    }

    function ChapterTitle($num, $label) {
        $this->SetFont('Times', 'B', 10);
        $this->SetFillColor(200, 220, 255);
        $this->SetX(10); // Start from left margin
        $this->Cell(10, 6, $num, 1, 0, 'C', true);
        // Full width for landscape A4 (297mm) minus margins (20mm) = 277mm.
        $this->Cell(267, 6, $label, 1, 1, 'L', true);
        $this->Ln(2);
    }

    function ContentRow($num, $label, $value, $indent = false) {
        $this->SetFont('Times', '', 9);
        $this->Cell(8, 5, $num, 0, 0, 'R');
        $this->SetFont('Times', '', 9);
        $x = $this->GetX();
        $this->SetX($x + ($indent ? 5 : 0));

        // Calculate width for label and value
        $current_col_width = 135; // Approx column width
        $label_width = 50;
        $value_width = $current_col_width - $label_width - 10; // 10 for num and ':'

        $this->Cell($label_width, 5, $label, 0, 0, 'L');
        $this->Cell(3, 5, ':', 0, 0, 'C');

        $y = $this->GetY();
        $x = $this->GetX();
        $this->MultiCell($value_width, 5, $value, 0, 'L');
        // Ensure the Y position is consistent after MultiCell
        if ($this->GetY() > $y + 5) {
             // MultiCell created more than one line
        } else {
            $this->SetXY($x + $value_width, $y); // Move to the right of the value
        }
        $this->Ln(2); // Spacing after the row
    }

    function PrintStudentData($data) {
        $s = $data['siswa'];
        $pend = $data['pendidikan'];
        $ayah = $data['orang_tua']['Ayah'] ?? [];
        $ibu = $data['orang_tua']['Ibu'] ?? [];
        $wali = $data['orang_tua']['Wali'] ?? [];
        $keg = $data['kegemaran'];
        $perk = $data['perkembangan'];
        $lulus = $data['lulus'];

        // Start in the first column
        $this->SetCol(0);

        // A. KETERANGAN PRIBADI SISWA
        $this->ChapterTitle('A.', 'KETERANGAN PRIBADI SISWA');
        $this->SetCol(0);
        $this->ContentRow('1.', 'Nama Lengkap', $s['nama_lengkap'] ?? '');
        $this->ContentRow('2.', 'Nama Panggilan', $s['nama_panggilan'] ?? '');
        $this->ContentRow('3.', 'Jenis Kelamin', ($s['jk'] ?? '') == 'L' ? 'Laki-laki' : 'Perempuan');
        $this->ContentRow('4.', 'Tempat & Tgl Lahir', ($s['tempat_lahir'] ?? '') . ', ' . ($s['tanggal_lahir'] ?? ''));
        $this->ContentRow('5.', 'Agama', $s['agama'] ?? '');
        $this->ContentRow('6.', 'Kewarganegaraan', $s['kewarganegaraan'] ?? '');
        $this->ContentRow('7.', 'Anak ke-', $s['anak_ke'] ?? '');
        $this->ContentRow('8.', 'Jml Sdr Kandung', $s['jml_saudara_kandung'] ?? '');
        $this->ContentRow('9.', 'Bahasa Sehari-hari', $s['bahasa_sehari_hari'] ?? '');
        $this->Ln(2);

        // B. KETERANGAN TEMPAT TINGGAL
        $this->ChapterTitle('B.', 'KETERANGAN TEMPAT TINGGAL');
        $this->SetCol(0);
        $this->ContentRow('10.', 'Alamat', $s['alamat'] ?? '');
        $this->ContentRow('11.', 'No. Telepon/HP', $s['telepon'] ?? '');
        $this->ContentRow('12.', 'Tinggal Dengan', $s['tinggal_dengan'] ?? '');
        $this->ContentRow('13.', 'Jarak ke Sekolah', $s['jarak_ke_sekolah'] ?? '');
        $this->Ln(2);

        // C. KETERANGAN KESEHATAN
        $this->ChapterTitle('C.', 'KETERANGAN KESEHATAN');
        $this->SetCol(0);
        $this->ContentRow('14.', 'Golongan Darah', $s['golongan_darah'] ?? '');
        $this->ContentRow('15.', 'Penyakit yg Diderita', $s['penyakit_diderita'] ?? '');
        $this->ContentRow('16.', 'Kelainan Jasmani', $s['kelainan_jasmani'] ?? '');
        $this->ContentRow('17.', 'Tinggi/Berat Badan', ($s['tinggi_badan'] ?? '') . ' cm / ' . ($s['berat_badan'] ?? '') . ' kg');
        $this->Ln(2);

        // D. KETERANGAN PENDIDIKAN SEBELUMNYA
        $this->ChapterTitle('D.', 'KETERANGAN PENDIDIKAN SEBELUMNYA');
        $this->SetCol(0);
        $this->ContentRow('18.', 'Lulusan Dari', $pend['nama_sekolah'] ?? '');
        $this->ContentRow('', 'Tgl/No. STTB', ($pend['tahun_sttb'] ?? '') . ' / ' . ($pend['nomor_sttb'] ?? ''));
        $this->Ln(2);

        // Move to the second column
        $this->SetCol(1);
        $this->SetY($this->y0); // Reset Y to top

        // E. KETERANGAN ORANG TUA KANDUNG
        $this->ChapterTitle('E.', 'KETERANGAN ORANG TUA KANDUNG');
        $this->SetCol(1);
        $this->ContentRow('19.', 'Nama Ayah', $ayah['nama_lengkap'] ?? '');
        $this->ContentRow('', 'Pekerjaan', $ayah['pekerjaan'] ?? '');
        $this->ContentRow('', 'Alamat', $ayah['alamat'] ?? '');
        $this->ContentRow('20.', 'Nama Ibu', $ibu['nama_lengkap'] ?? '');
        $this->ContentRow('', 'Pekerjaan', $ibu['pekerjaan'] ?? '');
        $this->ContentRow('', 'Alamat', $ibu['alamat'] ?? '');
        $this->Ln(2);

        // F. KETERANGAN WALI
        $this->ChapterTitle('F.', 'KETERANGAN WALI');
        $this->SetCol(1);
        $this->ContentRow('21.', 'Nama Wali', $wali['nama_lengkap'] ?? '');
        $this->ContentRow('22.', 'Pekerjaan', $wali['pekerjaan'] ?? '');
        $this->ContentRow('23.', 'Alamat', $wali['alamat'] ?? '');
        $this->Ln(2);

        // G. KEGEMARAN SISWA
        $this->ChapterTitle('G.', 'KEGEMARAN SISWA');
        $this->SetCol(1);
        $this->ContentRow('24.', 'Kesenian', $keg['kesenian'] ?? '');
        $this->ContentRow('25.', 'Olah Raga', $keg['olahraga'] ?? '');
        $this->ContentRow('26.', 'Organisasi', $keg['kemasyarakatan'] ?? '');
        $this->ContentRow('27.', 'Lain-lain', $keg['lain_lain'] ?? '');
        $this->Ln(2);

        // H. PERKEMBANGAN SISWA
        $this->ChapterTitle('H.', 'PERKEMBANGAN SISWA');
        $this->SetCol(1);
        $this->ContentRow('28.', 'Beasiswa', ($perk['beasiswa_nama'] ?? '') . ' thn ' . ($perk['beasiswa_tahun'] ?? ''));
        $this->ContentRow('29.', 'Meninggalkan Sekolah', ($perk['meninggalkan_sekolah_tanggal'] ?? '') . ' (' . ($perk['meninggalkan_sekolah_alasan'] ?? '') . ')');
        $this->ContentRow('30.', 'Akhir Pendidikan', ($perk['akhir_pendidikan_tanggal'] ?? '') . ' / ' . ($perk['akhir_pendidikan_no_ijazah'] ?? ''));
        $this->Ln(2);

        // I. SETELAH SELESAI PENDIDIKAN
        $this->ChapterTitle('I.', 'SETELAH SELESAI PENDIDIKAN');
        $this->SetCol(1);
        $this->ContentRow('31.', 'Melanjutkan ke', $lulus['melanjutkan_ke'] ?? '');
        $this->ContentRow('32.', 'Bekerja', 'Tgl: ' . ($lulus['bekerja_tanggal_mulai'] ?? '') . ', di: ' . ($lulus['bekerja_nama_perusahaan'] ?? '') . ', Gaji: ' . ($lulus['bekerja_penghasilan'] ?? ''));
    }
}

$pdf = new PDF('L', 'mm', 'A4');
$pdf->AliasNbPages();

foreach ($siswa_ids as $siswa_id) {
    $data = get_student_details($mysqli, $siswa_id);
    if ($data) {
        $s = $data['siswa'];
        $pdf->SetNomorInduk($s['nis'] ?? '');
        $pdf->AddPage();
        $pdf->PrintStudentData($data);
    }
}

$pdf->Output('I', 'Buku_Induk_Siswa.pdf');
$mysqli->close();
?>
