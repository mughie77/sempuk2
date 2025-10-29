<?php
// /pages/admin_manage_users.php

// 1. Sertakan file auth_check.php untuk memastikan pengguna terotentikasi dan memiliki peran yang benar
require_once __DIR__ . '/../core/auth_check.php';

// 2. Batasi akses hanya untuk Administrator
require_role(['Administrator']);

// Sertakan koneksi database untuk mengambil data
require_once __DIR__ . '/../core/db_connect.php';

// 3. Ambil semua data pengguna dari database
$users = [];
$sql = "SELECT user_id, username, nama_lengkap, role, created_at FROM users ORDER BY created_at DESC";
$result = $mysqli->query($sql);

if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}
$mysqli->close();

// 4. Sertakan template header
include __DIR__ . '/../includes/header.php';

// 5. Sertakan template sidebar
include __DIR__ . '/../includes/sidebar.php';

// 6. Sertakan template topbar
include __DIR__ . '/../includes/topbar.php';
?>

<!-- Konten Utama Halaman -->
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Pengguna</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Pengguna</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-table me-1"></i>Data Pengguna</span>
            <button class="btn btn-primary btn-sm">
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
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data pengguna.</td>
                            </tr>
                        <?php else: ?>
                            <?php $i = 1; foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($user['role']); ?></span></td>
                                    <td><?php echo date('d M Y, H:i', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm" title="Lihat Detail"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil-square"></i></button>
                                        <button class="btn btn-danger btn-sm" title="Hapus"><i class="bi bi-trash"></i></button>
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
<!-- Akhir Konten Utama -->

<?php
// 7. Sertakan template footer
include __DIR__ . '/../includes/footer.php';
?>
