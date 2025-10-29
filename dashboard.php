<?php
// /dashboard.php

// 1. Sertakan skrip auth_check di paling atas
// Ini akan memastikan hanya pengguna terotentikasi yang dapat mengakses halaman ini
require_once __DIR__ . '/core/auth_check.php';

// Tidak perlu memanggil require_role() di sini karena dashboard bisa diakses semua peran

// 2. Sertakan header template
include __DIR__ . '/includes/header.php';

// 3. Sertakan sidebar
include __DIR__ . '/includes/sidebar.php';

// 4. Sertakan topbar dan pembuka konten utama
include __DIR__ . '/includes/topbar.php';
?>

<!-- Konten Utama Dashboard Dimulai Di Sini -->
<div class="container-fluid px-4">
    <h1 class="mt-4">Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</h1>
    <p class="lead">Anda login sebagai: <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong></p>

    <div class="row">
        <?php
        // 5. Logika untuk menampilkan widget berdasarkan peran
        $role = $_SESSION['role'];

        if ($role == 'Administrator') {
            // Konten untuk Administrator
            echo '
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-people me-2"></i>Kelola Pengguna</h5>
                        <p class="card-text">Atur akun dan peran pengguna.</p>
                        <a href="<?php echo BASE_URL; ?>pages/admin_manage_users.php" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-person-badge me-2"></i>Data Siswa</h5>
                        <p class="card-text">Lihat dan kelola data induk siswa.</p>
                         <a href="#" class="stretched-link"></a>
                    </div>
                </div>
            </div>';
        } elseif ($role == 'Guru') {
            // Konten untuk Guru
            echo '
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-journal-text me-2"></i>Isi Jurnal Mengajar</h5>
                        <p class="card-text">Lengkapi jurnal harian kelas Anda.</p>
                        <a href="<?php echo BASE_URL; ?>pages/guru_isi_jurnal.php" class="stretched-link"></a>
                    </div>
                </div>
            </div>';
        } elseif ($role == 'Siswa') {
            // Konten untuk Siswa
             echo '
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-qr-code-scan me-2"></i>Presensi Hari Ini</h5>
                        <p class="card-text">Lakukan presensi dengan scan QR.</p>
                        <a href="<?php echo BASE_URL; ?>pages/siswa_presensi.php" class="stretched-link"></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-cash-coin me-2"></i>Saldo Tabungan</h5>
                        <p class="card-text">Cek saldo dan riwayat transaksi.</p>
                        <a href="<?php echo BASE_URL; ?>pages/siswa_cek_tabungan.php" class="stretched-link"></a>
                    </div>
                </div>
            </div>';
        }
        // Tambahkan blok lain untuk peran 'BK', 'Orang Tua', 'Petugas Tabungan', dll.

        ?>
    </div>

    <!-- Contoh komponen lain -->
    <div class="card mt-4">
        <div class="card-header">
            <i class="bi bi-calendar-event me-2"></i>Pengumuman Sekolah
        </div>
        <div class="card-body">
            <p>Belum ada pengumuman baru saat ini.</p>
        </div>
    </div>

</div>
<!-- Konten Utama Dashboard Berakhir Di Sini -->

<?php
// 6. Sertakan footer template
include __DIR__ . '/includes/footer.php';
?>
