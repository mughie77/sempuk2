<!-- Sidebar -->
<div class="bg-dark border-right" id="sidebar-wrapper">
    <div class="sidebar-heading text-white">
        <i class="bi bi-bank me-2"></i>SEMPU
    </div>
    <div class="list-group list-group-flush my-3">
        <?php
        $role = $_SESSION['role'] ?? 'Guest';
        $current_page = $_SERVER['REQUEST_URI'];

        // Helper function to create a single nav link
        function render_nav_link($url, $icon, $text) {
            $is_active = strpos($GLOBALS['current_page'], $url) !== false;
            $active_class = $is_active ? 'active' : '';
            echo '<a href="' . BASE_URL . $url . '" class="list-group-item list-group-item-action bg-dark text-white ' . $active_class . '"><i class="bi ' . $icon . ' me-2"></i>' . $text . '</a>';
        }

        // Helper for sub-menu links (indented)
        function render_sub_link($url, $icon, $text) {
            $is_active = strpos($GLOBALS['current_page'], $url) !== false;
            $active_class = $is_active ? 'active' : '';
            echo '<a href="' . BASE_URL . $url . '" class="list-group-item list-group-item-action bg-dark text-white ' . $active_class . '" style="padding-left: 2.5rem;"><i class="bi ' . $icon . ' me-2"></i>' . $text . '</a>';
        }

        // Helper function to create a collapsible dropdown menu
        function render_dropdown($id, $icon, $text, $links) {
            $is_active_parent = false;
            foreach ($links as $link) {
                if (strpos($GLOBALS['current_page'], $link['url']) !== false) {
                    $is_active_parent = true;
                    break;
                }
            }

            $collapsed_class = $is_active_parent ? '' : 'collapsed';
            $show_class = $is_active_parent ? 'show' : '';
            $aria_expanded = $is_active_parent ? 'true' : 'false';

            echo '<a href="#' . $id . '" data-bs-toggle="collapse" aria-expanded="' . $aria_expanded . '" class="list-group-item list-group-item-action bg-dark text-white d-flex justify-content-between align-items-center ' . $collapsed_class . '">';
            echo '<span><i class="bi ' . $icon . ' me-2"></i>' . $text . '</span>';
            echo '<i class="bi bi-chevron-down small"></i>';
            echo '</a>';
            echo '<div class="collapse ' . $show_class . '" id="' . $id . '">';
            foreach ($links as $link) {
                render_sub_link($link['url'], $link['icon'], $link['text']);
            }
            echo '</div>';
        }

        render_nav_link('dashboard.php', 'bi-speedometer2', 'Dashboard');

        if ($role == 'Administrator') {
            render_dropdown('masterDataCollapse', 'bi-stack', 'Master Data', [
                ['url' => 'pages/admin_manage_users.php', 'icon' => 'bi-people', 'text' => 'Kelola Pengguna'],
                ['url' => 'pages/admin_manage_siswa.php', 'icon' => 'bi-person-badge', 'text' => 'Data Induk Siswa'],
                ['url' => 'pages/admin_manage_guru.php', 'icon' => 'bi-briefcase', 'text' => 'Data Induk Guru']
            ]);
            render_dropdown('akademikCollapse', 'bi-mortarboard', 'Akademik', [
                ['url' => 'pages/admin_manage_tahun_pelajaran.php', 'icon' => 'bi-calendar-event', 'text' => 'Tahun Pelajaran'],
                ['url' => 'pages/admin_manage_program.php', 'icon' => 'bi-diagram-3', 'text' => 'Program Keahlian'],
                ['url' => 'pages/admin_manage_konsentrasi.php', 'icon' => 'bi-star', 'text' => 'Konsentrasi Keahlian'],
                ['url' => 'pages/admin_manage_kelas.php', 'icon' => 'bi-door-open', 'text' => 'Manajemen Kelas'],
                ['url' => 'pages/admin_manage_mapel.php', 'icon' => 'bi-book', 'text' => 'Manajemen Mapel']
            ]);
             render_dropdown('utilitasCollapse', 'bi-tools', 'Utilitas', [
                ['url' => 'pages/admin_kenaikan_kelas.php', 'icon' => 'bi-arrow-up-circle', 'text' => 'Kenaikan Kelas'],
                ['url' => 'pages/admin_manage_jadwal.php', 'icon' => 'bi-calendar3', 'text' => 'Unggah Jadwal Kelas'],
                ['url' => 'pages/admin_qr_manager.php', 'icon' => 'bi-qr-code', 'text' => 'Generate QR Presensi']
            ]);
        }

        if ($role == 'Guru') {
            render_nav_link('pages/guru_isi_jurnal.php', 'bi-journal-text', 'Isi Jurnal Mengajar');
        }

        if ($role == 'Siswa') {
            render_nav_link('pages/siswa_presensi.php', 'bi-qr-code-scan', 'Presensi QR');
            render_nav_link('pages/siswa_cek_tabungan.php', 'bi-cash-coin', 'Cek Tabungan');
            render_nav_link('pages/siswa_lapor_mood.php', 'bi-emoji-smile', 'Lapor Mood');
        }

        if ($role == 'BK') {
            render_nav_link('pages/bk_dashboard.php', 'bi-shield-check', 'Dashboard BK');
        }

        if ($role == 'Petugas Tabungan') {
            render_nav_link('pages/tabungan_transaksi.php', 'bi-wallet2', 'Proses Transaksi');
        }

        ?>
        <a href="<?php echo BASE_URL; ?>logout.php" class="list-group-item list-group-item-action bg-dark text-white text-danger mt-auto"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
    </div>
</div>
<!-- /#sidebar-wrapper -->
