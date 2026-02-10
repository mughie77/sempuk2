<?php
// /core/generate_pdf_buku_induk.php

require_once __DIR__ . '/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../includes/lib/fpdf/fpdf.php';

// Function to fetch all data for a single student (no changes needed here)
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


class PDF_Buku_Induk extends FPDF
{
    private $nomor_induk;
    protected $y0;
    protected $col = 0;

    // Fixed layout coordinates and dimensions
    private $page_margin = 10;
    private $column_width = 130;
    private $column_gutter = 17;
    private $line_height = 5;
    private $font_size = 9;

    // Pre-calculated X positions for the grid layout
    private $x_col_1; // Start of column 1
    private $x_col_2; // Start of column 2
    private $x_num_start;
    private $x_label_start;
    private $x_colon_start;
    private $x_value_start;

    function __construct($orientation='L', $unit='mm', $size='A4') {
        parent::__construct($orientation, $unit, $size);
        $this->SetMargins($this->page_margin, $this->page_margin);
        $this->x_col_1 = $this->page_margin;
        $this->x_col_2 = $this->page_margin + $this->column_width + $this->column_gutter;
    }

    function SetNomorInduk($nomor_induk) {
        $this->nomor_induk = $nomor_induk;
    }

    function Header() {
        $this->SetFont('Times', 'B', 12);
        $this->Cell(0, 6, 'BUKU INDUK PESERTA DIDIK', 0, 1, 'C');
        $this->SetFont('Times', '', 10);
        $this->Cell(0, 5, 'TAHUN PELAJARAN: ' . date('Y') . '/' . (date('Y') + 1), 0, 1, 'C');
        $this->Ln(2);

        // Photo box and NIS box
        $photo_box_width = 30; // 3cm
        $photo_box_height = 40; // 4cm
        $nis_box_width = 40;

        $page_width = $this->GetPageWidth();
        $photo_box_x = $page_width - $this->page_margin - $photo_box_width;
        $nis_box_x = $photo_box_x - $nis_box_width - 5;
        $box_y = $this->GetY();

        // NIS Box
        $this->SetFont('Times', 'B', 10);
        $this->SetXY($nis_box_x, $box_y);
        $this->Cell($nis_box_width, 6, 'NOMOR INDUK', 1, 2, 'C');
        $this->SetX($nis_box_x);
        $this->Cell($nis_box_width, 10, $this->nomor_induk, 1, 1, 'C');

        // Photo Box
        $this->Rect($photo_box_x, $box_y, $photo_box_width, $photo_box_height);
        $this->SetXY($photo_box_x, $box_y + ($photo_box_height/2) - 5);
        $this->SetFont('Times', 'I', 8);
        $this->MultiCell($photo_box_width, 5, 'Tempel Pas Foto 3x4', 0, 'C');

        $this->y0 = $box_y + $photo_box_height + 5; // Set start Y for content below boxes
        $this->SetY($this->y0);
    }

    function SetCol($col) {
        $this->col = $col;
        $x_start = ($col == 0) ? $this->x_col_1 : $this->x_col_2;
        $this->SetLeftMargin($x_start);
        $this->SetX($x_start);

        // Define grid X positions for the current column
        $this->x_num_start = $x_start;
        $this->x_label_start = $this->x_num_start + 6;
        $this->x_colon_start = $this->x_label_start + 55;
        $this->x_value_start = $this->x_colon_start + 3;
    }

    function AcceptPageBreak() {
        if ($this->col == 0) {
            $this->SetCol(1);
            $this->SetY($this->y0);
            return false;
        } else {
            $this->SetCol(0);
            return true;
        }
    }

    function ChapterTitle($num, $label) {
        $full_width = $this->GetPageWidth() - (2 * $this->page_margin);
        $this->SetX($this->page_margin);
        $this->SetFont('Times', 'B', $this->font_size);
        $this->SetFillColor(220, 220, 220);
        $this->Cell(8, 6, $num, 1, 0, 'C', true);
        $this->Cell($full_width - 8, 6, $label, 1, 1, 'L', true);
        $this->Ln(2);
    }

    function ContentRow($num, $label, $value, $sub_item = false) {
        $this->SetFont('Times', '', $this->font_size);
        $y_before = $this->GetY();

        // 1. Number
        $this->SetX($this->x_num_start);
        $this->Cell(5, $this->line_height, $num, 0, 0, 'R');

        // 2. Label
        $this->SetX($sub_item ? $this->x_label_start + 5 : $this->x_label_start);
        $this->Cell(55, $this->line_height, $label, 0, 0, 'L');

        // 3. Colon
        $this->SetX($this->x_colon_start);
        $this->Cell(3, $this->line_height, ':', 0, 0, 'C');

        // 4. Value
        $this->SetX($this->x_value_start);
        $value_width = $this->column_width - ($this->x_value_start - $this->GetX());
        $this->MultiCell($value_width, $this->line_height, $value ?? '', 0, 'L');

        // Reset Y position to ensure next row starts correctly
        $y_after = $this->GetY();
        $height_of_multicell = $y_after - $y_before;
        if ($height_of_multicell < $this->line_height) {
             $this->SetY($y_before + $this->line_height);
        }
        $this->Ln(1); // Small gap between rows
    }

    function PrintStudentData($data) {
        $s = $data['siswa'] ?? [];
        $pend = $data['pendidikan'] ?? [];
        $ayah = $data['orang_tua']['Ayah'] ?? [];
        $ibu = $data['orang_tua']['Ibu'] ?? [];
        $wali = $data['orang_tua']['Wali'] ?? [];
        $keg = $data['kegemaran'] ?? [];
        $perk = $data['perkembangan'] ?? [];
        $lulus = $data['lulus'] ?? [];

        $this->ChapterTitle('A.', 'KETERANGAN PRIBADI SISWA');

        // Column 1
        $this->SetCol(0);
        $this->ContentRow('1.', 'Nama Lengkap Peserta Didik', $s['nama_lengkap']);
        $this->ContentRow('2.', 'Nama Panggilan', $s['nama_panggilan']);
        $this->ContentRow('3.', 'Jenis Kelamin', ($s['jk'] == 'L' ? 'Laki-laki' : 'Perempuan'));
        $this->ContentRow('4.', 'Tempat dan Tanggal Lahir', $s['tempat_lahir'] . ', ' . ($s['tanggal_lahir'] ? date('d-m-Y', strtotime($s['tanggal_lahir'])) : ''));
        $this->ContentRow('5.', 'Agama', $s['agama']);
        $this->ContentRow('6.', 'Kewarganegaraan', $s['kewarganegaraan']);
        $this->ContentRow('7.', 'Anak ke-', $s['anak_ke']);
        $this->ContentRow('8.', 'Jumlah Saudara Kandung', $s['jml_saudara_kandung']);
        $this->ContentRow('9.', 'Bahasa Sehari-hari', $s['bahasa_sehari_hari']);
        $this->ContentRow('10.', 'Alamat Peserta Didik', $s['alamat']);
        $this->ContentRow('11.', 'Nomor Telepon/HP', $s['telepon']);
        $this->ContentRow('12.', 'Tinggal Dengan', $s['tinggal_dengan']);
        $this->ContentRow('13.', 'Jarak ke Sekolah', $s['jarak_ke_sekolah']);
        $this->ContentRow('14.', 'Golongan Darah', $s['golongan_darah']);
        $this->ContentRow('15.', 'Penyakit yang Pernah Diderita', $s['penyakit_diderita']);
        $this->ContentRow('16.', 'Kelainan Jasmani', $s['kelainan_jasmani']);
        $this->ContentRow('17.', 'Tinggi dan Berat Badan', $s['tinggi_badan'] . ' cm / ' . $s['berat_badan'] . ' kg');

        // Column 2
        $this->SetCol(1);
        $this->SetY($this->y0 + 8); // +8 to align with Chapter title
        $this->ContentRow('18.', 'Pendidikan Sebelumnya', '');
        $this->ContentRow('', 'a. Lulusan dari', $pend['nama_sekolah'], true);
        $this->ContentRow('', 'b. Tgl & No. Ijazah', $pend['tahun_sttb'] . ' / ' . $pend['nomor_sttb'], true);
        $this->ContentRow('19.', 'Orang Tua Kandung', '');
        $this->ContentRow('', 'a. Nama Ayah', $ayah['nama_lengkap'], true);
        $this->ContentRow('', 'b. Nama Ibu', $ibu['nama_lengkap'], true);
        $this->ContentRow('', 'c. Alamat', $ayah['alamat'], true);
        $this->ContentRow('', 'd. Pekerjaan Ayah', $ayah['pekerjaan'], true);
        $this->ContentRow('', 'e. Pekerjaan Ibu', $ibu['pekerjaan'], true);
        $this->ContentRow('20.', 'Wali Peserta Didik', '');
        $this->ContentRow('', 'a. Nama Wali', $wali['nama_lengkap'], true);
        $this->ContentRow('', 'b. Pekerjaan', $wali['pekerjaan'], true);
        $this->ContentRow('', 'c. Alamat', $wali['alamat'], true);

        $this->ChapterTitle('B.', 'PERKEMBANGAN SISWA');
        $this->SetCol(0);
        $this->ContentRow('1.', 'Beasiswa', ($perk['beasiswa_nama'] ?? '') . ' thn ' . ($perk['beasiswa_tahun'] ?? ''));
        $this->ContentRow('2.', 'Meninggalkan Sekolah', '');
        $this->ContentRow('', 'a. Tanggal', $perk['meninggalkan_sekolah_tanggal'], true);
        $this->ContentRow('', 'b. Alasan', $perk['meninggalkan_sekolah_alasan'], true);
        $this->SetCol(1);
        $this->SetY($this->GetY() - 18); // Align with column 1
        $this->ContentRow('3.', 'Akhir Pendidikan', '');
        $this->ContentRow('', 'a. Lulus Tanggal', $perk['akhir_pendidikan_tanggal'], true);
        $this->ContentRow('', 'b. No Ijazah', $perk['akhir_pendidikan_no_ijazah'], true);

    }
}

// Main script execution
$siswa_ids = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['siswa_ids'])) {
    $siswa_ids = (array)$_POST['siswa_ids'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['siswa_id'])) {
    $siswa_ids[] = (int)$_GET['siswa_id'];
}

if (empty($siswa_ids)) {
    die("Tidak ada siswa yang dipilih.");
}

$pdf = new PDF_Buku_Induk('L', 'mm', 'A4');
$pdf->AliasNbPages();

foreach ($siswa_ids as $siswa_id) {
    $data = get_student_details($mysqli, $siswa_id);
    if ($data) {
        $pdf->SetNomorInduk($data['siswa']['nis'] ?? '');
        $pdf->AddPage();
        $pdf->PrintStudentData($data);
    }
}

$pdf->Output('I', 'Buku_Induk_Siswa.pdf');
$mysqli->close();
?>
