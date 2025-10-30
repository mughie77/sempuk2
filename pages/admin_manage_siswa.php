<?php
// /pages/admin_manage_siswa.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

$students = [];
// Join dengan kelas untuk menampilkan nama kelas
$sql = "SELECT s.siswa_id, s.nis, s.nama_lengkap, s.telepon, k.nama_kelas
        FROM siswa s
        LEFT JOIN kelas k ON s.kelas_id = k.kelas_id
        ORDER BY s.nama_lengkap ASC";
if ($result = $mysqli->query($sql)) {
    $students = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Data Siswa</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Siswa</li>
    </ol>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>core/generate_pdf_buku_induk.php" method="POST" target="_blank">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-badge me-1"></i>Data Induk Siswa</span>
                <div>
                    <button type="submit" class="btn btn-info btn-sm">
                        <i class="bi bi-printer me-1"></i> Cetak Laporan PDF
                    </button>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                        <i class="bi bi-file-earmark-excel me-1"></i> Impor dari Excel
                    </button>
                    <a href="<?php echo BASE_URL; ?>pages/admin_siswa_form.php" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Siswa Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 1%;"><input type="checkbox" id="select-all-checkbox"></th>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas</th>
                                <th>Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($students)): ?>
                                <tr><td colspan="7" class="text-center">Belum ada data siswa.</td></tr>
                            <?php else: ?>
                                <?php $i = 1; foreach ($students as $student): ?>
                                    <tr>
                                        <td><input type="checkbox" name="siswa_ids[]" value="<?php echo $student['siswa_id']; ?>" class="student-checkbox"></td>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo htmlspecialchars($student['nis']); ?></td>
                                        <td><?php echo htmlspecialchars($student['nama_lengkap']); ?></td>
                                        <td><?php echo htmlspecialchars($student['nama_kelas'] ?? 'Belum ada kelas'); ?></td>
                                        <td><?php echo htmlspecialchars($student['telepon']); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>pages/admin_siswa_form.php?edit_id=<?php echo $student['siswa_id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>core/generate_pdf_buku_induk.php?siswa_id=<?php echo $student['siswa_id']; ?>" class="btn btn-info btn-sm" title="Cetak PDF" target="_blank">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>core/siswa_crud_process.php" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini? Ini juga akan menghapus akun login siswa.');">
                                                <input type="hidden" name="action" value="delete_siswa">
                                                <input type="hidden" name="siswa_id" value="<?php echo $student['siswa_id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i class="bi bi-trash"></i></button>
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
    </form>
</div>

<!-- Modal Impor Excel -->
<div class="modal fade" id="importExcelModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Impor Data Siswa dari Excel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?php echo BASE_URL; ?>core/siswa_import_excel.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <p>Unggah file Excel (.xlsx) dengan kolom yang sesuai untuk mengimpor banyak data siswa sekaligus. Akun login akan dibuat secara otomatis menggunakan NIS sebagai username dan password awal.</p>
            <div class="mb-3">
                <label for="excel_file" class="form-label">Pilih File Excel (.xlsx)</label>
                <input type="file" class="form-control" name="excel_file" id="excel_file" accept=".xlsx" required>
            </div>
            <p><a href="<?php echo BASE_URL; ?>core/download_template.php">Unduh Template Excel (.xlsx)</a></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Impor</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const studentCheckboxes = document.querySelectorAll('.student-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        studentCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });
});
</script>

<?php
include __DIR__ . '/../includes/footer.php';
$mysqli->close();
?>
