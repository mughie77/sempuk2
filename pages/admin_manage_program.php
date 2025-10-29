<?php
// /pages/admin_manage_program.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

$programs = [];
$sql = "SELECT program_id, nama_program, deskripsi FROM program_keahlian ORDER BY nama_program ASC";
if ($result = $mysqli->query($sql)) {
    $programs = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Program Keahlian</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Program Keahlian</li>
    </ol>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-diagram-3 me-1"></i>Data Program Keahlian</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProgramModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Program Baru
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Program Keahlian</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($programs)): ?>
                            <tr><td colspan="4" class="text-center">Belum ada data program keahlian.</td></tr>
                        <?php else: ?>
                            <?php $i = 1; foreach ($programs as $program): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($program['nama_program']); ?></td>
                                    <td><?php echo htmlspecialchars($program['deskripsi']); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editProgramModal" data-id="<?php echo $program['program_id']; ?>"><i class="bi bi-pencil-square"></i></button>
                                        <form action="<?php echo BASE_URL; ?>core/program_crud_process.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus program ini? Ini akan menghapus konsentrasi keahlian di bawahnya.');">
                                            <input type="hidden" name="action" value="delete_program">
                                            <input type="hidden" name="program_id" value="<?php echo $program['program_id']; ?>">
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

<!-- Modal Tambah Program -->
<div class="modal fade" id="addProgramModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Tambah Program Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?php echo BASE_URL; ?>core/program_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="add_program">
          <div class="mb-3"><label for="add-nama_program" class="form-label">Nama Program</label><input type="text" class="form-control" id="add-nama_program" name="nama_program" required></div>
          <div class="mb-3"><label for="add-deskripsi" class="form-label">Deskripsi</label><textarea class="form-control" id="add-deskripsi" name="deskripsi" rows="3"></textarea></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Program -->
<div class="modal fade" id="editProgramModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Program Keahlian</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?php echo BASE_URL; ?>core/program_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="edit_program"><input type="hidden" name="program_id" id="edit-program_id">
          <div class="mb-3"><label for="edit-nama_program" class="form-label">Nama Program</label><input type="text" class="form-control" id="edit-nama_program" name="nama_program" required></div>
          <div class="mb-3"><label for="edit-deskripsi" class="form-label">Deskripsi</label><textarea class="form-control" id="edit-deskripsi" name="deskripsi" rows="3"></textarea></div>
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
    const editProgramModal = document.getElementById('editProgramModal');
    editProgramModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const programId = button.getAttribute('data-id');
        const url = `<?php echo BASE_URL; ?>core/program_crud_process.php?action=get_program_details&id=${programId}`;

        fetch(url).then(res => res.json()).then(data => {
            if(data.error) { alert(data.error); }
            else {
                editProgramModal.querySelector('#edit-program_id').value = data.program_id;
                editProgramModal.querySelector('#edit-nama_program').value = data.nama_program;
                editProgramModal.querySelector('#edit-deskripsi').value = data.deskripsi;
            }
        });
    });
});
</script>
