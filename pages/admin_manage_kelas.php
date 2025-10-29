<?php
// /pages/admin_manage_kelas.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

// Ambil data untuk dropdown
$konsentrasi_list = $mysqli->query("SELECT konsentrasi_id, nama_konsentrasi FROM konsentrasi_keahlian ORDER BY nama_konsentrasi ASC")->fetch_all(MYSQLI_ASSOC);

$classes = [];
$sql = "SELECT k.kelas_id, k.nama_kelas, kon.nama_konsentrasi
        FROM kelas k
        LEFT JOIN konsentrasi_keahlian kon ON k.konsentrasi_id = kon.konsentrasi_id
        ORDER BY k.nama_kelas ASC";
if ($result = $mysqli->query($sql)) {
    $classes = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Data Kelas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Kelas</li>
    </ol>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-door-open me-1"></i>Data Kelas</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addKelasModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kelas Baru
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Kelas</th>
                            <th>Konsentrasi Keahlian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($classes)): ?>
                            <tr><td colspan="4" class="text-center">Belum ada data kelas.</td></tr>
                        <?php else: ?>
                            <?php $i = 1; foreach ($classes as $class): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($class['nama_kelas']); ?></td>
                                    <td><?php echo htmlspecialchars($class['nama_konsentrasi'] ?? 'N/A'); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>pages/admin_kelas_detail.php?id=<?php echo $class['kelas_id']; ?>" class="btn btn-info btn-sm" title="Kelola Siswa"><i class="bi bi-people"></i></a>
                                        <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editKelasModal" data-id="<?php echo $class['kelas_id']; ?>" title="Edit Kelas"><i class="bi bi-pencil-square"></i></button>
                                        <form action="<?php echo BASE_URL; ?>core/kelas_crud_process.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kelas ini?');">
                                            <input type="hidden" name="action" value="delete_kelas">
                                            <input type="hidden" name="kelas_id" value="<?php echo $class['kelas_id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Kelas"><i class="bi bi-trash"></i></button>
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

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="addKelasModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Tambah Kelas Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?php echo BASE_URL; ?>core/kelas_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="add_kelas">
          <div class="mb-3"><label class="form-label">Nama Kelas</label><input type="text" class="form-control" name="nama_kelas" required></div>
          <div class="mb-3"><label class="form-label">Konsentrasi Keahlian</label><select name="konsentrasi_id" class="form-select" required><?php foreach($konsentrasi_list as $k) echo "<option value='{$k['konsentrasi_id']}'>".htmlspecialchars($k['nama_konsentrasi'])."</option>"; ?></select></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Kelas -->
<div class="modal fade" id="editKelasModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Data Kelas</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?php echo BASE_URL; ?>core/kelas_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="edit_kelas"><input type="hidden" name="kelas_id" id="edit-kelas_id">
          <div class="mb-3"><label class="form-label">Nama Kelas</label><input type="text" class="form-control" id="edit-nama_kelas" name="nama_kelas" required></div>
          <div class="mb-3"><label class="form-label">Konsentrasi Keahlian</label><select name="konsentrasi_id" id="edit-konsentrasi_id" class="form-select" required><?php foreach($konsentrasi_list as $k) echo "<option value='{$k['konsentrasi_id']}'>".htmlspecialchars($k['nama_konsentrasi'])."</option>"; ?></select></div>
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
    const editKelasModal = document.getElementById('editKelasModal');
    editKelasModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const kelasId = button.getAttribute('data-id');
        const url = `<?php echo BASE_URL; ?>core/kelas_crud_process.php?action=get_kelas_details&id=${kelasId}`;

        fetch(url).then(res => res.json()).then(data => {
            if(data.error) { alert(data.error); }
            else {
                editKelasModal.querySelector('#edit-kelas_id').value = data.kelas_id;
                editKelasModal.querySelector('#edit-nama_kelas').value = data.nama_kelas;
                editKelasModal.querySelector('#edit-konsentrasi_id').value = data.konsentrasi_id;
            }
        });
    });
});
</script>
