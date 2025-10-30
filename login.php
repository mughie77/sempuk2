<?php
// /login.php
require_once __DIR__ . '/core/init.php';

// Jika pengguna sudah login, langsung arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit();
}

// Cek jika ada pesan error dari proses login atau dari auth_check
$error = $_GET['error'] ?? '';
$message = '';
if ($error === 'invalid') {
    $message = '<div class="alert alert-danger">Username atau password salah.</div>';
} elseif ($error === 'multilogin') {
    $message = '<div class="alert alert-warning">Anda telah logout karena akun ini login di perangkat lain.</div>';
} elseif ($error === 'denied') {
    $message = '<div class="alert alert-danger">Akses ditolak. Silakan login terlebih dahulu.</div>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SEMPU</title>
    <!-- Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 450px;
            border-radius: 1rem;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            background-color: #0d1b2a; /* Dark Blue */
            color: white;
            padding: 2rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            text-align: center;
        }
        .login-header h2 {
            font-weight: 700;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 27, 42, 0.25);
            border-color: #0d1b2a;
        }
        .btn-login {
            background-color: #0d1b2a;
            color: white;
            font-weight: 600;
            padding: 0.75rem;
            border: none;
            transition: background-color 0.3s;
        }
        .btn-login:hover {
            background-color: #1b263b;
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card login-card">
            <div class="login-header">
                <i class="bi bi-bank" style="font-size: 2.5rem;"></i>
                <h2 class="mt-2">SEMPU</h2>
                <p class="mb-0">Selamat Datang! Silakan Login.</p>
            </div>
            <div class="card-body p-5">
                <?php echo $message; ?>
                <form action="<?php echo BASE_URL; ?>core/login_process.php" method="POST">
                    <div class="mb-4">
                        <label for="username" class="form-label">Username / NISN Siswa</label>
                        <div class="input-group">
                             <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                         <div class="input-group">
                             <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-login">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
