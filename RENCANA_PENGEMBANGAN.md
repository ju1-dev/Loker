# Rencana Pengembangan Aplikasi Web InfoLoker

Dokumen ini merangkum rencana dan realisasi pengembangan aplikasi web **InfoLoker** (`C:\xampp\htdocs\Loker\`) untuk meningkatkan kelayakan antarmuka sebagai proyek portofolio showcase serta mempersiapkan repositori lokal agar siap dipublikasikan ke GitHub.

---

## 1. Tujuan Pengembangan

1. Mengubah tampilan daftar lowongan kerja pada `index.php` dari tabel statis (`<table>`) menjadi tata letak kartu (*card layout*) yang responsif di desktop, tablet, dan mobile.
2. Menerapkan pembagian halaman (*pagination*) di sisi server beserta kontrol input jumlah data per halaman (`per_halaman`) yang dapat diatur langsung oleh pengguna.
3. Menghadirkan `<header>` dan `<footer>` yang konsisten di seluruh 6 halaman antarmuka (`index.php`, `detail.php`, `tambah.php`, `edit.php`, `auth/login.php`, `auth/register.php`) tanpa menutupi konten utama.
4. Menjaga kepatuhan penuh terhadap konvensi kode PHP Native SMK XI RPL dan CSS murni tanpa `:root` maupun `var(...)`.
5. Menyusun dokumentasi proyek (`RENCANA_PENGEMBANGAN.md`, `README.md`), `.gitignore`, dan melakukan inisialisasi repositori Git lokal pada cabang `main`.

---

## 2. Teknik Pembagian Halaman: Server-Side Offset-Based Pagination

Untuk menampilkan sebagian data lowongan per halaman secara efisien dari `db_loker` (128 data awal pada `db_loker.sql`), aplikasi menerapkan teknik **Server-Side Offset-Based Pagination** (*Pagination Berbasis Offset di Sisi Server*).

### Cara Kerja Teknik Ini

1. **Validasi Input Pengguna (`$per_halaman` dan `$halaman`)**:
   - Nilai `per_halaman` diambil dari parameter `GET` (`$_GET['per_halaman']`), divalidasi dengan `filter_var(..., FILTER_VALIDATE_INT)`, memiliki nilai bawaan `9`, dan dibatasi pada rentang `1` hingga `100` agar aman dari input kosong, nol, negatif, atau teks acak.
   - Nilai `halaman` diambil dari parameter `GET` (`$_GET['halaman']`), divalidasi sebagai bilangan bulat, lalu dibatasi pada rentang `1` hingga `$total_halaman`.

2. **Menghitung Total Data dengan `SELECT COUNT(*)`**:
   - Sebelum mengambil daftar kartu, `index.php` menjalankan kueri terpisah `SELECT COUNT(*) AS total FROM lowongan` (atau dengan klausa `WHERE ... LIKE ?` saat pencarian `cari` aktif) menggunakan `mysqli_prepare`.
   - Total halaman dihitung dengan rumus:
     - `$total_halaman = ($total_data > 0) ? (int)ceil($total_data / $per_halaman) : 1;`

3. **Menghitung Posisi Awal (`$offset`)**:
   - Posisi baris awal untuk halaman aktif dihitung dengan rumus:
     - `$offset = ($halaman - 1) * $per_halaman;`

4. **Mengambil Data Per Halaman dengan SQL `LIMIT ? OFFSET ?`**:
   - Kueri utama `SELECT * FROM lowongan ... ORDER BY id DESC LIMIT ? OFFSET ?` dijalankan melalui `mysqli_prepare`, `$stmt->bind_param(...)`, `$stmt->execute()`, dan `$stmt->get_result()`.
   - Hanya baris sejumlah `$per_halaman` yang dikirim dari MySQL ke PHP, sehingga beban memori dan waktu muat halaman tetap ringan.

5. **Kontrol Dinamis & Pelestarian Filter**:
   - Formulir `GET` di `index.php` menyediakan kolom pencarian `cari` serta input angka `per_halaman` (`min="1" max="100"` dengan `onchange="this.form.submit()"` dan tombol `Terapkan`). Parameter `halaman` sengaja tidak disertakan di formulir filter agar perubahan pencarian atau jumlah item per halaman otomatis kembali ke halaman 1.
   - Seluruh tautan navigasi pagination (`Sebelumnya`, nomor halaman dengan jendela ringkas + `...`, dan `Berikutnya`) mempertahankan parameter `cari` dan `per_halaman`.

---

## 3. Ringkasan Perubahan Per Komponen

### A. Tata Letak Kartu & Pagination (`index.php`)
- Menghapus seluruh elemen `<table>` dari `index.php` dan menggantinya dengan `.loker-grid` berisi kartu `<article class="loker-card">`.
- Setiap kartu menampilkan chip `kode_lowongan`, lencana status (`Dibuka`/`Ditutup`), judul `posisi`, `perusahaan`, metadata (`Bidang`, `Tipe`, `Lokasi`, `Kisaran Gaji`, `Tanggal Tayang` ter-escape `htmlspecialchars`), serta tombol aksi (`Detail`, ditambah `Edit` dan `Hapus` POST form bila pengguna sudah login).
- Menambahkan ringkasan rentang data (`Menampilkan X-Y dari Z lowongan`) dan tampilan kondisi kosong (`.empty-state`) saat `$total_data === 0`.
- Mengganti `mysqli_query` pada jalur non-pencarian dengan `mysqli_prepare` agar 100% kueri memakai prepared statement.

### B. Header, Footer & Peningkatan UI Showcase (6 Halaman)
- Membuat partial bersama `includes/header.php` dan `includes/footer.php` yang menerima `$base_path` (`""` untuk halaman root, `"../"` untuk folder `auth/`) serta `$page_title`.
- Menerapkan tata letak Flexbox vertikal pada `body` (`display: flex; flex-direction: column; min-height: 100vh;`) dan `.main-content` (`flex: 1;`) tanpa `position: fixed`, sehingga `<footer>` konsisten berada di bawah konten dan tidak pernah menutupi elemen utama di desktop maupun mobile.
- Peningkatan per halaman:
  1. `index.php`: Banner ringkasan halaman, toolbar filter pencarian + kontrol `per_halaman`, grid kartu responsif (3 kolom desktop, 2 kolom tablet, 1 kolom mobile), dan bar pagination ringkas.
  2. `detail.php`: Mengganti tabel metadata mentah menjadi `<dl class="detail-meta-grid">`, memformat deskripsi dengan `nl2br(htmlspecialchars($data['deskripsi']))`, meng-escape `created_at`, dan mengelompokkan aksi `Kembali`, `Edit`, `Hapus`.
  3. `tambah.php` & `edit.php`: Kartu formulir terpusat (`.form-card`), grid 2 kolom di desktop dan 1 kolom di mobile, label eksplisit `<label for="...">` dengan `id="..."` di setiap field, serta tombol radio pil untuk `status` pada `edit.php` tanpa tag `<br>`.
  4. `auth/login.php` & `auth/register.php`: Kartu autentikasi terpusat (`.auth-card`) dengan `$base_path = "../"`, banner pesan status terstruktur, serta header dan footer yang seragam.

### C. CSS Murni & Kepatuhan Konvensi Kode (R4)
- `assets/css/style.css`: Menggunakan CSS murni dengan warna hex langsung, **tanpa** `:root` dan **tanpa** `var(...)`. Memperbaiki kesalahan penulisan `font-weight: 666` menjadi `600`, menambahkan indikator `:focus-visible`, area sentuh minimum `44px`, kontras warna terverifikasi WCAG AA, dan breakpoint responsif (`<= 960px` dan `<= 640px`).
- Seluruh berkas PHP: Memakai penulisan variabel `<variabel> = <nilai>`, blok kontrol `{ ... }` (tanpa `endif`/`endwhile`/`endforeach`), `mysqli_prepare` dengan `$stmt->...` dan `$result->...`, `exit;` setelah setiap `header("Location: ...")`, `htmlspecialchars(...)` pada seluruh output dinamis, serta `mysqli_set_charset($koneksi, "utf8mb4");` di `config/koneksi.php`.

---

## 4. Status Git & Langkah Push ke GitHub

- **Status saat ini**: Repositori Git lokal telah diinisialisasi di `C:\xampp\htdocs\Loker\` pada cabang `main` beserta file `.gitignore` dan `README.md`, dengan commit lokal yang bersih. Remote repository GitHub belum dikonfigurasi karena URL repositori target belum ditentukan pada lingkungan eksekusi.
- **Langkah push siap pakai** (jalankan dari `C:\xampp\htdocs\Loker\` setelah membuat repositori kosong di GitHub):

```powershell
$git = "C:\Program Files\Microsoft Visual Studio\18\Community\Common7\IDE\CommonExtensions\Microsoft\TeamFoundation\Team Explorer\Git\cmd\git.exe"
& $git remote add origin https://github.com/<username-github>/<nama-repo>.git
& $git branch -M main
& $git push -u origin main
```
