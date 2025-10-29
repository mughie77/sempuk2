# SEMPU - SEkolah Mandiri terPadU

SEMPU adalah aplikasi web monolitik yang dibangun menggunakan PHP native untuk manajemen sekolah yang terintegrasi. Aplikasi ini dirancang untuk menangani berbagai peran pengguna (Administrator, Guru, Siswa, Orang Tua, dll.) dalam satu platform terpusat.

## Tumpukan Teknologi

- **Bahasa Backend:** PHP Native (tanpa framework)
- **Database:** MySQL / MariaDB (menggunakan MySQLi dengan Prepared Statements)
- **Bahasa Frontend:** HTML5, CSS3, JavaScript
- **Framework Frontend:** Bootstrap 5
- **Ikon:** Bootstrap Icons
- **Font:** Google Fonts (Inter)

## Fitur Utama (Fondasi Awal)

- **Struktur Proyek Terorganisir:** Kode diatur ke dalam direktori `/core`, `/includes`, `/assets`, dan `/pages`.
- **Desain Elegan & Responsif:** Menggunakan template dasbor admin modern dengan palet warna profesional dan tata letak yang bersih.
- **Otentikasi Aman:**
  - Sistem login berbasis sesi PHP (`$_SESSION`).
  - *Password* di-hash menggunakan `password_hash()` dan diverifikasi dengan `password_verify()`.
  - Keamanan dari serangan *Session Fixation* dengan `session_regenerate_id(true)`.
- **Kontrol Akses Berbasis Peran (RBAC):**
  - Tampilan menu dan dasbor yang dinamis sesuai dengan peran pengguna (`$_SESSION['role']`).
  - Halaman dilindungi oleh skrip `auth_check.php` untuk memastikan hanya peran yang diizinkan yang dapat mengakses.
- **Keamanan Login Perangkat Tunggal:**
  - Pengguna akan secara otomatis *logout* jika akun mereka diakses dari perangkat lain, mencegah beberapa sesi aktif secara bersamaan.
- **Keamanan Database:**
  - Semua *query* database menggunakan *Prepared Statements* untuk mencegah serangan *SQL Injection*.
- **Keamanan Frontend:**
  - Semua data yang ditampilkan di HTML di-escape menggunakan `htmlspecialchars()` untuk mencegah serangan *Cross-Site Scripting* (XSS).

## Panduan Instalasi & Penyiapan

Untuk menjalankan proyek ini secara lokal, ikuti langkah-langkah berikut:

1.  **Prasyarat:**
    -   Pastikan Anda memiliki server web lokal seperti XAMPP, WAMP, atau MAMP yang terinstal.
    -   Pastikan server Anda menjalankan PHP (versi 7.4 atau lebih baru direkomendasikan).
    -   Pastikan Anda memiliki server database MySQL atau MariaDB.

2.  **Kloning Repositori:**
    ```bash
    git clone [URL_REPOSITORI_ANDA] sempu
    cd sempu
    ```

3.  **Setup Database:**
    -   Buka phpMyAdmin atau *tool* database pilihan Anda.
    -   Buat database baru dengan nama `sempu`.
    -   Impor skema dan data awal dengan mengeksekusi file `database.sql` yang ada di direktori *root*.

4.  **Konfigurasi Koneksi:**
    -   Buka file `/core/db_connect.php`.
    -   Sesuaikan kredensial database (`DB_USERNAME` dan `DB_PASSWORD`) jika berbeda dari pengaturan *default* Anda.
    ```php
    define('DB_USERNAME', 'root'); // Ganti jika perlu
    define('DB_PASSWORD', ''); // Ganti jika perlu
    ```

5.  **Jalankan Aplikasi:**
    -   Pindahkan atau salin folder proyek `sempu` ke direktori `htdocs` (untuk XAMPP) atau `www` (untuk WAMP/MAMP) di instalasi server web Anda.
    -   Buka *browser* web Anda dan navigasikan ke `http://localhost/sempu`.

6.  **Akun Demo:**
    Anda dapat login menggunakan akun demo berikut (seperti yang didefinisikan di `database.sql`):
    -   **Username:** `admin`
    -   **Password:** `password` *(Catatan: Ganti hash di `database.sql` dengan hash dari 'password' jika Anda ingin login. Contoh hash untuk 'password' adalah `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`)*

---
*Proyek ini sedang dalam pengembangan aktif.*
