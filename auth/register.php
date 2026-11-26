<?php
session_start();

if (isset($_SESSION['id_akun'])) {
    header("Location: ../index.php");
    exit;
}

$page_title = "Daftar Akun - InfoLoker";
$base_path = "../";
$pesan_input = $_GET['pesan'] ?? '';
$pesan = is_string($pesan_input) ? $pesan_input : '';
require "../includes/header.php";
?>

<section class="auth-card">
    <h1>Form Pendaftaran Akun</h1>
    <p class="form-intro">Buat akun baru untuk mengelola daftar lowongan di InfoLoker.</p>

    <?php if ($pesan !== '') { ?>
        <?php if ($pesan === 'kosong') { ?>
            <div class="alert alert-error" role="alert">Semua kolom wajib diisi.</div>
        <?php } elseif ($pesan === 'username_pendek') { ?>
            <div class="alert alert-error" role="alert">Username minimal 3 karakter.</div>
        <?php } elseif ($pesan === 'password_pendek') { ?>
            <div class="alert alert-error" role="alert">Password minimal 8 karakter.</div>
        <?php } elseif ($pesan === 'password_beda') { ?>
            <div class="alert alert-error" role="alert">Konfirmasi password tidak sama.</div>
        <?php } elseif ($pesan === 'terdaftar') { ?>
            <div class="alert alert-error" role="alert">Username sudah terdaftar. Gunakan username lain.</div>
        <?php } elseif ($pesan === 'gagal') { ?>
            <div class="alert alert-error" role="alert">Pendaftaran gagal. Silakan coba lagi.</div>
        <?php } ?>
    <?php } ?>

    <form action="proses_register.php" method="POST" class="app-form">
        <div class="form-field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" autocomplete="username" minlength="3" required>
        </div>

        <div class="form-field">
            <label for="password">Password (minimal 8 karakter)</label>
            <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" required>
        </div>

        <div class="form-field">
            <label for="konfirmasi">Konfirmasi Password</label>
            <input type="password" id="konfirmasi" name="konfirmasi" autocomplete="new-password" minlength="8" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Daftar</button>
        </div>
    </form>

    <p class="auth-switch">
        Sudah punya akun?
        <a href="login.php">Login di sini</a>
    </p>
</section>

<?php require "../includes/footer.php"; ?>
