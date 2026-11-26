<?php
session_start();
require "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$username_raw = $_POST['username'] ?? '';
$password_raw = $_POST['password'] ?? '';

$username = is_string($username_raw) ? trim($username_raw) : '';
$password = is_string($password_raw) ? $password_raw : '';

if ($username === '' || $password === '') {
    header("Location: login.php?pesan=gagal");
    exit;
}

$sql = "SELECT id, username, password FROM akun WHERE username = ?";
$stmt = mysqli_prepare($koneksi, $sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$hasil = $stmt->get_result();
$akun = $hasil->fetch_assoc();

if ($akun && password_verify($password, $akun['password'])) {
    session_regenerate_id(true);
    $_SESSION['id_akun'] = $akun['id'];
    $_SESSION['username'] = $akun['username'];

    header("Location: ../index.php?pesan=login_sukses");
    exit;
} else {
    header("Location: login.php?pesan=gagal");
    exit;
}
?>