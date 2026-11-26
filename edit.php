<?php
require "includes/auth_check.php";
require "config/koneksi.php";

$id_raw = $_GET['id'] ?? '';
if (!is_string($id_raw) || $id_raw === '' || !ctype_digit($id_raw)) {
    header("Location: index.php");
    exit;
}

$id_int = (int)$id_raw;
$pesan_input = $_GET['pesan'] ?? '';
$pesan = is_string($pesan_input) ? $pesan_input : '';
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

$page_title = "Edit Lowongan " . $data['kode_lowongan'] . " - InfoLoker";
$base_path = "";
require "includes/header.php";
?>

<div class="page-back">
    <a href="index.php" class="back-link">&larr; Kembali ke Daftar Lowongan</a>
</div>

<section class="form-card">
    <h1>Edit Data Lowongan</h1>
    <p class="form-intro">Perbarui informasi untuk kode lowongan <strong><?= htmlspecialchars($data['kode_lowongan']) ?></strong>.</p>

    <?php if ($pesan !== '') { ?>
        <?php if ($pesan === 'kode_duplikat') { ?>
            <div class="alert alert-error" role="alert">Gagal memperbarui: Kode lowongan sudah dipakai oleh data lain.</div>
        <?php } elseif ($pesan === 'kosong') { ?>
            <div class="alert alert-error" role="alert">Gagal memperbarui: Semua kolom wajib diisi.</div>
        <?php } elseif ($pesan === 'gagal') { ?>
            <div class="alert alert-error" role="alert">Gagal memperbarui data ke database.</div>
        <?php } ?>
    <?php } ?>

    <form action="update.php" method="POST" class="app-form">
        <input type="hidden" name="id" value="<?= (int)$data['id'] ?>">

        <div class="form-grid">
            <div class="form-field">
                <label for="kode_lowongan">Kode Lowongan</label>
                <input type="text" id="kode_lowongan" name="kode_lowongan" value="<?= htmlspecialchars($data['kode_lowongan']) ?>" required>
            </div>

            <div class="form-field">
                <label for="tipe">Tipe Pekerjaan</label>
                <select id="tipe" name="tipe" required>
                    <option value="Full-time" <?= ($data['tipe'] === 'Full-time') ? 'selected' : '' ?>>Full-time</option>
                    <option value="Part-time" <?= ($data['tipe'] === 'Part-time') ? 'selected' : '' ?>>Part-time</option>
                    <option value="Magang / PKL" <?= ($data['tipe'] === 'Magang / PKL') ? 'selected' : '' ?>>Magang / PKL</option>
                    <option value="Kontrak" <?= ($data['tipe'] === 'Kontrak') ? 'selected' : '' ?>>Kontrak</option>
                </select>
            </div>

            <div class="form-field">
                <label for="posisi">Posisi</label>
                <input type="text" id="posisi" name="posisi" value="<?= htmlspecialchars($data['posisi']) ?>" required>
            </div>

            <div class="form-field">
                <label for="bidang">Bidang Pekerjaan</label>
                <select id="bidang" name="bidang" required>
                    <option value="Teknologi Informasi" <?= ($data['bidang'] === 'Teknologi Informasi') ? 'selected' : '' ?>>Teknologi Informasi</option>
                    <option value="Administrasi" <?= ($data['bidang'] === 'Administrasi') ? 'selected' : '' ?>>Administrasi</option>
                    <option value="Desain & Media" <?= ($data['bidang'] === 'Desain & Media') ? 'selected' : '' ?>>Desain &amp; Media</option>
                    <option value="Pemasaran" <?= ($data['bidang'] === 'Pemasaran') ? 'selected' : '' ?>>Pemasaran</option>
                    <option value="Teknik Komputer" <?= ($data['bidang'] === 'Teknik Komputer') ? 'selected' : '' ?>>Teknik Komputer</option>
                    <option value="Otomotif & Manufaktur" <?= ($data['bidang'] === 'Otomotif & Manufaktur') ? 'selected' : '' ?>>Otomotif &amp; Manufaktur</option>
                    <option value="Keuangan & Akuntansi" <?= ($data['bidang'] === 'Keuangan & Akuntansi') ? 'selected' : '' ?>>Keuangan &amp; Akuntansi</option>
                </select>
            </div>

            <div class="form-field">
                <label for="perusahaan">Nama Perusahaan</label>
                <input type="text" id="perusahaan" name="perusahaan" value="<?= htmlspecialchars($data['perusahaan']) ?>" required>
            </div>

            <div class="form-field">
                <label for="lokasi">Lokasi Penempatan</label>
                <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>" required>
            </div>

            <div class="form-field form-field-full">
                <label for="gaji">Kisaran Gaji</label>
                <input type="text" id="gaji" name="gaji" value="<?= htmlspecialchars($data['gaji']) ?>" required>
            </div>

            <div class="form-field form-field-full">
                <fieldset class="radio-fieldset">
                    <legend class="field-label">Status Lowongan</legend>
                    <div class="radio-group">
                        <label for="status_dibuka" class="radio-pill">
                            <input type="radio" id="status_dibuka" name="status" value="Dibuka" <?= ($data['status'] === 'Dibuka') ? 'checked' : '' ?>>
                            <span>Dibuka</span>
                        </label>
                        <label for="status_ditutup" class="radio-pill">
                            <input type="radio" id="status_ditutup" name="status" value="Ditutup" <?= ($data['status'] === 'Ditutup') ? 'checked' : '' ?>>
                            <span>Ditutup</span>
                        </label>
                    </div>
                </fieldset>
            </div>

            <div class="form-field form-field-full">
                <label for="deskripsi">Deskripsi &amp; Kualifikasi</label>
                <textarea id="deskripsi" name="deskripsi" rows="6" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</section>

<?php require "includes/footer.php"; ?>
