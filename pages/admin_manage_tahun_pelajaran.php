<?php
// /pages/admin_manage_tahun_pelajaran.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

$academic_years = [];
$sql = "SELECT tahun_pelajaran_id, tahun_ajaran, status FROM tahun_pelajaran ORDER BY tahun_ajaran DESC";
if ($result = $mysqli->query($sql)) {
    $academic_years = $result->fetch_all(MYSQLI_ASSOC);
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Tahun Pelajaran</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Tahun Pelajaran</li>
    </ol>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-calendar-event me-1"></i>Data Tahun Pelajaran</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Tahun Pelajaran
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Tahun Ajaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($academic_years)): ?>
                        <tr><td colspan="3" class="text-center">Belum ada data.</td></tr>
                    <?php else: ?>
                        <?php foreach ($academic_years as $year): ?>
                            <tr>
                                <td class="align-middle"><?php echo htmlspecialchars($year['tahun_ajaran']); ?></td>
                                <td class="align-middle">
                                    <?php if ($year['status'] === 'Aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($year['status'] !== 'Aktif'): ?>
                                        <form action="<?php echo BASE_URL; ?>core/tahun_pelajaran_process.php" method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="set_active">
                                            <input type="hidden" name="tahun_pelajaran_id" value="<?php echo $year['tahun_pelajaran_id']; ?>">
                                            <button type="submit" class="btn btn-success btn-sm">Jadikan Aktif</button>
                                        </form>
                                        <form action="<?php echo BASE_URL; ?>core/tahun_pelajaran_process.php" method="POST" class="d-inline" onsubmit="return confirm('PENTING: Menghapus tahun pelajaran akan menghapus SEMUA data yang terkait (jadwal, absensi, jurnal, dll) di tahun tersebut. Yakin ingin melanjutkan?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="tahun_pelajaran_id" value="<?php echo $year['tahun_pelajaran_id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-danger btn-sm" disabled>Hapus</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Tambah Tahun Pelajaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?php echo BASE_URL; ?>core/tahun_pelajaran_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="add">
          <div class="mb-3">
            <label for="tahun_ajaran" class="form-label">Format Tahun Ajaran (e.g., 2023/2024)</label>
            <input type="text" class="form-control" name="tahun_ajaran" id="tahun_ajaran" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


<?php
include __DIR__ . '/../includes/footer.php';
$mysqli->close();
?>
