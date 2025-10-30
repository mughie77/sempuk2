<?php
// /pages/admin_kenaikan_kelas.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';

// Ambil daftar kelas untuk dropdown
$kelas_list = $mysqli->query("SELECT kelas_id, nama_kelas FROM kelas ORDER BY nama_kelas ASC")->fetch_all(MYSQLI_ASSOC);

$students_in_class = [];
$selected_kelas_id = null;
if (isset($_GET['kelas_id']) && is_numeric($_GET['kelas_id'])) {
    $selected_kelas_id = (int)$_GET['kelas_id'];
    $stmt = $mysqli->prepare("SELECT siswa_id, nis, nama_lengkap FROM siswa WHERE kelas_id = ? ORDER BY nama_lengkap ASC");
    $stmt->bind_param("i", $selected_kelas_id);
    $stmt->execute();
    $students_in_class = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Alat Bantu Kenaikan Kelas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Kenaikan Kelas</li>
    </ol>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); endif; ?>

    <!-- Step 1: Pilih Kelas Asal -->
    <div class="card mb-4">
        <div class="card-header">Langkah 1: Pilih Kelas Asal</div>
        <div class="card-body">
            <form method="GET">
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label for="kelas_id" class="form-label">Pilih kelas yang siswanya akan dipindahkan:</label>
                        <select name="kelas_id" id="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelas_list as $kelas): ?>
                                <option value="<?php echo $kelas['kelas_id']; ?>" <?php echo ($selected_kelas_id == $kelas['kelas_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($kelas['nama_kelas']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Tampilkan Siswa</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if ($selected_kelas_id && !empty($students_in_class)): ?>
    <!-- Step 2 & 3: Pilih Siswa dan Kelas Tujuan -->
    <form action="<?php echo BASE_URL; ?>core/kenaikan_kelas_process.php" method="POST">
        <input type="hidden" name="action" value="process_kenaikan">
        <input type="hidden" name="kelas_asal_id" value="<?php echo $selected_kelas_id; ?>">

        <div class="card">
            <div class="card-header">Langkah 2 & 3: Pilih Siswa dan Kelas Tujuan</div>
            <div class="card-body">
                <h5>Pilih Siswa yang Akan Dinaikkan/Diluluskan</h5>
                <div class="table-responsive mb-3" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students_in_class as $student): ?>
                                <tr>
                                    <td><input type="checkbox" name="siswa_ids[]" value="<?php echo $student['siswa_id']; ?>"></td>
                                    <td><?php echo htmlspecialchars($student['nis']); ?></td>
                                    <td><?php echo htmlspecialchars($student['nama_lengkap']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <hr>

                <h5>Pilih Aksi</h5>
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <label for="kelas_tujuan_id" class="form-label">Pindahkan ke Kelas Tujuan:</label>
                        <select name="kelas_tujuan_id" id="kelas_tujuan_id" class="form-select">
                            <option value="">-- Pilih Kelas Tujuan --</option>
                            <?php foreach ($kelas_list as $kelas): ?>
                                <?php if ($kelas['kelas_id'] != $selected_kelas_id): ?>
                                <option value="<?php echo $kelas['kelas_id']; ?>"><?php echo htmlspecialchars($kelas['nama_kelas']); ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                             <option value="lulus">** LULUSKAN SISWA (Hapus dari Kelas) **</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success mt-4" onclick="return confirm('Apakah Anda yakin ingin memproses siswa yang dipilih?');">
                            Proses Kenaikan Kelas
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
    <?php elseif ($selected_kelas_id): ?>
        <div class="alert alert-info">Tidak ada siswa di kelas yang dipilih.</div>
    <?php endif; ?>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
$mysqli->close();
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[name="siswa_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});
</script>
