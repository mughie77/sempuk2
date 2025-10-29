<?php
// /pages/admin_kelas_detail.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . BASE_URL . "pages/admin_manage_kelas.php");
    exit();
}
$kelas_id = (int)$_GET['id'];

$sql_kelas = "SELECT nama_kelas FROM kelas WHERE kelas_id = ?";
$stmt_kelas = $mysqli->prepare($sql_kelas);
$stmt_kelas->bind_param("i", $kelas_id);
$stmt_kelas->execute();
$result_kelas = $stmt_kelas->get_result();
$class_details = $result_kelas->fetch_assoc();
$stmt_kelas->close();

if (!$class_details) {
    $_SESSION['flash_message'] = ['message' => 'Kelas tidak ditemukan.', 'type' => 'danger'];
    header("Location: " . BASE_URL . "pages/admin_manage_kelas.php");
    exit();
}

$students_in_class = [];
$sql_in_class = "SELECT siswa_id, nis, nama_lengkap FROM siswa WHERE kelas_id = ? ORDER BY nama_lengkap ASC";
$stmt_in_class = $mysqli->prepare($sql_in_class);
$stmt_in_class->bind_param("i", $kelas_id);
$stmt_in_class->execute();
$students_in_class = $stmt_in_class->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_in_class->close();

$students_without_class = [];
$sql_without_class = "SELECT siswa_id, nis, nama_lengkap FROM siswa WHERE kelas_id IS NULL OR kelas_id = '' ORDER BY nama_lengkap ASC";
$students_without_class = $mysqli->query($sql_without_class)->fetch_all(MYSQLI_ASSOC);

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Kelola Kelas: <?php echo htmlspecialchars($class_details['nama_kelas']); ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>pages/admin_manage_kelas.php">Manajemen Kelas</a></li>
        <li class="breadcrumb-item active">Kelola Siswa</li>
    </ol>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); endif; ?>

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-people-fill me-1"></i>Siswa di Kelas Ini (<?php echo count($students_in_class); ?>)</div>
                <div class="card-body">
                    <table class="table table-sm table-striped">
                        <thead><tr><th>NIS</th><th>Nama Lengkap</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php if (empty($students_in_class)): ?>
                                <tr><td colspan="3" class="text-center">Belum ada siswa di kelas ini.</td></tr>
                            <?php else: ?>
                                <?php foreach ($students_in_class as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['nis']); ?></td>
                                        <td><?php echo htmlspecialchars($student['nama_lengkap']); ?></td>
                                        <td>
                                            <form action="<?php echo BASE_URL; ?>core/kelas_member_process.php" method="POST" onsubmit="return confirm('Yakin ingin mengeluarkan siswa ini dari kelas?');">
                                                <input type="hidden" name="action" value="remove_student_from_class">
                                                <input type="hidden" name="kelas_id" value="<?php echo $kelas_id; ?>">
                                                <input type="hidden" name="siswa_id" value="<?php echo $student['siswa_id']; ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Keluarkan</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-person-plus-fill me-1"></i>Tambahkan Siswa ke Kelas</div>
                <div class="card-body">
                    <?php if (empty($students_without_class)): ?>
                        <div class="alert alert-info">Semua siswa sudah memiliki kelas.</div>
                    <?php else: ?>
                        <form action="<?php echo BASE_URL; ?>core/kelas_member_process.php" method="POST">
                            <input type="hidden" name="action" value="add_student_to_class">
                            <input type="hidden" name="kelas_id" value="<?php echo $kelas_id; ?>">
                            <div class="mb-3">
                                <label for="siswa-select" class="form-label">Pilih Siswa:</label>
                                <select name="siswa_id" id="siswa-select" class="form-select" required>
                                    <option value="">-- Pilih Siswa --</option>
                                    <?php foreach ($students_without_class as $student): ?>
                                        <option value="<?php echo $student['siswa_id']; ?>"><?php echo htmlspecialchars($student['nama_lengkap']) . ' (' . htmlspecialchars($student['nis']) . ')'; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambahkan ke Kelas</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
$mysqli->close();
?>
