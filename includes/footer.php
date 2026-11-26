<?php
$sudah_login_footer = isset($_SESSION['id_akun']);
$jalur_dasar_footer = $base_path ?? "";
?>
        </div>
    </main>
    <footer class="site-footer">
        <div class="container footer-inner">
            <div class="footer-brand">
                <p class="footer-title">InfoLoker</p>
                <p class="footer-desc">Portal informasi lowongan kerja dan program magang berbasis PHP Native dan MySQLi.</p>
            </div>
            <nav class="footer-nav" aria-label="Navigasi footer">
                <a href="<?= htmlspecialchars($jalur_dasar_footer) ?>index.php" class="footer-link">Daftar Lowongan</a>
                <?php if ($sudah_login_footer) { ?>
                    <a href="<?= htmlspecialchars($jalur_dasar_footer) ?>tambah.php" class="footer-link">Tambah Lowongan</a>
                    <a href="<?= htmlspecialchars($jalur_dasar_footer) ?>auth/logout.php" class="footer-link">Logout</a>
                <?php } else { ?>
                    <a href="<?= htmlspecialchars($jalur_dasar_footer) ?>auth/login.php" class="footer-link">Login</a>
                    <a href="<?= htmlspecialchars($jalur_dasar_footer) ?>auth/register.php" class="footer-link">Register</a>
                <?php } ?>
            </nav>
        </div>
        <div class="footer-bottom">
            <div class="container footer-bottom-inner">
                <p>&copy; <?= date("Y") ?> InfoLoker. Dikembangkan dengan PHP Native &amp; MySQLi.</p>
            </div>
        </div>
    </footer>
</body>
</html>
