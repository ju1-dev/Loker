<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_akun'])) {
    header("Location: auth/login.php?pesan=belum_login");
    exit;
}
?>