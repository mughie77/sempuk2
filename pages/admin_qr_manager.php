<?php
// /pages/admin_qr_manager.php

require_once __DIR__ . '/../core/auth_check.php';
require_role(['Administrator']);
require_once __DIR__ . '/../core/db_connect.php';
require_once __DIR__ . '/../includes/lib/phpqrcode/qrlib.php';

$today = date('Y-m-d');
$qr_token = null;

// Cek apakah token untuk hari ini sudah ada
$sql = "SELECT token FROM qr_harian WHERE tanggal = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $qr_token = $row['token'];
    }
    $stmt->close();
}

// Logika untuk generate token baru
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'generate_qr') {
    // Generate token unik baru
    $new_token = 'SEMPU_PRESENSI_' . date('Ymd') . '_' . bin2hex(random_bytes(16));

    // Simpan atau perbarui token di database (menggunakan ON DUPLICATE KEY UPDATE)
    $sql_insert = "INSERT INTO qr_harian (tanggal, token) VALUES (?, ?) ON DUPLICATE KEY UPDATE token = ?";
    if ($stmt_insert = $mysqli->prepare($sql_insert)) {
        $stmt_insert->bind_param("sss", $today, $new_token, $new_token);
        $stmt_insert->execute();
        $stmt_insert->close();

        // Perbarui variabel token dan redirect untuk refresh halaman
        $qr_token = $new_token;
        $_SESSION['flash_message'] = ['message' => 'Token QR baru untuk hari ini telah berhasil dibuat.', 'type' => 'success'];
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

// Path untuk menyimpan gambar QR Code
$qr_image_path = null;
if ($qr_token) {
    // Pastikan direktori 'generated' ada di dalam 'assets/img'
    $qr_dir = __DIR__ . '/../assets/img/generated/';
    if (!is_dir($qr_dir)) {
        mkdir($qr_dir, 0755, true);
    }
    $qr_file = $qr_dir . 'presensi_harian.png';
    QRcode::png($qr_token, $qr_file, QR_ECLEVEL_L, 10);
    $qr_image_path = BASE_URL . 'assets/img/generated/presensi_harian.png';
}


include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
include __DIR__ . '/../includes/topbar.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Generator QR Harian</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">QR Harian</li>
    </ol>

    <?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['flash_message']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['flash_message']['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['flash_message']); endif; ?>

    <div class="card">
        <div class="card-header">
            <i class="bi bi-qr-code-scan me-1"></i>QR Code Presensi untuk Tanggal: <?php echo date('d F Y'); ?>
        </div>
        <div class="card-body text-center">
            <?php if ($qr_image_path): ?>
                <p>Pindai kode QR di bawah ini untuk melakukan presensi masuk harian.</p>
                <img src="<?php echo $qr_image_path; ?>?t=<?php echo time(); ?>" alt="QR Code Presensi Harian" class="img-fluid border rounded" style="max-width: 300px;">
                <p class="mt-3"><strong>Token Aktif:</strong><br><small class="text-muted"><?php echo htmlspecialchars($qr_token); ?></small></p>
            <?php else: ?>
                <p class="text-danger">Belum ada token QR yang dibuat untuk hari ini.</p>
            <?php endif; ?>

            <form action="" method="POST" class="mt-4">
                <input type="hidden" name="action" value="generate_qr">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-repeat me-1"></i>
                    <?php echo $qr_token ? 'Buat Ulang Token QR' : 'Buat Token QR untuk Hari Ini'; ?>
                </button>
            </form>
        </div>
        <div class="card-footer text-muted">
            Catatan: Token ini unik untuk setiap hari. Membuat ulang token akan menggantikan token yang lama.
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../includes/footer.php';
$mysqli->close();
?>
