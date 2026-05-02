-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2026 at 04:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_panel`
--

-- --------------------------------------------------------

--
-- Table structure for table `berita_acaras`
--

CREATE TABLE `berita_acaras` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `tanggal_acara` date NOT NULL,
  `deskripsi` text NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `publikasi` enum('publik','draft','arsip') NOT NULL DEFAULT 'draft',
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `berita_acaras`
--

INSERT INTO `berita_acaras` (`id`, `judul`, `slug`, `tanggal_acara`, `deskripsi`, `thumbnail`, `publikasi`, `tags`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'Tk Sc2 Menara Kota Parepare Meriahkan Peringatan Hari Kemerdekaan Dengan Upacara Dan Kegiatan Seru', 'tk-sc2-menara-kota-parepare-meriahkan-peringatan-hari-kemerdekaan-dengan-upacara-dan-kegiatan-seru', '2026-01-04', 'Dalam rangka menyemarakkan Hari Kemerdekaan Republik Indonesia yang ke-79, TK SC2 Menara Parepare menyelenggarakan berbagai kegiatan bertema kebangsaan yang sarat nilai edukatif. Kegiatan diawali dengan upacara bendera yang diikuti oleh seluruh siswa, guru, dan staf sekolah. Anak-anak tampak bersemangat mengenakan seragam khas sekolah berwarna hijau dan kuning, penuh antusiasme menyambut hari kemerdekaan.\nUpacara dilangsungkan di aula sekolah yang telah dihias meriah dengan nuansa merah putih, menciptakan atmosfer patriotik yang kuat. Meski masih usia dini, para siswa mengikuti jalannya upacara dengan penuh kesungguhan. Dalam momen ini, guru-guru dengan penuh kesabaran membimbing siswa mengenal arti penting menghormati pahlawan dan mencintai bangsa Indonesia sejak dini.\nUsai upacara, suasana semakin semarak dengan digelarnya berbagai permainan tradisional dan lomba di halaman sekolah. Area bermain yang nyaman dan aman—dilengkapi rumput sintetis dan fasilitas bermain seperti perosotan—menjadi tempat anak-anak menyalurkan energi dan keceriaan mereka. Selain menjadi ajang perayaan kemerdekaan, kegiatan ini juga bertujuan melatih kedisiplinan, kebersamaan, dan sportivitas di kalangan siswa.\nKepala Sekolah TK SC2 Menara Parepare menyampaikan bahwa kegiatan ini tidak hanya menjadi ajang memperingati jasa pahlawan, tetapi juga sebagai sarana menanamkan semangat nasionalisme kepada anak sejak dini. “Melalui kegiatan ini, anak-anak tidak hanya diperkenalkan pada sejarah perjuangan bangsa, tetapi juga diajak untuk bangga menjadi bagian dari bangsa Indonesia,” ungkapnya.\nRangkaian kegiatan peringatan Hari Kemerdekaan di TK SC2 Menara Parepare berlangsung dengan penuh semangat dan sukacita. Acara ditutup dengan pembagian hadiah kepada para siswa yang berhasil meraih juara dalam berbagai perlombaan.\nDengan terselenggaranya kegiatan ini, diharapkan nilai-nilai patriotisme dan cinta tanah air dapat tumbuh dan mengakar dalam diri anak-anak, membentuk generasi penerus bangsa yang cerdas, berkarakter, dan cinta Indonesia.', 'berita-acara/thumbnail-1767527583-KUrwBnXcrw.jpg', 'publik', '\"Hari Merdeka\"', '2026-01-04 03:53:03', '2026-01-06 23:59:05', NULL),
(5, 'Rutinitas Hari Juma\'at Tk Sc2 Menara Berkunjung Ke Monumen 100 Jiwa', 'rutinitas-hari-jumaat-tk-sc2-menara-berkunjung-ke-monumen-100-jiwa', '2025-01-12', 'Dalam rangka mengisi rutinitas kegiatan pembelajaran di hari Jum’at, TK SC2 Menara Kota Parepare melaksanakan kegiatan kunjungan edukatif ke Monumen 100 Jiwa pada Jum’at, 12 Januari 2025. Kegiatan ini diikuti oleh seluruh peserta didik dengan pendampingan guru, sebagai bagian dari pembelajaran luar kelas (outing class).\n\nKunjungan ini bertujuan untuk mengenalkan nilai-nilai sejarah, kebangsaan, serta menumbuhkan rasa cinta tanah air sejak usia dini. Melalui kegiatan ini, anak-anak diajak untuk mengenal Monumen 100 Jiwa sebagai salah satu simbol perjuangan dan pengorbanan para pahlawan, dengan penyampaian yang disesuaikan dengan usia dan tingkat pemahaman anak.\n\nSelama kegiatan berlangsung, siswa terlihat antusias mengikuti penjelasan guru, mengamati lingkungan sekitar monumen, serta berpartisipasi dalam kegiatan sederhana seperti tanya jawab dan dokumentasi bersama. Selain menambah wawasan, kegiatan ini juga melatih sikap disiplin, kebersamaan, dan kepedulian terhadap lingkungan sekitar.\n\nMelalui rutinitas kegiatan seperti ini, TK SC2 Menara berharap dapat memberikan pengalaman belajar yang bermakna, menyenangkan, serta mampu mendukung perkembangan karakter dan sosial anak secara optimal.', 'berita-acara/thumbnail-1767529530-5j29vhuqfn.jpg', 'publik', '\"Kegiatan Jumat,Outing Class\"', '2026-01-04 04:25:30', '2026-01-04 04:25:30', NULL),
(9, 'Kunjungan Walikota Parepare Pak H.tasmid Hamid Di Tk Sc2 Menara', 'kunjungan-walikota-parepare-pak-htasmid-hamid-di-tk-sc2-menara', '2026-01-09', 'Kegiatan kunjungan Wali Kota Parepare, Bapak H. Tasmid Hamid, di TK SC2 Menara berlangsung dengan penuh kehangatan dan antusiasme. Kunjungan ini disambut langsung oleh kepala sekolah, para guru, serta anak-anak TK SC2 Menara.\n\nDalam kegiatan tersebut, anak-anak mengikuti berbagai aktivitas edukatif, seperti menyanyikan lagu bersama, berinteraksi langsung dengan Wali Kota, serta menunjukkan hasil karya dan kegiatan belajar mereka di sekolah. Anak-anak juga diajak untuk berani menyapa, menjawab pertanyaan sederhana, dan belajar bersikap percaya diri di hadapan tamu.\n\nSelain itu, Bapak Wali Kota memberikan motivasi dan pesan positif kepada anak-anak agar selalu semangat belajar, berperilaku baik, serta menghargai guru dan orang tua. Kegiatan ini bertujuan untuk memberikan pengalaman belajar yang bermakna bagi anak-anak melalui interaksi langsung dengan pemimpin daerah, sekaligus menanamkan nilai keberanian, sopan santun, dan rasa bangga terhadap lingkungan sekolah sejak usia dini.\n\nKunjungan ini diharapkan dapat menjadi pengalaman berharga dan memperluas wawasan anak-anak TK SC2 Menara dalam suasana yang menyenangkan dan edukatif.', 'berita-acara/thumbnail-1771940383-7jj9yAO7PB.jpg', 'publik', '\"Walikota\"', '2026-01-08 20:57:52', '2026-02-24 05:39:43', NULL),
(10, 'Menumbuhkan Budaya Literasi Sejak Dini: Keseruan Kunjungan Edukatif Siswa Tk Sc2 Menara Ke Perpustakaan', 'menumbuhkan-budaya-literasi-sejak-dini-keseruan-kunjungan-edukatif-siswa-tk-sc2-menara-ke-perpustakaan', '2025-02-26', 'TK SC2 Menara sukses menggelar kegiatan kunjungan edukatif ke perpustakaan sebagai upaya mengenalkan dunia literasi kepada para siswa sejak dini. Kegiatan ini dirancang untuk memberikan pengalaman belajar yang menyenangkan di luar kelas sekaligus membangun kedekatan anak dengan buku.\n\nSelama kunjungan, para siswa diajak berkeliling mengenal berbagai koleksi buku cerita dan ensiklopedia anak. Antusiasme terlihat jelas saat anak-anak diberikan kesempatan untuk memilih buku favorit mereka dan mendengarkan sesi mendongeng (storytelling) yang interaktif. Suasana perpustakaan yang nyaman membuat anak-anak betah mengeksplorasi setiap sudut ruangan.\n\nPihak sekolah berharap melalui kegiatan ini, minat baca siswa dapat tumbuh menjadi sebuah kebiasaan positif. Dengan memperkenalkan perpustakaan sebagai tempat yang seru, siswa diharapkan tidak hanya mahir membaca, tetapi juga memiliki rasa ingin tahu yang tinggi dan imajinasi yang luas untuk masa depan mereka.', 'berita-acara/thumbnail-1767962799-yM6f9gJZe7.jpg', 'publik', '\"Perpustakaan\"', '2026-01-09 04:44:24', '2026-01-09 04:46:39', NULL),
(11, 'Keceriaan Anak Tk Sc2 Menara Dalam Kegiatan Mewarnai Di Cahaya Ujung Baru', 'keceriaan-anak-tk-sc2-menara-dalam-kegiatan-mewarnai-di-cahaya-ujung-baru', '2025-10-11', 'TK SC2 Menara melaksanakan kegiatan mewarnai yang bertempat di Cahaya Ujung Baru sebagai bagian dari pembelajaran kreatif dan pengembangan motorik halus anak. Kegiatan ini diikuti dengan penuh antusias oleh seluruh peserta didik yang dengan semangat menuangkan ide dan imajinasi mereka melalui berbagai pilihan warna.\n\nDalam kegiatan tersebut, anak-anak diberikan kebebasan untuk berekspresi sesuai kreativitas masing-masing, sekaligus dilatih untuk mengenal warna, meningkatkan konsentrasi, serta melatih kesabaran dan kerapian. Guru-guru mendampingi anak selama kegiatan berlangsung dengan memberikan arahan sederhana dan motivasi agar anak merasa percaya diri dalam berkarya.\n\nMelalui kegiatan mewarnai ini, diharapkan anak-anak dapat mengembangkan kreativitas, rasa percaya diri, serta menikmati proses belajar dalam suasana yang menyenangkan di luar lingkungan sekolah. Kegiatan ini juga menjadi pengalaman belajar yang bermakna bagi anak-anak TK SC2 Menara.', 'berita-acara/thumbnail-1768121876-qaSuDvIwiG.jpg', 'publik', '\"Mewarnai,Kreativitas\"', '2026-01-11 00:57:02', '2026-01-11 00:57:56', NULL),
(13, 'Kunjungan Edukatif Tk Sc2 Menara Ke Dinas Pemadam Kebakaran', 'kunjungan-edukatif-tk-sc2-menara-ke-dinas-pemadam-kebakaran', '2026-02-24', 'Pada tanggal 24 Februari 2026, siswa-siswi TK SC2 Menara melaksanakan kunjungan edukatif ke Dinas Pemadam Kebakaran sebagai bagian dari kegiatan pembelajaran luar kelas. Kegiatan ini bertujuan untuk mengenalkan profesi pemadam kebakaran serta menanamkan pemahaman dasar tentang pentingnya keselamatan dan kewaspadaan terhadap bahaya kebakaran sejak usia dini.\n\nDalam kunjungan tersebut, anak-anak diperkenalkan dengan berbagai peralatan pemadam kebakaran, mobil damkar, serta tata cara penanganan kebakaran secara sederhana. Petugas juga memberikan penjelasan dengan bahasa yang mudah dipahami dan interaktif sehingga siswa terlihat antusias dan aktif bertanya.\n\nMelalui kegiatan ini, diharapkan siswa dapat memahami peran penting pemadam kebakaran dalam menjaga keselamatan masyarakat serta belajar untuk lebih berhati-hati dalam kehidupan sehari-hari. Kunjungan ini menjadi pengalaman belajar yang menyenangkan sekaligus bermanfaat bagi perkembangan pengetahuan dan keberanian anak-anak.', 'berita-acara/thumbnail-1771941110-SVZHVdCg58.jpg', 'publik', '\"Kunjungan Edukatif\"', '2026-02-24 05:51:50', '2026-02-24 05:51:50', NULL),
(14, 'Elajar Dari Alam: Petualangan Seru Siswa Tk Sc2 Menara Di Kebun Raya Jompie', 'elajar-dari-alam-petualangan-seru-siswa-tk-sc2-menara-di-kebun-raya-jompie', '2026-02-10', 'Pada tanggal 24 Februari 2026, siswa-siswi TK SC2 Menara melaksanakan kegiatan pembelajaran luar kelas di Kebun Raya Jompie. Kegiatan ini bertujuan untuk mengenalkan anak-anak pada berbagai jenis tanaman serta menumbuhkan rasa cinta terhadap lingkungan sejak usia dini.\n\nSelama kunjungan, siswa diperkenalkan dengan beragam tumbuhan, pepohonan, serta manfaatnya bagi kehidupan sehari-hari. Guru memberikan penjelasan sederhana mengenai pentingnya menjaga kelestarian alam, sementara anak-anak diajak mengamati langsung lingkungan sekitar dengan penuh antusias.\n\nMelalui kegiatan ini, diharapkan siswa dapat belajar secara langsung dari alam, meningkatkan rasa ingin tahu, serta membangun kepedulian terhadap lingkungan. Kegiatan berlangsung dengan tertib, menyenangkan, dan memberikan pengalaman belajar yang berkesan bagi seluruh siswa.', 'berita-acara/thumbnail-1771941382-NYvYucwdaE.jpg', 'publik', '\"Kunjungan Edukatif,Kebun Raya Jompie\"', '2026-02-24 05:56:22', '2026-02-24 05:56:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `berita_acara_media`
--

CREATE TABLE `berita_acara_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `berita_acara_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `nama_file` varchar(255) DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `is_thumbnail` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `berita_acara_media`
--

INSERT INTO `berita_acara_media` (`id`, `berita_acara_id`, `path`, `nama_file`, `urutan`, `keterangan`, `is_thumbnail`, `created_at`, `updated_at`) VALUES
(1, 5, 'berita-acara/gallery/gallery-1767529530-GGd4QoBVd2.jpg', 'gallery-1767529530-GGd4QoBVd2.jpg', 1, NULL, 0, '2026-01-04 04:25:30', '2026-01-04 04:25:30'),
(2, 5, 'berita-acara/gallery/gallery-1767529530-lbAS2fdto4.jpg', 'gallery-1767529530-lbAS2fdto4.jpg', 2, NULL, 0, '2026-01-04 04:25:30', '2026-01-04 04:25:30'),
(3, 4, 'berita-acara/gallery/gallery-1767531653-qJxSAifMCE.jpg', 'gallery-1767531653-qJxSAifMCE.jpg', 1, NULL, 0, '2026-01-04 05:00:53', '2026-01-04 05:00:53'),
(6, 9, 'berita-acara/gallery/gallery-1768121488-KgVekoBr96.jpg', 'gallery-1768121488-KgVekoBr96.jpg', 2, NULL, 0, '2026-01-11 00:51:28', '2026-01-11 00:51:28'),
(7, 4, 'berita-acara/gallery/gallery-1768121611-fGwoQ5YWE0.jpg', 'gallery-1768121611-fGwoQ5YWE0.jpg', 0, NULL, 0, '2026-01-11 00:53:31', '2026-01-11 00:53:31'),
(8, 10, 'berita-acara/gallery/gallery-1768121670-q7IN4a2YiV.jpg', 'gallery-1768121670-q7IN4a2YiV.jpg', 1, NULL, 0, '2026-01-11 00:54:30', '2026-01-11 00:54:30'),
(9, 11, 'berita-acara/gallery/gallery-1768121822-mXMYmmCn6x.jpg', 'gallery-1768121822-mXMYmmCn6x.jpg', 0, NULL, 0, '2026-01-11 00:57:02', '2026-01-11 00:57:02');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('tksc2menara-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1775652797),
('tksc2menara-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1775652797;', 1775652797),
('tksc2menara-cache-livewire-rate-limiter:2ab33b7adc5915924eb6d0f37a3d68feef9ec612', 'i:1;', 1770707433),
('tksc2menara-cache-livewire-rate-limiter:2ab33b7adc5915924eb6d0f37a3d68feef9ec612:timer', 'i:1770707433;', 1770707433),
('tksc2menara-cache-livewire-rate-limiter:4f17a116666d5488ad726a27ab8e3f686d0ed62c', 'i:1;', 1775136931),
('tksc2menara-cache-livewire-rate-limiter:4f17a116666d5488ad726a27ab8e3f686d0ed62c:timer', 'i:1775136931;', 1775136931),
('tksc2menara-cache-livewire-rate-limiter:564e7bba1426c3338ca67758d44d24f502f1c3d9', 'i:1;', 1775652012),
('tksc2menara-cache-livewire-rate-limiter:564e7bba1426c3338ca67758d44d24f502f1c3d9:timer', 'i:1775652012;', 1775652012),
('tksc2menara-cache-livewire-rate-limiter:58b0a28049f877dfb6e6b6947c0a98c6045dba47', 'i:1;', 1772604126),
('tksc2menara-cache-livewire-rate-limiter:58b0a28049f877dfb6e6b6947c0a98c6045dba47:timer', 'i:1772604126;', 1772604126),
('tksc2menara-cache-livewire-rate-limiter:5e45613e4260dd4aaeef9bb33dbc20b52cbfbe76', 'i:1;', 1771940896),
('tksc2menara-cache-livewire-rate-limiter:5e45613e4260dd4aaeef9bb33dbc20b52cbfbe76:timer', 'i:1771940896;', 1771940896),
('tksc2menara-cache-livewire-rate-limiter:67bf5ad2ceeddcc59c469be503f6d2091c12477a', 'i:1;', 1776180513),
('tksc2menara-cache-livewire-rate-limiter:67bf5ad2ceeddcc59c469be503f6d2091c12477a:timer', 'i:1776180513;', 1776180513),
('tksc2menara-cache-livewire-rate-limiter:75ba231110467490801437d12e62c2cae745f89f', 'i:1;', 1775986210),
('tksc2menara-cache-livewire-rate-limiter:75ba231110467490801437d12e62c2cae745f89f:timer', 'i:1775986210;', 1775986210),
('tksc2menara-cache-livewire-rate-limiter:7806b760454f35ce82f7bb4e8f76bf161fce4c6e', 'i:1;', 1770651026),
('tksc2menara-cache-livewire-rate-limiter:7806b760454f35ce82f7bb4e8f76bf161fce4c6e:timer', 'i:1770651026;', 1770651026),
('tksc2menara-cache-livewire-rate-limiter:89f06d7e89927c3eaa486b33981f976a413a1f60', 'i:1;', 1771135873),
('tksc2menara-cache-livewire-rate-limiter:89f06d7e89927c3eaa486b33981f976a413a1f60:timer', 'i:1771135873;', 1771135873),
('tksc2menara-cache-livewire-rate-limiter:915d06123d2611f37988bd88a114d7041dd8d35c', 'i:1;', 1775904750),
('tksc2menara-cache-livewire-rate-limiter:915d06123d2611f37988bd88a114d7041dd8d35c:timer', 'i:1775904750;', 1775904750),
('tksc2menara-cache-livewire-rate-limiter:9460b85d7c541642dd914e608e559135cffcb3fd', 'i:1;', 1776221752),
('tksc2menara-cache-livewire-rate-limiter:9460b85d7c541642dd914e608e559135cffcb3fd:timer', 'i:1776221752;', 1776221752),
('tksc2menara-cache-livewire-rate-limiter:94b8abf24a59dc713f4d5e868554c27eff35247e', 'i:1;', 1775896442),
('tksc2menara-cache-livewire-rate-limiter:94b8abf24a59dc713f4d5e868554c27eff35247e:timer', 'i:1775896442;', 1775896442),
('tksc2menara-cache-livewire-rate-limiter:cd9751eff226b9ca8e0363eb1042e7230e1105f3', 'i:1;', 1773330359),
('tksc2menara-cache-livewire-rate-limiter:cd9751eff226b9ca8e0363eb1042e7230e1105f3:timer', 'i:1773330359;', 1773330359),
('tksc2menara-cache-livewire-rate-limiter:fd8ea466a48aa7dbe279de0407c173792b0ce9cb', 'i:1;', 1774414619),
('tksc2menara-cache-livewire-rate-limiter:fd8ea466a48aa7dbe279de0407c173792b0ce9cb:timer', 'i:1774414619;', 1774414619);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_fasilitas` varchar(255) NOT NULL,
  `gambar_fasilitas` varchar(255) DEFAULT NULL,
  `tanggal_update` date DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fasilitas`
--

INSERT INTO `fasilitas` (`id`, `nama_fasilitas`, `gambar_fasilitas`, `tanggal_update`, `deskripsi`, `is_published`, `urutan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'Pojok Baca', 'fasilitas/fasilitas_1767538425_IwRE7yuIOS.png', '2026-01-12', 'pojok baca', 0, 0, '2026-01-04 06:53:45', '2026-01-11 21:46:30', NULL),
(5, 'Ruang Kepala  Sekolah', 'fasilitas/fasilitas_1768115443_D9f4aTDMF4.png', '2026-01-11', 'Ruang Kepala \nSekolah', 1, 1, '2026-01-10 23:10:43', '2026-01-10 23:10:43', NULL),
(6, 'Sentra Persiapan', 'fasilitas/fasilitas_1768115495_GsrQ2WbliL.png', '2026-01-11', 'Ruang Kelas', 1, 2, '2026-01-10 23:11:35', '2026-01-10 23:11:35', NULL),
(7, 'Senta Pembangunan', 'fasilitas/fasilitas_1768115542_WswHLJsslU.png', '2026-01-11', 'Ruang Kelas', 1, 3, '2026-01-10 23:12:22', '2026-01-10 23:12:22', NULL),
(8, 'Sentra Peran', 'fasilitas/fasilitas_1768115592_ssMTG6akmo.png', '2026-01-11', 'Ruang Kelas', 1, 4, '2026-01-10 23:13:12', '2026-01-10 23:13:12', NULL),
(9, 'Taman Bermain', 'fasilitas/fasilitas_1768115713_KuJ1nhX5q3.jpeg', '2026-01-11', 'taman bermain anak anak', 1, 5, '2026-01-10 23:15:13', '2026-01-10 23:15:13', NULL),
(10, 'Perosotan', 'fasilitas/fasilitas_1768115786_n4zKfQ8rR3.png', '2026-01-11', 'Tamana bermain', 1, 6, '2026-01-10 23:16:26', '2026-01-10 23:16:26', NULL),
(11, 'Toilet', 'fasilitas/fasilitas_1768115862_mMuUbGkAJ1.png', '2026-01-11', 'Toilet', 1, 7, '2026-01-10 23:17:42', '2026-01-10 23:17:42', NULL),
(12, 'Halaman Baris berbaris', 'fasilitas/fasilitas_1768115989_xYMIsrQOM3.jpg', '2026-01-11', 'Halaman\nBaris berbaris', 1, 8, '2026-01-10 23:19:49', '2026-01-10 23:19:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gurus`
--

CREATE TABLE `gurus` (
  `id_guru` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `nuptk` bigint(20) DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL,
  `guru_kelas` varchar(255) DEFAULT NULL,
  `kelas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('aktif','nonaktif','pensiun') NOT NULL DEFAULT 'aktif',
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `pendidikan_terakhir` varchar(255) DEFAULT NULL,
  `bidang_studi` varchar(255) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gurus`
--

INSERT INTO `gurus` (`id_guru`, `nama_lengkap`, `jabatan`, `nuptk`, `foto_path`, `guru_kelas`, `kelas_id`, `status`, `email`, `telepon`, `alamat`, `pendidikan_terakhir`, `bidang_studi`, `tanggal_mulai`, `tanggal_selesai`, `created_at`, `updated_at`) VALUES
(1, 'ANDI DARMAWATY. S.Pd. Gr', 'Kepala Sekolah', 21013917290172, 'guru/foto/guru_1767526322_h3ZBDJ3tnW.jpg', NULL, NULL, 'aktif', 'andidarmawaty9@gmail.com', '082189533334', 'BTN Timurama Blok A1/5', 'S1 Pendidikan Anak Usia Dini', NULL, '2008-03-04', NULL, '2026-01-04 03:32:02', '2026-01-04 03:32:02'),
(2, 'ANDI DAHLIA. S.Pd AUD', 'Guru', 210191725172, 'guru/foto/guru_1767531965_yao6OLv3W5.jpg', 'Kelas B1', 1, 'aktif', 'andidahlia12@gmail.com', '0852677365553', 'Jl. Menara No.8', 'S1 Pendidikan Anak Usia Dini', NULL, '2008-07-04', NULL, '2026-01-04 05:06:05', '2026-01-04 05:06:05'),
(3, 'Gr. FATMAWATI. S.Pd AUD', 'Guru', 219183528162922, 'guru/foto/guru_1767532058_rJ2s1EfNtM.jpg', 'Kelas B2', 2, 'aktif', 'fatmawaty@gmail.com', '08261897543', 'JL Andi Arsyad', 'S1 Pendidikan Anak Usia Dini', NULL, '2008-02-27', NULL, '2026-01-04 05:07:38', '2026-01-04 05:07:38');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` bigint(20) UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `created_at`, `updated_at`) VALUES
(1, 'Kelas B1', '2026-01-04 03:33:00', '2026-01-04 03:33:00'),
(2, 'Kelas B2', '2026-01-04 03:33:02', '2026-01-04 03:33:02');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_12_114024_create_tahun_ajarans_table', 1),
(5, '2025_12_12_123604_create_kelas_table', 1),
(6, '2025_12_19_151800_create_personal_access_tokens_table', 1),
(7, '2025_12_19_160117_create_regist_pendaftars_table', 1),
(8, '2025_12_22_125722_create_pembayarans_table', 1),
(9, '2025_12_22_134532_create_siswa_pendaftars_table', 1),
(10, '2025_12_22_165826_alter_penghasilan_column_type_in_siswa_pendaftars_table', 1),
(11, '2025_12_27_160939_create_rincian_pembayarans_table', 1),
(12, '2025_12_27_220955_add_jenis_pembayaran_to_pembayarans_table', 1),
(13, '2026_01_02_072640_create_siswas_table', 1),
(14, '2026_01_02_113610_create_gurus_table', 1),
(15, '2026_01_04_061638_create_berita_acaras_table', 1),
(16, '2026_01_04_075036_create_berita_acara_media_table', 1),
(18, '2026_01_04_133016_create_fasilitas_table', 2),
(19, '2026_01_04_144106_create_struktur_organisasis_table', 3),
(20, '2026_01_04_162224_create_sambutan_kepala_sekolahs_table', 4),
(21, '2026_01_05_163620_create_kontaks_table', 5),
(23, '2026_01_07_084100_add_tahun_ajaran_id_to_siswa_pendaftars_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayarans`
--

CREATE TABLE `pembayarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `regist_pendaftar_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nama_bank` varchar(255) DEFAULT NULL,
  `no_rek` text DEFAULT NULL,
  `metode_pembayaran` varchar(255) DEFAULT NULL,
  `jumlah_pembayaran` decimal(15,2) NOT NULL DEFAULT 0.00,
  `jenis_pembayaran` varchar(255) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `catatan_admin` varchar(255) DEFAULT NULL,
  `status_pembayaran` enum('menunggu','diproses','diverifikasi','ditolak') NOT NULL DEFAULT 'menunggu',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayarans`
--

INSERT INTO `pembayarans` (`id`, `regist_pendaftar_id`, `tanggal_pembayaran`, `nama`, `nama_bank`, `no_rek`, `metode_pembayaran`, `jumlah_pembayaran`, `jenis_pembayaran`, `bukti_pembayaran`, `catatan_admin`, `status_pembayaran`, `created_at`, `updated_at`) VALUES
(2, 1, '2026-01-05', 'andidarma', 'ANDI DARMAWATY', 'jC5xzyxnXlVzs5EzEiB5Uw==', 'transfer', 150000.00, 'Baju Olahraga', 'bukti-pembayaran/bukti_1767629640_WVo0pwSnCr.jpeg', NULL, 'diverifikasi', '2026-01-05 08:14:00', '2026-01-05 08:15:17'),
(8, 7, '2026-01-08', 'rosihan sulnas', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/01KEFGRWQ9GKXQGM9EFRWGWB1Z.jpg', 'Pembayaran manual telah diverifikasi oleh admin', 'diverifikasi', '2026-01-08 11:18:04', '2026-01-08 11:19:11'),
(9, 11, '2026-01-09', 'luki', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/01KEHEXJN8W33S5V8EQ470ZAHD.jpg', 'Pembayaran manual telah diverifikasi oleh admin', 'diverifikasi', '2026-01-09 05:22:52', '2026-01-09 05:25:17'),
(12, 13, '2026-01-11', 'Adit', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/01KENXVBA1WMXS4YAZSF6NESYW.jpg', 'Pembayaran manual telah diverifikasi oleh admin', 'diverifikasi', '2026-01-10 23:01:24', '2026-01-10 23:03:10'),
(14, 15, '2026-01-11', 'Brian', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/01KENYTSXN4QSKD1YRH7GE4B82.jpg', 'Pembayaran manual telah diverifikasi oleh admin', 'diverifikasi', '2026-01-10 23:19:19', '2026-01-10 23:20:21'),
(15, 16, '2026-01-11', 'Reza', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/01KENZVC71KKPVDNDCRXX7ED60.png', 'Pembayaran manual telah diverifikasi oleh admin', 'diverifikasi', '2026-01-10 23:36:57', '2026-01-10 23:38:08'),
(16, 17, '2026-01-11', 'Dafa', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/01KEP1BFCD41XD5Q2N0FP1YHX4.png', 'Pembayaran manual telah diverifikasi oleh admin', 'diverifikasi', '2026-01-10 23:52:29', '2026-01-11 00:04:24'),
(17, 18, '2026-01-11', 'Kris', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/01KEP1HHAJ2BARE4XHG8F09ACA.png', 'Pembayaran manual telah diverifikasi oleh admin', 'diverifikasi', '2026-01-11 00:05:16', '2026-01-11 00:07:43'),
(18, 19, '2026-01-11', 'Wati', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/01KEP3KGP5TX7M1JMT6DR4G65J.jpg', 'Pembayaran manual telah diverifikasi oleh admin', 'diverifikasi', '2026-01-11 00:28:12', '2026-01-11 00:43:45'),
(19, 20, '2026-01-11', 'Nisa', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/01KEP4HVJAA86S74N1ZTC9TX0E.jpg', 'Pembayaran manual telah diverifikasi oleh admin', 'diverifikasi', '2026-01-11 00:59:08', '2026-01-11 01:00:19'),
(20, 12, '2026-01-11', 'Ridwan Hakin', 'Pembayaran di Kantor', '', 'manual', 150000.00, 'Baju Olahraga', 'bukti-pembayaran/01KEPDG0JR3YYB1W3Y710YKAT7.png', 'Pembayaran manual telah diverifikasi oleh admin', 'diverifikasi', '2026-01-11 03:31:50', '2026-01-11 03:36:36'),
(21, 12, '2026-01-11', 'Ridwan Hakin', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/01KEPDH382BZDVMEPZJ050C4SS.jpg', 'Pembayaran manual telah diverifikasi oleh admin', 'diverifikasi', '2026-01-11 03:35:36', '2026-01-11 03:37:11'),
(22, 22, '2026-01-11', 'Larasati', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', NULL, NULL, 'menunggu', '2026-01-11 06:09:40', '2026-01-11 06:09:40'),
(24, 24, '2026-01-12', 'rika', 'Pembayaran di Kantor', '', 'manual', 10000.00, 'Biaya Pendaftaran', NULL, NULL, 'menunggu', '2026-01-12 05:51:35', '2026-01-12 05:51:35'),
(25, 23, '2026-01-13', 'Lulu', 'Lulu', 'jC5xzyxnXlVzs5EzEiB5Uw==', 'transfer', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/bukti_1768268887_ceJK7AeeTZ.jpg', NULL, 'diverifikasi', '2026-01-12 17:48:07', '2026-01-12 17:49:15'),
(30, 4, '2026-02-05', 'nugi', 'Mandiri', 'jC5xzyxnXlVzs5EzEiB5Uw==', 'transfer', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/bukti_1770301292_igaef0MbFu.jpg', 'Bukti pembayaran tidak jelas coba update ulang', 'ditolak', '2026-02-05 06:21:32', '2026-02-05 06:22:43'),
(32, 5, '2026-02-08', 'mala', 'BNI', 'jC5xzyxnXlVzs5EzEiB5Uw==', 'transfer', 10000.00, 'Biaya Pendaftaran', 'bukti-pembayaran/bukti_1770550536_hwqyvkVBPl.jpg', NULL, 'diverifikasi', '2026-02-08 03:35:36', '2026-02-08 03:36:29');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\RegistPendaftar', 25, 'pendaftar-auth-token', '326c24699e77beaafd5288328de10e879c8fe2cedf8a7d531aaab6200eb7635b', '[\"*\"]', '2026-02-09 04:57:10', NULL, '2026-02-09 04:45:43', '2026-02-09 04:57:10'),
(2, 'App\\Models\\RegistPendaftar', 25, 'pendaftar-auth-token', '543ec4427291347d86e4a4af426b45e6521cdd47ecfa3423fff9f2ffd907160b', '[\"*\"]', '2026-02-09 04:58:05', NULL, '2026-02-09 04:57:14', '2026-02-09 04:58:05'),
(3, 'App\\Models\\RegistPendaftar', 25, 'pendaftar-auth-token', 'e52a6b02565cfa232124fbe4c251f3f02d9a7c22c104182529676e44e4d3aae8', '[\"*\"]', '2026-02-09 05:07:39', NULL, '2026-02-09 05:07:21', '2026-02-09 05:07:39'),
(4, 'App\\Models\\RegistPendaftar', 25, 'pendaftar-auth-token', 'd7d0d6f339a68ebd66eb97f83ab0a6a82df9cf19eee08ed9db993bcec658294d', '[\"*\"]', '2026-02-09 05:08:21', NULL, '2026-02-09 05:08:04', '2026-02-09 05:08:21'),
(5, 'App\\Models\\RegistPendaftar', 25, 'pendaftar-auth-token', 'f61275c6cbd48ec9dd4eb3385572f98153fc6e555d3fbc8800debf1e1944e684', '[\"*\"]', '2026-02-09 05:15:25', NULL, '2026-02-09 05:12:46', '2026-02-09 05:15:25'),
(6, 'App\\Models\\RegistPendaftar', 25, 'pendaftar-auth-token', '71f87091ff2d83bc4c703d1469c66c0ad302feb1635fdbd10b7e38860971da14', '[\"*\"]', '2026-02-09 05:17:13', NULL, '2026-02-09 05:16:55', '2026-02-09 05:17:13'),
(7, 'App\\Models\\RegistPendaftar', 25, 'pendaftar-auth-token', 'c90984ac5637980c18c34e60a87ed641e9fd1d2387902f4db3b448a1a3f15b9c', '[\"*\"]', '2026-02-09 05:37:39', NULL, '2026-02-09 05:24:49', '2026-02-09 05:37:39'),
(8, 'App\\Models\\RegistPendaftar', 25, 'pendaftar-auth-token', 'a9bc92589d68d02c69fff30eaac81a5dd455c06357b2755e378adbe81fc5a946', '[\"*\"]', '2026-02-09 05:40:42', NULL, '2026-02-09 05:40:12', '2026-02-09 05:40:42'),
(9, 'App\\Models\\RegistPendaftar', 25, 'pendaftar-auth-token', '3df2c15a4828b42b55c6a219a2b8740fc4fbe836906736089fd3eabc693f594e', '[\"*\"]', NULL, NULL, '2026-02-09 05:43:49', '2026-02-09 05:43:49'),
(10, 'App\\Models\\RegistPendaftar', 25, 'pendaftar-auth-token', '14e39bc0d8c8e16666ad501bdbb981af9491eb99767f32db692bf5d1f983a65e', '[\"*\"]', '2026-02-09 05:45:30', NULL, '2026-02-09 05:44:02', '2026-02-09 05:45:30'),
(11, 'App\\Models\\RegistPendaftar', 25, 'pendaftar-auth-token', '8dbaa8ee0b475e3d08c3e3de6baf850d9af39355ec81a950924a1f806e871538', '[\"*\"]', '2026-02-09 05:49:08', NULL, '2026-02-09 05:48:42', '2026-02-09 05:49:08');

-- --------------------------------------------------------

--
-- Table structure for table `regist_pendaftars`
--

CREATE TABLE `regist_pendaftars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `encrypted_password` text NOT NULL,
  `encrypted_data` text DEFAULT NULL,
  `registration_ip` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `regist_pendaftars`
--

INSERT INTO `regist_pendaftars` (`id`, `username`, `email`, `password`, `encrypted_password`, `encrypted_data`, `registration_ip`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 'andidarma', 'andidarmawaty93@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1BiY+T+xJ31mQQ6um/vCPcxzfxuVFlzrZbvWLcS0kSfA5j6/cRThw57wZBDdvgN61UVeO2zhxo3dnyKGVUFQW122mTPYdTUbNXgXSTZAnOJfu9p1oGIC1dDsqNRc6AezSWTSN0tmV5c/+PgOs3EY/klngqOlYa51zgstkhOrwnilJL05r79PiNcbMF9AxVxfxSxh8LYv3mblId2O5X4JXFctGDyjiqd0obGIqw1O9UEyyfU8g+Da8uYwDUPNhrjMjkN92cRdbCEWjJEUSqrOW0pn9Q+wRPWyX/vPl1FcTo45uT5YxDkCtch3rjkI5Z9dSA5otgGkFXA+Ps/Hyd3CP9WIq+ng/et3Y/JIOsEV/WI4/pEjBxuqWwes8hEXhnyG7rDo1xm2bk29Skw0be0DNTKBjWi+L8Hg+m9larPHUoL66F+s5PTKRGQAybQ3nszkxA==', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-04 02:59:54', '2026-01-04 02:59:54'),
(2, 'aldi', 'aldialfatih016@gmail.com', 'scxL6aW1kGr/oiOP3m+3pw==', 'scxL6aW1kGr/oiOP3m+3pw==', '/neVMk4Q0vtX9RQ1VzOp1BiY+T+xJ31mQQ6um/vCPcxzfxuVFlzrZbvWLcS0kSfA5j6/cRThw57wZBDdvgN61UVeO2zhxo3dnyKGVUFQW122mTPYdTUbNXgXSTZAnOJfu9p1oGIC1dDsqNRc6AezSWTSN0tmV5c/+PgOs3EY/klngqOlYa51zgstkhOrwnilJL05r79PiNcbMF9AxVxfxSxh8LYv3mblId2O5X4JXFctGDyjiqd0obGIqw1O9UEy+WuPIfS6Io2qftAym0O9HhTwAC9Nahe8Cd8r15jcgBjsH09O9jQ3J43NQPjGQyVsDn7yGZJEO7xJj9jq9CY9fj3KmiQvE63rUTdeCNI3F3lZw7ZLaAw0JeYuSt2yvwjclInvW/MDd99xxf4iSiCh2C1+ToW7DSDyOovopeBlQNDAMSUmEQKa8KvB57mGZEGSy1V0JmAb7cFfeOpTjBMimg==', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-06 04:19:20', '2026-01-06 04:19:20'),
(3, 'lala', 'itawalicus@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1BiY+T+xJ31mQQ6um/vCPcxzfxuVFlzrZbvWLcS0kSfA5j6/cRThw57wZBDdvgN61UVeO2zhxo3dnyKGVUFQW122mTPYdTUbNXgXSTZAnOJfu9p1oGIC1dDsqNRc6AezSWTSN0tmV5c/+PgOs3EY/klngqOlYa51zgstkhOrwnilJL05r79PiNcbMF9AxVxfxSxh8LYv3mblId2O5X4JXFctGDyjiqd0obGIqw1O9UEyaA6dYkygsAMYy2HJZnFFET25QNg8q496Gt8gPnz49U+5vaSntq+2HVVNj2AjfxbOSHkg9BGASYOQVUMmr+PvHNZs6dgZ04ZUclpP/hP/QQCjE9ct9z1MU1/q2WWXDtKEyfDiRe8omv7UDLH+W8GloxKObs1Kbyf/dPSHHxXHeWNB3TrBIHjXeArxp1zsHxyPc07N2BQB+l3oUKS9kqJqyA==', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-07 00:49:29', '2026-01-07 00:49:29'),
(4, 'nugi', 'm.nugiramadhan@gmail.com', 'P/NiDUKeZgyQPlXf57xnoA==', 'P/NiDUKeZgyQPlXf57xnoA==', '/neVMk4Q0vtX9RQ1VzOp1BiY+T+xJ31mQQ6um/vCPcxzfxuVFlzrZbvWLcS0kSfA5j6/cRThw57wZBDdvgN61UVeO2zhxo3dnyKGVUFQW122mTPYdTUbNXgXSTZAnOJfu9p1oGIC1dDsqNRc6AezSWTSN0tmV5c/+PgOs3EY/klngqOlYa51zgstkhOrwnilJL05r79PiNcbMF9AxVxfxSxh8LYv3mblId2O5X4JXFctGDyjiqd0obGIqw1O9UEyDcdpKIo5uDmlj74c/haf44Vw09QVJmOdxj1UgT/ukJ8/IeKpy9CB01XuUae6l5CCM8Gq48aK+6LSokFUDWaiaUioldDPFukGXASrRbLuZaJ+ALEbfbkGxn7HSpGI/T//Jp7PvH3JyMGYZZB6YaS4BTk4hPpq8cGewj3M61FU5LfNYt/xFmCFlYgPj0IgSProM3pVs1OS9dEAD/T83a3HYA==', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-07 07:15:08', '2026-01-07 07:15:08'),
(5, 'mala', 'nirmalamala1311@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1BiY+T+xJ31mQQ6um/vCPcxzfxuVFlzrZbvWLcS0kSfA5j6/cRThw57wZBDdvgN61UVeO2zhxo3dnyKGVUFQW122mTPYdTUbNXgXSTZAnOJfu9p1oGIC1dDsqNRc6AezSWTSN0tmV5c/+PgOs3EY/klngqOlYa51zgstkhOrwnilJL05r79PiNcbMF9AxVxfxSxh8LYv3mblId2O5X4JXFctGDyjiqd0obGIqw1O9UEyBQSrVFrBrMRxHDGneax3doh5wfUs9rd7pkHR1Q1U49Qq696f0VA0jAeXiOQZpRfVhUpx8VI2Bwr7HrqEBr+7mh6d0UVOrLR4SQ4RsQtn1gWC/qsX7fdaoZ7gfhuFy4kmZ6znmZyTnePC7MCvUQyCKa5zjxc9dhlLtI/NnakZAoTnu1d9O1YPdDgC2ylpx0KeiJ5T5FuE3FLv1O1yXlpBlA==', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 04:08:06', '2026-01-08 04:08:06'),
(6, 'sucilawati', 'sucilawati@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1BiY+T+xJ31mQQ6um/vCPcxzfxuVFlzrZbvWLcS0kSfA5j6/cRThw57wZBDdvgN61UVeO2zhxo3dnyKGVUFQW122mTPYdTUbNXgXSTZAnOJfu9p1oGIC1dDsqNRc6AezSWTSN0tmV5c/+PgOs3EY/klngqOlYa51zgstkhOrwnilJL05r79PiNcbMF9AxVxfxSxh8LYv3mblId2O5X4JXFctGDyjiqd0obGIqw1O9UEyXr4ht3OoQYbq7LKF0ja2U7keVxhFzBmEQhL38DDgkJOpgXKmdahL6dzpeHOGwq0I61C0Q/aZjaex4eKCBFoJ0vutO4C4+NpLSlY7ROkyTEA5uCgUI09NNzp2YAHpqbGiLLDJ2VEabOIhrG/3UI08R+/BH/UVcACi5ZcMd489Fz/p2bx9QX7oYUFlMqXHMGN5NnIIP+6aD5/bRyck5KqUug==', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 07:52:00', '2026-01-08 07:52:00'),
(7, 'rosihan sulnas', 'nirmalasarirodito28@gmail.con', 'GanTBrTOIAN4QTC20MltjA==', 'GanTBrTOIAN4QTC20MltjA==', '/neVMk4Q0vtX9RQ1VzOp1BiY+T+xJ31mQQ6um/vCPcxzfxuVFlzrZbvWLcS0kSfA5j6/cRThw57wZBDdvgN61cLwdqeW8SepawTRVfDMmecXg5o2zd+Hgucd7mFKu8AvPgWng6dH35TWp6TqW9Cb3eCOStk7Lbe97Vj2usQJyB0hSYBBnY1a0l0ftX9umsSeDz5tdmqSISH/CiFJVk1eAsLjgG9pPOWn6XxxKJ96GLKqJfV7dtEwIK7365SqohyYE6MMublfKD6+RGgCeW8d4/5a/aslBGU0vI5EWYYMRoekwckig2FCoJTqAs6OaHE+q+jV6SdAU84b56QUqAGy6y4gMvir2PEbEcalQorb0ZpjdIkv5MLUUiBXY63DeoCjk4lFZIUMmpZZ7IK6eIarpLOW4pdsr1k7PwReN+ZVv4XWKrmIayAuqMy1LyOv2T+vBouuMEmoooWuX4M4TRAOR7lwDUy2irPnRLn2voUBFRN3k8sV2O+6mzF8aV+IBgtQ3q2AYv3ryLpsEwVyAwJLmA==', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', '2026-01-08 10:59:57', '2026-01-08 10:59:57'),
(8, 'Qimal', 'tes@gmail.com', 'lo+PAWy46m2FAb9zIsvKyA==', 'lo+PAWy46m2FAb9zIsvKyA==', '/neVMk4Q0vtX9RQ1VzOp1BiY+T+xJ31mQQ6um/vCPcxzfxuVFlzrZbvWLcS0kSfA5j6/cRThw57wZBDdvgN61UVeO2zhxo3dnyKGVUFQW122mTPYdTUbNXgXSTZAnOJfu9p1oGIC1dDsqNRc6AezSWTSN0tmV5c/+PgOs3EY/klngqOlYa51zgstkhOrwnilJL05r79PiNcbMF9AxVxfxSxh8LYv3mblId2O5X4JXFctGDyjiqd0obGIqw1O9UEyYaad8fgbqLloSWvBumgEjFM70IYcjt8MvthMwKClgi+3pyI+XtPQwFPhTrkLSazBorFQYoeGbHxTPQiVOTq15AWUCW+VvUTnzWHR/CE8LHk42L4fvmoa0g7vIJrIM+jahBHxTzlQRON/aphvYSbzG/4aQ18QKXWF1ht49xxyyQe5dVtOAyj8aS1Ls9X6IFx9CI37zhO4TXctuDruSKLgVA==', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-08 11:11:31', '2026-01-08 11:11:31'),
(9, 'Rosmiati', 'kasmiabahar568@gmail.com', '+TMPDrEb85RdnH82HB9eKw==', '+TMPDrEb85RdnH82HB9eKw==', '/neVMk4Q0vtX9RQ1VzOp1BiY+T+xJ31mQQ6um/vCPcxzfxuVFlzrZbvWLcS0kSfA5j6/cRThw57wZBDdvgN61Tif79Wx9fNcsCbyHb2o6y8B7KGO7m1H5170KYHsffESaRhnQad8hOtTw8dEPkVf9+R41xzAlZWkR0Y1brS2NSw2wbwC3lzzRQhU2RtBkmhpfLmrnK+ARfD5VRRfHw5Gp2cHwAAok08+jkdJrugGs7J1++oTT4E344QJ9krK/ydW4GpV+yN20QbJO/ZeP8POk9xT48TOZFWwK0gKrZxCNn//U2yMlXNtkPNGl7f1JJqPw6AJB4/SgAupX6uabDdzPDt5ZAzushIdgLHYOTPuCvvFZDnEee/1maFJyV3S+JVJsQo/s/ntaCoX423CW8SuMgwrs5XEfcmREXfWY99bf96dpE8lt7j9Qelm4n37Z0amt7FQTyKptvoExjM9I50khA==', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2026-01-08 18:04:05', '2026-01-08 18:04:05'),
(10, 'Afrillia Mandiangan', 'april12@gmail.com', 'immEbN2DsJaXGD7dooEtlw==', 'immEbN2DsJaXGD7dooEtlw==', '/neVMk4Q0vtX9RQ1VzOp1BiY+T+xJ31mQQ6um/vCPcxzfxuVFlzrZbvWLcS0kSfA5j6/cRThw57wZBDdvgN61cLwdqeW8SepawTRVfDMmeeNK5O+hz340S9N7PK6Vs6Elxo7PuXloRi6xLqfIIkStjAYheHuY2AI3GC5KV4nJL7NPzAXEfa7g4J9H2yBu5n2N5f2YaHGmN/NpPjvvppagDACJfLE8m5uTzvrtQmgztWzcy0KEjD9OiiZrTwgl49nkY5qBnzxUo9GDGZmDHtx2mgeo4PMqjwQ2jhPoU6MlODLMxSYlmNm87oIz8gJwYkN4op4fHh8mBIljCFdKPvWCvoEZyrUQAkIlnxOqgK2+bHDsq5be+EoBvEIEcrUH1vb0C+DdjfOZqgXX6NvNUej1NTrcuTa0S1P1DAvFsHmk8+VvKJqDreBwpQ74irfEUm1kFY5c1n+mtdGaXOZZxy6+kin9vt4GTa9usBuLdVKWBIPbKh/ghZMvyWc+6AsrG1kxEOM6k7Ho++V8TFzqdWNaw==', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1', '2026-01-08 18:05:07', '2026-01-08 18:05:07'),
(11, 'luki', 'andirahmat@gmail.com', 'immEbN2DsJaXGD7dooEtlw==', 'immEbN2DsJaXGD7dooEtlw==', '/neVMk4Q0vtX9RQ1VzOp1GXIP7zHzVw+or8N0Vx2VWYaHTSOuox6Eh4v7VFtXX6OMPb92v4iaIurvp93/oXRilmcyKdnz/6RFLE02DlTLtwH3hXHZ0Ju/lVII4EOXYM1d4j7eYQaDItzyvtHjHwKT1bpsymvNFeOwctMHeSobex99Fk+p7MP5K5hLxrynlIs0vACU9cxRgPzuo04idwPzgZ+UUoyCgZkJ+bUx7DnFZDdUowLqgYLASISkBNuiDyXiEMq5iikVGsgETqdKS65RoXG+xR3TdM/ZoOII9L+CMYY331Q1h8E2pgCDc/oJcqfVTPvot3Pce0KdoXLYzFdlox0Jkgpaf9opvxaQbxzD6Juh/FVM7W8K5VzlVI72vjng7nguR7wiqXWpZNBvB1IAcXyrU1Bli+AEB1eWBcajkQYV8Xgw6Haopomixks6Ym5mXgmKW9gZYPlvAacJNoI5CcMbRosUQ8aCyHik6JQg86reR/gjD3Q3R4CoOlc3BxK', '180.254.198.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2026-01-09 05:02:38', '2026-01-09 05:02:38'),
(12, 'Ridwan Hakin', 'ridwanhakim12@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1GXIP7zHzVw+or8N0Vx2VWalnaupNbA2CiyQR+NjopL83DS6edMhHq4ljstT7czYG3X1H1Lipug5SMUGieluGitjsRnPMpqMSqtoc22EXmkeoOTxd3hOktv0Bswhk2YgcUKeObqMWhSlJ+xos6eMSa+FouYsbkNLy9Zm8yo34EnOiGkYp+dj/MJWjF+wunUZlniPsWO3/7w75ZAZxiZ5dJRQeF69/X0vNmqWk6NNvvYl1ivIauj41gEia7RH4nnhst2nulNA8pz6F78ccDKsPQitzKwD9OKxZ1SVGchLL4BuBW4nqaO1q1xKhBG1F9ibvh+WKJQS9IZYHrm/lR2kGv92ksBpI6hlZhBwo/niWjzzVWenfC9OF6IzDWYUc0JteCgoe4COWzeXOCSzVHNe+EkRjnQvADVKdMzLWuJtS2/5yQ7HRc/OFiyRvgjDd36XOQ==', '180.253.112.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 21:52:47', '2026-01-10 21:52:47'),
(13, 'Adit', 'dummy@gmail.com', '0PrlUXYaScz3LlJUUb6f/A==', '0PrlUXYaScz3LlJUUb6f/A==', '/neVMk4Q0vtX9RQ1VzOp1O9dtfLUoxt9rPMI8WpEDkpRiUrbadfE2XyfpArQfwpFvZRM0sF+yi86ZrExaVhId53AYvj4g89Z15uab+XAxFmJ8/HJ+BTsVMTJdTiesIzzBpqbmxx6BaL0kDe+Hs36opvZKU1xq+G6zCIvmytXjP2iEYLyGw1sqS5naDuDriwErs88CkNt61hDFjbfx0oOfWdHLO/P+sAthIjNtLihRkU14prPBlizCZ2GK2c1sQN4BLGO8mKXEqUd4wUAEKHYxaDQ9tH0OS4sEpFWSY8QOwnGwGZQO7tNJAH3pb8EsevVTp7a85xAZBn38XPlO/jVDy69WrbwRjf5oFvo3reSG/gEbIBXgTwPwVOt9fU8OEvxbADpzs5M818+XbFxB4NI+C//WakUl2aTJLEsZ227sSOj64h62oskfZcwlyxdZWua7aK/aHP5cecUBpkv8OWLjA==', '103.136.57.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 22:25:03', '2026-01-10 22:25:03'),
(14, 'zamsul', 'zamsul12@gmai.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1GXIP7zHzVw+or8N0Vx2VWalnaupNbA2CiyQR+NjopL83DS6edMhHq4ljstT7czYG3X1H1Lipug5SMUGieluGitjsRnPMpqMSqtoc22EXmkeoOTxd3hOktv0Bswhk2YgcUKeObqMWhSlJ+xos6eMSa+FouYsbkNLy9Zm8yo34EnOiGkYp+dj/MJWjF+wunUZlniPsWO3/7w75ZAZxiZ5dJRQeF69/X0vNmqWk6NNvvYlZKHJA9CvahrdkWe6YBnk1fW3W1USmaUHefXD80BNN4pnmY3HgnwIxq1ssZ/BykgtQB+kDksBiP0IDHz74MCQwnXrjvFtjzFpVP3oZXieA4o9WpUA04kLK97fu0a+7VrEOgpZxKvMEnTaPdBG/ZJbERetAzpDE+m0VT47zm9Bl7V4aG9zFR6DQB/YUL1U/H4nuqS6gQmI3JrgI6XC3NfHcQ==', '180.253.112.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 22:46:57', '2026-01-10 22:46:57'),
(15, 'Brian', 'dummy2@gmail.com', 'Zwu1qBFovuixFufZz0eVSg==', 'Zwu1qBFovuixFufZz0eVSg==', '/neVMk4Q0vtX9RQ1VzOp1O9dtfLUoxt9rPMI8WpEDkpRiUrbadfE2XyfpArQfwpFvZRM0sF+yi86ZrExaVhId53AYvj4g89Z15uab+XAxFmJ8/HJ+BTsVMTJdTiesIzzBpqbmxx6BaL0kDe+Hs36opvZKU1xq+G6zCIvmytXjP2iEYLyGw1sqS5naDuDriwErs88CkNt61hDFjbfx0oOfWdHLO/P+sAthIjNtLihRkU14prPBlizCZ2GK2c1sQN4TW7ATNix/t6gY9jpthLEHGRaolFiOOKQ32haBNc0fHm57/qaO37CWCaeOHMk6+kLjf5qqhfN34NikhZeUCfK32O+Fx3al+QucJdSvQrqgniSju6sMawvd6ugq16gRahP41t+0jn/dSJnbG5uhEeDp8BCLLky0bcQ7t+v+kqB+o+Iy5YabwgR3FArI7B+IzdArfXO0U3vNQ5/89nNNnXEyg==', '103.136.57.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 23:04:20', '2026-01-10 23:04:20'),
(16, 'Reza', 'dummy3@gmail.com', 'vEI6UPADD1ddDIm44tvk3g==', 'vEI6UPADD1ddDIm44tvk3g==', '/neVMk4Q0vtX9RQ1VzOp1O9dtfLUoxt9rPMI8WpEDkpRiUrbadfE2XyfpArQfwpFvZRM0sF+yi86ZrExaVhId53AYvj4g89Z15uab+XAxFmJ8/HJ+BTsVMTJdTiesIzzBpqbmxx6BaL0kDe+Hs36opvZKU1xq+G6zCIvmytXjP2iEYLyGw1sqS5naDuDriwErs88CkNt61hDFjbfx0oOfWdHLO/P+sAthIjNtLihRkU14prPBlizCZ2GK2c1sQN4+fuJSueQyXwBr40ettrWSzirOxoEjMrb0BPwSg4/WYrWijDK1ggqsh6jHFLzqjxZ9cutkhRFN5iYcS0+WeY20NuuiXIygHMXkqo3HVKXG6tVDE2mx7HRBZEa3t+8Gw08ZC5K1AfvnQZEvv547yNA8royXBLwNFvFXbAXjOYtpG/Nm7yvkzrcJI8l4l6SPTOAqDk7vboaRONYl3MEEnzHyQ==', '103.136.57.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 23:18:46', '2026-01-10 23:18:46'),
(17, 'Dafa', 'dummy4@gmail.com', '1hvwc5BWSpwGOUUgHtBSRA==', '1hvwc5BWSpwGOUUgHtBSRA==', '/neVMk4Q0vtX9RQ1VzOp1O9dtfLUoxt9rPMI8WpEDkpRiUrbadfE2XyfpArQfwpFvZRM0sF+yi86ZrExaVhId53AYvj4g89Z15uab+XAxFmJ8/HJ+BTsVMTJdTiesIzzBpqbmxx6BaL0kDe+Hs36opvZKU1xq+G6zCIvmytXjP2iEYLyGw1sqS5naDuDriwErs88CkNt61hDFjbfx0oOfWdHLO/P+sAthIjNtLihRkU14prPBlizCZ2GK2c1sQN4HjhmdwBxI1pc9++v6FBJxvsqT4jd/cVH2vOaGoRkCjDG/wP99ExUDMhiplRI0c3Lvj0AUuh3eDT5OdQmkWAhZXUadqamayr++/bTQTNzWJtbkcA5cLOY94gYyovMHFu9zT+aG1EK/H4F4BxRODKuehyPC/RqE7HQxEcgW3L8v5CEK3XPOsRg45EV9aOBTFM36UZg4XH9DFlizFuv4FL8dA==', '103.136.57.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 23:38:13', '2026-01-10 23:38:13'),
(18, 'Kris', 'dummy5@gmail.com', 'GEhM3c3rz2Kci07oNtmwwA==', 'GEhM3c3rz2Kci07oNtmwwA==', '/neVMk4Q0vtX9RQ1VzOp1O9dtfLUoxt9rPMI8WpEDkpRiUrbadfE2XyfpArQfwpFvZRM0sF+yi86ZrExaVhId53AYvj4g89Z15uab+XAxFmJ8/HJ+BTsVMTJdTiesIzzBpqbmxx6BaL0kDe+Hs36opvZKU1xq+G6zCIvmytXjP2iEYLyGw1sqS5naDuDriwErs88CkNt61hDFjbfx0oOfWdHLO/P+sAthIjNtLihRkU14prPBlizCZ2GK2c1sQN4YAY0jUScgDU+khXNJC6GOmeEgP2Np2UaEPKEfg3MIvm53g77DW4ssF8Kg/WPVLYj5DHUSLQrtkPNc8XmceXTmZOE/g5hXJsaxcwPhNk5t/NQkf6pG2bQqGMp513VWyYYSik2J319m0mIbegQiC4+DuXq5n4VsP4uSAItNkF2QNDg7h83DNsdtBWCVl/W/b9RqxfnAmUFxCsTsWf0Yb9DyA==', '103.136.57.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 23:49:39', '2026-01-10 23:49:39'),
(19, 'Wati', 'dummy6@gmail.com', 'l6n+Ks2TVgKdb3fBIPGwCg==', 'l6n+Ks2TVgKdb3fBIPGwCg==', '/neVMk4Q0vtX9RQ1VzOp1O9dtfLUoxt9rPMI8WpEDkpRiUrbadfE2XyfpArQfwpFvZRM0sF+yi86ZrExaVhId53AYvj4g89Z15uab+XAxFmJ8/HJ+BTsVMTJdTiesIzzBpqbmxx6BaL0kDe+Hs36opvZKU1xq+G6zCIvmytXjP2iEYLyGw1sqS5naDuDriwErs88CkNt61hDFjbfx0oOfWdHLO/P+sAthIjNtLihRkU14prPBlizCZ2GK2c1sQN4p2B67XGVgG9bMHyL5noSMvCbnFa+Qv2FqlyuliwbHlU+lKbecKnSmaQgKwvEwy3cjcKEDfYs5lN4QhgKSDk4cfaIAwloEzQFycoEcAZvUEdXHR50RDXW79SMXBsEbOdd3mw6TCGwAKlqFY9RgWFfKn1RPXjbKCMVSd4+c1VYdTO1P8qaXrqqp7OTPJULmkT7KJ6oC2DOWlvy5Z3NJmefOQ==', '103.136.57.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-11 00:10:57', '2026-01-11 00:10:57'),
(20, 'Nisa', 'dummy7@gmail.com', 'swaSaGwFORUPyyE06Bnn2g==', 'swaSaGwFORUPyyE06Bnn2g==', '/neVMk4Q0vtX9RQ1VzOp1O9dtfLUoxt9rPMI8WpEDkpRiUrbadfE2XyfpArQfwpFvZRM0sF+yi86ZrExaVhId53AYvj4g89Z15uab+XAxFmJ8/HJ+BTsVMTJdTiesIzzBpqbmxx6BaL0kDe+Hs36opvZKU1xq+G6zCIvmytXjP2iEYLyGw1sqS5naDuDriwErs88CkNt61hDFjbfx0oOfWdHLO/P+sAthIjNtLihRkU14prPBlizCZ2GK2c1sQN45dIYLzstxOIa1QmweXIqufAn6sVAQvde8NleWjbtB91rZKkICXBwL/6ErACDpxprlzcvUdtmvs3UZYPPY9YAHIKITe+ng5M4ybp1AZN3TwNN9reFhJnuOu/R7w/l2TjuG2LF8/cDtn+vwy1NClANRWS0oanNiT4yciZB2kkSNQznrzla9lOohXwcW6Miac64jDwhG5zYdkMZTlhQJIj8Tg==', '103.136.57.172', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-11 00:23:57', '2026-01-11 00:23:57'),
(21, 'Reni', 'renilawaty@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1GXIP7zHzVw+or8N0Vx2VWalnaupNbA2CiyQR+NjopL83DS6edMhHq4ljstT7czYG3X1H1Lipug5SMUGieluGitjsRnPMpqMSqtoc22EXmkeoOTxd3hOktv0Bswhk2YgcUKeObqMWhSlJ+xos6eMSa+FouYsbkNLy9Zm8yo34EnOiGkYp+dj/MJWjF+wunUZlniPsWO3/7w75ZAZxiZ5dJRQeF69/X0vNmqWk6NNvvYlsGtHozboxTgi94uMzsNwSDZtxvH5VXMBQGxuztqUyC0B2k+OR0f5d3N3NIaagyP5tqaoBi9nYqfTLk9sBKMQ40F/XMPEASwSnodiuGgS4KthhVFAQ2mAg1uPg3BHH8CoOxq/fqjfqObyvaBKsrHvcqM0h0ft9gZGImKbpyYpkw80NL5nUFhlVzkLir7xccLk74EvbrrZIEosQoo6xLxZaw==', '180.253.112.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-11 02:27:27', '2026-01-11 02:27:27'),
(22, 'Larasati', 'lala12@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1GXIP7zHzVw+or8N0Vx2VWalnaupNbA2CiyQR+NjopL83DS6edMhHq4ljstT7czYGxasYvPCdkgOhY+Y4tOP2Xy1kP59IXBvhCdxcMXRYlavFL8BNM6E0+l+bRD3wflKL4e/sSszUtjWb3T+c2o6x9fdB9Uda0zo2ke376AmlGR4SarQxDqF8fZV1o4BEkjICiRkA3k0fm8/AM1ueC+wdvpJ7L6c86fJT72MBdbHoVZB7Sjuv0uPWxqaXfBUWadrT1sYaVyGeFnZkcUjoVilHKIVdOolvYtTXEwf3PerD/lTsmMg1cXNLS+5C6p+5jaOHWbb9ne7ElHGLACNjANgm5DMVfBnfg5Tk5xmNy3usdR9LCSktbUZk0xYjsGtPtXgcTWYeqbbhJFuiQAFoo9jUaPRNFPY7YB5L4QbWaNdbMXNIsKJ6qa5GL7Dr8B/GNvsGxlhIi0ig4zVzcE0v9zqAZqHSi81c9Z1PUueU+z4RtZzT57w73x8kNDQrV/Qk+sGXA==', '180.253.112.239', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2026-01-11 06:03:49', '2026-01-11 06:03:49'),
(23, 'Lulu', 'lulu12@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1H6fa+SoZTeC4V7yG69WLAOiuSM6iySYOTdUH5lTvmzkqaDW/JdYkP8j3bLN2fDaBRClLWPl+HrbwefQtInO5rPgANHQZ0lcSJ4f0k1Wcp1MfSr8wis2E91vY0ZM6pJqVV6VehRmIx2z3TGLM5qfJonmWdu4acr7K6dKJNXYHxFyChiAICSMJeRTsyqXE9K8tRDyWbwToxZr9F7bqmRA0je60Fs+y6cw26f0lvnlocrGGy41Q6ldcbfoUH8In6nrJUFhyZKNn6g2iUckoFeWa9CX1TFy84MLSKoT3izG1LUDXyc1LCGIoC/hRz1rAC8a81fw0q5F49kM8IdyM+zvz+/RQ85v5jwytZsmfO7HvggATrdg86JoUeEBYJXTqVEpfNlsYL9CNPlVOYAeLdyv6WDOTdc4ETbodAnKHop1kVN10SxytQMFneXJ/1pnWfAg+w==', '114.7.163.154', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-11 21:12:41', '2026-01-11 21:12:41'),
(24, 'rika', 'rika12@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1H6fa+SoZTeC4V7yG69WLAOiuSM6iySYOTdUH5lTvmzkqaDW/JdYkP8j3bLN2fDaBRClLWPl+HrbwefQtInO5rPgANHQZ0lcSJ4f0k1Wcp1MfSr8wis2E91vY0ZM6pJqVV6VehRmIx2z3TGLM5qfJonmWdu4acr7K6dKJNXYHxFyChiAICSMJeRTsyqXE9K8tRDyWbwToxZr9F7bqmRA0je60Fs+y6cw26f0lvnlocrGPXCC6BcmSOXm8MCwhThkuNkyU+COFbK/xrtlPTgS/rbdmsE+BbeGq6YL7AmVmXVnfuDCmUKBN7pDvI+LKhDzoaMM9kCMYEvR9jqvgfQ+1l525pCYrPwXPwx5szxujxREwZqhK5xK/9MaFXVPdWzJWM9MTBCGR44fiUqxpMP3CQyheeLaX9eg7YrXfq61Di8fuXToOyHYkB2i0bsjc1w0Ng==', '114.7.163.154', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-12 01:28:25', '2026-01-12 01:28:25'),
(25, 'oca', 'oca12@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1AxbDD+RP5EeFnKu3q9kHKyFHOE6oRKTT7mlsXG/6pd1UT5UkYWp6tjoAVWXwTjbgq36xbZOzeKrJbWU8ZCaqNVUOClEMhUDfVZC2DP+oxIxQkew4wQDONiA09A9AKr0wyFn9pW12MlYYHTSV5Vz3A8xPFcpPZ0jltq6xfuSdzLotFz4ZZc1pGxKPtS6z7cVChg3/wCGXqqmULbpHnyrH6nrFeUYVOyeMBSXz+Tj4xvBYXXs/UNzbq24gJtrBsMCbvyYKQ76YhDrhI7TFtfW84lMkJGZN3jPmnFzMRRNK5h4nzqtb8zsJG5caUjwnucg1qZ6C4vmxhQc2wxShd9EzlYAx1tD7q069u5eymLAnttXxTZPF4S/4OyNujbsc90gdd+hdATUGTROEfr79epyI4ky6CdD9PsbfRhTXXfI9QFGLXcSovzmAShJlYrtKDN8bA==', '160.22.126.66', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-05 19:34:17', '2026-02-05 19:34:17'),
(26, 'busman', 'busmanpasarai0@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1GXIP7zHzVw+or8N0Vx2VWa90TS7gmrWhGS+m1GpeBbP/lPJ3x3MtFs+k+aT113Mx/TEut78hLWBdyb+LPo5ngxOSQ9d/DlF9LsXaBVBYoT/solgLbG3dvDSBJCDKgugua/Q+fQxqpXbtgImxScoZBF8+wMPywgv74o6SWR+WRw+Dw1M+jDE5/YQiPqGD9aW5Zct4smc1hbUnSi0hUQzKDH+cTIzv1kf1W+0QtGDW0Sh80B2EfJfyxkx/5nW7dwDeJsNr7P4+MlXEUcLP5ccqWijlw+Eil9r5CVJyN47Ur75lc16AHZiKPyE9Gl9hD+k4VH1gx4ry2hiZQZBcRAAPTjgiNKC0QLnAzl/PTU27Ipo1zRCU9oDVGiORmzrM5jYGSspdnuQ+x4qT+bDfWYjZD8BZiV5o/ieJybJm8p3TTUP8orK+6pqpJxWLyY/CSlChA==', '180.251.157.168', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-08 01:06:04', '2026-02-08 01:06:04'),
(27, 'sasa', 'sasa@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1D48Xgoj6iOCVaXY3598RLiE83gUg/VS7+HGDFlcfzr/GISIkXMaeGrGe4DIY74JrxmsxSxkxab4GDJRwIDhALrbPB7o4z51LMD8q6w7fO7psrjzn0TnZnGiASI1fQK04KLcpgQTZE9mPaA4t7mbZgsqmsL+3PUj8xnmYcZi5oONWEtW3Qd+JrxjiLmCCTZSHUjIXkfm+UcbAsDkOEv9mdmOdoCgMTrpSLVfDX6/02ASEuFSmR6hLQ5PHlYTeSaPx4azxrmfKx6fzGCUuqJOzz0AX6gLB4kUChB4g80qZnJvR0pDlld5ab5MwD2vBjEqgWZHBZ59KZsGnh0diaT3KqkRT+fwLV7QFXwycx2GCFK9pP5DRZce1sF/nJ/IR27wqbVVmU38n8fTj1q7lHgdQ2arhOdv4SIrdjti+phg16mzxrrxDFZXhKArFalbEp3FocRCWE9Gd+vebT6dhU9TH5sbMxk/5GQI++5McmpbzGzt', '2001:448a:70af:137c:7d3f:7b33:166d:d48e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 04:54:05', '2026-04-08 04:54:05'),
(28, 'nuge', 'nuge12@gmail.com', 'gmMEOVL/u6MvxpXzoX9VKw==', 'gmMEOVL/u6MvxpXzoX9VKw==', '/neVMk4Q0vtX9RQ1VzOp1H6fa+SoZTeC4V7yG69WLAMnxE0w7txeoUT5M6qImfuSX66hSphlROnHosx005uFU7KmHXvUwWqbadQ6AyfcaJ6DjJ1qQbm8J2cW0vfPL0h0IOfjI7xFSb+YZCls1nPv+6riCw4gJ3ENJEDXoLZGJLZhf80IK4kdSJTDjGd8GoEaGvj5aShRcFELApxQdHdbPWw8rPO2ZDsYjOnbG4QsCs7kOei3HbNz0Yp3LwjP4f3RqmaVp1FhWsLYUMEpR7s1CLWRxJqK2/982yoDSjLJtCqpGwIjCgm0Mn06GZRWtIOvxh8i3LTFbbYmtPcNZkB3kc//xN7HvINeA6Dymi72RyikeMM/esqZlR9w4F6zFxrxzlzXeVEWY4u0BkKjHNdQWM+fZgs6HuPDKCzX6pc7dXlpqtr0KCyz9RmsbC+p/chwZzOLYKsRqDJqiJTQ8SN8hg==', '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-14 19:41:26', '2026-04-14 19:41:26');

-- --------------------------------------------------------

--
-- Table structure for table `rincian_pembayarans`
--

CREATE TABLE `rincian_pembayarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_rincian_pembayaran` varchar(255) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `jenis` enum('formulir','uang_bangunan','seragam','buku','lainnya') NOT NULL DEFAULT 'lainnya',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rincian_pembayarans`
--

INSERT INTO `rincian_pembayarans` (`id`, `nama_rincian_pembayaran`, `total_harga`, `deskripsi`, `jenis`, `is_active`, `urutan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Biaya Pendaftaran', 10000.00, NULL, 'formulir', 1, 0, '2026-01-04 02:58:44', '2026-01-04 02:58:44', NULL),
(2, 'Baju Olahraga', 150000.00, NULL, 'seragam', 1, 1, '2026-01-04 02:59:13', '2026-01-04 02:59:27', NULL),
(3, 'Baju Kotak', 100000.00, NULL, 'seragam', 1, 2, '2026-01-09 04:48:20', '2026-01-09 04:48:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sambutan_kepala_sekolahs`
--

CREATE TABLE `sambutan_kepala_sekolahs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sambutan` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sambutan_kepala_sekolahs`
--

INSERT INTO `sambutan_kepala_sekolahs` (`id`, `sambutan`, `foto`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '<p>Assalamu\'alaikum warahmatullahi wabarakatuh, Selamat datang di website resmi TK SC2 Menara Parepare. Website ini kami hadirkan sebagai sarana informasi bagi orang tua dan masyarakat, serta untuk menampilkan berbagai kegiatan dan pencapaian anak-anak didik kami.</p><p>Sebagai lembaga pendidikan anak usia dini, kami berkomitmen untuk memberikan pendidikan yang seimbang antara nilai-nilai akademik, karakter, dan spiritual. Dengan pendekatan yang menyenangkan, kami berharap dapat membantu mengembangkan potensi terbaik dari setiap anak.</p><p>Kami mengucapkan terima kasih atas kepercayaan orang tua yang telah mempercayakan pendidikan anak-anaknya kepada kami. Semoga dengan kerja sama yang baik, kita dapat menciptakan generasi yang berakhlak mulia dan cerdas.</p><p>Wassalamu\'alaikum warahmatullahi wabarakatuh.</p>', 'sambutan-kepala-sekolah/kepala-sekolah.jpg', '2026-01-04 08:28:11', '2026-01-05 05:04:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('07ZIMFpT9ChUu3AFipyK7bjaDq0Wd9HmAQVtUJ39', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQmFUVFpjczV0dkFST0w2RXFGM1VIdVdkQWZKMnNBQ1ZnTHRQTXJLRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222215),
('0rpP1885s397lAlrvsTCVordz4aJkgboJp5OX1Fe', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSU5hdkk4WDdBUFlFaGZudEdCMjBUNUVZUEVNOEwzY0h5elE1bUdGQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9iZXJpdGEtYWNhcmEvbGF0ZXN0P2xpbWl0PTMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776218579),
('1iqfIRAIzGoiOFfyUxWvQf7SEmd616Rsa2Cqb72o', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicTVEMzkwVTVrbnJYTTVpaE1mankzd3pnbjJqWjlaQ0p2a05PU1hYQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776224126),
('1uatTAEtD6LHAjUuWS2w9x8AhtIOwRgGcX26ulik', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWnhtTWZtdElxQ0RkNmxDZ1JHYVFQSDlyVUhJY0Zkc2dVUjBya1VObyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776221860),
('207YkbF30HPIseDOlzRjYZ44NrncDlHBpYWZMlwO', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlQzUlVJQTRFNXJzcjkzNU5CVmFlVVZINE54V2s3ZXhSeHpwZGMzUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221221),
('2aABUCQa5FxP4z4Kh3F4GfOFHQhXSzvrA0IZgejs', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTV1dGNEYlFXbDViS29tenB6Q2pjbksyUHdxOGdKVnBud0ZUdXVHUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776222021),
('2Egr9C8nGZNqtSOXoi8xDZy7h6mx14CixWX8gmKz', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieXJ5TGc4NG1mZ25hcFNDTHVScnFuWDlnRm1wR25JWlhlbjhCMHpWTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9iZXJpdGEtYWNhcmEvbGF0ZXN0P2xpbWl0PTMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222176),
('2tS2gjWI8sH9W56CGf6QjrBOG1pelsykBbkbmXoU', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSnBSVXF2QnpwSU5ldEFVdG9SS0YxdmthZXc5QnBvSGpGbnRaazRqaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776218576),
('2uVF0zYvqZGhSCBHxQRYQHEXrVvxnACDnbcQffAN', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQXNEd3ZTRGZEVEpVZDB2Rm1pSWdQTXdjUkQwRHBBeDJORk54ekdtTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224530),
('2W9RhQ8WknBcXZSuwGd9ynhcTXWRxcCOkcqVmkqP', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRGhsc0xMTEpmNzV5SjhvVjBETEtybkU5aTAzTXRSbEFyaXRtdHRyNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221252),
('3Fi1I17ERnD78DihY4pRtvuRB68pEyfGFZVRgGUL', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieDBoaWJqVjR4ZDN6U3pJOUprNmpnR3oyRlRIUXN5bFR5SjFpMlM2TyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222023),
('4GvFOdEhmNkpwWIX1XvIxpeTt841nNol2MdhQi4A', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHhhZThYUzdSVnczSXY0TGd3SlZndnhhSjZEM1dNQkQzd2owYnFlOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224565),
('7AA5ulggicCvE7VksDHhssqf7x1R5TYcAWQUQH0D', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMEFMZVhXTk9tUllKSVoya3REVDR3ZzZ4cjdiS2dIUjhmVHFVOWFMciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221989),
('7KwggOZNC3CkmEkTyLyRpdN6Ml8GkvNhgE09bDih', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidktVSkVIUjYzdjY5ZDluWWF4c0lHVVRZQTl5VzVyaGFLTkdaaEVNaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221262),
('857mUniUTfl1rABntks53hgHYXs2Xz2ZZDmkiYPh', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSzlqSTJNNnFDVkM4U0Y5ZmI2Rmd6S3MxQ1FQSmxqR1pHOTdSTkJKdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zYW1idXRhbi9hY3RpdmUiO3M6NToicm91dGUiO3M6MTU6InNhbWJ1dGFuLmFjdGl2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221240),
('8cvct7gdLrj1GBRBo75TAjA6J4W9qrMp9Sbw0VL0', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibjJNdVhrOGI5Z1B2Q09nU0N6VnFuOTQ3dkpVcWZjYjBHbGh2UWNsMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222211),
('8H6OlQSrISB9Ht1BP2aNrU02IDXoaKC5XV3MsIw0', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicTJVVXRFTWdPUVdXelhyeVhrVnZ3amdYbTBUMWY3RUF4Yk9hNk5JNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222019),
('91jalicqKlOCCyqAyqY7FPFrUUJTpFNIHzsUVK7V', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFhOSlRkVFIxS1VVYkVKdWFqZTY1cUlaM09SSlNqVkJTNWhzQXlGUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222203),
('96qn8DOSKM3YfFgC7UM3mOdChDeWQ4RjD0ywQB4w', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidzJ6Umx1eENadXlPMkRLcllGWlo5T2NmMGhPNnBDWUcwU0NCSGNoZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222628),
('9T1w3cQNzBD1UIKorSwjAhaXXFwAjdcO2MHLKmVt', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZnV2d2ZCZk9zVlQ5TVJIdXNHSUhGbGhpNHJmcTEyMW8wTldEWFI2USI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221330),
('a0vtbPgmnFYyK1VWZ8LmRnM3m12CdBRrsgkRFRVA', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOGtnaXNSUHEwMWlETVlvT2xVd3dpYWYyQmhvVHVDQ3Flc3J2Q0ZqaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222207),
('A2FeH0OuXT6Nc0cAERxX9CBqX8J6SU2KwygxEn2B', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTU9UZnhMZ2JnUTd6c0Roc0U2bFpyZjZxZHp5Y3pSZnc2NWdEUjhGUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776221272),
('AasS88ZmTH3CjqbVx2hGGYXWUI72pqfsr9SuqoEX', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ3BTWmxjUWViSHNZcUtFeEFwdDZUWk9LUXZsY3VJM2hNOGRHOHkwVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222145),
('AGC8MCjtXrJuI2cbRqdlCfETx4VHwHcISyanDCUr', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQVNwSDFKcHVQT3JtRDlOS2duN2d6WUpoS05ZSHF5Yzg1WmpGbEJmeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zYW1idXRhbi9hY3RpdmUiO3M6NToicm91dGUiO3M6MTU6InNhbWJ1dGFuLmFjdGl2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776218583),
('AoVykdWVGk4wawQ0A8muqXtZC306rkqw66xg2CDb', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMmdvSmhNeUJKam1kczNVTnh6UUlnWWl2SmJqSEdRNE9jbTFXeURnayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776224493),
('awVXEmc4384NXUow5FqQdfr8XaNvbNvQIeGIqaiz', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZUEwWFFTbEJuWWYzT0F6OGlYQVRGcGpFUVgwVUdkVGhJZkRqdkdpVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221394),
('B4oalnisG5CFkEFEz4iFoqX7hwMGQiGlnPJ0EDOf', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicHUxd1YzM1hVU0lCRmZkMDl2YXlVaVZIRU5vcHZLS0dTRW5pc3dCbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776224524),
('bfbtKYpkQTxKrDUjYp1FX4mFtUZ7Jt6UgklDhSEw', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMkRYVGxjWDZGMDJoUjJrQ1ZJV084NmdkUG94MlpPYzRLMWRieHNKUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221328),
('bHTq356e0LTB6MyyYkQ8szOIrVH11FCt9OFvmymk', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVzdUa2oydFVXcmtOUkZTR3VUUXduRktTb0VwNnFSRVlvNHptZzRxbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221278),
('bJ0Jyz9EQDdIKHruDpfh2pQJKNrEA136ysNlPx52', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYmVURTZReEtNRFJPOXhZQVRac05Bd3ZSSUlQVDF1UERkdVI3OFkybyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221248),
('bLkH80lF8YiAzBvlqtkvKPlUByBPUPxZgKt7ZA4x', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ1ZFUWhBSnRnZlRXajBCeHNZMmdHMzIySjJCTEpkVDJ1dUYxaDB0ZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zYW1idXRhbi9hY3RpdmUiO3M6NToicm91dGUiO3M6MTU6InNhbWJ1dGFuLmFjdGl2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776218577),
('btgsmn5lWSGnKeZEu9Jq0HSrBaoSwB0REjlnAsOr', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWU5zek1XajN1Vnp2dGhFdEpCZXJpdGRhV1VnMDVZWjZMQ2pOT3o5bCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221865),
('BUsYkmzFRnCpO6ktSfR9wsYEeQIE0WuWUmnUFjLO', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHQ3UmpaUjN0YTEzYTRESHVYTTd3UENaNWUyOUtwZkJZNUd5WUlGNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222017),
('BWjWsREBh2XcD0LHdEGVBw2wsjsGDMwmwoi2i1C9', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWHlRYmNnbVNoRG1XcGNWNWhtTXVwNFduaUtEanQ4QkRBeWVtUEZyaSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776224113),
('CjI0kx2i0AQiKut1GOT8c2jh02Zi1KoYJ4ofY201', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYVJIVEw1b1gzbndkZHkyTEJINzRpb3RqYVlET3lvYzd5a2xTNU9HOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221243),
('CLD3qt917Toqbjch6VLrHLAKctOllAUnaTaRiwNf', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSFExWDhKdnMySkxzMHVYSEZrMVpQVTE3dzVXdWZxb2sxTEF4aUVCRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224509),
('clWOVDNQKNqcPR9fyFSotxgbGT27siamQbrg61v2', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZUxTREZwSDNFQkRHaGVqcTc1SUU3N0dsMnlPcU1Cek4wVHd4a3Z3SyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9iZXJpdGEtYWNhcmEvbGF0ZXN0P2xpbWl0PTMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221226),
('cow4PU259vOz8Nnq1IIAtHimLWTWHZn0tXqN4Kuu', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM0o2dk1SRGVJQWs3Y3pNeGJTM1ZiNmhGTDk0NWdQaWc2ZXdlMk44NCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224554),
('cvlR00FdcjYlR9mQtWOSS94XbPDmOAu0KESK9ncC', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieVEyN0U5TWc3SEdZdXMzbjNFQkxxYWE4Unl4TkN6RDRzVTNmVEpGVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221811),
('cYeE5J3Qj9tNudacP30wJKwiuEL9o19JtW70aRxV', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTWZpbW81VjdlYU1rSThpZFk4djJ5ZzJ1bDhyd3FzUkhibFFmdUpTZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9iZXJpdGEtYWNhcmEvbGF0ZXN0P2xpbWl0PTMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222188),
('D1nT1fixNZbvqzS7XpTa6KYJ39baTiT7QiV0WZDy', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoielRydENFb0xDY25tNkJVNXJVaWtuUUVKbEhtZzdXbzdTalJlQzJyMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221849),
('E4yOhQGZFvi13BSFTlxuy48tNhbZIKr2g3jKH3Ka', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibWF2QUZ6alpiWkR3T0ViOHcxMDE0dlhwR3pMU21aZmVtRUxXNncwRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222025),
('E81o6W1CPEXX8dKDn6WeHgD26wWyCMtGRus72vSI', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiekR6cU1IaUxORHBTNWg1WXk5QWV3TVRFamhGTzY0SWhRZG9JcDhHayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221862),
('ega5q6hQi2H9kA1YfbqgNIzxwxg13US6ASGSdC3a', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUlNNZmpuYUxvMFB4cUFDa0ZGdkNraDNvakVXN0JocVhhR1pFMGJmRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222474),
('EwMwJHoDmg1kPV9eurIiWCKXJxnP1p3UarH5YFqG', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWkJlalpFY2RNR3hQWkRQeTgweVByNk1CcTVKaEZyVThYS2VMamJ6UiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221847),
('f0wtWhSyTyGoG3VOPPzi3re2c7klldTCD40N2Ywy', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYTdDYVNSeGxickw3dm95ZzBuQ1FRTENwNXhTNTVidUI4bmY0ZE9uRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zdGF0cy9ob21lIjtzOjU6InJvdXRlIjtzOjEwOiJzdGF0cy5ob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776218588),
('FAy1vPTKuEwDN9pJi57oebmfNlrLbBnpa3fSSPOd', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWtUQzNPRVZldmpMVWNoY2ZQaVBzRmU5TnA5UmMwQlRzcnZMNGJWdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776218581),
('FCZzf3uCoGUax2ciEXEe9Q41SMUgDPi2ybCr7HsB', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNFNTbnNOVGFaQVl0bG80Q3RwSnJEOUVNVDEzUmhFVVJlajdqNFRURCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222782),
('fSr1k21QyvPaExJ75nqxoxsA3gy524VMIlYquNV3', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMm55RjlVTFlLcUhGYVVpVE5lREtqVmRGWFY0bUpNdnRSQlZqcVk5OCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224143),
('fVatlic0m9dZX26UlIdoQETeWHpqQdCXrcDCjTYe', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYVJtWndjMU4wNGt3YmVUb3NBRFRvZTlDZ3pvTEM4V3VjbEhiQ0Q2WiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221838),
('GsLlrMTihdZDbQ2lJ7EPCyPxwka4dqkZ9GWiqOvz', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNTI3STBwRFZnOUJUYTNDVVJBTVlOazZVRDZXUVByTnJXbnc4VlFRUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776224513),
('hGog28cMoGf9HqEknwcIHWZk8btVkOhsM63W68R5', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN3NaS01uRThBamwyZFdPekdSd1FDY2hKaHMwVElnMlR5VDJ3OFpxdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224528),
('hNctwNWyGbyRc0v1Hab8yjZ0vdS4ubjY5eX2VcpJ', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWg4M3FBNlFnR0h3dURJSHBjN2pKRjc2cDg2cDloc0dJUk9QSHFHYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222143),
('hvHoIcc81Ggm2G0B1wWSA0e6RQeZITXXEVQo1sWk', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicVN0RE5HdmF5VGZEZjNvcjQzVnh1YnNjTmFlQkhYMnhsS2pjaUhUMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222472),
('I2xxDwawoDgMYkG8qtdL4BuheVS7SacOVi5cvuce', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiS2F3TVMzMUJIZG1QR3VJQ3MySWRVZDJPOUNhaTdSNURRQjMwZ1RLZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224558),
('IgncKZdW91g7iPwS6DP61kfv5fYRHxSPnqYgekjR', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicmRTRHVJbWJEckhEYklhQ2FiRTZkN241Zll5Z1g2Z0J1NjlsN3VLZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222476),
('IijL9cxjZCNMSpdGP7EflUr6cfEY6e8pDwSobPFp', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNzRobXJ2T2FFMm5YVUJ4UlRSRVA2M1VlOG5VV3dHNVZoMWR0dzdBRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224522),
('IndARdiIfBmvZPEGGkDoNB9SLgDnh9VdoUfDxbdy', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVTAzZGt1dzhua0djNVFPWEZTT0VMUGlBRWRkcDlrSDhjdDRrNzhNUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221250),
('ipmXEGIVV6X1APlAzBsR98CIhYSiNYK2ii75Ioc5', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUlczWXoxNTNRNTdkV1J5bWV4NUw4d3FMWnBjMWNRUUpKZE5Kc1k3bCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224137),
('J2S0PGvtquXwlmSi4LXCnl03N9W7PwhLMQSUtLRn', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicWxsY0tTMUFFSkNrdDhBM3d4ZjI5NEFxNk9hVmlTN2E3aUxISXEweiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221871),
('j6YhbqMuiqTVoGrOejCL81Le4FzsWliwj5bk5B0M', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaE05RjBYZW1qY1VQbjBDT2NYUXA5QU92TWlqOE1LT2hvN3VJVkNPMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776224452),
('J73CvDvNPBTJU1uLY3g8ZhZazKQ9AMty4hHAN0Ky', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVU92V2hhZmYwU3pBRVBYaGdQeGpCUWhweEhyQVN5b2ZSOER3bjVIQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224562),
('jEB9I2a4XJsfWSW9761lwWJ8nUztCscPdJYg9Uye', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiemxUZzQ5QjNkNFYxTG5Zdnh3dlRoVEZvMHozNlBQZDVWVVR2b0RJbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221245),
('jh5gOm6eu18G0uSgrzMd7pky6o73P0lHPiaT0bIm', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWXkxRUNyOXd1akdVRklmcDVwWGFxVGtkRE5LT3lGdm4xN1dYN2hRdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776222014),
('Jhku9zUp4TThau6u6hS5KORbKfCGg2ianrFq0BH6', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3pSSVZFaGpCT0JnSXd1bEJVWjRMZkxFUHB2dkNpMnVQNTlFMzhUciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776221851),
('JTnPe1xvmM6LN6XBHqM2eKufHLVD5ihUM5HWbbbu', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUtyam5jV1R5N1Vkc3cxRlpsMnNBYURDMUNpdFJ0T0FSemlpdkMyeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222235),
('JzFa9oSpTFsOIu6KXiMH3c8sLIXJ3a3sPTDg1Nlz', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTXEwNHEzRnRHOVVPWjRLTUNrZkp2Sndua09FdUF3N0FTQkpmRW5pVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222237),
('KcEwD6JN5MxRCF0KLwyMwLps0cW8ThQTqW0meDYx', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTGdWMjdzckFXNFZDNDV3SXhCZFp6TFA4U0lYTVluTkQ2MDdEU0tvYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222631),
('kP0odZgeH6DFd0WE2hsjMJurVNfgdAxbaTYVOZRK', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZUNiWE5MeGd5emlENHdXZjJjTmxCTk1Kbm80SzZHVmhGbE5JTzFIOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224550),
('L2cH09yLVSTgsZ8HX4FWDb8gKWTSrIuWw2BNDuEe', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWRkU3VQZ04wSWtlRHM0c0RwTWtQUVQ3cmRib0lncjJhbUg4bUNtaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221265),
('la7y0AJhyNjcKGKRCYQFkyCsovUzm8xap3O5c9GP', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVWR0UzhJNGpHeU9SYk5uaVhDa0pMc3dMc3lLeXdnS3AzTkRBdm9URCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776224447),
('ldwSxLW7JR3lwFLKzLz6aOtalbiaoVfEzU9D2Lf9', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWERxak9KeUtjZHgxaTJwVWtuNk52c0FTRVBrSUNRa2M1d0cxWXB2ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222226),
('lf0EIF6z6xoMyYJtIULWnX0CznAQHfsh8gRNuTQW', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMFVhbXM3TGYyRnBhc0pkbWpHM3gyWkF0Z3BmQ0hjNWZmd2ZCcnE0eSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776224449),
('lglqhCEylZRaFFp7mjQ9quzFyTYXl0Z3mU990aQI', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNW1mdEw2c1NPT0w4WGdsWWd2bHI2aDRCSjE2OW9kcEc1WnYyRmd0bCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222191),
('lJ1Ifg6s8vABX4GvL0YxvU95PqZalYBc5cS5vNQW', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVI4aEtncGlqckk0R1ZsMUY0WXBWSkFCS2pSZ1VFYzNBM09xU0NVNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222787),
('Lk3QWKT0MupE0OecNWworZgZA58KZWiO4aN28HnP', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaEVqVmtuTW9Ga29BTGl5QkJHQTdJTkJrMDBHalNCUzU4U2xSV0N2NCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222205),
('lREPXu7GmmzikubW3sFtcPg4ehQ57UnIzFpaCj2j', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiakNSekJVaE9URFNtYldXS3gyWTNPektuMEg3YWFuR3Y2WU5hcTBQbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9iZXJpdGEtYWNhcmEvbGF0ZXN0P2xpbWl0PTMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776218574),
('lrr0m354XymPrqO4qN4LR4qSsmrnleXU3PahVkxq', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0xCbUlzSklGa0VyMmxxcDlrdEZ2d1JsaFZyV3Jib0ZBOElVMmJWQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776224135),
('LsvEK8vsDVEd4LFjsb4RwCdmPcUZUARkcjts0XEC', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZFVLdW9IcTJrU09HS1JNUlJieFFteUk4VVppbzBqWE1Ua2ZBTVhYOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zYW1idXRhbi9hY3RpdmUiO3M6NToicm91dGUiO3M6MTU6InNhbWJ1dGFuLmFjdGl2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222184),
('m8jiFjkvrILZPL3SsBBFjGtt9jAvwh9NPIfkJDcS', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiM3pSbjRKMGptbWtPandtVW94aDJ0V21PWEpIeUJKSlNVczR0djhDMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776224500),
('MGlEEJsqSOsrScZ5WwNlRAPpjQPBsMiqJqkNn0nx', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidGlidVo3bEYweXB1N2J0Vk5wdzZRSmZGazFYV0hWVVZiOXoyYTRQeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221867),
('mIwL5ktYXxNrwqkVbkUuoKlBCtMzzXOkaXj0j4pM', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZnZwU1RUYmxXWU9aRFBrNFAzSGJOeEtycVZjMVJqaUd5SVdXOERicyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224539),
('moU6EIbFCrLSk8B8dzsiniPhWwhoLBYJYFZSuLTG', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU2laNWl2OG41MTQwNFpJRzRZVFU0MVZVaFJBeUs1S2c5blJIOGtVWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776224532),
('MuRHEcmfzEFfLGL3cmcL7jke611SvyMljalBLrQL', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU2Z4ZERyODltakpsNkFUa0hZeDlYR2lwTlIySmg4UU1DaXF4OENOZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222231),
('MvMRZhNbx5gk2k9ViSvpsr3CRpfeiZh2L1sfyWsO', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZWcwaExpOVB1NlZ0a2NLd1pxMlFGTVhDN29jdEFKcUVWOGVBamFjMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224567),
('N9zrIvfHZuIlTRzFLFffa5CsGvboupqIWlsa5yA9', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQXBGdFoyWUFmMmlOOUY3cXVqY0hBM3c5MHRnREl3Z1Z4TnVoTFRuZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222478),
('NdqqWs0wi1VbJ0Kw3SrxHYHick6XliPGV4AR04PB', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTkI2czJVaUgwZ0pFYkVRR251RVYwUlpDc2tyR1QyWnp5cHg5YURiQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776224495),
('NFbYTbQ05IWl6toYq2f6wucGK0lY4oDUMKq1mHaO', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicDlaa0ZxR2dqc2RLZFVGcFNTSXY1RGZXajVSTld0SjNDSE83dGRGYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224556),
('NGJqWiczL42kk9WbAB84r6PDvDqXDf4y3SXmMbL7', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiY1JYVjk4NFhlamtYemYyanVyYTNHeWVXRjdZUkVyaFlEYlhOOHRRYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222213),
('NGZAEwTojo0XKuq2pQeR1k0V3coJh5YzvuIkVhey', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZEw0WkhISTgxQjF4QTczZkFRbGdweFUwTnRGd0tLMm9ZYXNmZnBEVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221280),
('nhkQUsHl6xVimlAvmdTaiYVXGhmwsKnevEw89Ud4', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicVMzWk1NMldrVzJMOXZEWmZpS1I5bW5WQWVIcjFBVnVLTUQ3ZmZqViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776221283),
('nHUqW9aFmw0Kxc1fTnb4QUaKOgLMjpdIcRxpGTDt', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicTF5cnpZZWxxT2haV0hmM2Y1d0xsWEVoZDBYeUltaGJ4TkxSMXBXayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221397),
('Nkw8OKdkU3hR12ePIGx9enJ74pJBVWZZfOCAqD0B', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSTdWVERmd1VDM1hlV0dwclJ6UnZrV1BOYU1BdHRxQjVoaEJIdEdvbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222193),
('nlMQOQ0tUCGTAsqwFWImncOcImTcGGSkBr0kCD4J', NULL, '114.79.39.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUnlGTm1mOUIwbVNsejJhMkdkMXBZT2Y1NEJEZXZqcURFSU1hMnhPbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222780),
('nMvCfCZbr5xp4VgZGmSKGUVKuX5XNg8gZ3dN1eHt', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVDdlQTQ0aXRMa2NCYU9LRVVXWTBHQ0s0aU15bjdYcnNwdExweVgzViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222197),
('nQpMNCjAE5le4K1ZpypY3036wbLFcSn86RDohakU', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiQlZCR3pHa0REbVRsdUFabEZiNko0RzVqMkFibGVkWnJ1ZW9jWUhVZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222001),
('NsyQPBVTZ7P9VJbL2xM4s0t9GZ23WFUq1YM0kYIX', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUDFPQ2VBNDJueVJBdzZTSXR6ZTlFWjBySGZLUW9zZkwwVGZwanI2OSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njc6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9iZXJpdGEtYWNhcmEvbGF0ZXN0P2xpbWl0PTMiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221219),
('nuby7auDa9Jb9n3ONXQ1AZEESXZBByCpKJqI7RwK', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYnlJQU52eHo4UlFQU1dwMG1EZ0RkOUczOXBpREp5V3c4QXlyUTExRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zYW1idXRhbi9hY3RpdmUiO3M6NToicm91dGUiO3M6MTU6InNhbWJ1dGFuLmFjdGl2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221224);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('NW8RXnB3siPBSLGRckUSMzmeZZ5IlTrc12zLkJrh', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWnVQM3VkY1c5RnRMUkM2Rzhzdk1RZE9hbFVRYWZhVmF3TVE2Nzc0RCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221855),
('NY29F7BvzPk4dbkQa4JQiB3zE3MxjlLSRgcnJX8s', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia0hEakZ1NnlOTUdiWTJnUEF0RE03cGRzeWpKMEdxWHNHR3VKalFPZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222233),
('OBPcaRTkBXndKJASQqMII90Pz2plMFueeSrTNQv8', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic05QU0tqRG0wZXo3dHhWajgzZ2RXUWtQTlFPbG5nZnh2bVBwT2hlciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776224552),
('oHblmu5qNZzqtMbV4GNs7CFJEYWCDpormVSamRZn', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSkNydmJlSFIxRVprVjU1MUNJazVMOVZvQmxaV3gxM1FSS0FhaUZKOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222199),
('P18UnuZkYFkUSsL7DTXFTbHFgridOUpItTDidNui', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoialVyMGhBSnpMakp4aUpLcjRGMHZZTGduYUNGZFEyWk9FRUJKMDBVYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222201),
('P8sxtyWeg1Vp0DdOUjbcQiXme3UfMdmdygEs3xGk', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid3VGMTJhSnFTSDA1WnlBOHBDN2RwOVlDMjFtN0M5UzFLOFpXQUhIaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221390),
('pad6Lw0IJ5OlbTKJcBTS0SmyrF5gHCzYafKyCITH', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWER4R0NVbjh3TVRKNEJkTGFpV2c2dzlQMG5YaXNWeWcwd0NvYTNGdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221808),
('pmkhWqDAOUteblU42uEOWLoeou1Qg296rsnOrAP3', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRVVlOXFPTUY1Z3hvaGd3RDJFN1RLb0gzQjR2ODIxWGpwTGExaEx3ayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221991),
('q5kRfaTGcGbhqiHasmULYF8KMDs9AXY78BSX09S6', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid2J2RWRCMDJCOEtmQWtYclRZYmxPYmkxcmJ4b21SRHZ6N1hSRWpWNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221235),
('Q8bPrdLhvCBOOMjnXs2VslesCIFwd1acZjscyS8F', 2, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVkk1MVFlcnRrTExvb2lWU0Y1aTlleWNySm9RREp1OTBBc0ZMZlNMSiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjU4OiJodHRwczovL2FkbWluLnRrc2MybWVuYXJhLmRwZG5zLm9yZy9hZG1pbi9zaXN3YS1wZW5kYWZ0YXJzIjtzOjU6InJvdXRlIjtzOjQ3OiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuc2lzd2EtcGVuZGFmdGFycy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiR4TWRWN05KV2tKc0pMU0RoenRuendlcFFxM0REb2lDbUxXejh5SThpTFpyWVJDUEVBMm5HSyI7fQ==', 1776224638),
('QBmBg4cMW0qImFmzuVysFrofHyyGrbYzmBpAw7pE', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFdpbGp3VjZWN056bFA3TjIwaGM0bGlnYnhQQW9QQlhBc3YxbVl5aSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221392),
('qc7E6A5LmjSD8opeie0LfejHOwUpthvgRxHa5WLZ', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUVQMEJ6bFo1VUtTaUpRNkRFV0VuTXJwTExuZzlKSVplRUMzWjVuNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776222222),
('QFHs0ukD1zC4F2hLSMj3a7ABSoYQLVZElXVdIGbB', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVUxacnk1S21YcHI0R3F0d3JFSW1HRUxPeHdldEhicXFVYW5XRUZjMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221260),
('qhVU5vgZTZY8auncFRn4piphKi74Hg8BOWtxL3Nh', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjA4b1FRQ09aR1d1RmpSeW1aUWNtNXc4a3B2c0NRT2dRNmZ2aHZwWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776224560),
('QNvULpXOK6rxC5to6UrJls40SDXMGoX8eBiY9s7E', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibzJzQXBCZk9jZmdzeHhzYnVVSkprWXlwUWFKMG1nNjdCNjR3Zks0TSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776224537),
('qqx0Yu7O2uCLGUY3l0tvnztSshxI4ckHVJflV30t', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWk4ZlVOeE1kMEk5NlJSTGRBUHVhYng5dVdoZlZBcG1UNG5ZaEVZbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224141),
('rdcpUHElMMdFt5dRvDkIowSAiip4cLLZEno4ivvQ', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0FteTRSY0c4YXNkclM5eVA0Z1o5ZDVyNk85TWN2V1RlV01rOU9KdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221276),
('S40kBanXfy395jGUTtzv07ih5lB0tzxTTGYP5p5C', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSDNYRFNFbUFxa0Z6blFDR3NnUEVTTTBCand5NlhLWThiSko4ZWM0ZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221869),
('SqDRnILG0XLgiPPEDpcbfgm2RQZIhe7tTrKsn0Ed', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU2xiaGlCUXBUVFc5WVZKVTgzcGY3RTBnMlhLbnFvVVFGWjlGa2JxVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224131),
('SucZBYvrRRO6hva7qZ8ZkvoyWHOTyAtKqPYliIrm', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR1VMa0w4OEFFVDU0OFZRQ3lKVkx0WGU3TEhFR1V3cDNyTFBCRWhCMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6OTQ6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2xpaGF0LWZpbGUvYXBpL3NlY3VyZS1maWxlcy9ha3RlXzE3NjgyMjEzNjVfbExYT3Z5bExmNy5qcGciO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222240),
('t9DlVjjeDWPevGCQyp2SMZycPuL2ecJnTsSszoYI', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiODZ3RDJKS0Zqdzltb21nbHZXbXVqczRGeHpYTGFsc0pQWWNoMUVlRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224133),
('tGccu0DPqSHm4ofauUM5cvaMzByna8HXXHDlDc6h', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN3UwZkpkOVM0MHh1U2tkN25rbkpVMm52RjlqMXU2Yms5a3VMVVpqdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221853),
('tgmkcYNq2N2MwIG0VFSrznUroNjO7bHlYwSqrvq1', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0hjZ2h5bmNlUkRrNk9UV1VKZjFiQjd1aU9UM1ZEbkpsejcwd09IUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222012),
('toxLD2pcrxZ2sHWj7JjyOHZzKku1hNUfVtRDxEK3', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWXNGbUJiOVJUOHhGYVpsQjBzcjdnS3kwVVNoRDZGVGdORk1GaHo3RiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9wZW1iYXlhcmFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776222229),
('tuUvBw04i3mTe957trtBilCmAqhtgUdy2hMNzYwo', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN1NheUFoV3Y3dGpSMHRTSEt0RVRRd2VyZkl3eGNaNzBKWFIwZGNKMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224535),
('u0USyMy1jLJ7aQxXk0rTjTKqeYVTY4i3LAdf4kYb', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUHpCazIxZWtWTkdBcFZ3UnNKa0FyYkhvZlR5QjhXZGRJcGt2R0dYbiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776221267),
('U7zs5puWaf83GVdoYTaZS0irXICpBGMgYqtfJ3L6', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRUxDdUx1ZGtrU0tZekhTNldvUnFIN2JEU0h6cHYzdUM5NzhjanJDTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221285),
('uF2MYQ4hohzBR7jEYK3JI7V691QxGs7XpLrqPaAn', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiajEzTUI1Wm5od1dqVnBSOWlMOW41UzlpN0F6QUxBWEVmTmZrd1FNbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224516),
('uhlnW9orCK1YFIovYwQi07z91ilaGfGq3I6E326f', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWs4c1RtMlBSaVdoQUdoeDR1aERmOHZpU2d0VXhwUWkwdEVoUWJiaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221873),
('UlpsQPBEWKu1n4pOpIizNyHoPzIti5sVcw4YCfp3', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZjVtTXBkNmJUU3I2N2VxMjlDOExKd09UUFl0d3h2TUZKcFV0R0RmOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224542),
('UsBqJiRy7nGBjtDYr9QLpQvGEOclresfMO053yhj', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnRXQkRSR0RPSWhpcEFOc1F2d1RJVGRKelhlOWVnMVBYMFJJM2dyYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222010),
('uslk6rrUtHIIiRbGsZnMeeN24DsaTpihptJjmr23', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoienhGQml0N0JiTEpLeklqbjNNT25KZFEyR0NGYXNwUFlVa3Nyc01ZMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zdGF0cy9ob21lIjtzOjU6InJvdXRlIjtzOjEwOiJzdGF0cy5ob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776218586),
('VXNx4a4sCrqNBqz5oSxt2TgeJn358InjE99XuxrG', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZEZDcm9LOFZ0bTdMRmNSbW40NjVnazNZY1RXOHFhd2d0V2l3VnBXSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zYW1idXRhbi9hY3RpdmUiO3M6NToicm91dGUiO3M6MTU6InNhbWJ1dGFuLmFjdGl2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222195),
('vyXfFDLbqhTGPuFDJj7rzrWzhXpm5q1984Gx0AER', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoidDhQc2FFNTZKMk02NmZGYXlFZkNZSEhVTkpOaWszQnZ4U1BiR09ZYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776224486),
('W9cdid2VtJZ69PWyH68tVnXhVnOSVhCYBHdmkNVA', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWFPb2h3Vk9JUU92dzN4ZWNVak1abFA1MWRibzF2QVdhMFB3ZUw3aCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776222224),
('WeT8O3d5PlJz5qRsCdaLUZ1mbjkkJXzdhmvCCbAV', NULL, '114.79.39.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmlaeEl3emNGT29saHJXQjlPSll0QkdsamdsYWZ6SldyNXJzcVJiQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222784),
('wjhuoVu2rkIWR86hbWmijWYwBbExAeLx53Upbuol', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSzBSd3k0aDJlVmtzSk5scElYRGNmMlM1R0txZE1EUUtUQk5PdE9oeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224548),
('wr9Fz3lXgz2RjBYmS6KoujYQfyxldOAyz9rlQGXR', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmlGR1pkVGtLN0NrT0ExQ25reFpwYjRFQ3FFYm9EMGlzUE9mZm0zaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221274),
('X14zGdvySlEvnLJAsemxDu4CQk4P6OOedxdqZDUx', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTXZ3SUY0WkhUdGZpbjE1YVJZb0N5NGtyeW5UVzRNYktIOUMzUHE5QSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224122),
('Y60R2PSuWLuCgrzE5TRhim4GKX51EYrEiln3kvHX', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSXp0cEdCTFFTYVJFZHh4N1JDaHI0b1NMOHBKTmdNNE4yM0hhZWR2eiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224534),
('yNvBI7WLHje7siU4UAvzQIfEwqUApwg7gNqm2gqc', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaFAzaUw3azBYZ1FkMGIzR0dsdGR1SDRsd2hBd0xwekd2ak55cTdVdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776221269),
('YqJ8T7v28xq0vjJZytpwKbEpMCbZguxDF5Rgrbvk', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3ZOeTVMNU0wSlM2RnZ1YVN3MEVzVHNBejNObkN4WXBuaElmSDVRUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224124),
('yvOngIRbFVgjp4aVGePYk4QqLOALf6z8FSmwMqL7', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZGgwU25VeDBDSUx1dHR0Nm9rTkdrc0I5MHNhQkpRTWdDc0NDWExFUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776224444),
('zA6FDkYuzVd6SQD021y9TfFq8Q6jnJpHJGezXIDX', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHBYTXdMbWdFMmZ2ZGZUSE5KalZ3emV0ZkpqYXBHcUdHVEdQUmtsWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9yaW5jaWFuLXBlbWJheWFyYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224511),
('zauG4CnjevpFwgtzWzLlcAoiMwAtNuhXcLxBjTPb', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVUZKWlE5cGE2enBLZFR1eVdvRFJScnpjV3R0VnhXblo1bHdUWWdQSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222210),
('ZJgOmHoLhevfhbZlbKgU38udYSmK9RaYKMtkpPZ1', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiclh3czRLMWt4QlZrOHVvUXdjdG1uWTB4M1dnVWl1M3ZTQzFyZklXZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS90YWh1bi1hamFyYW4vbGF0ZXN0IjtzOjU6InJvdXRlIjtzOjE5OiJ0YWh1bi1hamFyYW4ubGF0ZXN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776222178),
('zsNLdF0PIfSDJMOWyi4HwbzLy5fQWOWoPMBhpPIc', NULL, '114.79.37.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUE12dXVoa0dBRkU5YmhETDVDbWRQQ1ZxMVpjT09FMm01Qm9JVnBscCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHBzOi8vYWRtaW4udGtzYzJtZW5hcmEuZHBkbnMub3JnL2FwaS9zaXN3YS1wZW5kYWZ0YXIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776224139);

-- --------------------------------------------------------

--
-- Table structure for table `siswas`
--

CREATE TABLE `siswas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_pendaftar_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tahun_ajaran_id` bigint(20) UNSIGNED NOT NULL,
  `kelas_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `nis` varchar(255) DEFAULT NULL,
  `nsin` varchar(255) DEFAULT NULL,
  `status` enum('aktif','pindah','lulus') NOT NULL DEFAULT 'aktif',
  `foto_path` varchar(255) DEFAULT NULL,
  `formulir_path` varchar(255) DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `alasan_keluar` text DEFAULT NULL,
  `asal_sekolah` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswas`
--

INSERT INTO `siswas` (`id`, `siswa_pendaftar_id`, `tahun_ajaran_id`, `kelas_id`, `nama_lengkap`, `nis`, `nsin`, `status`, `foto_path`, `formulir_path`, `tanggal_masuk`, `tanggal_keluar`, `alasan_keluar`, `asal_sekolah`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'Nirmalasari Rodito Sulnas', '001012026', NULL, 'aktif', 'siswa/foto/foto_1767532533_sZoDZJDsOj.jpg', 'siswa/dokumen/akte_1767532342_22EdqPYcVE.jpeg', '2026-01-04', NULL, NULL, NULL, '2026-01-04 05:15:33', '2026-01-04 05:15:33'),
(2, NULL, 1, 1, 'Syarifah Almalik', '003082024', NULL, 'aktif', 'siswa/foto/foto_1767592489_3HY09CbrKZ.jpg', NULL, '2024-07-05', '2025-06-02', 'Lulus', NULL, '2026-01-04 21:54:49', '2026-01-10 22:16:49'),
(3, NULL, 1, NULL, 'ALFATUNNISA', '003072025', NULL, 'aktif', 'siswa/foto/foto_1767790598_DsyT8Ix5o0.jpg', NULL, '2025-07-22', NULL, NULL, NULL, '2026-01-07 04:56:38', '2026-01-07 04:56:38'),
(4, 2, 1, 1, 'Avtivanisari Rodito Sulnas', '004012026', NULL, 'aktif', 'siswa/foto/foto_1767925997_1mA0Ca8ED1.jpg', 'siswa/dokumen/akte_1767775936_W5YbDnpYSN.jpeg', '2026-01-09', NULL, NULL, NULL, '2026-01-08 18:33:17', '2026-01-08 18:33:17'),
(5, 6, 1, 2, 'Lukman Hakim', '005062025', NULL, 'aktif', 'siswa/foto/foto_1768110593_6ccwB0fMdS.jpg', 'siswa/dokumen/akte_1767964690_PrO7SfpkL8.jpg', '2025-06-11', NULL, NULL, NULL, '2026-01-10 21:49:53', '2026-01-10 21:49:53'),
(6, 5, 1, 2, 'Auliah Handayani Tahir', '006072025', NULL, 'aktif', 'siswa/foto/foto_1768112140_MvqJgXDYrt.jpg', 'siswa/dokumen/akte_1767899699_b1Jw7ipxhK.jpeg', '2025-07-11', NULL, NULL, NULL, '2026-01-10 22:15:40', '2026-01-10 22:15:40'),
(7, 4, 1, 1, 'Tenri Zahra', '007062025', NULL, 'aktif', 'siswa/foto/foto_1768112309_OZu0vyc4zc.jpg', 'siswa/dokumen/akte_1767874672_h3nJv92y18.jpg', '2025-06-11', NULL, NULL, NULL, '2026-01-10 22:18:29', '2026-01-10 22:18:29'),
(10, 7, 1, 1, 'Fauzan Alfurqan', '008012026', NULL, 'aktif', 'siswa/foto/foto_1768118440_ADau7tcN5A.jpg', 'siswa/dokumen/akte_1768111045_IxCKAFxgf0.jpg', '2026-01-11', NULL, NULL, NULL, '2026-01-11 00:00:40', '2026-01-11 00:00:40'),
(11, 12, 1, 2, 'Dafa Dwi Putra', '009012026', NULL, 'aktif', 'siswa/foto/foto_1768118519_gjQxMgEXV6.jpg', 'siswa/dokumen/akte_1768117707_ZPDi74lFUg.jpg', '2026-01-11', NULL, NULL, NULL, '2026-01-11 00:01:59', '2026-01-11 00:01:59'),
(12, 11, 1, 2, 'Reza Pangestu', '010012026', NULL, 'aktif', 'siswa/foto/foto_1768118573_d3P3XdZ0PO.jpg', 'siswa/dokumen/akte_1768116864_657qsOjjFv.jpg', '2026-01-11', NULL, NULL, NULL, '2026-01-11 00:02:53', '2026-01-11 00:02:53'),
(13, 9, 1, 1, 'Aditya Pratama', '011012026', NULL, 'aktif', 'siswa/foto/foto_1768118738_1PnmHvjH6u.jpg', 'siswa/dokumen/akte_1768114471_TyZTEwuvIT.jpg', '2026-01-11', NULL, NULL, NULL, '2026-01-11 00:05:38', '2026-01-11 00:05:38'),
(14, 10, 1, 1, 'Immanuel Brian', '012012026', NULL, 'aktif', 'siswa/foto/foto_1768118965_26tc9R2oV9.jpg', 'siswa/dokumen/akte_1768115803_STL2iihKlM.jpg', '2026-01-11', NULL, NULL, NULL, '2026-01-11 00:09:25', '2026-01-11 00:09:25'),
(15, 13, 1, 2, 'Kristian Putra', '013012026', NULL, 'aktif', 'siswa/foto/foto_1768119025_gqyBoWZjJy.jpg', 'siswa/dokumen/akte_1768118596_lb0g5nx1Ws.jpg', '2026-01-11', NULL, NULL, NULL, '2026-01-11 00:10:25', '2026-01-11 00:10:25'),
(16, 3, 3, 2, 'Muhammad Nugi Ramadhan', '001062024', NULL, 'aktif', 'siswa/foto/foto_1768119974_RtFZkDfEEW.jpg', 'siswa/dokumen/akte_1767800185_XtNP2jIT2F.png', '2024-06-11', NULL, NULL, NULL, '2026-01-11 00:26:14', '2026-01-11 00:26:14'),
(17, 15, 3, 2, 'Khoirunisa Putri', '002072024', NULL, 'lulus', 'siswa/foto/foto_1768122078_T6lPB76rlX.jpg', 'siswa/dokumen/akte_1768120415_FJR4PadfQV.jpg', '2024-07-11', NULL, NULL, NULL, '2026-01-11 01:01:18', '2026-01-11 03:48:57'),
(18, 14, 3, 1, 'Dwi Saraswati', '003072024', NULL, 'lulus', 'siswa/foto/foto_1768132189_IYoiOOpbXV.jpg', 'siswa/dokumen/akte_1768119764_OPLNg8fIAh.jpg', '2024-07-11', NULL, NULL, NULL, '2026-01-11 03:49:49', '2026-01-11 03:50:03');

-- --------------------------------------------------------

--
-- Table structure for table `siswa_pendaftars`
--

CREATE TABLE `siswa_pendaftars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `regist_pendaftar_id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `nik` text NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(255) NOT NULL,
  `jenis_kelamin` varchar(255) NOT NULL,
  `usia` int(11) NOT NULL,
  `alamat_jalan` text NOT NULL,
  `rt` varchar(255) NOT NULL,
  `rw` varchar(255) NOT NULL,
  `kelurahan` varchar(255) NOT NULL,
  `kecamatan` varchar(255) NOT NULL,
  `kota` varchar(255) NOT NULL,
  `kode_pos` varchar(255) NOT NULL,
  `tinggi_badan` int(11) NOT NULL,
  `berat_badan` int(11) NOT NULL,
  `jumlah_saudara` int(11) NOT NULL,
  `jarak_sekolah` decimal(5,2) NOT NULL,
  `waktu_tempuh` int(11) NOT NULL,
  `nama_ayah` varchar(255) NOT NULL,
  `nama_ibu` varchar(255) NOT NULL,
  `nik_ayah` text NOT NULL,
  `nik_ibu` text NOT NULL,
  `tempat_lahir_ayah` varchar(255) NOT NULL,
  `tempat_lahir_ibu` varchar(255) NOT NULL,
  `tanggal_lahir_ayah` date NOT NULL,
  `tanggal_lahir_ibu` date NOT NULL,
  `pendidikan_ayah` varchar(255) NOT NULL,
  `pendidikan_ibu` varchar(255) NOT NULL,
  `pekerjaan_ayah` varchar(255) NOT NULL,
  `pekerjaan_ibu` varchar(255) NOT NULL,
  `alamat_ayah` text NOT NULL,
  `alamat_ibu` text NOT NULL,
  `no_telp` text NOT NULL,
  `penghasilan` text NOT NULL,
  `akte_kelahiran_path` varchar(255) DEFAULT NULL,
  `kartu_keluarga_path` varchar(255) DEFAULT NULL,
  `kia_path` varchar(255) DEFAULT NULL,
  `bpjs_path` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','diproses','diverifikasi','ditolak') NOT NULL DEFAULT 'menunggu',
  `catatan_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswa_pendaftars`
--

INSERT INTO `siswa_pendaftars` (`id`, `regist_pendaftar_id`, `tahun_ajaran_id`, `nama_lengkap`, `nik`, `tempat_lahir`, `tanggal_lahir`, `agama`, `jenis_kelamin`, `usia`, `alamat_jalan`, `rt`, `rw`, `kelurahan`, `kecamatan`, `kota`, `kode_pos`, `tinggi_badan`, `berat_badan`, `jumlah_saudara`, `jarak_sekolah`, `waktu_tempuh`, `nama_ayah`, `nama_ibu`, `nik_ayah`, `nik_ibu`, `tempat_lahir_ayah`, `tempat_lahir_ibu`, `tanggal_lahir_ayah`, `tanggal_lahir_ibu`, `pendidikan_ayah`, `pendidikan_ibu`, `pekerjaan_ayah`, `pekerjaan_ibu`, `alamat_ayah`, `alamat_ibu`, `no_telp`, `penghasilan`, `akte_kelahiran_path`, `kartu_keluarga_path`, `kia_path`, `bpjs_path`, `status`, `catatan_admin`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Nirmalasari Rodito Sulnas', 'rTgP1it9ZYWcLSIIKaDN3UWI1JTKuTRWG/eRcfpTuNI=', 'Parepare', '2023-08-28', 'islam', 'perempuan', 3, 'BTN. Timurama Blok A1/5', '01', '08', 'Lompoe', 'Bacukiki', 'Parepare', '91172', 100, 30, 2, 2.00, 15, 'Rosihan Sulnas', 'Andi Darmawaty', 'bOoETfQxUfiZdYecOcooI/pq05PtiTwT81zJ+K2Dx90=', 'i5fxIG2UYjSmt/lj4iGIYFe5zNwLpS7vipszgqfhjpQ=', 'Parepare', 'Parepare', '1987-03-12', '1970-07-24', 'sma', 's1', 'Wirasuasta', 'Kepala Sekolah', 'BTN. Timurama', 'BTN. Timurama', 'vymeXIi3KVJuKwpBGnN2vg==', '4p9nxbkP9Lwn4oQ/iMjWFA==', 'api/secure-files/akte_1767532342_22EdqPYcVE.jpeg', 'api/secure-files/kk_1767532342_Jt0IRcbjNA.jpeg', 'api/secure-files/kia_1767532342_F7HZLoR6BZ.jpeg', 'api/secure-files/bpjs_1767532342_pgMzlNC1hZ.jpeg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-04 05:12:22', '2026-02-08 06:03:28'),
(2, 3, 1, 'Avtivanisari Rodito Sulnas', 'rTgP1it9ZYWcLSIIKaDN3UWI1JTKuTRWG/eRcfpTuNI=', 'Parepare', '2023-08-28', 'konghucu', 'perempuan', 3, 'BTN. Timurama Blok A1/5', '01', '07', 'Lompoe', 'Bacukiki', 'Parepare', '91197', 100, 20, 2, 13.90, 15, 'Rosihan Sulnas', 'Andi Darmawaty', 'bOoETfQxUfiZdYecOcooI/pq05PtiTwT81zJ+K2Dx90=', 'FKj+G2/ZEjGTc5q/d0KkJjv9PYzRmwdBENrWHHu+sjk=', 'Parepare', 'Parepare', '1987-08-28', '1987-08-26', 's1', 's1', 'Wirasuasta', 'Kepala Sekolah', 'BTN. Timurama', 'BTN. Timurama', 'EqRWc1IiXkf5ja1pHs55Hw==', 'GyVFEizLMSjA6M/HQDBS0Q==', 'api/secure-files/akte_1767775936_W5YbDnpYSN.jpeg', 'api/secure-files/kk_1767775936_pjBi5JYIb9.jpeg', 'api/secure-files/kia_1767775936_tXmPFPo64o.jpeg', 'api/secure-files/bpjs_1767775936_WQ3X2mrFjm.jpeg', 'ditolak', 'gambar yang di berikan tidak jelas', '2026-01-07 00:52:16', '2026-02-08 06:03:28'),
(3, 4, NULL, 'Muhammad Nugi Ramadhan', 'hIonYlcQqua2IOXKgkZ1soVh6Z5mNeChSRPtn57ut1Y=', 'Parepare', '2024-12-09', 'islam', 'laki-laki', 3, 'Lumpue', '01', '07', 'Lumpue', 'Bacukiki', 'Parepare', '91197', 110, 30, 2, 10.00, 15, 'Adit', 'Rahma', 'bOoETfQxUfiZdYecOcooI/pq05PtiTwT81zJ+K2Dx90=', 'FKj+G2/ZEjGTc5q/d0KkJjv9PYzRmwdBENrWHHu+sjk=', 'Sidrap', 'Parepare', '2003-02-10', '2002-08-12', 's1', 's1', 'Wirasuasta', 'IRT', 'Menara', 'Menara', 'EqRWc1IiXkf5ja1pHs55Hw==', 'nsf43dvOzrGf8IeR82XGyw==', 'api/secure-files/akte_1767800185_XtNP2jIT2F.png', 'api/secure-files/kk_1767800185_0Cc7pM4fJQ.jpeg', 'api/secure-files/kia_1767800185_HiUKiDA5Zv.jpeg', 'api/secure-files/bpjs_1767800185_8SesWVE6AL.jpeg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-07 07:36:25', '2026-02-08 06:03:28'),
(4, 5, 3, 'Tenri Zahra', 'rTgP1it9ZYWcLSIIKaDN3UWI1JTKuTRWG/eRcfpTuNI=', 'Parepare', '2022-07-06', 'islam', 'laki-laki', 3, 'Jl. Menara', '01', '08', 'Lompoe', 'Soreang', 'Parepare', '918725', 90, 20, 1, 5.00, 10, 'Beddu', 'Aqeela Kalista', 'bOoETfQxUfiZdYecOcooI/pq05PtiTwT81zJ+K2Dx90=', 'FKj+G2/ZEjGTc5q/d0KkJjv9PYzRmwdBENrWHHu+sjk=', 'Sidrap', 'Soppeng', '2002-02-10', '2001-02-27', 's1', 's1', 'Hacker', 'IT', 'Menara', 'Menara', 'EqRWc1IiXkf5ja1pHs55Hw==', '4p9nxbkP9Lwn4oQ/iMjWFA==', 'akte_1770568880_oVltxxwRkE.jpg', 'kk_1770568880_mJYsWfZI81.jpg', 'kia_1770568880_xSinCxTxLv.jpg', 'bpjs_1770568880_Y5Xa1yOCUG.jpg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-08 04:17:52', '2026-02-08 08:41:20'),
(5, 7, NULL, 'Auliah Handayani Tahir', 'qr/SFzKiJJW8wN8r0kcIM0PgGRgQooqMpfvrbMdiFQc=', 'Parepare', '2022-01-09', 'islam', 'perempuan', 3, 'BTN Timurama Blok A2/3', '01', '08', 'Lompoe', 'Bacukiki', 'Parepare', '917273', 98, 14, 2, 12.00, 14, 'Rosihan Sulnas', 'Andi darmawaty', 'dUbxfwdWJ+ZsLNHQd4HgUP0ctcMHcptJFKEVTIP26wI=', 'O9sqFCDzjxTdRj0mflIVG2hGUUC46XwK+A0RNysyI0o=', 'Parepare', 'Parepare', '1972-04-11', '1973-07-24', 's1', 's1', 'Komisi Penanggulanan AIDS', 'Kepala Sekolah', 'BTN Timurama BlokA1/5', 'BTN Timurama BlokA1/5', 'vymeXIi3KVJuKwpBGnN2vg==', '4p9nxbkP9Lwn4oQ/iMjWFA==', 'api/secure-files/akte_1767899699_b1Jw7ipxhK.jpeg', 'api/secure-files/kk_1767899699_4dfvq76XTl.jpeg', 'api/secure-files/kia_1767899699_tGukFYinjN.png', 'api/secure-files/bpjs_1767899699_HD8yvlV27n.jpeg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-08 11:14:59', '2026-02-08 06:03:28'),
(6, 11, NULL, 'Lukman Hakim', 'yc3RufkeNXZSXsmlmkvIK/ORS2pJAisoXZsbz8RO7/8=', 'Pinrang', '2023-12-07', 'islam', 'laki-laki', 4, 'Jl. Bau Massepe no. 161', '01', '05', 'Lumpee', 'Bacukiki Barat', 'Parepare', '91123', 90, 20, 2, 2.00, 14, 'Faruq', 'Saila Umaira', '+ykw+NSc2OfUhb/HBUAzewvtIrU0Z/zlCoAdCsMC76c=', 'ziwGgr+mKrUd94kYCTIIYFvAZVpGdf3G0kz7QLaegHA=', 'Endrekang', 'Pangkep', '2003-08-28', '2001-03-28', 's1', 's1', 'Hacker', 'Mentri Keuangan', 'Lakessi', 'Lakessi', 'XPI2TdB+xxSPAhwJQF5FLQ==', 'K5ycKmMdTiGt3uRXj7pzQw==', 'api/secure-files/akte_1767964690_PrO7SfpkL8.jpg', 'api/secure-files/kk_1767964690_ynLbDISLkN.jpg', 'api/secure-files/kia_1767964690_oQp86Oy4ZC.png', 'api/secure-files/bpjs_1767964690_mYuBLqJqOQ.png', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-09 05:18:10', '2026-02-08 06:03:28'),
(7, 12, NULL, 'Fauzan Alfurqan', 'leW/MvTcTB2H26M8jbhDlbjlHTQUM39SsSilp26ImKQ=', 'Parepare', '2023-02-09', 'islam', 'laki-laki', 3, 'Jl. Menara', '02', '08', 'Lompoe', 'Bacukiki', 'Parepare', '91197', 94, 19, 0, 2.00, 5, 'Ridwan Hakim', 'Maimunah Latifa', 'ZixaWegHotM8Q+DQrkUVVg0xAlXlloV//XtV/NdlRys=', 'ldJksNaQyLvDvCHQuo2/mANUpUbQ1xd8qbs1RpxID7c=', 'Sidrap', 'Soppeng', '2001-08-22', '1997-01-26', 'sma', 'smp', 'Wirasuasta', 'IRT', 'Menara', 'Menara', 'EqRWc1IiXkf5ja1pHs55Hw==', '4p9nxbkP9Lwn4oQ/iMjWFA==', 'api/secure-files/akte_1768111045_IxCKAFxgf0.jpg', 'api/secure-files/kk_1768111045_I1ufwaJBUO.jpg', 'api/secure-files/kia_1768111045_xzAzg7P8zr.jpg', 'api/secure-files/bpjs_1768111045_IRx6xiEvNH.jpg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-10 21:57:25', '2026-02-08 06:03:28'),
(8, 14, NULL, 'Kemal Lafatah', 'vCRMxtJeCHZ9g0U5Q0cpjQRDo9uXyyGhLrfkl2rfOME=', 'Parepare', '2024-08-24', 'islam', 'laki-laki', 4, 'BTN. Timurama Blok A1/5', '02', '08', 'Lompoe', 'Bacukiki', 'Parepare', '91197', 100, 20, 1, 3.00, 15, 'Zamsul Bakri', 'Lidyanti Utami', 'iZp6Wr0e2aBObad5ibO4fPYVYDMhQG7YgIpJvhKoRYY=', 'vBwpoTXj1/pGFNQpYO5aLgt83j3Ipoe0WpfdnTDsnrs=', 'Parepare', 'Parepare', '1987-09-24', '1976-04-23', 'smp', 'sma', 'Wirasuasta', 'IRT', 'BTN. Timurama', 'BTN. Timurama', 'EqRWc1IiXkf5ja1pHs55Hw==', 'PtCCzIRKdVA8TqxQ2yR/Qg==', 'api/secure-files/akte_kelahiran_1768114424_M2MtNZRiO2.jpg', 'api/secure-files/kartu_keluarga_1768114424_Zd5jX3MvHU.jpg', 'api/secure-files/kia_1768114424_Poqa0BhOxJ.jpg', 'api/secure-files/bpjs_1768114424_4HETbTOXls.jpg', 'menunggu', NULL, '2026-01-10 22:52:19', '2026-02-08 06:03:28'),
(9, 13, NULL, 'Aditya Pratama', '1NFB2ZfTshQ2ZWk7jXomgy6CSYqLM4pclTATiEQHt/w=', 'Parepare', '2019-09-09', 'islam', 'laki-laki', 4, 'Btn Timurama No.99', '01', '07', 'Lompoe', 'Bacukiki', 'Parepare', '91125', 99, 9, 0, 1.00, 10, 'Soedirman', 'Kartini', 'alTgkc+nZ+V4Qksi2fNID2fKqkKuNCytNjOrmiMcvU0=', 'KOt3QSvDS1Fjckg7U6ecjQczkr5Ar/6EzOb1Q/2np4E=', 'Surabaya', 'Parepare', '1989-12-01', '1991-03-25', 's1', 's1', 'Pengusaha', 'Freelance', 'Btn Timurama No.99', 'Btn Timurama No.99', '4m2O/44CvA51AvCmyI2MHA==', '7PALy81oirZOSvquwada0A==', 'api/secure-files/akte_1768114471_TyZTEwuvIT.jpg', 'api/secure-files/kk_1768114471_UzdAYvwng0.jpg', 'api/secure-files/kia_1768114471_oj0MoniQRS.jpg', 'api/secure-files/bpjs_1768114471_EYSF41b8hJ.jpeg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-10 22:54:31', '2026-02-08 06:03:28'),
(10, 15, NULL, 'Immanuel Brian', 'xSKk+1e7WC6KBk4lGai9TfQHvDjX23Ydwdz1aWuVb3o=', 'Parepare', '2021-08-08', 'kristen', 'laki-laki', 4, 'Jl. Menara No.88', '08', '08', 'Watang Soreang', 'Soreang', 'Parepare', '91133', 100, 15, 1, 0.50, 5, 'Wicaksono', 'Merry Kristiani', 'iIW8dCyHcS493eM2dd/8thnwyG36yxoOlqz4xQ0P2eo=', 'aN/kCV8cqaOL7kMHeJYdtIeMe2Imk1tdnv1NUteT8a4=', 'Malang', 'Makassar', '1997-07-07', '2001-01-01', 's1', 'd3', 'Direktur', 'Ibu Rumah Tangga', 'Jl. Menara No.88', 'Jl. Menara No.88', '9cuXyyyy2uSY72q5SaplgA==', 'z7oa5FhgCYVXzoZ78u2mYw==', 'api/secure-files/akte_1768115803_STL2iihKlM.jpg', 'api/secure-files/kk_1768115803_mgwW7Kk7S0.jpg', 'api/secure-files/kia_1768115803_kC3lTT7y3f.jpg', 'api/secure-files/bpjs_1768115803_I617sgTZgn.jpeg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-10 23:16:43', '2026-02-08 06:03:28'),
(11, 16, NULL, 'Reza Pangestu', 'pvbjGmq6YDfzdZHcgshZ99u6HPhM2uxjMX+SjYQKCWw=', 'Makassar', '2021-03-02', 'islam', 'laki-laki', 3, 'Jl. Poros No.23', '02', '03', 'Soreang', 'Soreang', 'Parepare', '91132', 105, 10, 2, 1.00, 10, 'Muhammadong', 'Sriwati', 'jsVwtVzWFsrbx46Vpg1hSGYh9kQwnJg5e+94GXsywrk=', 'j6yncQqDmHW3HO9NUwG+UizAqb7U92vpi/763QhwvjU=', 'Makassar', 'Gowa', '2001-02-01', '2001-05-20', 'sma', 'sma', 'Wirausaha', 'Ibu Rumah Tangga', 'Jl. Poros No.23', 'Jl. Poros No.23', 'uNchcmAzoa1r8CFdiagJ8w==', 'UvPEvIlWznR0IC1i+XTvHg==', 'api/secure-files/akte_1768116864_657qsOjjFv.jpg', 'api/secure-files/kk_1768116864_F2HtDuFoMp.jpg', 'api/secure-files/kia_1768116864_DchHKNIqQQ.jpg', 'api/secure-files/bpjs_1768116864_hK2m2KDoiM.jpeg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-10 23:34:24', '2026-02-08 06:03:28'),
(12, 17, NULL, 'Dafa Dwi Putra', 'hHnTm0ki1u31xStFAgTMSUNF1ykCEO8rVm8zwt63/8g=', 'Parepare', '2021-02-15', 'islam', 'laki-laki', 4, 'Jl. Petta Unga No.71', '07', '01', 'Watang Soreang', 'Soreang', 'Parepare', '91133', 100, 12, 3, 0.50, 5, 'Aldi', 'Maimuna', '51a2Grcfz+HbTWQjzXfsFDfrB4C/yvhp/Q3LEXOCW98=', 'AVGDC/5PWTtQ9VhLc6F1K1xk/XVIZME5NQMDHaocnPQ=', 'Parepare', 'Toraja', '1999-09-09', '1998-12-18', 's1', 'sma', 'PNS', 'Ibu Rumah Tangga', 'Jl. Petta Unga No.71', 'Jl. Petta Unga No.71', 'BXA35jLbjdWX8xKxkboFeA==', 'xEEHU+l48vl/aNV6ggylqQ==', 'api/secure-files/akte_1768117707_ZPDi74lFUg.jpg', 'api/secure-files/kk_1768117707_FCTX7t062v.jpg', 'api/secure-files/kia_1768117707_fBZDoXcqWs.jpg', 'api/secure-files/bpjs_1768117707_GK1TfpjrZx.jpeg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-10 23:48:27', '2026-02-08 06:03:28'),
(13, 18, NULL, 'Kristian Putra', 'XiwZ3IxFjr6nTJ5wGflgOG+JptNNexsnbf9r61URbk4=', 'Parepare', '2021-09-29', 'kristen', 'laki-laki', 4, 'Jl. Menara 2', '09', '03', 'Watang Soreang', 'Soreang', 'Parepare', '91133', 98, 9, 1, 0.50, 2, 'Andi Sakebong', 'Mardiyana', 'pk0qku0JoVpaUTIDEbiD0H2jOklmYwZ5xZ6dUz9JqiQ=', 'T+JffeYAiYmRU3uzB7fWd6h53rBqNqvF9SB55YFBXYU=', 'Parepare', 'Pinrang', '1992-02-22', '2002-12-12', 'sma', 's1', 'Buruh Harian Lepas', 'Wiraswasta', 'Jl. Menara 2', 'Jl. Menara 2', 'pbJ/L8bSDfuBuA03IGzspg==', 'UvPEvIlWznR0IC1i+XTvHg==', 'api/secure-files/akte_1768118596_lb0g5nx1Ws.jpg', 'api/secure-files/kk_1768118596_njIVTPsFyl.jpg', 'api/secure-files/kia_1768118596_6ZXu2Zt0wh.jpg', 'api/secure-files/bpjs_1768118596_C6sI0aBuMG.jpeg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-11 00:03:16', '2026-02-08 06:03:28'),
(14, 19, NULL, 'Dwi Saraswati', 'vgKPUQxomsno5SujzMdDgNaMCk3Goep69ebMtQo6Fkc=', 'Parepare', '2021-03-13', 'islam', 'perempuan', 4, 'Jl. Menara', '06', '01', 'Watang Soreang', 'Soreang', 'Parepare', '91133', 100, 9, 3, 0.50, 1, 'Rivaldi Andi', 'Sasa Dwi Pangestu', 'TD63Y9tqekA+2Aug8URMw7lf9P/d6fiw30cHPMNQzUk=', 'xGvGy0Btflq7VgQ8+GEX/uU+UoU7mFg2oGDxtUGa6SY=', 'Parepare', 'Toraja', '1990-11-07', '1995-09-19', 'sma', 'sma', 'Buruh', 'Ibu Rumah Tangga', 'Jl. Menara', 'Jl. Menara', '4w61ReYvo8QEE0jGLgTe+A==', 'JjDV3JVlyqpUfuTwQFtnUA==', 'api/secure-files/akte_1768119764_OPLNg8fIAh.jpg', 'api/secure-files/kk_1768119764_7HH4O7gyFi.jpg', 'api/secure-files/kia_1768119764_VNzb6D4iSz.jpg', 'api/secure-files/bpjs_1768119764_qopCJnKStI.jpeg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-11 00:22:44', '2026-02-08 06:03:28'),
(15, 20, NULL, 'Khoirunisa Putri', '0nio+lKwfz/P+XOPM3ItkG0tGPTg2Z0lZlml59vMpJA=', 'Parepare', '2021-11-12', 'islam', 'perempuan', 3, 'Jl. Menara No.98', '03', '02', 'Watang Soreang', 'Soreang', 'Parepare', '91133', 100, 9, 2, 0.50, 3, 'Andi Makassau', 'Mahalinni', '2pLa+xEy155dkQdFd9JzESu2AlbZph47lvGoO6vXeho=', 'O14pS9RhE1PaBpaBw8Mr+nh9QgI1Gslo8f+tc7TmzQo=', 'Parepare', 'Makassar', '2003-06-16', '2002-03-13', 'd3', 'sma', 'Wirausaha', 'Ibu Rumah Tangga', 'Jl. Menara No.98', 'Jl. Menara No.98', 'Gvh/rjnzuc91XEvo+c9eQQ==', '3i5t3ojSGvWps0Lu5VA46Q==', 'api/secure-files/akte_1768120415_FJR4PadfQV.jpg', 'api/secure-files/kk_1768120415_I6RdmAuioS.jpg', 'api/secure-files/kia_1768120415_UbcoEqDAqq.jpg', 'api/secure-files/bpjs_1768120415_vXwQWllpzt.jpeg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-11 00:33:35', '2026-02-08 06:03:28'),
(16, 22, NULL, 'Clara Utami', 'rTgP1it9ZYWcLSIIKaDN3UWI1JTKuTRWG/eRcfpTuNI=', 'Parepare', '2021-01-01', 'islam', 'perempuan', 3, 'BTN. Timurama Blok A1/5', '02', '07', 'Lumpue', 'Bacukiki', 'Parepare', '91197', 100, 20, 0, 2.00, 12, 'Larasati', 'Bagas', 'bOoETfQxUfiZdYecOcooI/pq05PtiTwT81zJ+K2Dx90=', 'FKj+G2/ZEjGTc5q/d0KkJjv9PYzRmwdBENrWHHu+sjk=', 'Parepare', 'Parepare', '2004-02-24', '1997-07-22', 's1', 'sma', 'Wirasuasta', 'IRT', 'BTN. Timurama', 'BTN. Timurama', 'EqRWc1IiXkf5ja1pHs55Hw==', '7PALy81oirZOSvquwada0A==', 'api/secure-files/akte_1768140491_uI17TYRUGz.jpg', 'api/secure-files/kk_1768140491_lEjkAcO30z.jpg', 'api/secure-files/kia_1768140491_3Msza77Sjo.jpg', 'api/secure-files/bpjs_1768140491_y3sJzK27W5.jpg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-11 06:08:11', '2026-02-08 06:03:28'),
(18, 24, NULL, 'Stevelin Friska', 'JvOJnxvMaLrQ8y3c4aUsR7PTNQ4Y3tY6Q9gbvnMOhDM=', 'Parepare', '2021-09-02', 'islam', 'perempuan', 3, 'Jl. Menara', '01', '07', 'Lumpue', 'Bacukiki Barat', 'Parepare', '91197', 90, 20, 1, 2.00, 15, 'Samuel', 'Rika', 'rBRzOPbHCw8Tgshz+/MGMnFfM9jrb+BKKaY+/pGqMhM=', 'XmQKU063LHiYZJidEqTVgoUDIQXpMwGojSzcJFHjJqM=', 'Parepare', 'Soppeng', '1998-06-12', '2000-04-12', 's1', 's1', 'Hacker', 'IRT', 'BTN. Timurama', 'BTN. Timurama', 'EqRWc1IiXkf5ja1pHs55Hw==', 'isn63XbEIbEJ3zvemMioxw==', 'api/secure-files/akte_1768221365_lLXOvylLf7.jpg', 'api/secure-files/kk_1768221365_Tz8cPY6dP9.jpg', 'api/secure-files/kia_1768221365_HvLgKRKVyN.jpg', 'api/secure-files/bpjs_1768221365_WVZQVAY3WT.jpg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-12 04:36:05', '2026-02-08 06:03:28'),
(20, 23, 1, 'Putri Feliza', 'rTgP1it9ZYWcLSIIKaDN3UWI1JTKuTRWG/eRcfpTuNI=', 'Parepare', '2021-08-24', 'islam', 'perempuan', 3, 'Lumpue', '02', '08', 'Lumpue', 'Bacukiki Barat', 'Parepare', '91197', 90, 20, 1, 2.00, 15, 'Muliadi', 'Nurul Auliah', '6pnSn/OW1KkcV5OylxSG58U96Fq1S+CjiO9fI9OZi/8=', 'FKj+G2/ZEjGTc5q/d0KkJjv9PYzRmwdBENrWHHu+sjk=', 'Parepare', 'Soppeng', '2000-02-14', '2003-04-23', 'd3', 'd3', 'Wirasuasta', 'Guru', 'BTN. Timurama', 'BTN. Timurama', 'EqRWc1IiXkf5ja1pHs55Hw==', 'isn63XbEIbEJ3zvemMioxw==', 'akte_1770565449_UGwUgcTBoL.jpg', 'kk_1770565449_JTKpV9fjwq.jpg', 'kia_1770565449_h3IjE1wDpV.jpg', 'bpjs_1770565449_8pL91ayOix.jpg', 'diverifikasi', 'Pendaftaran telah diverifikasi oleh admin.', '2026-01-12 10:04:17', '2026-02-08 07:44:09'),
(24, 25, NULL, 'oca', 'rTgP1it9ZYWcLSIIKaDN3UWI1JTKuTRWG/eRcfpTuNI=', 'Parepare', '2026-02-03', 'islam', 'perempuan', 5, 'BTN. Timurama Blok A1/5', '02', '01', 'Lumpue', 'Bacukiki', 'Parepare', '91197', 100, 20, 1, 10.00, 4, 'Zamsul Bakri', 'Maimunah Latifa', '6pnSn/OW1KkcV5OylxSG58U96Fq1S+CjiO9fI9OZi/8=', 'vBwpoTXj1/pGFNQpYO5aLgt83j3Ipoe0WpfdnTDsnrs=', 'Parepare', 'Soppeng', '2026-02-12', '2026-02-05', 's1', 'smp', 'Wirasuasta', 'Kepala Sekolah', 'BTN. Timurama', 'Menara', 'EqRWc1IiXkf5ja1pHs55Hw==', 'z7oa5FhgCYVXzoZ78u2mYw==', 'akte_kelahiran_1771135792_wNMjcY8YXT.jpg', 'kartu_keluarga_1771135792_6HRmLVBzWo.jpg', 'kia_1771135792_GSiEYwGtq3.jpg', 'bpjs_1771135792_lEgZ9AFQfv.jpg', 'menunggu', NULL, '2026-02-08 00:46:43', '2026-02-14 22:09:52'),
(25, 27, NULL, 'Yuniarti Salsabila Sulnas', 'VUQz9iCWTKjoM3hHRNMFcj9EqaNcUcaLbThYFwYuVHo=', 'Parepare', '2019-03-27', 'islam', 'perempuan', 3, 'Kota Depok, Provinsi Jawa Barat', '01', '07', 'Lumpue', 'Bacukiki', 'Jakarta', '91197', 100, 12, 1, 13.00, 2, 'sawal', 'samsiah', '3J0vlfo+72QaRm4hvd2ZO5VPoqXLzhFFzQdl5l9vCvo=', 'a72BVzx71Z44U1dhK/mkATdXKhWdoy5qTj9bxrXcAMI=', 'Parepare', 'Parepare', '1098-04-23', '1023-02-23', 's1', 'sma', 'Hacker', 'IRT', 'Kota Depok, Provinsi Jawa Barat', 'Kota Depok, Provinsi Jawa Barat', 'EqRWc1IiXkf5ja1pHs55Hw==', '4p9nxbkP9Lwn4oQ/iMjWFA==', 'akte_1775653052_fdMiVO6oRw.jpeg', 'kk_1775653052_pEx9UNrcL4.jpeg', 'kia_1775653052_EJ9wjt57Vi.jpeg', 'bpjs_1775653052_HYog7BWddC.png', 'menunggu', NULL, '2026-04-08 04:57:32', '2026-04-08 04:57:32');

-- --------------------------------------------------------

--
-- Table structure for table `struktur_organisasis`
--

CREATE TABLE `struktur_organisasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_struktur` varchar(255) NOT NULL,
  `gambar_struktur` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `struktur_organisasis`
--

INSERT INTO `struktur_organisasis` (`id`, `nama_struktur`, `gambar_struktur`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Struktur Organisasi TK SC2 Menara Parepare Tahun 2024', 'struktur-organisasi/struktur_1767538464_VSqPvNHLGQ.png', 0, '2026-01-04 06:54:24', '2026-01-04 07:31:51', NULL),
(2, 'Struktur Organisasi TK SC2 Menara Parepare Tahun 2025', 'struktur-organisasi/struktur_1767540751_HuHLmZY78M.png', 1, '2026-01-04 07:32:31', '2026-01-04 07:49:46', '2026-01-04 07:49:46'),
(3, 'Struktur Organisasi TK SC2 Menara Parepare Tahun 2025', 'struktur-organisasi/struktur_1767541813_tRffRMZTyi.png', 1, '2026-01-04 07:50:13', '2026-01-04 07:50:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajarans`
--

CREATE TABLE `tahun_ajarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_tahun_ajaran` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tahun_ajarans`
--

INSERT INTO `tahun_ajarans` (`id`, `nama_tahun_ajaran`, `created_at`, `updated_at`) VALUES
(1, '2024/2025', '2026-01-04 03:33:26', '2026-01-04 03:33:26'),
(3, '2023/2024', '2026-01-04 23:30:11', '2026-01-04 23:30:11'),
(5, '2026/2027', '2026-01-11 02:11:57', '2026-01-11 02:11:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Andi Darmawaty', 'tksc2menara@gmail.com', NULL, '$2y$12$xMdV7NJWkJsJLSDhztnzwepQq3DDoiCmLWz8yI8iLZrYRCPEA2nGK', 'WdagtgnjAzyyHVrBOVUgsEDwAXRU8RM6fWOYwsASgpNoopY1BJk5HyvkDrOa', '2026-01-07 02:33:59', '2026-01-07 02:33:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berita_acaras`
--
ALTER TABLE `berita_acaras`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `berita_acaras_slug_unique` (`slug`);

--
-- Indexes for table `berita_acara_media`
--
ALTER TABLE `berita_acara_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `berita_acara_media_berita_acara_id_foreign` (`berita_acara_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gurus`
--
ALTER TABLE `gurus`
  ADD PRIMARY KEY (`id_guru`),
  ADD UNIQUE KEY `gurus_nuptk_unique` (`nuptk`),
  ADD UNIQUE KEY `gurus_email_unique` (`email`),
  ADD KEY `gurus_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pembayarans_regist_pendaftar_id_foreign` (`regist_pendaftar_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `regist_pendaftars`
--
ALTER TABLE `regist_pendaftars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regist_pendaftars_username_unique` (`username`),
  ADD UNIQUE KEY `regist_pendaftars_email_unique` (`email`);

--
-- Indexes for table `rincian_pembayarans`
--
ALTER TABLE `rincian_pembayarans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sambutan_kepala_sekolahs`
--
ALTER TABLE `sambutan_kepala_sekolahs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `siswas`
--
ALTER TABLE `siswas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `siswas_nis_unique` (`nis`),
  ADD UNIQUE KEY `siswas_nsin_unique` (`nsin`),
  ADD KEY `siswas_siswa_pendaftar_id_foreign` (`siswa_pendaftar_id`),
  ADD KEY `siswas_tahun_ajaran_id_foreign` (`tahun_ajaran_id`),
  ADD KEY `siswas_kelas_id_foreign` (`kelas_id`);

--
-- Indexes for table `siswa_pendaftars`
--
ALTER TABLE `siswa_pendaftars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_pendaftars_regist_pendaftar_id_foreign` (`regist_pendaftar_id`),
  ADD KEY `siswa_pendaftars_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- Indexes for table `struktur_organisasis`
--
ALTER TABLE `struktur_organisasis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tahun_ajarans`
--
ALTER TABLE `tahun_ajarans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tahun_ajarans_nama_tahun_ajaran_unique` (`nama_tahun_ajaran`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berita_acaras`
--
ALTER TABLE `berita_acaras`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `berita_acara_media`
--
ALTER TABLE `berita_acara_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `gurus`
--
ALTER TABLE `gurus`
  MODIFY `id_guru` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `pembayarans`
--
ALTER TABLE `pembayarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `regist_pendaftars`
--
ALTER TABLE `regist_pendaftars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `rincian_pembayarans`
--
ALTER TABLE `rincian_pembayarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sambutan_kepala_sekolahs`
--
ALTER TABLE `sambutan_kepala_sekolahs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `siswas`
--
ALTER TABLE `siswas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `siswa_pendaftars`
--
ALTER TABLE `siswa_pendaftars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `struktur_organisasis`
--
ALTER TABLE `struktur_organisasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tahun_ajarans`
--
ALTER TABLE `tahun_ajarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berita_acara_media`
--
ALTER TABLE `berita_acara_media`
  ADD CONSTRAINT `berita_acara_media_berita_acara_id_foreign` FOREIGN KEY (`berita_acara_id`) REFERENCES `berita_acaras` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gurus`
--
ALTER TABLE `gurus`
  ADD CONSTRAINT `gurus_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL;

--
-- Constraints for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD CONSTRAINT `pembayarans_regist_pendaftar_id_foreign` FOREIGN KEY (`regist_pendaftar_id`) REFERENCES `regist_pendaftars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `siswas`
--
ALTER TABLE `siswas`
  ADD CONSTRAINT `siswas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL,
  ADD CONSTRAINT `siswas_siswa_pendaftar_id_foreign` FOREIGN KEY (`siswa_pendaftar_id`) REFERENCES `siswa_pendaftars` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `siswas_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `siswa_pendaftars`
--
ALTER TABLE `siswa_pendaftars`
  ADD CONSTRAINT `siswa_pendaftars_regist_pendaftar_id_foreign` FOREIGN KEY (`regist_pendaftar_id`) REFERENCES `regist_pendaftars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_pendaftars_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
