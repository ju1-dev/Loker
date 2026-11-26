<?php
session_start();
require "config/koneksi.php";

$sudah_login = isset($_SESSION['id_akun']);
$cari_input = $_GET['cari'] ?? '';
$cari = is_string($cari_input) ? trim($cari_input) : '';
$pesan_input = $_GET['pesan'] ?? '';
$pesan = is_string($pesan_input) ? $pesan_input : '';
$per_halaman_default = 9;

$per_halaman_input = $_GET['per_halaman'] ?? '';
$per_halaman_valid = filter_var($per_halaman_input, FILTER_VALIDATE_INT);
if ($per_halaman_valid === false || $per_halaman_valid < 1) {
    $per_halaman = $per_halaman_default;
} elseif ($per_halaman_valid > 100) {
    $per_halaman = 100;
} else {
    $per_halaman = (int)$per_halaman_valid;
}

if ($cari !== '') {
    $like = "%" . $cari . "%";
    $sql_total = "SELECT COUNT(*) AS total FROM lowongan WHERE kode_lowongan LIKE ? OR posisi LIKE ? OR perusahaan LIKE ? OR bidang LIKE ? OR lokasi LIKE ?";
    $stmt_total = mysqli_prepare($koneksi, $sql_total);
    $stmt_total->bind_param("sssss", $like, $like, $like, $like, $like);
} else {
    $sql_total = "SELECT COUNT(*) AS total FROM lowongan";
    $stmt_total = mysqli_prepare($koneksi, $sql_total);
}

$stmt_total->execute();
$hasil_total = $stmt_total->get_result();
$row_total = $hasil_total->fetch_assoc();
$total_data = (int)($row_total['total'] ?? 0);

if ($total_data > 0) {
    $total_halaman = (int)ceil($total_data / $per_halaman);
} else {
    $total_halaman = 1;
}

$halaman_input = $_GET['halaman'] ?? '1';
$halaman_valid = filter_var($halaman_input, FILTER_VALIDATE_INT);
if ($halaman_valid === false || $halaman_valid < 1) {
    $halaman = 1;
} elseif ($halaman_valid > $total_halaman) {
    $halaman = $total_halaman;
} else {
    $halaman = (int)$halaman_valid;
}

$offset = ($halaman - 1) * $per_halaman;

if ($cari !== '') {
    $sql = "SELECT * FROM lowongan WHERE kode_lowongan LIKE ? OR posisi LIKE ? OR perusahaan LIKE ? OR bidang LIKE ? OR lokasi LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    $stmt->bind_param("sssssii", $like, $like, $like, $like, $like, $per_halaman, $offset);
} else {
    $sql = "SELECT * FROM lowongan ORDER BY id DESC LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    $stmt->bind_param("ii", $per_halaman, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
$jumlah_tampil = $result->num_rows;

if ($total_data > 0 && $jumlah_tampil > 0) {
    $data_mulai = $offset + 1;
    $data_selesai = $offset + $jumlah_tampil;
} else {
    $data_mulai = 0;
    $data_selesai = 0;
}

$query_dasar = "per_halaman=" . $per_halaman;
if ($cari !== '') {
    $query_dasar = "cari=" . urlencode($cari) . "&" . $query_dasar;
}

$page_title = "InfoLoker - Portal Lowongan Kerja & Magang";
$base_path = "";
require "includes/header.php";
?>

<section class="page-hero">
    <div class="page-hero-text">
        <h1>Daftar Lowongan Kerja &amp; Magang</h1>
        <p class="page-subtitle">Temukan informasi lowongan kerja full-time, part-time, kontrak, dan program magang sesuai bidang keahlian Anda.</p>
    </div>
    <?php if ($sudah_login) { ?>
        <div class="page-hero-cta">
            <a href="tambah.php" class="btn btn-primary">Tambah Lowongan Baru</a>
        </div>
    <?php } ?>
</section>

<?php if ($pesan !== '') { ?>
    <?php if ($pesan === 'login_sukses' && isset($_SESSION['username'])) { ?>
        <div class="alert alert-sukses" role="status">Login berhasil. Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?>.</div>
    <?php } elseif ($pesan === 'register_sukses' && isset($_SESSION['username'])) { ?>
        <div class="alert alert-sukses" role="status">Pendaftaran berhasil. Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?>.</div>
    <?php } elseif ($pesan === 'tambah_sukses') { ?>
        <div class="alert alert-sukses" role="status">Data lowongan berhasil ditambahkan.</div>
    <?php } elseif ($pesan === 'update_sukses') { ?>
        <div class="alert alert-sukses" role="status">Data lowongan berhasil diperbarui.</div>
    <?php } elseif ($pesan === 'hapus_sukses') { ?>
        <div class="alert alert-sukses" role="status">Data lowongan berhasil dihapus.</div>
    <?php } elseif ($pesan === 'tidak_ditemukan') { ?>
        <div class="alert alert-error" role="alert">Data lowongan yang diminta tidak ditemukan.</div>
    <?php } elseif ($pesan === 'gagal') { ?>
        <div class="alert alert-error" role="alert">Proses gagal dijalankan. Silakan coba lagi.</div>
    <?php } ?>
<?php } ?>

<section class="filter-bar" aria-label="Pencarian dan pengaturan tampilan lowongan">
    <form action="index.php" method="GET" class="form-cari">
        <div class="filter-group filter-search">
            <label for="cari">Cari Lowongan</label>
            <input type="text" id="cari" name="cari" placeholder="Cari kode, posisi, perusahaan, bidang, atau lokasi..." value="<?= htmlspecialchars($cari) ?>">
        </div>
        <div class="filter-group filter-per-page">
            <label for="per_halaman">Tampil per halaman</label>
            <input type="number" id="per_halaman" name="per_halaman" min="1" max="100" value="<?= $per_halaman ?>" onchange="this.form.submit()">
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">Terapkan</button>
            <a href="index.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</section>

<div class="results-summary">
    <p>
        Menampilkan <strong><?= $data_mulai ?>-<?= $data_selesai ?></strong> dari <strong><?= $total_data ?></strong> lowongan
        (Halaman <?= $halaman ?> dari <?= $total_halaman ?>)
    </p>
    <?php if ($cari !== '') { ?>
        <span class="search-badge">Kata kunci: <?= htmlspecialchars($cari) ?></span>
    <?php } ?>
</div>

<?php if ($jumlah_tampil > 0) { ?>
    <div class="loker-grid">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <article class="loker-card">
                <div class="card-body-top">
                    <div class="card-top">
                        <span class="kode-chip"><?= htmlspecialchars($row['kode_lowongan']) ?></span>
                        <span class="status-badge <?= ($row['status'] === 'Dibuka') ? 'status-dibuka' : 'status-ditutup' ?>">
                            <?= htmlspecialchars($row['status']) ?>
                        </span>
                    </div>
                    <h2 class="card-title">
                        <a href="detail.php?id=<?= (int)$row['id'] ?>"><?= htmlspecialchars($row['posisi']) ?></a>
                    </h2>
                    <p class="card-company"><?= htmlspecialchars($row['perusahaan']) ?></p>

                    <dl class="card-meta-list">
                        <div class="card-meta-row">
                            <dt>Bidang</dt>
                            <dd><?= htmlspecialchars($row['bidang']) ?></dd>
                        </div>
                        <div class="card-meta-row">
                            <dt>Tipe</dt>
                            <dd><span class="tipe-tag"><?= htmlspecialchars($row['tipe']) ?></span></dd>
                        </div>
                        <div class="card-meta-row">
                            <dt>Lokasi</dt>
                            <dd><?= htmlspecialchars($row['lokasi']) ?></dd>
                        </div>
                        <div class="card-meta-row">
                            <dt>Kisaran Gaji</dt>
                            <dd><?= htmlspecialchars($row['gaji']) ?></dd>
                        </div>
                        <div class="card-meta-row">
                            <dt>Tanggal Tayang</dt>
                            <dd><?= htmlspecialchars($row['created_at']) ?></dd>
                        </div>
                    </dl>
                </div>

                <div class="card-actions">
                    <a href="detail.php?id=<?= (int)$row['id'] ?>" class="btn btn-secondary btn-card">Detail</a>
                    <?php if ($sudah_login) { ?>
                        <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-outline btn-card">Edit</a>
                        <form action="hapus.php" method="POST" class="inline-form">
                            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                            <button type="submit" class="btn btn-hapus btn-card" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</button>
                        </form>
                    <?php } ?>
                </div>
            </article>
        <?php } ?>
    </div>
<?php } else { ?>
    <section class="empty-state">
        <h2>Lowongan Tidak Ditemukan</h2>
        <?php if ($cari !== '') { ?>
            <p>Tidak ada lowongan yang cocok dengan kata kunci "<?= htmlspecialchars($cari) ?>". Coba ubah kata kunci pencarian atau reset filter untuk melihat semua lowongan.</p>
            <a href="index.php?per_halaman=<?= $per_halaman ?>" class="btn btn-primary">Tampilkan Semua Lowongan</a>
        <?php } else { ?>
            <p>Belum ada data lowongan yang tersedia saat ini.</p>
            <?php if ($sudah_login) { ?>
                <a href="tambah.php" class="btn btn-primary">Tambah Lowongan Pertama</a>
            <?php } ?>
        <?php } ?>
    </section>
<?php } ?>

<?php if ($total_data > 0 && $total_halaman > 1) { ?>
    <nav class="pagination-nav" aria-label="Navigasi halaman lowongan">
        <?php if ($halaman > 1) { ?>
            <a href="?<?= htmlspecialchars($query_dasar) ?>&amp;halaman=<?= $halaman - 1 ?>" class="page-link">Sebelumnya</a>
        <?php } else { ?>
            <span class="page-disabled" aria-disabled="true">Sebelumnya</span>
        <?php } ?>

        <?php if ($halaman === 1) { ?>
            <span class="page-current" aria-current="page">1</span>
        <?php } else { ?>
            <a href="?<?= htmlspecialchars($query_dasar) ?>&amp;halaman=1" class="page-link">1</a>
        <?php } ?>

        <?php
        $window_mulai = max(2, $halaman - 2);
        $window_selesai = min($total_halaman - 1, $halaman + 2);
        ?>

        <?php if ($window_mulai > 2) { ?>
            <span class="page-ellipsis" aria-hidden="true">...</span>
        <?php } ?>

        <?php for ($i = $window_mulai; $i <= $window_selesai; $i++) { ?>
            <?php if ($i === $halaman) { ?>
                <span class="page-current" aria-current="page"><?= $i ?></span>
            <?php } else { ?>
                <a href="?<?= htmlspecialchars($query_dasar) ?>&amp;halaman=<?= $i ?>" class="page-link"><?= $i ?></a>
            <?php } ?>
        <?php } ?>

        <?php if ($window_selesai < $total_halaman - 1) { ?>
            <span class="page-ellipsis" aria-hidden="true">...</span>
        <?php } ?>

        <?php if ($total_halaman > 1) { ?>
            <?php if ($halaman === $total_halaman) { ?>
                <span class="page-current" aria-current="page"><?= $total_halaman ?></span>
            <?php } else { ?>
                <a href="?<?= htmlspecialchars($query_dasar) ?>&amp;halaman=<?= $total_halaman ?>" class="page-link"><?= $total_halaman ?></a>
            <?php } ?>
        <?php } ?>

        <?php if ($halaman < $total_halaman) { ?>
            <a href="?<?= htmlspecialchars($query_dasar) ?>&amp;halaman=<?= $halaman + 1 ?>" class="page-link">Berikutnya</a>
        <?php } else { ?>
            <span class="page-disabled" aria-disabled="true">Berikutnya</span>
        <?php } ?>
    </nav>
<?php } ?>

<?php require "includes/footer.php"; ?>
