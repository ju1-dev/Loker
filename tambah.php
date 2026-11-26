<?php
require "includes/auth_check.php";
require "config/koneksi.php";

$page_title = "Tambah Lowongan - InfoLoker";
$base_path = "";
$pesan_input = $_GET['pesan'] ?? '';
$pesan = is_string($pesan_input) ? $pesan_input : '';
require "includes/header.php";
?>

<div class="page-back">
    <a href="index.php" class="back-link">&larr; Kembali ke Daftar Lowongan</a>
</div>

<section class="form-card">
    <h1>Tambah Data Lowongan</h1>
    <p class="form-intro">Lengkapi detail lowongan kerja atau program magang baru di bawah ini.</p>

    <?php if ($pesan !== '') { ?>
        <?php if ($pesan === 'kode_duplikat') { ?>
            <div class="alert alert-error" role="alert">Gagal menyimpan: Kode lowongan sudah terdaftar.</div>
        <?php } elseif ($pesan === 'kosong') { ?>
            <div class="alert alert-error" role="alert">Gagal menyimpan: Semua kolom wajib diisi.</div>
        <?php } elseif ($pesan === 'gagal') { ?>
            <div class="alert alert-error" role="alert">Gagal menyimpan data ke database.</div>
        <?php } ?>
    <?php } ?>

    <form action="simpan.php" method="POST" class="app-form">
        <input type="hidden" name="status" value="Dibuka">

        <div class="form-grid">
            <div class="form-field">
                <label for="kode_lowongan">Kode Lowongan</label>
                <input type="text" id="kode_lowongan" name="kode_lowongan" placeholder="Contoh: LKR-130" required>
            </div>

            <div class="form-field">
                <label for="tipe">Tipe Pekerjaan</label>
                <select id="tipe" name="tipe" required>
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Magang / PKL">Magang / PKL</option>
                    <option value="Kontrak">Kontrak</option>
                </select>
            </div>

            <div class="form-field">
                <label for="posisi">Posisi</label>
                <input type="text" id="posisi" name="posisi" placeholder="Contoh: Junior Web Developer" required>
            </div>

            <div class="form-field">
                <label for="bidang">Bidang Pekerjaan</label>
                <select id="bidang" name="bidang" required>
                    <option value="">Pilih Bidang...</option>
                    <option value="Teknologi Informasi">Teknologi Informasi</option>
                    <option value="Administrasi">Administrasi</option>
                    <option value="Desain & Media">Desain &amp; Media</option>
                    <option value="Pemasaran">Pemasaran</option>
                    <option value="Teknik Komputer">Teknik Komputer</option>
                    <option value="Otomotif & Manufaktur">Otomotif &amp; Manufaktur</option>
                    <option value="Keuangan & Akuntansi">Keuangan &amp; Akuntansi</option>
                </select>
            </div>

            <div class="form-field">
                <label for="perusahaan">Nama Perusahaan</label>
                <input type="text" id="perusahaan" name="perusahaan" placeholder="Contoh: PT Solusi Cipta Digital" required>
            </div>

            <div class="form-field">
                <label for="lokasi">Lokasi Penempatan</label>
                <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: Purbalingga / Remote" required>
            </div>

            <div class="form-field form-field-full">
                <label for="gaji">Kisaran Gaji</label>
                <input type="text" id="gaji" name="gaji" placeholder="Contoh: Rp 4.000.000 - Rp 5.500.000" required>
            </div>

            <div class="form-field form-field-full">
                <label for="deskripsi">Deskripsi &amp; Kualifikasi</label>
                <textarea id="deskripsi" name="deskripsi" rows="6" placeholder="Tuliskan persyaratan dan tugas pekerjaan..." required></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Lowongan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</section>

<?php require "includes/footer.php"; ?>
