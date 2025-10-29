<!-- Sidebar -->
<div class="bg-dark border-right" id="sidebar-wrapper">
    <div class="sidebar-heading text-white">
        <i class="bi bi-bank me-2"></i>SEMPU
    </div>
    <div class="list-group list-group-flush my-3">
        <?php
        // Contoh menu dinamis berdasarkan peran
        $role = $_SESSION['role'] ?? 'Guest';

        // Helper function untuk membuat link dengan BASE_URL
        function nav_link($url, $icon, $text) {
            return '<a href="' . BASE_URL . $url . '" class="list-group-item list-group-item-action bg-dark text-white"><i class="bi ' . $icon . ' me-2"></i>' . $text . '</a>';
        }

        echo nav_link('dashboard.php', 'bi-speedometer2', 'Dashboard');

        if ($role == 'Administrator') {
            echo nav_link('pages/admin_manage_users.php', 'bi-people', 'Kelola Pengguna');
            echo nav_link('pages/admin_manage_siswa.php', 'bi-person-badge', 'Kelola Siswa');
            echo nav_link('pages/admin_manage_guru.php', 'bi-briefcase', 'Kelola Guru');
        }

        if ($role == 'Guru') {
            echo nav_link('pages/guru_isi_jurnal.php', 'bi-journal-text', 'Isi Jurnal Mengajar');
        }

        if ($role == 'Siswa') {
            echo nav_link('pages/siswa_presensi.php', 'bi-qr-code-scan', 'Presensi QR');
            echo nav_link('pages/siswa_cek_tabungan.php', 'bi-cash-coin', 'Cek Tabungan');
            echo nav_link('pages/siswa_lapor_mood.php', 'bi-emoji-smile', 'Lapor Mood');
        }

        if ($role == 'BK') {
            echo nav_link('pages/bk_dashboard.php', 'bi-shield-check', 'Dashboard BK');
        }

        if ($role == 'Petugas Tabungan') {
            echo nav_link('pages/tabungan_transaksi.php', 'bi-wallet2', 'Proses Transaksi');
        }

        ?>
        <a href="<?php echo BASE_URL; ?>logout.php" class="list-group-item list-group-item-action bg-dark text-white text-danger mt-auto"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
    </div>
</div>
<!-- /#sidebar-wrapper -->
