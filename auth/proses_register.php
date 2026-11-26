<?php
session_start();
require "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

$username_raw = $_POST['username'] ?? '';
$password_raw = $_POST['password'] ?? '';
$konfirmasi_raw = $_POST['konfirmasi'] ?? '';

$username = is_string($username_raw) ? trim($username_raw) : '';
$password = is_string($password_raw) ? $password_raw : '';
$konfirmasi = is_string($konfirmasi_raw) ? $konfirmasi_raw : '';

if ($username === '' || $password === '' || $konfirmasi === '') {
    header("Location: register.php?pesan=kosong");
    exit;
}

if (strlen($username) < 3) {
    header("Location: register.php?pesan=username_pendek");
    exit;
}

if (strlen($password) < 8) {
    header("Location: register.php?pesan=password_pendek");
    exit;
}

if ($password !== $konfirmasi) {
    header("Location: register.php?pesan=password_beda");
    exit;
}

$sql_cek = "SELECT id FROM akun WHERE username = ?";
$stmt_cek = mysqli_prepare($koneksi, $sql_cek);
$stmt_cek->bind_param("s", $username);
$stmt_cek->execute();
$hasil_cek = $stmt_cek->get_result();

if ($hasil_cek->num_rows > 0) {
    header("Location: register.php?pesan=terdaftar");
    exit;
}

$hash_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO akun (username, password) VALUES (?, ?)";
$stmt = mysqli_prepare($koneksi, $sql);
$stmt->bind_param("ss", $username, $hash_password);

if ($stmt->execute()) {
    session_regenerate_id(true);
    $_SESSION['id_akun'] = $stmt->insert_id;
    $_SESSION['username'] = $username;

    header("Location: ../index.php?pesan=register_sukses");
    exit;
} else {
    header("Location: register.php?pesan=gagal");
    exit;
}
?>