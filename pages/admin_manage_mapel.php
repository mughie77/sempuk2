<?php
// /pages/admin_manage_mapel.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

$subjects = [];
$sql = "SELECT mapel_id, nama_mapel FROM mapel ORDER BY nama_mapel ASC";
if ($result = $mysqli->query($sql)) {
    $subjects = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Mata Pelajaran</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Mapel</li>
    </ol>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-book me-1"></i>Data Mata Pelajaran</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMapelModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Mapel Baru
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($subjects)): ?>
                            <tr><td colspan="3" class="text-center">Belum ada data mata pelajaran.</td></tr>
                        <?php else: ?>
                            <?php $i = 1; foreach ($subjects as $subject): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($subject['nama_mapel']); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editMapelModal" data-id="<?php echo $subject['mapel_id']; ?>"><i class="bi bi-pencil-square"></i></button>
                                        <form action="<?php echo BASE_URL; ?>core/mapel_crud_process.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?');">
                                            <input type="hidden" name="action" value="delete_mapel">
                                            <input type="hidden" name="mapel_id" value="<?php echo $subject['mapel_id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
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

<!-- Modal Tambah Mapel -->
<div class="modal fade" id="addMapelModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Tambah Mapel Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?php echo BASE_URL; ?>core/mapel_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="add_mapel">
          <div class="mb-3">
            <label for="add-nama_mapel" class="form-label">Nama Mata Pelajaran</label>
            <input type="text" class="form-control" id="add-nama_mapel" name="nama_mapel" required>
          </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Mapel -->
<div class="modal fade" id="editMapelModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Data Mata Pelajaran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?php echo BASE_URL; ?>core/mapel_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="edit_mapel">
          <input type="hidden" name="mapel_id" id="edit-mapel_id">
          <div class="mb-3">
            <label for="edit-nama_mapel" class="form-label">Nama Mata Pelajaran</label>
            <input type="text" class="form-control" id="edit-nama_mapel" name="nama_mapel" required>
          </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan Perubahan</button></div>
      </form>
    </div>
  </div>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
$mysqli->close();
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editMapelModal = document.getElementById('editMapelModal');
    editMapelModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const mapelId = button.getAttribute('data-id');
        const url = `<?php echo BASE_URL; ?>core/mapel_crud_process.php?action=get_mapel_details&id=${mapelId}`;

        fetch(url).then(res => res.json()).then(data => {
            if(data.error) { alert(data.error); }
            else {
                editMapelModal.querySelector('#edit-mapel_id').value = data.mapel_id;
                editMapelModal.querySelector('#edit-nama_mapel').value = data.nama_mapel;
            }
        });
    });
});
</script>
