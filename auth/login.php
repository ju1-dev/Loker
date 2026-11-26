<?php
session_start();

if (isset($_SESSION['id_akun'])) {
    header("Location: ../index.php");
    exit;
}

$page_title = "Login Pengguna - InfoLoker";
$base_path = "../";
$pesan_input = $_GET['pesan'] ?? '';
$pesan = is_string($pesan_input) ? $pesan_input : '';
require "../includes/header.php";
?>

<section class="auth-card">
    <h1>Login Pengguna</h1>
    <p class="form-intro">Masuk ke akun pengelola untuk menambah, memperbarui, atau menghapus data lowongan.</p>

    <?php if ($pesan !== '') { ?>
        <?php if ($pesan === 'gagal') { ?>
            <div class="alert alert-error" role="alert">Username atau password salah.</div>
        <?php } elseif ($pesan === 'belum_login') { ?>
            <div class="alert alert-info" role="status">Silakan login terlebih dahulu untuk mengakses halaman tersebut.</div>
        <?php } elseif ($pesan === 'logout') { ?>
            <div class="alert alert-info" role="status">Anda telah berhasil logout.</div>
        <?php } elseif ($pesan === 'register_berhasil') { ?>
            <div class="alert alert-sukses" role="status">Pendaftaran berhasil. Silakan login.</div>
        <?php } ?>
    <?php } ?>

    <form action="proses_login.php" method="POST" class="app-form">
        <div class="form-field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" autocomplete="username" required>
        </div>

        <div class="form-field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>

    <p class="auth-switch">
        Belum punya akun?
        <a href="register.php">Daftar di sini</a>
    </p>
</section>

<?php require "../includes/footer.php"; ?>
