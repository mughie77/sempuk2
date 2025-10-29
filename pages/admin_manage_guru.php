<?php
// /pages/admin_manage_guru.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

$teachers = [];
$sql = "SELECT guru_id, nip, nama_lengkap, alamat, telepon FROM guru ORDER BY nama_lengkap ASC";
if ($result = $mysqli->query($sql)) {
    $teachers = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Data Guru</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Guru</li>
    </ol>

    <!-- Flash Message -->
    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-briefcase me-1"></i>Data Induk Guru</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGuruModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Guru Baru
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama Lengkap</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($teachers)): ?>
                            <tr><td colspan="6" class="text-center">Belum ada data guru.</td></tr>
                        <?php else: ?>
                            <?php $i = 1; foreach ($teachers as $teacher): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($teacher['nip']); ?></td>
                                    <td><?php echo htmlspecialchars($teacher['nama_lengkap']); ?></td>
                                    <td><?php echo htmlspecialchars($teacher['alamat']); ?></td>
                                    <td><?php echo htmlspecialchars($teacher['telepon']); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" title="Edit"
                                                data-bs-toggle="modal" data-bs-target="#editGuruModal"
                                                data-id="<?php echo $teacher['guru_id']; ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="<?php echo BASE_URL; ?>core/guru_crud_process.php" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?');">
                                            <input type="hidden" name="action" value="delete_guru">
                                            <input type="hidden" name="guru_id" value="<?php echo $teacher['guru_id']; ?>">
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
</div>

<!-- Modal Tambah Guru -->
<div class="modal fade" id="addGuruModal" tabindex="-1" aria-labelledby="addGuruModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addGuruModalLabel">Tambah Guru Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo BASE_URL; ?>core/guru_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="add_guru">
          <div class="mb-3">
            <label for="add-nip" class="form-label">NIP</label>
            <input type="text" class="form-control" id="add-nip" name="nip" required>
          </div>
          <div class="mb-3">
            <label for="add-nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="add-nama_lengkap" name="nama_lengkap" required>
          </div>
          <div class="mb-3">
            <label for="add-alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="add-alamat" name="alamat" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="add-telepon" class="form-label">Telepon</label>
            <input type="text" class="form-control" id="add-telepon" name="telepon">
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

<!-- Modal Edit Guru -->
<div class="modal fade" id="editGuruModal" tabindex="-1" aria-labelledby="editGuruModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editGuruModalLabel">Edit Data Guru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo BASE_URL; ?>core/guru_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="edit_guru">
          <input type="hidden" name="guru_id" id="edit-guru_id">
          <div class="mb-3">
            <label for="edit-nip" class="form-label">NIP</label>
            <input type="text" class="form-control" id="edit-nip" name="nip" required>
          </div>
          <div class="mb-3">
            <label for="edit-nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="edit-nama_lengkap" name="nama_lengkap" required>
          </div>
           <div class="mb-3">
            <label for="edit-alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="edit-alamat" name="alamat" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="edit-telepon" class="form-label">Telepon</label>
            <input type="text" class="form-control" id="edit-telepon" name="telepon">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
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
    const editGuruModal = document.getElementById('editGuruModal');
    editGuruModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const guruId = button.getAttribute('data-id');
        const url = `<?php echo BASE_URL; ?>core/guru_crud_process.php?action=get_guru_details&id=${guruId}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if(data.error) {
                    alert(data.error);
                } else {
                    const modal = editGuruModal;
                    modal.querySelector('#edit-guru_id').value = data.guru_id;
                    modal.querySelector('#edit-nip').value = data.nip;
                    modal.querySelector('#edit-nama_lengkap').value = data.nama_lengkap;
                    modal.querySelector('#edit-alamat').value = data.alamat;
                    modal.querySelector('#edit-telepon').value = data.telepon;
                }
            })
            .catch(error => console.error('Error:', error));
    });
});
</script>
