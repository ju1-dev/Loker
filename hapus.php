<?php
require "includes/auth_check.php";
require "config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id_raw = $_POST['id'] ?? '';
$id = is_string($id_raw) ? trim($id_raw) : '';
if ($id === '' || !ctype_digit($id)) {
    header("Location: index.php");
    exit;
}

$id_int = (int)$id;
$sql = "DELETE FROM lowongan WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql);
$stmt->bind_param("i", $id_int);

if ($stmt->execute()) {
    header("Location: index.php?pesan=hapus_sukses");
    exit;
} else {
    header("Location: index.php?pesan=gagal");
    exit;
}
?>