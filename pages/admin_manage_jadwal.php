<?php
// /pages/admin_manage_jadwal.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

$kelas_list = $mysqli->query("SELECT kelas_id, nama_kelas FROM kelas ORDER BY nama_kelas ASC")->fetch_all(MYSQLI_ASSOC);
$mapel_list = $mysqli->query("SELECT mapel_id, nama_mapel FROM mapel ORDER BY nama_mapel ASC")->fetch_all(MYSQLI_ASSOC);
$guru_list = $mysqli->query("SELECT guru_id, nama_lengkap FROM guru ORDER BY nama_lengkap ASC")->fetch_all(MYSQLI_ASSOC);

$schedules = [];
$sql = "SELECT j.jadwal_id, k.nama_kelas, m.nama_mapel, g.nama_lengkap as nama_guru, j.hari, j.jam_mulai, j.jam_selesai
        FROM jadwal_pelajaran j
        JOIN kelas k ON j.kelas_id = k.kelas_id
        JOIN mapel m ON j.mapel_id = m.mapel_id
        JOIN guru g ON j.guru_id = g.guru_id
        ORDER BY k.nama_kelas, FIELD(j.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), j.jam_mulai";
if ($result = $mysqli->query($sql)) {
    $schedules = $result->fetch_all(MYSQLI_ASSOC);
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Jadwal Pelajaran</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Jadwal</li>
    </ol>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); endif; ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-calendar3 me-1"></i>Data Jadwal Pelajaran</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJadwalModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Jadwal Baru
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Kelas</th><th>Hari</th><th>Jam</th><th>Mata Pelajaran</th><th>Guru</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($schedules)): ?>
                            <tr><td colspan="6" class="text-center">Belum ada data jadwal.</td></tr>
                        <?php else: ?>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($schedule['nama_kelas']); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['hari']); ?></td>
                                    <td><?php echo htmlspecialchars(date('H:i', strtotime($schedule['jam_mulai']))) . ' - ' . htmlspecialchars(date('H:i', strtotime($schedule['jam_selesai']))); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['nama_mapel']); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['nama_guru']); ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editJadwalModal" data-id="<?php echo $schedule['jadwal_id']; ?>"><i class="bi bi-pencil-square"></i></button>
                                        <form action="<?php echo BASE_URL; ?>core/jadwal_crud_process.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                            <input type="hidden" name="action" value="delete_jadwal"><input type="hidden" name="jadwal_id" value="<?php echo $schedule['jadwal_id']; ?>">
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

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="addJadwalModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Tambah Jadwal Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?php echo BASE_URL; ?>core/jadwal_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="add_jadwal">
          <div class="mb-3"><label class="form-label">Kelas</label><select name="kelas_id" class="form-select" required><?php foreach($kelas_list as $k) echo "<option value='{$k['kelas_id']}'>".htmlspecialchars($k['nama_kelas'])."</option>"; ?></select></div>
          <div class="mb-3"><label class="form-label">Mata Pelajaran</label><select name="mapel_id" class="form-select" required><?php foreach($mapel_list as $m) echo "<option value='{$m['mapel_id']}'>".htmlspecialchars($m['nama_mapel'])."</option>"; ?></select></div>
          <div class="mb-3"><label class="form-label">Guru</label><select name="guru_id" class="form-select" required><?php foreach($guru_list as $g) echo "<option value='{$g['guru_id']}'>".htmlspecialchars($g['nama_lengkap'])."</option>"; ?></select></div>
          <div class="mb-3"><label class="form-label">Hari</label><select name="hari" class="form-select" required><option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option></select></div>
          <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Jam Mulai</label><input type="time" name="jam_mulai" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Jam Selesai</label><input type="time" name="jam_selesai" class="form-control" required></div>
          </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Jadwal -->
<div class="modal fade" id="editJadwalModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Jadwal</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?php echo BASE_URL; ?>core/jadwal_crud_process.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="action" value="edit_jadwal"><input type="hidden" name="jadwal_id" id="edit-jadwal_id">
          <div class="mb-3"><label class="form-label">Kelas</label><select name="kelas_id" id="edit-kelas_id_select" class="form-select" required><?php foreach($kelas_list as $k) echo "<option value='{$k['kelas_id']}'>".htmlspecialchars($k['nama_kelas'])."</option>"; ?></select></div>
          <div class="mb-3"><label class="form-label">Mata Pelajaran</label><select name="mapel_id" id="edit-mapel_id_select" class="form-select" required><?php foreach($mapel_list as $m) echo "<option value='{$m['mapel_id']}'>".htmlspecialchars($m['nama_mapel'])."</option>"; ?></select></div>
          <div class="mb-3"><label class="form-label">Guru</label><select name="guru_id" id="edit-guru_id_select" class="form-select" required><?php foreach($guru_list as $g) echo "<option value='{$g['guru_id']}'>".htmlspecialchars($g['nama_lengkap'])."</option>"; ?></select></div>
          <div class="mb-3"><label class="form-label">Hari</label><select name="hari" id="edit-hari" class="form-select" required><option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option></select></div>
          <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Jam Mulai</label><input type="time" name="jam_mulai" id="edit-jam_mulai" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Jam Selesai</label><input type="time" name="jam_selesai" id="edit-jam_selesai" class="form-control" required></div>
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
    const editJadwalModal = document.getElementById('editJadwalModal');
    editJadwalModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const jadwalId = button.getAttribute('data-id');
        const url = `<?php echo BASE_URL; ?>core/jadwal_crud_process.php?action=get_jadwal_details&id=${jadwalId}`;

        fetch(url).then(res => res.json()).then(data => {
            if(data.error) { alert(data.error); }
            else {
                editJadwalModal.querySelector('#edit-jadwal_id').value = data.jadwal_id;
                editJadwalModal.querySelector('#edit-kelas_id_select').value = data.kelas_id;
                editJadwalModal.querySelector('#edit-mapel_id_select').value = data.mapel_id;
                editJadwalModal.querySelector('#edit-guru_id_select').value = data.guru_id;
                editJadwalModal.querySelector('#edit-hari').value = data.hari;
                editJadwalModal.querySelector('#edit-jam_mulai').value = data.jam_mulai;
                editJadwalModal.querySelector('#edit-jam_selesai').value = data.jam_selesai;
            }
        });
    });
});
</script>
