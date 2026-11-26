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
    header("Location: edit.php?id=" . $id_int . "&pesan=kosong");
    exit;
}

$sql_cek = "SELECT id FROM lowongan WHERE kode_lowongan = ? AND id != ?";
$stmt_cek = mysqli_prepare($koneksi, $sql_cek);
$stmt_cek->bind_param("si", $kode_lowongan, $id_int);
$stmt_cek->execute();
$hasil_cek = $stmt_cek->get_result();

if ($hasil_cek->num_rows > 0) {
    header("Location: edit.php?id=" . $id_int . "&pesan=kode_duplikat");
    exit;
}

$sql = "UPDATE lowongan SET kode_lowongan = ?, posisi = ?, perusahaan = ?, bidang = ?, tipe = ?, lokasi = ?, gaji = ?, deskripsi = ?, status = ? WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql);
$stmt->bind_param("sssssssssi", $kode_lowongan, $posisi, $perusahaan, $bidang, $tipe, $lokasi, $gaji, $deskripsi, $status, $id_int);

if ($stmt->execute()) {
    header("Location: index.php?pesan=update_sukses");
    exit;
} else {
    header("Location: edit.php?id=" . $id_int . "&pesan=gagal");
    exit;
}
?>