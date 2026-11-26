<?php
session_start();
require "config/koneksi.php";

$sudah_login = isset($_SESSION['id_akun']);
$id_raw = $_GET['id'] ?? '';

if (!is_string($id_raw) || $id_raw === '' || !ctype_digit($id_raw)) {
    header("Location: index.php");
    exit;
}

$id_int = (int)$id_raw;
$sql = "SELECT * FROM lowongan WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql);
$stmt->bind_param("i", $id_int);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    header("Location: index.php?pesan=tidak_ditemukan");
    exit;
}

$page_title = $data['posisi'] . " - Detail Lowongan InfoLoker";
$base_path = "";
require "includes/header.php";
?>

<div class="page-back">
    <a href="index.php" class="back-link">&larr; Kembali ke Daftar Lowongan</a>
</div>

<article class="detail-card">
    <header class="detail-header">
        <div class="detail-badges">
            <span class="kode-chip"><?= htmlspecialchars($data['kode_lowongan']) ?></span>
            <span class="status-badge <?= ($data['status'] === 'Dibuka') ? 'status-dibuka' : 'status-ditutup' ?>">
                <?= htmlspecialchars($data['status']) ?>
            </span>
            <span class="tipe-tag"><?= htmlspecialchars($data['tipe']) ?></span>
        </div>
        <h1><?= htmlspecialchars($data['posisi']) ?></h1>
        <p class="detail-company"><?= htmlspecialchars($data['perusahaan']) ?></p>
    </header>

    <dl class="detail-meta-grid">
        <div class="detail-meta-item">
            <dt>Bidang Pekerjaan</dt>
            <dd><?= htmlspecialchars($data['bidang']) ?></dd>
        </div>
        <div class="detail-meta-item">
            <dt>Tipe Pekerjaan</dt>
            <dd><?= htmlspecialchars($data['tipe']) ?></dd>
        </div>
        <div class="detail-meta-item">
            <dt>Lokasi Penempatan</dt>
            <dd><?= htmlspecialchars($data['lokasi']) ?></dd>
        </div>
        <div class="detail-meta-item">
            <dt>Kisaran Gaji</dt>
            <dd><?= htmlspecialchars($data['gaji']) ?></dd>
        </div>
        <div class="detail-meta-item">
            <dt>Tanggal Tayang</dt>
            <dd><?= htmlspecialchars($data['created_at']) ?></dd>
        </div>
        <div class="detail-meta-item">
            <dt>Status Lowongan</dt>
            <dd><?= htmlspecialchars($data['status']) ?></dd>
        </div>
    </dl>

    <section class="detail-description">
        <h2>Deskripsi &amp; Kualifikasi</h2>
        <div class="deskripsi-body"><?= nl2br(htmlspecialchars($data['deskripsi'])) ?></div>
    </section>

    <div class="detail-actions">
        <a href="index.php" class="btn btn-secondary">Kembali ke Daftar</a>
        <?php if ($sudah_login) { ?>
            <a href="edit.php?id=<?= (int)$data['id'] ?>" class="btn btn-primary">Edit Lowongan</a>
            <form action="hapus.php" method="POST" class="inline-form">
                <input type="hidden" name="id" value="<?= (int)$data['id'] ?>">
                <button type="submit" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus Data</button>
            </form>
        <?php } ?>
    </div>
</article>

<?php require "includes/footer.php"; ?>
