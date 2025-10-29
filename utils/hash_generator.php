<?php
// /utils/hash_generator.php

$hashed_password = '';
$input_password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['password']) && !empty(trim($_POST['password']))) {
        // Ambil password dari input form
        $input_password = $_POST['password'];

        // Buat hash menggunakan algoritma default (BCRYPT)
        // Ini adalah metode yang direkomendasikan dan aman
        $hashed_password = password_hash($input_password, PASSWORD_DEFAULT);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Password Hash Generator</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .result-box {
            background-color: #e9ecef;
            padding: 1rem;
            border-radius: 0.5rem;
            word-wrap: break-word;
            font-family: monospace;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h1 class="h4 mb-0">Password Hash Generator</h1>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Gunakan alat ini untuk membuat hash kata sandi yang aman menggunakan fungsi `password_hash()` bawaan PHP. Hash yang dihasilkan cocok untuk disimpan di database.</p>

                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="password" class="form-label">Masukkan Kata Sandi:</label>
                                <input type="text" class="form-control" id="password" name="password" required autofocus>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Buat Hash</button>
                            </div>
                        </form>

                        <?php if ($hashed_password): ?>
                        <div class="mt-4">
                            <hr>
                            <h2 class="h5">Hasil:</h2>
                            <p><strong>Kata Sandi Asli:</strong> <?php echo htmlspecialchars($input_password); ?></p>
                            <p><strong>Hash yang Dihasilkan:</strong></p>
                            <div id="result" class="result-box" onclick="copyToClipboard()">
                                <?php echo $hashed_password; ?>
                            </div>
                            <small class="form-text text-muted">Klik pada kotak hash untuk menyalin ke clipboard.</small>
                            <div id="copy-feedback" class="text-success mt-2" style="display: none;">
                                Berhasil disalin!
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const resultBox = document.getElementById('result');
            const feedback = document.getElementById('copy-feedback');

            if (navigator.clipboard) {
                navigator.clipboard.writeText(resultBox.innerText).then(() => {
                    feedback.style.display = 'block';
                    setTimeout(() => {
                        feedback.style.display = 'none';
                    }, 2000);
                });
            }
        }
    </script>
</body>
</html>
