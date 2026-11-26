# InfoLoker - Portal Lowongan Kerja & Magang

Aplikasi web CRUD portal lowongan kerja dan program magang (PKL) yang dibangun menggunakan **PHP Native**, **MySQLi (Prepared Statements)**, **HTML5 semantik**, dan **CSS murni**. Proyek ini disiapkan sebagai portofolio showcase dengan tata letak kartu responsif, pembagian halaman dinamis di sisi server, serta antarmuka yang konsisten di seluruh halaman publik dan autentikasi.

---

## Fitur Utama

1. **Daftar Lowongan Berbasis Kartu (Card Layout)**:
   - Menampilkan lowongan kerja dan magang dalam grid kartu responsif (3 kolom desktop, 2 kolom tablet, 1 kolom mobile).
   - Setiap kartu menampilkan kode lowongan, status (`Dibuka` / `Ditutup`), posisi, perusahaan, bidang, tipe pekerjaan, lokasi, kisaran gaji, tanggal tayang, dan tombol aksi.
2. **Pencarian & Server-Side Offset-Based Pagination Dinamis**:
   - Pencarian berdasarkan kode lowongan, posisi, perusahaan, bidang, atau lokasi.
   - Kontrol input `Tampil per halaman` (`per_halaman`, rentang `1` sampai `100`, bawaan `9`) yang langsung mengubah jumlah kartu per halaman melalui kueri SQL `LIMIT ? OFFSET ?` dan `SELECT COUNT(*)`.
   - Navigasi halaman ringkas (`Sebelumnya`, nomor halaman dengan jendela aktif, `Berikutnya`) yang mempertahankan kata kunci pencarian dan ukuran halaman.
3. **Detail Lowongan & Manajemen Data (CRUD)**:
   - Halaman detail (`detail.php`) dengan informasi terstruktur dan deskripsi multi-baris (`nl2br`).
   - Tambah (`tambah.php`), ubah (`edit.php`), dan hapus (`hapus.php`) data lowongan yang dilindungi autentikasi sesi.
4. **Autentikasi Pengguna**:
   - Registrasi akun baru (`auth/register.php`) dengan validasi panjang username/password dan hashing `password_hash()`.
   - Login (`auth/login.php`) dengan `password_verify()` dan `session_regenerate_id(true)`, serta logout aman (`auth/logout.php`).
5. **Konsistensi Antarmuka & Aksesibilitas**:
   - Header dan footer bersama (`includes/header.php`, `includes/footer.php`) di semua halaman tanpa menutupi konten utama.
   - Kontras warna memenuhi standar WCAG AA, fokus keyboard terlihat (`:focus-visible`), serta label formulir eksplisit.

---

## Tech Stack

- **Bahasa Server**: PHP Native (diuji pada PHP 8.5.12 CLI / XAMPP)
- **Basis Data**: MySQL / MariaDB dengan ekstensi MySQLi (`mysqli_prepare`, `$stmt->...`, `$result->...`)
- **Frontend**: HTML5 Semantik & CSS Murni (`assets/css/style.css` dengan nilai hex langsung)

---

## Cara Menjalankan di Lokal (XAMPP)

1. Salin atau letakkan folder proyek ini ke direktori web server XAMPP:
   `C:\xampp\htdocs\Loker\`
2. Jalankan layanan **Apache** dan **MySQL** melalui XAMPP Control Panel.
3. Buat database bernama `db_loker` melalui phpMyAdmin (`http://localhost/phpmyadmin`) atau MySQL CLI, lalu impor berkas `db_loker.sql` (berisi 1 akun admin awal dan 128 data contoh lowongan).
4. Pastikan konfigurasi pada `config/koneksi.php` sesuai dengan pengaturan MySQL lokal Anda (bawaan XAMPP: host `localhost`, user `root`, password kosong `""`, database `db_loker`).
5. Buka aplikasi melalui browser:
   `http://localhost/Loker/`

### Akun Demo & Pendaftaran
- Tabel `akun` pada `db_loker.sql` sudah menyediakan akun bawaan dengan username `admin` (hash bcrypt tersimpan di database).
- Untuk langsung menguji fitur pengelola (`Tambah`, `Edit`, `Hapus`), Anda juga dapat membuat akun baru melalui halaman **Register** (`auth/register.php`) dengan username minimal 3 karakter dan password minimal 8 karakter.

---

## Struktur Proyek

```text
Loker/
├── assets/
│   └── css/
│       └── style.css          # Stylesheet CSS murni (responsif, hex langsung)
├── auth/
│   ├── login.php              # Halaman login pengguna
│   ├── logout.php             # Proses logout & hapus sesi
│   ├── proses_login.php       # Handler verifikasi login
│   ├── proses_register.php    # Handler registrasi akun baru
│   └── register.php           # Halaman pendaftaran akun
├── config/
│   └── koneksi.php            # Koneksi MySQLi + charset utf8mb4
├── includes/
│   ├── auth_check.php         # Proteksi halaman khusus pengguna login
│   ├── footer.php             # Komponen footer bersama
│   └── header.php             # Komponen header & navigasi bersama
├── db_loker.sql               # Skema tabel & data awal (akun + 128 lowongan)
├── detail.php                 # Halaman detail satu lowongan
├── edit.php                   # Formulir ubah data lowongan
├── hapus.php                  # Handler hapus data lowongan (POST)
├── index.php                  # Daftar kartu lowongan + pencarian + pagination
├── RENCANA_PENGEMBANGAN.md    # Dokumen rencana pengembangan & teknik pagination
├── README.md                  # Dokumentasi utama proyek
├── simpan.php                 # Handler simpan lowongan baru (POST)
├── tambah.php                 # Formulir tambah data lowongan
├── update.php                 # Handler perbarui data lowongan (POST)
└── .gitignore                 # Daftar berkas yang diabaikan Git
```
