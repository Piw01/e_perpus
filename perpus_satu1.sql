-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Feb 2026 pada 13.24
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpus_satu`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_hitung_denda` (IN `p_id_peminjaman` INT, IN `p_tanggal_kembali` DATE, OUT `p_keterlambatan` INT, OUT `p_total_denda` INT)   BEGIN
    DECLARE v_tanggal_harus_kembali DATE;
    DECLARE v_denda_per_hari INT;
    
    -- Ambil tanggal harus kembali
    SELECT tanggal_harus_kembali INTO v_tanggal_harus_kembali
    FROM peminjaman
    WHERE id_peminjaman = p_id_peminjaman;
    
    -- Ambil setting denda
    SELECT denda_per_hari INTO v_denda_per_hari
    FROM setting_perpus
    LIMIT 1;
    
    -- Hitung keterlambatan
    SET p_keterlambatan = DATEDIFF(p_tanggal_kembali, v_tanggal_harus_kembali);
    
    IF p_keterlambatan > 0 THEN
        SET p_total_denda = p_keterlambatan * v_denda_per_hari;
    ELSE
        SET p_keterlambatan = 0;
        SET p_total_denda = 0;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=Super Admin, 2=Admin, 3=Operator',
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `foto_profil` varchar(100) DEFAULT 'default_admin.png',
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_lengkap`, `username`, `password`, `level`, `email`, `no_hp`, `foto_profil`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Administrator E-Perpus', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'admin@eperpussbg.my.id', '081234567890', 'default_admin.png', 'aktif', '2026-01-12 05:42:54', '2026-01-12 05:42:54'),
(3, 'Pi', 'pi', '$2y$10$fw595qJSz6eFq5bjMaWCCeH9BA3V0RtXEagiXJNeeycbrQ/g7SQ9y', 1, NULL, NULL, 'default_admin.png', 'aktif', '2026-01-19 11:26:09', '2026-01-19 11:26:09'),
(4, 'Syahril', 'syh', '$2y$10$CZsinSUXsq5lENlIKlRo5eS3BZhLpv7kcvVoqJ/Vo.zPnLHs/9tZ.', 2, NULL, NULL, 'default_admin.png', 'aktif', '2026-01-19 11:26:32', '2026-01-19 11:26:32'),
(5, 'Yoni', 'yon', '$2y$10$oI70CbNwq2IsHBiU8aM9PehXCo9JB7bb.SKNUp7oHlZ.SpWRfPamG', 2, NULL, NULL, 'default_admin.png', 'aktif', '2026-01-19 11:26:47', '2026-01-19 11:26:47'),
(6, 'Raihan', 'han', '$2y$10$42jBhffqp8xQVXFOf0TMlubkC0dPXuTTBvMNpelMuFmCoPVC82rXi', 2, NULL, NULL, 'default_admin.png', 'aktif', '2026-01-19 11:27:02', '2026-01-19 11:27:02'),
(7, 'Danial', 'nil', '$2y$10$HrFpuiBrHWSaaC5ea.3qYOz1lYkb3bC.GqIuES65A6kVRtYJZdZQK', 3, NULL, NULL, 'default_admin.png', 'aktif', '2026-01-19 11:27:16', '2026-01-19 11:27:16'),
(8, 'Ian', 'ian', '$2y$10$BfpuFjKaWy9LicLqibZbJeRAHxv3GvgdmGyjHLHgnONVL/inEaIjy', 2, NULL, NULL, 'default_admin.png', 'aktif', '2026-01-19 11:27:28', '2026-01-19 11:27:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `no_anggota` varchar(20) NOT NULL COMMENT 'Nomor ID anggota',
  `nik` varchar(16) DEFAULT NULL COMMENT 'NIK KTP',
  `nama_lengkap` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text NOT NULL,
  `kelurahan` varchar(50) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kota` varchar(50) DEFAULT 'Subang',
  `kode_pos` varchar(5) DEFAULT NULL,
  `no_hp` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_profil` varchar(100) DEFAULT 'default_user.png',
  `pekerjaan` varchar(50) DEFAULT 'Pelajar/Mahasiswa',
  `instansi` varchar(100) DEFAULT NULL,
  `status_anggota` enum('aktif','nonaktif','suspended') DEFAULT 'aktif',
  `tanggal_daftar` date NOT NULL,
  `tanggal_expired` date NOT NULL COMMENT 'Masa aktif keanggotaan',
  `total_pinjam` int(11) DEFAULT 0,
  `denda_aktif` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `no_anggota`, `nik`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `kelurahan`, `kecamatan`, `kota`, `kode_pos`, `no_hp`, `email`, `username`, `password`, `foto_profil`, `pekerjaan`, `instansi`, `status_anggota`, `tanggal_daftar`, `tanggal_expired`, `total_pinjam`, `denda_aktif`, `created_at`, `updated_at`) VALUES
(1, 'ANG-2026-001', '3213012001010001', 'Lutfi Mahesa', 'L', 'Bandung', '2004-11-14', 'Jl. Raya Subang KM 5', 'Kalijati', 'Kalijati', 'Subang', '41271', '081234567890', 'lutfi@email.com', 'lutfi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'default_user.png', 'Mahasiswa', 'Universitas Teknologi Bandung', 'aktif', '2026-01-01', '2027-01-01', 4, 0, '2026-01-12 05:42:55', '2026-01-23 08:50:26'),
(3, 'ANG-2026-002', '1234567891234567', 'Pi', 'L', 'Bandung', '1999-01-01', 'sbg', 'pgdn', 'pgdn', 'Subang', '15646', '085238473823', 'pi@gmail.com', 'pi', '$2y$10$kkQUudepgVknho4tOy8gtuO0RyXZVZMOwJN17qkpPrGE384ETjnYy', 'default_user.png', 'Mahasiswa', 'UTB', 'aktif', '2026-01-23', '2027-01-23', 0, 0, '2026-01-23 12:33:08', '2026-01-23 12:33:08'),
(4, 'ANG-2026-003', '1234567890098765', 'nurman', 'L', 'sbg', '2003-03-02', 'sbg', 'pgdn', 'pgdn', 'Subang', '23484', '082937737848', 'nrmn@gmail.com', 'nrm', '$2y$10$3S8K4kBAhfE5kejM3iKW3eq4RkVt.l5lyVrJlGbHo53VdS51g0umi', 'default_user.png', 'mhs', 'utb', 'aktif', '2026-01-31', '2027-01-31', 1, 0, '2026-01-31 18:30:17', '2026-02-01 10:49:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `isbn` varchar(30) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `id_penulis` int(11) NOT NULL,
  `id_penerbit` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `tahun_terbit` year(4) NOT NULL,
  `sinopsis` text DEFAULT NULL,
  `jumlah_total` int(11) NOT NULL DEFAULT 1 COMMENT 'Total eksemplar',
  `jumlah_tersedia` int(11) NOT NULL DEFAULT 1 COMMENT 'Yang bisa dipinjam',
  `lokasi_rak` varchar(20) DEFAULT 'A1',
  `foto_sampul` varchar(100) DEFAULT 'default_book.jpg',
  `halaman` int(5) DEFAULT NULL,
  `bahasa` varchar(20) DEFAULT 'Indonesia',
  `status` enum('tersedia','habis','rusak') DEFAULT 'tersedia',
  `rating` decimal(2,1) DEFAULT 0.0,
  `jumlah_review` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id_buku`, `isbn`, `judul`, `id_penulis`, `id_penerbit`, `id_kategori`, `tahun_terbit`, `sinopsis`, `jumlah_total`, `jumlah_tersedia`, `lokasi_rak`, `foto_sampul`, `halaman`, `bahasa`, `status`, `rating`, `jumlah_review`, `created_at`, `updated_at`) VALUES
(1, '978-602-03-0371-9', 'Laskar Pelangi', 1, 1, 1, '2008', 'Novel tentang perjuangan anak-anak Belitung', 5, 5, 'A1', 'laskar_pelangi.jpg', 529, 'Indonesia', 'tersedia', 4.8, 152, '2026-01-12 05:42:55', '2026-01-23 08:51:21'),
(2, '978-602-06-1234-5', 'Bumi', 2, 1, 1, '2014', 'Serial Bumi - petualangan Raib, Seli, dan Ali', 3, 3, 'A2', 'bumi.jpg', 440, 'Indonesia', 'tersedia', 4.6, 89, '2026-01-12 05:42:55', '2026-01-23 08:50:55'),
(3, '978-979-22-3870-5', 'Bumi Manusia', 3, 2, 1, '1980', 'Tetralogi Buru - Pramoedya', 4, 4, 'B1', 'bumi_manusia.jpg', 535, 'Indonesia', 'tersedia', 4.9, 234, '2026-01-12 05:42:55', '2026-01-23 08:51:21'),
(4, '64-6221541', 'KIDNAPPED', 7, 6, 1, '2006', '\"Kidnapped\" karya Robert Louis Stevenson adalah novel petualangan fiksi sejarah yang pertama kali diterbitkan pada tahun 1886. Kehidupan David Balfour yang berusia tujuh belas tahun berubah menjadi kelam ketika pamannya mengkhianatinya, yang menyebabkan penculikannya dan perjalanan paksa ke perbudakan kolonial. Setelah sebuah kapal karam mempertemukannya kembali dengan Alan Breck Stewart, seorang buronan Jacobite, keduanya menjadi teman yang tak terduga yang melarikan diri melalui Dataran Tinggi Skotlandia yang berbahaya. Berlatar belakang Skotlandia abad ke-18 yang bergejolak, perjalanan mereka terjalin', 1, 1, 'A1', '', NULL, 'Indonesia', 'tersedia', 0.0, 0, '2026-02-01 03:25:21', '2026-02-01 10:49:59'),
(5, '10293847565647', 'The call of the wild', 8, 4, 1, '2008', '\"The Call of the Wild\" karya Jack London adalah novel petualangan yang diterbitkan pada tahun 1903. Buck, seekor anjing perkasa yang hidup nyaman di California, dicuri dan dijual untuk dijadikan anjing penarik kereta salju selama Demam Emas Klondike. Terlempar ke hutan belantara Yukon yang brutal, ia harus berjuang untuk bertahan hidup di antara anjing-anjing lain dan majikan yang kejam. Saat Buck bertahan di lingkungan yang tak kenal ampun, ia menjadi semakin primitif, melepaskan kehidupan jinaknya dan menjawab', 7, 7, 'A1', '', NULL, 'Indonesia', 'tersedia', 0.0, 0, '2026-02-01 03:33:08', '2026-02-01 03:33:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id_detail` int(11) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `kondisi_pinjam` enum('baik','rusak ringan','rusak berat') DEFAULT 'baik',
  `kondisi_kembali` enum('baik','rusak ringan','rusak berat','hilang') DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id_detail`, `id_peminjaman`, `id_buku`, `kondisi_pinjam`, `kondisi_kembali`, `catatan`) VALUES
(1, 1, 3, 'baik', NULL, NULL),
(2, 2, 1, 'baik', NULL, NULL),
(3, 3, 2, 'baik', NULL, NULL),
(4, 4, 3, 'baik', NULL, NULL),
(5, 4, 1, 'baik', NULL, NULL),
(6, 5, 4, 'baik', NULL, NULL);

--
-- Trigger `detail_peminjaman`
--
DELIMITER $$
CREATE TRIGGER `trg_after_insert_detail_peminjaman` AFTER INSERT ON `detail_peminjaman` FOR EACH ROW BEGIN
    UPDATE buku 
    SET jumlah_tersedia = jumlah_tersedia - 1
    WHERE id_buku = NEW.id_buku;
    
    UPDATE buku 
    SET status = CASE 
        WHEN jumlah_tersedia = 0 THEN 'habis'
        ELSE 'tersedia'
    END
    WHERE id_buku = NEW.id_buku;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'fa-book',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`, `icon`, `created_at`) VALUES
(1, 'Fiksi', 'Novel, cerpen, dan karya fiksi lainnya', 'fa-book-open', '2026-01-12 05:42:55'),
(2, 'Non-Fiksi', 'Biografi, sejarah, sains', 'fa-graduation-cap', '2026-01-12 05:42:55'),
(3, 'Teknologi', 'Pemrograman, IT, komputer', 'fa-laptop-code', '2026-01-12 05:42:55'),
(4, 'Pendidikan', 'Buku pelajaran dan referensi', 'fa-chalkboard-teacher', '2026-01-12 05:42:55'),
(5, 'Agama', 'Kitab suci dan kajian agama', 'fa-mosque', '2026-01-12 05:42:55'),
(6, 'Komik & Manga', 'Komik dan manga', 'fa-book-reader', '2026-01-12 05:42:55'),
(7, 'Horror', NULL, 'fa-book', '2026-01-22 15:46:29');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `user_type` enum('admin','anggota') NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `aktivitas` varchar(100) NOT NULL COMMENT 'Jenis aktivitas',
  `modul` varchar(50) NOT NULL COMMENT 'Modul yang diakses',
  `keterangan` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `kode_peminjaman` varchar(20) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL COMMENT 'Admin yang memproses',
  `tanggal_pinjam` date NOT NULL,
  `tanggal_harus_kembali` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `durasi_hari` int(3) NOT NULL DEFAULT 7 COMMENT 'Durasi peminjaman',
  `status_pinjam` enum('dipinjam','dikembalikan','terlambat') DEFAULT 'dipinjam',
  `total_buku` int(3) NOT NULL DEFAULT 0,
  `catatan_pinjam` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `kode_peminjaman`, `id_anggota`, `id_admin`, `tanggal_pinjam`, `tanggal_harus_kembali`, `tanggal_kembali`, `durasi_hari`, `status_pinjam`, `total_buku`, `catatan_pinjam`, `created_at`, `updated_at`) VALUES
(1, 'PJM-202601-0001', 1, 1, '2026-01-22', '2026-01-29', '2026-01-22', 7, 'dikembalikan', 1, NULL, '2026-01-22 15:41:27', '2026-01-22 15:48:30'),
(2, 'PJM-202601-0002', 1, 1, '2026-01-22', '2026-01-24', '2026-01-22', 2, 'dikembalikan', 1, NULL, '2026-01-22 15:57:48', '2026-01-22 15:58:07'),
(3, 'PJM-202601-0003', 1, 3, '2026-01-22', '2026-01-27', '2026-01-23', 5, 'dikembalikan', 1, NULL, '2026-01-22 17:17:38', '2026-01-23 08:50:55'),
(4, 'PJM-202601-0004', 1, 1, '2026-01-23', '2026-01-27', '2026-01-23', 4, 'dikembalikan', 2, NULL, '2026-01-23 08:50:26', '2026-01-23 08:51:21'),
(5, 'PJM-202602-0001', 4, 1, '2026-02-01', '2026-02-08', '2026-02-01', 7, 'dikembalikan', 1, NULL, '2026-02-01 10:49:45', '2026-02-01 10:49:59');

--
-- Trigger `peminjaman`
--
DELIMITER $$
CREATE TRIGGER `trg_after_insert_peminjaman` AFTER INSERT ON `peminjaman` FOR EACH ROW BEGIN
    UPDATE anggota 
    SET total_pinjam = total_pinjam + 1
    WHERE id_anggota = NEW.id_anggota;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_after_update_peminjaman_kembali` AFTER UPDATE ON `peminjaman` FOR EACH ROW BEGIN
    IF NEW.status_pinjam = 'dikembalikan' AND OLD.status_pinjam != 'dikembalikan' THEN
        UPDATE buku b
        JOIN detail_peminjaman dp ON b.id_buku = dp.id_buku
        SET b.jumlah_tersedia = b.jumlah_tersedia + 1,
            b.status = 'tersedia'
        WHERE dp.id_peminjaman = NEW.id_peminjaman;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penerbit`
--

CREATE TABLE `penerbit` (
  `id_penerbit` int(11) NOT NULL,
  `nama_penerbit` varchar(100) NOT NULL,
  `kota` varchar(50) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penerbit`
--

INSERT INTO `penerbit` (`id_penerbit`, `nama_penerbit`, `kota`, `alamat`, `telepon`, `email`, `created_at`) VALUES
(1, 'Gramedia Pustaka Utama', 'Jakarta', 'Jl. Palmerah Barat 29-37, Jakarta', '021-5483008', 'info@gramedia.com', '2026-01-12 05:42:55'),
(2, 'Erlangga', 'Jakarta', 'Jl. H. Baping Raya No.100, Jakarta', '021-53650606', 'cs@erlangga.co.id', '2026-01-12 05:42:55'),
(3, 'Mizan Pustaka', 'Bandung', 'Jl. Cinambo 136, Bandung', '022-7834310', 'redaksi@mizan.com', '2026-01-12 05:42:55'),
(4, 'Project Gutenberg™ electronic works', 'U.S.', NULL, NULL, NULL, '2026-01-31 19:44:38'),
(5, 'Grasindo', 'Jakarta', NULL, NULL, NULL, '2026-01-31 20:04:56'),
(6, 'Harpers and Brothers', 'New york and London', NULL, NULL, NULL, '2026-02-01 03:27:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `kode_pengembalian` varchar(20) NOT NULL,
  `id_peminjaman` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `keterlambatan_hari` int(3) DEFAULT 0,
  `denda_keterlambatan` int(11) DEFAULT 0,
  `denda_kerusakan` int(11) DEFAULT 0,
  `denda_kehilangan` int(11) DEFAULT 0,
  `total_denda` int(11) DEFAULT 0,
  `status_bayar` enum('lunas','belum_lunas','dicicil') DEFAULT 'lunas',
  `jumlah_dibayar` int(11) DEFAULT 0,
  `sisa_denda` int(11) DEFAULT 0,
  `catatan_pengembalian` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengembalian`
--

INSERT INTO `pengembalian` (`id_pengembalian`, `kode_pengembalian`, `id_peminjaman`, `id_admin`, `tanggal_kembali`, `keterlambatan_hari`, `denda_keterlambatan`, `denda_kerusakan`, `denda_kehilangan`, `total_denda`, `status_bayar`, `jumlah_dibayar`, `sisa_denda`, `catatan_pengembalian`, `created_at`) VALUES
(1, 'KMB-202601-0001', 1, 1, '2026-01-22', 7, 0, 50000, 0, 50000, 'lunas', 50000, -50000, NULL, '2026-01-22 15:48:30'),
(2, 'KMB-202601-0002', 2, 1, '2026-01-22', 2, 0, 0, 0, 0, 'lunas', 0, 0, NULL, '2026-01-22 15:58:07'),
(3, 'KMB-202601-0003', 3, 1, '2026-01-23', 4, 0, 0, 0, 0, 'lunas', 0, 0, NULL, '2026-01-23 08:50:55'),
(4, 'KMB-202601-0004', 4, 1, '2026-01-23', 4, 0, 50000, 0, 50000, 'lunas', 40000, 0, NULL, '2026-01-23 08:51:21'),
(5, 'KMB-202602-0001', 5, 1, '2026-02-01', 0, 0, 50000, 0, 50000, 'lunas', 50000, 0, NULL, '2026-02-01 10:49:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penulis`
--

CREATE TABLE `penulis` (
  `id_penulis` int(11) NOT NULL,
  `nama_penulis` varchar(100) NOT NULL,
  `biografi` text DEFAULT NULL,
  `negara` varchar(50) DEFAULT 'Indonesia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penulis`
--

INSERT INTO `penulis` (`id_penulis`, `nama_penulis`, `biografi`, `negara`, `created_at`) VALUES
(1, 'Andrea Hirata', 'Penulis novel Laskar Pelangi', 'Indonesia', '2026-01-12 05:42:55'),
(2, 'Tere Liye', 'Penulis Indonesia produktif', 'Indonesia', '2026-01-12 05:42:55'),
(3, 'Pramoedya Ananta Toer', 'Sastrawan Indonesia', 'Indonesia', '2026-01-12 05:42:55'),
(4, 'J.K. Rowling', 'Penulis Harry Potter', 'Inggris', '2026-01-12 05:42:55'),
(5, 'Haruki Murakami', 'Novelis Jepang', 'Jepang', '2026-01-12 05:42:55'),
(6, 'Tan Malaka', NULL, 'Indonesia', '2026-01-22 15:46:43'),
(7, 'Robert Louis Stevenson', NULL, 'Indonesia', '2026-01-31 19:41:25'),
(8, 'Jack London', NULL, 'Indonesia', '2026-01-31 19:45:18'),
(9, 'H. Rider Haggard', NULL, 'Indonesia', '2026-01-31 19:45:49'),
(10, 'DANIEL KAHNEMAN', NULL, 'Indonesia', '2026-01-31 19:58:52'),
(12, 'Brian Chrisna', NULL, 'Indonesia', '2026-01-31 20:04:26'),
(14, 'Arthur O. Friel', NULL, 'Indonesia', '2026-02-01 03:34:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reservasi`
--

CREATE TABLE `reservasi` (
  `id_reservasi` int(11) NOT NULL,
  `kode_reservasi` varchar(20) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tanggal_reservasi` datetime NOT NULL DEFAULT current_timestamp(),
  `tanggal_expired` datetime NOT NULL COMMENT 'Batas ambil buku',
  `status` enum('pending','diambil','expired','batal') DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reservasi`
--

INSERT INTO `reservasi` (`id_reservasi`, `kode_reservasi`, `id_anggota`, `id_buku`, `tanggal_reservasi`, `tanggal_expired`, `status`, `catatan`, `created_at`) VALUES
(4, 'RSV-202601-0001', 3, 2, '2026-01-23 20:36:59', '2026-01-26 14:36:59', 'diambil', NULL, '2026-01-23 13:36:59'),
(5, 'RSV-202601-0002', 4, 2, '2026-02-01 01:30:34', '2026-02-03 19:30:34', 'diambil', NULL, '2026-01-31 18:30:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_perpus`
--

CREATE TABLE `setting_perpus` (
  `id_setting` int(11) NOT NULL,
  `nama_perpus` varchar(100) DEFAULT 'E-Perpus Subang',
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT 'eperpussbg.my.id',
  `logo` varchar(100) DEFAULT 'logo.png',
  `durasi_pinjam_default` int(3) DEFAULT 7 COMMENT 'Durasi peminjaman (hari)',
  `max_buku_pinjam` int(2) DEFAULT 3 COMMENT 'Maksimal buku per peminjaman',
  `max_perpanjangan` int(2) DEFAULT 1 COMMENT 'Maksimal perpanjang',
  `denda_per_hari` int(11) DEFAULT 1000 COMMENT 'Denda keterlambatan per hari',
  `denda_rusak_ringan` int(11) DEFAULT 50000,
  `denda_rusak_berat` int(11) DEFAULT 100000,
  `denda_hilang` int(11) DEFAULT 200000,
  `masa_aktif_anggota` int(3) DEFAULT 365 COMMENT 'Masa aktif (hari)',
  `biaya_daftar` int(11) DEFAULT 0 COMMENT 'Biaya pendaftaran',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `setting_perpus`
--

INSERT INTO `setting_perpus` (`id_setting`, `nama_perpus`, `alamat`, `telepon`, `email`, `website`, `logo`, `durasi_pinjam_default`, `max_buku_pinjam`, `max_perpanjangan`, `denda_per_hari`, `denda_rusak_ringan`, `denda_rusak_berat`, `denda_hilang`, `masa_aktif_anggota`, `biaya_daftar`, `updated_at`) VALUES
(1, 'E-Perpus Subang', 'Jl. Raya Subang, Subang, Jawa Barat', '0260-123456', 'info@eperpussbg.my.id', 'eperpussbg.my.id', 'logo.png', 7, 3, 1, 1000, 50000, 100000, 200000, 365, 0, '2026-01-12 05:42:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `nisn` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nama_siswa` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `jenis_kelamin` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tempat_lahir` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `alamat` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_hp` varchar(13) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `foto_siswa` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`nisn`, `nama_siswa`, `jenis_kelamin`, `tempat_lahir`, `tgl_lahir`, `alamat`, `no_hp`, `foto_siswa`) VALUES
('233552011147', 'Lutfi Mahesa', 'L', 'Bandung', '2004-11-14', 'Bandung', '1234567891', 'default.png');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_buku_lengkap`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_buku_lengkap` (
`id_buku` int(11)
,`isbn` varchar(30)
,`judul` varchar(255)
,`nama_penulis` varchar(100)
,`nama_penerbit` varchar(100)
,`nama_kategori` varchar(50)
,`tahun_terbit` year(4)
,`jumlah_total` int(11)
,`jumlah_tersedia` int(11)
,`lokasi_rak` varchar(20)
,`foto_sampul` varchar(100)
,`status` enum('tersedia','habis','rusak')
,`rating` decimal(2,1)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_peminjaman_aktif`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_peminjaman_aktif` (
`kode_peminjaman` varchar(20)
,`no_anggota` varchar(20)
,`nama_lengkap` varchar(100)
,`tanggal_pinjam` date
,`tanggal_harus_kembali` date
,`total_buku` int(3)
,`status_pinjam` enum('dipinjam','dikembalikan','terlambat')
,`hari_terlambat` int(7)
,`admin_nama` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_statistik_dashboard`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_statistik_dashboard` (
`total_buku` bigint(21)
,`total_anggota_aktif` bigint(21)
,`total_buku_dipinjam` bigint(21)
,`total_terlambat` bigint(21)
,`total_denda_belum_lunas` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_buku_lengkap`
--
DROP TABLE IF EXISTS `v_buku_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_buku_lengkap`  AS SELECT `b`.`id_buku` AS `id_buku`, `b`.`isbn` AS `isbn`, `b`.`judul` AS `judul`, `p`.`nama_penulis` AS `nama_penulis`, `pen`.`nama_penerbit` AS `nama_penerbit`, `k`.`nama_kategori` AS `nama_kategori`, `b`.`tahun_terbit` AS `tahun_terbit`, `b`.`jumlah_total` AS `jumlah_total`, `b`.`jumlah_tersedia` AS `jumlah_tersedia`, `b`.`lokasi_rak` AS `lokasi_rak`, `b`.`foto_sampul` AS `foto_sampul`, `b`.`status` AS `status`, `b`.`rating` AS `rating` FROM (((`buku` `b` left join `penulis` `p` on(`b`.`id_penulis` = `p`.`id_penulis`)) left join `penerbit` `pen` on(`b`.`id_penerbit` = `pen`.`id_penerbit`)) left join `kategori` `k` on(`b`.`id_kategori` = `k`.`id_kategori`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_peminjaman_aktif`
--
DROP TABLE IF EXISTS `v_peminjaman_aktif`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_peminjaman_aktif`  AS SELECT `p`.`kode_peminjaman` AS `kode_peminjaman`, `a`.`no_anggota` AS `no_anggota`, `a`.`nama_lengkap` AS `nama_lengkap`, `p`.`tanggal_pinjam` AS `tanggal_pinjam`, `p`.`tanggal_harus_kembali` AS `tanggal_harus_kembali`, `p`.`total_buku` AS `total_buku`, `p`.`status_pinjam` AS `status_pinjam`, to_days(curdate()) - to_days(`p`.`tanggal_harus_kembali`) AS `hari_terlambat`, `adm`.`username` AS `admin_nama` FROM ((`peminjaman` `p` join `anggota` `a` on(`p`.`id_anggota` = `a`.`id_anggota`)) join `admin` `adm` on(`p`.`id_admin` = `adm`.`id_admin`)) WHERE `p`.`status_pinjam` = 'dipinjam' ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_statistik_dashboard`
--
DROP TABLE IF EXISTS `v_statistik_dashboard`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_statistik_dashboard`  AS SELECT (select count(0) from `buku` where `buku`.`status` = 'tersedia') AS `total_buku`, (select count(0) from `anggota` where `anggota`.`status_anggota` = 'aktif') AS `total_anggota_aktif`, (select count(0) from `peminjaman` where `peminjaman`.`status_pinjam` = 'dipinjam') AS `total_buku_dipinjam`, (select count(0) from `peminjaman` where `peminjaman`.`status_pinjam` = 'terlambat') AS `total_terlambat`, (select coalesce(sum(`pengembalian`.`sisa_denda`),0) from `pengembalian` where `pengembalian`.`status_bayar` <> 'lunas') AS `total_denda_belum_lunas` ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_level` (`level`);

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD UNIQUE KEY `no_anggota` (`no_anggota`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_no_anggota` (`no_anggota`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_status` (`status_anggota`),
  ADD KEY `idx_search_anggota` (`nama_lengkap`,`email`,`no_hp`);

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `id_penulis` (`id_penulis`),
  ADD KEY `id_penerbit` (`id_penerbit`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `idx_isbn` (`isbn`),
  ADD KEY `idx_judul` (`judul`),
  ADD KEY `idx_status` (`status`);
ALTER TABLE `buku` ADD FULLTEXT KEY `idx_search` (`judul`,`sinopsis`);
ALTER TABLE `buku` ADD FULLTEXT KEY `idx_search_buku` (`judul`,`sinopsis`);

--
-- Indeks untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `idx_peminjaman` (`id_peminjaman`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`),
  ADD KEY `idx_nama` (`nama_kategori`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_user` (`user_type`,`user_id`),
  ADD KEY `idx_aktivitas` (`aktivitas`),
  ADD KEY `idx_tanggal` (`created_at`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD UNIQUE KEY `kode_peminjaman` (`kode_peminjaman`),
  ADD KEY `id_anggota` (`id_anggota`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `idx_kode` (`kode_peminjaman`),
  ADD KEY `idx_status` (`status_pinjam`),
  ADD KEY `idx_tanggal` (`tanggal_pinjam`,`tanggal_harus_kembali`);

--
-- Indeks untuk tabel `penerbit`
--
ALTER TABLE `penerbit`
  ADD PRIMARY KEY (`id_penerbit`),
  ADD KEY `idx_nama` (`nama_penerbit`);

--
-- Indeks untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD UNIQUE KEY `kode_pengembalian` (`kode_pengembalian`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `idx_kode` (`kode_pengembalian`),
  ADD KEY `idx_status` (`status_bayar`);

--
-- Indeks untuk tabel `penulis`
--
ALTER TABLE `penulis`
  ADD PRIMARY KEY (`id_penulis`),
  ADD KEY `idx_nama` (`nama_penulis`);

--
-- Indeks untuk tabel `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id_reservasi`),
  ADD UNIQUE KEY `kode_reservasi` (`kode_reservasi`),
  ADD KEY `id_anggota` (`id_anggota`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `idx_kode` (`kode_reservasi`),
  ADD KEY `idx_status` (`status`);

--
-- Indeks untuk tabel `setting_perpus`
--
ALTER TABLE `setting_perpus`
  ADD PRIMARY KEY (`id_setting`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `penerbit`
--
ALTER TABLE `penerbit`
  MODIFY `id_penerbit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `penulis`
--
ALTER TABLE `penulis`
  MODIFY `id_penulis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id_reservasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `setting_perpus`
--
ALTER TABLE `setting_perpus`
  MODIFY `id_setting` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_penulis`) REFERENCES `penulis` (`id_penulis`),
  ADD CONSTRAINT `buku_ibfk_2` FOREIGN KEY (`id_penerbit`) REFERENCES `penerbit` (`id_penerbit`),
  ADD CONSTRAINT `buku_ibfk_3` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);

--
-- Ketidakleluasaan untuk tabel `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `detail_peminjaman_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_peminjaman_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`);

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengembalian_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `reservasi`
--
ALTER TABLE `reservasi`
  ADD CONSTRAINT `reservasi_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservasi_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
