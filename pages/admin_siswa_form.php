<?php
// /pages/admin_siswa_form.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

$page_title = "Tambah Siswa Baru";
$form_action = "add_siswa";
$siswa = [];
$ortu = ['Ayah' => [], 'Ibu' => [], 'Wali' => []];
$pendidikan = [];
$pindahan = [];
$kegemaran = [];
$perkembangan = [];
$setelah_lulus = [];

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $page_title = "Edit Data Siswa";
    $form_action = "edit_siswa";
    $siswa_id = (int)$_GET['edit_id'];

    $stmt_siswa = $mysqli->prepare("SELECT * FROM siswa WHERE siswa_id = ?");
    $stmt_siswa->bind_param("i", $siswa_id);
    $stmt_siswa->execute();
    $siswa = $stmt_siswa->get_result()->fetch_assoc();
    $stmt_siswa->close();

    if (!$siswa) {
        $_SESSION['flash_message'] = ['message' => 'Data siswa tidak ditemukan.', 'type' => 'danger'];
        header("Location: " . BASE_URL . "pages/admin_manage_siswa.php");
        exit();
    }

    $stmt_ortu = $mysqli->prepare("SELECT * FROM orang_tua WHERE siswa_id = ?");
    $stmt_ortu->bind_param("i", $siswa_id);
    $stmt_ortu->execute();
    $result_ortu = $stmt_ortu->get_result();
    while ($row = $result_ortu->fetch_assoc()) $ortu[$row['tipe']] = $row;
    $stmt_ortu->close();

    $pendidikan = $mysqli->query("SELECT * FROM pendidikan_sebelumnya WHERE siswa_id = $siswa_id")->fetch_assoc() ?? [];
    $pindahan = $mysqli->query("SELECT * FROM riwayat_pindahan WHERE siswa_id = $siswa_id")->fetch_assoc() ?? [];
    $kegemaran = $mysqli->query("SELECT * FROM kegemaran_siswa WHERE siswa_id = $siswa_id")->fetch_assoc() ?? [];
    $perkembangan = $mysqli->query("SELECT * FROM perkembangan_siswa WHERE siswa_id = $siswa_id")->fetch_assoc() ?? [];
    $setelah_lulus = $mysqli->query("SELECT * FROM info_setelah_lulus WHERE siswa_id = $siswa_id")->fetch_assoc() ?? [];
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $page_title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>pages/admin_manage_siswa.php">Manajemen Siswa</a></li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>

    <form action="<?php echo BASE_URL; ?>core/siswa_crud_process.php" method="POST">
        <input type="hidden" name="action" value="<?php echo $form_action; ?>">
        <?php if (isset($siswa_id)): ?><input type="hidden" name="siswa_id" value="<?php echo $siswa_id; ?>"><?php endif; ?>

        <div class="card">
            <div class="card-header"><ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-a">A. Diri Siswa</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-b">B. Tempat Tinggal</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-c">C. Kesehatan</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-d">D. Pendidikan</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-e">E. Ayah</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-f">F. Ibu</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-g">G. Wali</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-h">H. Kegemaran</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-i">I. Perkembangan</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-j">J. Setelah Lulus</a></li>
            </ul></div>
            <div class="card-body">
                <div class="tab-content">
                    <?php include 'siswa_form_tabs/tab_a.php'; ?>
                    <?php include 'siswa_form_tabs/tab_b.php'; ?>
                    <?php include 'siswa_form_tabs/tab_c.php'; ?>
                    <?php include 'siswa_form_tabs/tab_d.php'; ?>
                    <?php include 'siswa_form_tabs/tab_e.php'; ?>
                    <?php include 'siswa_form_tabs/tab_f.php'; ?>
                    <?php include 'siswa_form_tabs/tab_g.php'; ?>
                    <?php include 'siswa_form_tabs/tab_h.php'; ?>
                    <?php include 'siswa_form_tabs/tab_i.php'; ?>
                    <?php include 'siswa_form_tabs/tab_j.php'; ?>
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
