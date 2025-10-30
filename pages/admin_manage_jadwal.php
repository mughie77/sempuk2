<?php
// /pages/admin_manage_jadwal.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

// Ambil semua kelas beserta jadwalnya (jika ada)
$classes = [];
$sql = "SELECT k.kelas_id, k.nama_kelas, jf.file_path, jf.original_filename
        FROM kelas k
        LEFT JOIN jadwal_files jf ON k.kelas_id = jf.kelas_id
        ORDER BY k.nama_kelas ASC";
if ($result = $mysqli->query($sql)) {
    $classes = $result->fetch_all(MYSQLI_ASSOC);
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Unggah Jadwal</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Unggah Jadwal</li>
    </ol>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-calendar-plus me-1"></i>
            Unggah Jadwal per Kelas
        </div>
        <div class="card-body">
            <p>Pilih kelas, unggah file jadwal (PDF/Excel), dan file lama akan otomatis digantikan.</p>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Jadwal Saat Ini</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($classes)): ?>
                            <tr><td colspan="3" class="text-center">Belum ada data kelas. Buat kelas terlebih dahulu.</td></tr>
                        <?php else: ?>
                            <?php foreach ($classes as $class): ?>
                                <tr>
                                    <td class="align-middle"><strong><?php echo htmlspecialchars($class['nama_kelas']); ?></strong></td>
                                    <td class="align-middle">
                                        <?php if ($class['file_path']): ?>
                                            <a href="<?php echo BASE_URL . 'assets/schedules/' . $class['file_path']; ?>" target="_blank">
                                                <i class="bi bi-file-earmark-text"></i> <?php echo htmlspecialchars($class['original_filename']); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Belum ada jadwal</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle">
                                        <form action="<?php echo BASE_URL; ?>core/jadwal_upload_process.php" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                                            <input type="hidden" name="action" value="upload_jadwal">
                                            <input type="hidden" name="kelas_id" value="<?php echo $class['kelas_id']; ?>">
                                            <input type="file" name="jadwal_file" class="form-control form-control-sm" required>
                                            <button type="submit" class="btn btn-primary btn-sm" title="Unggah Jadwal Baru"><i class="bi bi-upload"></i></button>

                                            <?php if ($class['file_path']): ?>
                                            <button type="button" class="btn btn-danger btn-sm" title="Hapus Jadwal" data-bs-toggle="modal" data-bs-target="#deleteJadwalModal" data-kelasid="<?php echo $class['kelas_id']; ?>"><i class="bi bi-trash"></i></button>
                                            <?php endif; ?>
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
</div>

<!-- Modal Hapus Jadwal -->
<div class="modal fade" id="deleteJadwalModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Penghapusan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus file jadwal untuk kelas ini?</p>
      </div>
      <div class="modal-footer">
        <form action="<?php echo BASE_URL; ?>core/jadwal_upload_process.php" method="POST">
            <input type="hidden" name="action" value="delete_jadwal">
            <input type="hidden" name="kelas_id" id="delete-kelas-id">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
$mysqli->close();
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteJadwalModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const kelasId = button.getAttribute('data-kelasid');
        deleteModal.querySelector('#delete-kelas-id').value = kelasId;
    });
});
</script>
