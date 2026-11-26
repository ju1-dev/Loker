<?php
require "includes/auth_check.php";
require "config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah.php");
    exit;
}

$kode_lowongan_raw = $_POST['kode_lowongan'] ?? '';
$posisi_raw = $_POST['posisi'] ?? '';
$perusahaan_raw = $_POST['perusahaan'] ?? '';
$bidang_raw = $_POST['bidang'] ?? '';
$tipe_raw = $_POST['tipe'] ?? '';
$lokasi_raw = $_POST['lokasi'] ?? '';
$gaji_raw = $_POST['gaji'] ?? '';
$deskripsi_raw = $_POST['deskripsi'] ?? '';
$status_raw = $_POST['status'] ?? 'Dibuka';

$kode_lowongan = is_string($kode_lowongan_raw) ? trim($kode_lowongan_raw) : '';
$posisi = is_string($posisi_raw) ? trim($posisi_raw) : '';
$perusahaan = is_string($perusahaan_raw) ? trim($perusahaan_raw) : '';
$bidang = is_string($bidang_raw) ? trim($bidang_raw) : '';
$tipe = is_string($tipe_raw) ? trim($tipe_raw) : '';
$lokasi = is_string($lokasi_raw) ? trim($lokasi_raw) : '';
$gaji = is_string($gaji_raw) ? trim($gaji_raw) : '';
$deskripsi = is_string($deskripsi_raw) ? trim($deskripsi_raw) : '';
$status = is_string($status_raw) ? trim($status_raw) : 'Dibuka';

if ($status !== 'Dibuka' && $status !== 'Ditutup') {
    $status = 'Dibuka';
}

if (
    $kode_lowongan === '' ||
    $posisi === '' ||
    $perusahaan === '' ||
    $bidang === '' ||
    $tipe === '' ||
    $lokasi === '' ||
    $gaji === '' ||
    $deskripsi === ''
) {
    header("Location: tambah.php?pesan=kosong");
    exit;
}

$sql_cek = "SELECT id FROM lowongan WHERE kode_lowongan = ?";
$stmt_cek = mysqli_prepare($koneksi, $sql_cek);
$stmt_cek->bind_param("s", $kode_lowongan);
$stmt_cek->execute();
$hasil_cek = $stmt_cek->get_result();

if ($hasil_cek->num_rows > 0) {
    header("Location: tambah.php?pesan=kode_duplikat");
    exit;
}

$sql = "INSERT INTO lowongan (kode_lowongan, posisi, perusahaan, bidang, tipe, lokasi, gaji, deskripsi, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $sql);
$stmt->bind_param("sssssssss", $kode_lowongan, $posisi, $perusahaan, $bidang, $tipe, $lokasi, $gaji, $deskripsi, $status);

if ($stmt->execute()) {
    header("Location: index.php?pesan=tambah_sukses");
    exit;
} else {
    header("Location: tambah.php?pesan=gagal");
    exit;
}
?>