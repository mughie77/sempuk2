<!-- Sidebar -->
<div class="bg-dark border-right" id="sidebar-wrapper">
    <div class="sidebar-heading text-white">
        <i class="bi bi-bank me-2"></i>SEMPU
    </div>
    <div class="list-group list-group-flush my-3">
        <?php
        // Contoh menu dinamis berdasarkan peran
        $role = $_SESSION['role'] ?? 'Guest';

        echo '<a href="/dashboard.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>';

        if ($role == 'Administrator') {
            echo '<a href="/pages/admin_manage_users.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-people me-2"></i>Kelola Pengguna</a>';
            echo '<a href="/pages/admin_manage_siswa.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-person-badge me-2"></i>Kelola Siswa</a>';
            echo '<a href="/pages/admin_manage_guru.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-briefcase me-2"></i>Kelola Guru</a>';
        }

        if ($role == 'Guru') {
            echo '<a href="/pages/guru_isi_jurnal.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-journal-text me-2"></i>Isi Jurnal Mengajar</a>';
        }

        if ($role == 'Siswa') {
            echo '<a href="/pages/siswa_presensi.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-qr-code-scan me-2"></i>Presensi QR</a>';
            echo '<a href="/pages/siswa_cek_tabungan.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-cash-coin me-2"></i>Cek Tabungan</a>';
            echo '<a href="/pages/siswa_lapor_mood.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-emoji-smile me-2"></i>Lapor Mood</a>';
        }

        if ($role == 'BK') {
            echo '<a href="/pages/bk_dashboard.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-shield-check me-2"></i>Dashboard BK</a>';
        }

        if ($role == 'Petugas Tabungan') {
            echo '<a href="/pages/tabungan_transaksi.php" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi bi-wallet2 me-2"></i>Proses Transaksi</a>';
        }

        ?>
        <a href="/logout.php" class="list-group-item list-group-item-action bg-dark text-white text-danger mt-auto"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
    </div>
</div>
<!-- /#sidebar-wrapper -->
