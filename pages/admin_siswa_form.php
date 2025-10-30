<?php
// /pages/admin_siswa_form.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

$page_title = "Tambah Siswa Baru";
$form_action = "add_siswa";
$siswa_data = [];
$ortu_data = ['Ayah' => [], 'Ibu' => [], 'Wali' => []];

// Logika untuk mode Edit
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $page_title = "Edit Data Siswa";
    $form_action = "edit_siswa";
    $siswa_id = (int)$_GET['edit_id'];

    $stmt_siswa = $mysqli->prepare("SELECT * FROM siswa WHERE siswa_id = ?");
    $stmt_siswa->bind_param("i", $siswa_id);
    $stmt_siswa->execute();
    $siswa_data = $stmt_siswa->get_result()->fetch_assoc();
    $stmt_siswa->close();

    if (!$siswa_data) {
        // Siswa tidak ditemukan, kembali ke halaman utama
        $_SESSION['flash_message'] = ['message' => 'Data siswa tidak ditemukan.', 'type' => 'danger'];
        header("Location: " . BASE_URL . "pages/admin_manage_siswa.php");
        exit();
    }

    $stmt_ortu = $mysqli->prepare("SELECT * FROM orang_tua WHERE siswa_id = ?");
    $stmt_ortu->bind_param("i", $siswa_id);
    $stmt_ortu->execute();
    $result_ortu = $stmt_ortu->get_result();
    while ($row = $result_ortu->fetch_assoc()) {
        $ortu_data[$row['tipe']] = $row;
    }
    $stmt_ortu->close();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $page_title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>pages/admin_manage_siswa.php">Manajemen Siswa</a></li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>

    <form action="<?php echo BASE_URL; ?>core/siswa_crud_process.php" method="POST">
        <input type="hidden" name="action" value="<?php echo $form_action; ?>">
        <?php if (isset($siswa_id)): ?>
            <input type="hidden" name="siswa_id" value="<?php echo $siswa_id; ?>">
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <!-- Navigasi Tab -->
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#data-pokok">Data Pokok</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#data-alamat">Alamat & Kontak</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#data-ortu">Orang Tua / Wali</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#data-akademik">Akademik & Lainnya</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">

                    <!-- Tab Data Pokok -->
                    <div class="tab-pane fade show active" id="data-pokok">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" value="<?php echo htmlspecialchars($siswa_data['nama_lengkap'] ?? ''); ?>" required></div>
                            <div class="col-md-6"><label class="form-label">NIS</label><input type="text" name="nis" class="form-control" value="<?php echo htmlspecialchars($siswa_data['nis'] ?? ''); ?>" required></div>
                            <div class="col-md-6"><label class="form-label">Jenis Kelamin</label><select name="jk" class="form-select"><option value="L" <?php echo (($siswa_data['jk'] ?? '') == 'L') ? 'selected' : ''; ?>>Laki-laki</option><option value="P" <?php echo (($siswa_data['jk'] ?? '') == 'P') ? 'selected' : ''; ?>>Perempuan</option></select></div>
                            <div class="col-md-6"><label class="form-label">NISN</label><input type="text" name="nisn" class="form-control" value="<?php echo htmlspecialchars($siswa_data['nisn'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Tempat Lahir</label><input type="text" name="tempat_lahir" class="form-control" value="<?php echo htmlspecialchars($siswa_data['tempat_lahir'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" value="<?php echo htmlspecialchars($siswa_data['tanggal_lahir'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">NIK</label><input type="text" name="nik" class="form-control" value="<?php echo htmlspecialchars($siswa_data['nik'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Agama</label><input type="text" name="agama" class="form-control" value="<?php echo htmlspecialchars($siswa_data['agama'] ?? ''); ?>"></div>
                            <div class="col-md-4"><label class="form-label">Anak ke-</label><input type="number" name="anak_ke" class="form-control" value="<?php echo htmlspecialchars($siswa_data['anak_ke'] ?? ''); ?>"></div>
                            <div class="col-md-4"><label class="form-label">No. Akta Lahir</label><input type="text" name="no_akta_lahir" class="form-control" value="<?php echo htmlspecialchars($siswa_data['no_akta_lahir'] ?? ''); ?>"></div>
                            <div class="col-md-4"><label class="form-label">No. Kartu Keluarga</label><input type="text" name="no_kk" class="form-control" value="<?php echo htmlspecialchars($siswa_data['no_kk'] ?? ''); ?>"></div>
                        </div>
                    </div>

                    <!-- Tab Data Alamat -->
                    <div class="tab-pane fade" id="data-alamat">
                        <div class="row g-3">
                           <div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control"><?php echo htmlspecialchars($siswa_data['alamat'] ?? ''); ?></textarea></div>
                           <div class="col-md-3"><label class="form-label">RT</label><input type="text" name="rt" class="form-control" value="<?php echo htmlspecialchars($siswa_data['rt'] ?? ''); ?>"></div>
                           <div class="col-md-3"><label class="form-label">RW</label><input type="text" name="rw" class="form-control" value="<?php echo htmlspecialchars($siswa_data['rw'] ?? ''); ?>"></div>
                           <div class="col-md-6"><label class="form-label">Dusun</label><input type="text" name="dusun" class="form-control" value="<?php echo htmlspecialchars($siswa_data['dusun'] ?? ''); ?>"></div>
                           <div class="col-md-6"><label class="form-label">Kelurahan</label><input type="text" name="kelurahan" class="form-control" value="<?php echo htmlspecialchars($siswa_data['kelurahan'] ?? ''); ?>"></div>
                           <div class="col-md-6"><label class="form-label">Kecamatan</label><input type="text" name="kecamatan" class="form-control" value="<?php echo htmlspecialchars($siswa_data['kecamatan'] ?? ''); ?>"></div>
                           <div class="col-md-4"><label class="form-label">Kode Pos</label><input type="text" name="kode_pos" class="form-control" value="<?php echo htmlspecialchars($siswa_data['kode_pos'] ?? ''); ?>"></div>
                           <div class="col-md-4"><label class="form-label">Jenis Tinggal</label><input type="text" name="jenis_tinggal" class="form-control" value="<?php echo htmlspecialchars($siswa_data['jenis_tinggal'] ?? ''); ?>"></div>
                           <div class="col-md-4"><label class="form-label">Jarak ke Sekolah (KM)</label><input type="number" name="jarak_sekolah_km" class="form-control" value="<?php echo htmlspecialchars($siswa_data['jarak_sekolah_km'] ?? ''); ?>"></div>
                           <div class="col-md-4"><label class="form-label">Telepon</label><input type="text" name="telepon" class="form-control" value="<?php echo htmlspecialchars($siswa_data['telepon'] ?? ''); ?>"></div>
                           <div class="col-md-4"><label class="form-label">HP</label><input type="text" name="hp" class="form-control" value="<?php echo htmlspecialchars($siswa_data['hp'] ?? ''); ?>"></div>
                           <div class="col-md-4"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($siswa_data['email'] ?? ''); ?>"></div>
                        </div>
                    </div>

                     <!-- Tab Data Ortu -->
                    <div class="tab-pane fade" id="data-ortu">
                        <div class="row g-3">
                            <div class="col-12"><h5>Data Ayah</h5><hr class="mt-1"></div>
                            <div class="col-md-6"><label class="form-label">Nama Ayah</label><input type="text" name="ayah_nama" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ayah']['nama'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">NIK Ayah</label><input type="text" name="ayah_nik" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ayah']['nik'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Tahun Lahir</label><input type="text" name="ayah_tahun_lahir" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ayah']['tahun_lahir'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Pendidikan</label><input type="text" name="ayah_pendidikan" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ayah']['pendidikan'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Pekerjaan</label><input type="text" name="ayah_pekerjaan" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ayah']['pekerjaan'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Penghasilan</label><input type="text" name="ayah_penghasilan" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ayah']['penghasilan'] ?? ''); ?>"></div>

                            <div class="col-12 mt-4"><h5>Data Ibu</h5><hr class="mt-1"></div>
                            <div class="col-md-6"><label class="form-label">Nama Ibu</label><input type="text" name="ibu_nama" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ibu']['nama'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">NIK Ibu</label><input type="text" name="ibu_nik" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ibu']['nik'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Tahun Lahir</label><input type="text" name="ibu_tahun_lahir" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ibu']['tahun_lahir'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Pendidikan</label><input type="text" name="ibu_pendidikan" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ibu']['pendidikan'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Pekerjaan</label><input type="text" name="ibu_pekerjaan" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ibu']['pekerjaan'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">Penghasilan</label><input type="text" name="ibu_penghasilan" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Ibu']['penghasilan'] ?? ''); ?>"></div>

                            <div class="col-12 mt-4"><h5>Data Wali (Opsional)</h5><hr class="mt-1"></div>
                            <div class="col-md-6"><label class="form-label">Nama Wali</label><input type="text" name="wali_nama" class="form-control" value="<?php echo htmlspecialchars($ortu_data['Wali']['nama'] ?? ''); ?>"></div>
                        </div>
                    </div>

                    <!-- Tab Data Akademik -->
                    <div class="tab-pane fade" id="data-akademik">
                         <div class="row g-3">
                            <div class="col-md-12"><label class="form-label">Sekolah Asal</label><input type="text" name="sekolah_asal" class="form-control" value="<?php echo htmlspecialchars($siswa_data['sekolah_asal'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">No. Peserta Ujian Nasional</label><input type="text" name="no_peserta_un" class="form-control" value="<?php echo htmlspecialchars($siswa_data['no_peserta_un'] ?? ''); ?>"></div>
                            <div class="col-md-6"><label class="form-label">No. Seri Ijazah</label><input type="text" name="no_seri_ijazah" class="form-control" value="<?php echo htmlspecialchars($siswa_data['no_seri_ijazah'] ?? ''); ?>"></div>
                            <div class="col-md-4"><label class="form-label">Berat Badan (kg)</label><input type="number" name="berat_badan" class="form-control" value="<?php echo htmlspecialchars($siswa_data['berat_badan'] ?? ''); ?>"></div>
                            <div class="col-md-4"><label class="form-label">Tinggi Badan (cm)</label><input type="number" name="tinggi_badan" class="form-control" value="<?php echo htmlspecialchars($siswa_data['tinggi_badan'] ?? ''); ?>"></div>
                            <div class="col-md-4"><label class="form-label">Jml. Saudara</label><input type="number" name="jml_saudara" class="form-control" value="<?php echo htmlspecialchars($siswa_data['jml_saudara'] ?? ''); ?>"></div>
                         </div>
                    </div>

                </div>
            </div>
            <div class="card-footer text-end">
                <a href="<?php echo BASE_URL; ?>pages/admin_manage_siswa.php" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Data Siswa</button>
            </div>
        </div>
    </form>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
$mysqli->close();
?>
