<?php
// /pages/admin_manage_users.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

$users = [];
$sql = "SELECT user_id, username, nama_lengkap, role, created_at FROM users ORDER BY created_at DESC";
if ($result = $mysqli->query($sql)) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<!-- Konten Utama Halaman -->
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Pengguna</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Pengguna</li>
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
            <span><i class="bi bi-table me-1"></i>Data Pengguna</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Pengguna Baru
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Peran (Role)</th>
                            <th>Tanggal Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr><td colspan="6" class="text-center">Tidak ada data pengguna.</td></tr>
                        <?php else: ?>
                            <?php $i = 1; foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($user['role']); ?></span></td>
                                    <td><?php echo date('d M Y, H:i', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" title="Edit"
                                                data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                data-id="<?php echo $user['user_id']; ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="<?php echo BASE_URL; ?>core/user_crud_process.php" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
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

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo BASE_URL; ?>core/user_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="add_user">
          <div class="mb-3">
            <label for="add-username" class="form-label">Username</label>
            <input type="text" class="form-control" id="add-username" name="username" required>
          </div>
          <div class="mb-3">
            <label for="add-nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="add-nama_lengkap" name="nama_lengkap" required>
          </div>
          <div class="mb-3">
            <label for="add-password" class="form-label">Password</label>
            <input type="password" class="form-control" id="add-password" name="password" required>
          </div>
          <div class="mb-3">
            <label for="add-role" class="form-label">Peran (Role)</label>
            <select class="form-select" id="add-role" name="role" required>
                <option value="Administrator">Administrator</option>
                <option value="Guru">Guru</option>
                <option value="Siswa">Siswa</option>
                <option value="Orang Tua">Orang Tua</option>
                <option value="Petugas Tabungan">Petugas Tabungan</option>
                <option value="BK">BK</option>
            </select>
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

<!-- Modal Edit Pengguna -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Edit Data Pengguna</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?php echo BASE_URL; ?>core/user_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="edit_user">
          <input type="hidden" name="user_id" id="edit-user_id">
          <div class="mb-3">
            <label for="edit-username" class="form-label">Username</label>
            <input type="text" class="form-control" id="edit-username" name="username" required>
          </div>
          <div class="mb-3">
            <label for="edit-nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="edit-nama_lengkap" name="nama_lengkap" required>
          </div>
          <div class="mb-3">
            <label for="edit-password" class="form-label">Password Baru (Opsional)</label>
            <input type="password" class="form-control" id="edit-password" name="password">
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
          </div>
          <div class="mb-3">
            <label for="edit-role" class="form-label">Peran (Role)</label>
            <select class="form-select" id="edit-role" name="role" required>
                <option value="Administrator">Administrator</option>
                <option value="Guru">Guru</option>
                <option value="Siswa">Siswa</option>
                <option value="Orang Tua">Orang Tua</option>
                <option value="Petugas Tabungan">Petugas Tabungan</option>
                <option value="BK">BK</option>
            </select>
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
    const editUserModal = document.getElementById('editUserModal');
    editUserModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-id');

        // URL untuk mengambil data
        const url = `<?php echo BASE_URL; ?>core/user_crud_process.php?action=get_user_details&id=${userId}`;

        // Ambil data pengguna via Fetch API
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if(data.error) {
                    alert(data.error);
                } else {
                    // Isi form di dalam modal
                    const modal = editUserModal;
                    modal.querySelector('#edit-user_id').value = data.user_id;
                    modal.querySelector('#edit-username').value = data.username;
                    modal.querySelector('#edit-nama_lengkap').value = data.nama_lengkap;
                    modal.querySelector('#edit-role').value = data.role;
                }
            })
            .catch(error => console.error('Error:', error));
    });
});
</script>
