<?php
// /pages/admin_manage_konsentrasi.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

$program_list = $mysqli->query("SELECT program_id, nama_program FROM program_keahlian ORDER BY nama_program ASC")->fetch_all(MYSQLI_ASSOC);

$concentrations = [];
$sql = "SELECT k.konsentrasi_id, k.nama_konsentrasi, p.nama_program
        FROM konsentrasi_keahlian k
        JOIN program_keahlian p ON k.program_id = p.program_id
        ORDER BY p.nama_program, k.nama_konsentrasi ASC";
if ($result = $mysqli->query($sql)) {
    $concentrations = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Konsentrasi Keahlian</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Konsentrasi Keahlian</li>
    </ol>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-star me-1"></i>Data Konsentrasi Keahlian</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Konsentrasi Baru
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th><th>Nama Konsentrasi Keahlian</th><th>Program Keahlian Induk</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($concentrations)): ?>
                            <tr><td colspan="4" class="text-center">Belum ada data.</td></tr>
                        <?php else: ?>
                            <?php $i = 1; foreach ($concentrations as $con): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($con['nama_konsentrasi']); ?></td>
                                    <td><?php echo htmlspecialchars($con['nama_program']); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $con['konsentrasi_id']; ?>"><i class="bi bi-pencil-square"></i></button>
                                        <form action="<?php echo BASE_URL; ?>core/konsentrasi_crud_process.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                            <input type="hidden" name="action" value="delete_konsentrasi"><input type="hidden" name="konsentrasi_id" value="<?php echo $con['konsentrasi_id']; ?>">
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

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Tambah Konsentrasi Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?php echo BASE_URL; ?>core/konsentrasi_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="add_konsentrasi">
          <div class="mb-3"><label class="form-label">Program Keahlian Induk</label><select name="program_id" class="form-select" required><?php foreach($program_list as $p) echo "<option value='{$p['program_id']}'>".htmlspecialchars($p['nama_program'])."</option>"; ?></select></div>
          <div class="mb-3"><label class="form-label">Nama Konsentrasi Keahlian</label><input type="text" name="nama_konsentrasi" class="form-control" required></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Konsentrasi Keahlian</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?php echo BASE_URL; ?>core/konsentrasi_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="edit_konsentrasi"><input type="hidden" name="konsentrasi_id" id="edit-konsentrasi_id">
          <div class="mb-3"><label class="form-label">Program Keahlian Induk</label><select name="program_id" id="edit-program_id" class="form-select" required><?php foreach($program_list as $p) echo "<option value='{$p['program_id']}'>".htmlspecialchars($p['nama_program'])."</option>"; ?></select></div>
          <div class="mb-3"><label class="form-label">Nama Konsentrasi Keahlian</label><input type="text" name="nama_konsentrasi" id="edit-nama_konsentrasi" class="form-control" required></div>
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
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const konsentrasiId = button.getAttribute('data-id');
        const url = `<?php echo BASE_URL; ?>core/konsentrasi_crud_process.php?action=get_konsentrasi_details&id=${konsentrasiId}`;

        fetch(url).then(res => res.json()).then(data => {
            if(data.error) { alert(data.error); }
            else {
                editModal.querySelector('#edit-konsentrasi_id').value = data.konsentrasi_id;
                editModal.querySelector('#edit-program_id').value = data.program_id;
                editModal.querySelector('#edit-nama_konsentrasi').value = data.nama_konsentrasi;
            }
        });
    });
});
</script>
