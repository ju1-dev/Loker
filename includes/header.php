<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sudah_login = isset($_SESSION['id_akun']);
$judul_halaman = $page_title ?? "Info Loker - Portal Lowongan Kerja";
$jalur_dasar = $base_path ?? "";
$username_aktif = $_SESSION['username'] ?? "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judul_halaman) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars($jalur_dasar) ?>assets/css/style.css">
</head>
<body>
    <a href="#konten-utama" class="skip-link">Langsung ke konten utama</a>
    <header class="site-header">
        <div class="container header-inner">
            <a href="<?= htmlspecialchars($jalur_dasar) ?>index.php" class="brand-logo">
                <span class="brand-mark" aria-hidden="true">IL</span>
                <span class="brand-text">InfoLoker</span>
            </a>

            <nav class="site-nav" aria-label="Navigasi utama">
                <a href="<?= htmlspecialchars($jalur_dasar) ?>index.php" class="nav-link">Daftar Lowongan</a>
                <?php if ($sudah_login) { ?>
                    <span class="nav-user">Admin: <strong><?= htmlspecialchars($username_aktif) ?></strong></span>
                    <a href="<?= htmlspecialchars($jalur_dasar) ?>tambah.php" class="nav-link nav-link-primary">Tambah Lowongan</a>
                    <a href="<?= htmlspecialchars($jalur_dasar) ?>auth/logout.php" class="nav-link nav-link-outline">Logout</a>
                <?php } else { ?>
                    <a href="<?= htmlspecialchars($jalur_dasar) ?>auth/login.php" class="nav-link">Login</a>
                    <a href="<?= htmlspecialchars($jalur_dasar) ?>auth/register.php" class="nav-link nav-link-primary">Register</a>
                <?php } ?>
            </nav>
        </div>
    </header>
    <main id="konten-utama" class="main-content">
        <div class="container">
