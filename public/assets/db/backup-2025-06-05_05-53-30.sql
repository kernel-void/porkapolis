-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: spp
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `biaya`
--

DROP TABLE IF EXISTS `biaya`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `biaya` (
  `id_biaya` char(36) NOT NULL DEFAULT 'f7fc9693-c2bd-498a-b6ee-a9c994377a92',
  `nama` varchar(255) NOT NULL,
  `jenis` enum('SPP','NON-SPP') NOT NULL DEFAULT 'SPP',
  `kode` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `status` enum('AKTIF','NON AKTIF') NOT NULL DEFAULT 'AKTIF',
  `kategori` enum('Atas','Menengah','Bawah') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_biaya`),
  UNIQUE KEY `biaya_kode_unique` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `biaya`
--

LOCK TABLES `biaya` WRITE;
/*!40000 ALTER TABLE `biaya` DISABLE KEYS */;
INSERT INTO `biaya` VALUES ('1ad76f2e-c0c6-4294-bbde-ea431bae15b6','SPP','SPP','SPP-1',NULL,15000.00,'AKTIF','Bawah','2025-06-04 19:19:12','2025-06-04 20:00:27'),('24426645-7f13-4550-af6d-31f377f9f394','SERAGAM','NON-SPP','SGRM-1',NULL,200000.00,'AKTIF','Bawah','2025-06-04 20:35:04','2025-06-04 20:35:04'),('459b9b12-c8a0-4e24-9a84-69015933cc68','SERAGAM','NON-SPP','SGRM-2',NULL,225000.00,'AKTIF','Menengah','2025-06-04 20:35:29','2025-06-04 20:35:29'),('60c27fba-8342-4303-a3e0-c34d299297d7','SPP','SPP','SPP-2',NULL,20000.00,'AKTIF','Menengah','2025-06-04 20:02:11','2025-06-04 20:02:33'),('b1fa5703-bb9b-4add-85ab-7e4b082779b9','SERAGAM','NON-SPP','SGRM-3',NULL,250000.00,'AKTIF','Atas','2025-06-04 20:35:49','2025-06-04 20:35:49');
/*!40000 ALTER TABLE `biaya` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gurus`
--

DROP TABLE IF EXISTS `gurus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gurus` (
  `id_guru` char(36) NOT NULL,
  `users_id` char(36) DEFAULT NULL,
  `nip` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(255) NOT NULL,
  `status` enum('TETAP','HONOR','MAGANG') NOT NULL DEFAULT 'HONOR',
  `role_id` enum('3','4','5') NOT NULL DEFAULT '3',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_guru`),
  UNIQUE KEY `gurus_nip_unique` (`nip`),
  KEY `gurus_users_id_foreign` (`users_id`),
  CONSTRAINT `gurus_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`id_users`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gurus`
--

LOCK TABLES `gurus` WRITE;
/*!40000 ALTER TABLE `gurus` DISABLE KEYS */;
INSERT INTO `gurus` VALUES ('de17a5ce-bee2-4cb3-82a1-2783bedc98fd',NULL,'12345','SITI NURBAYA','Perempuan','Sorong','2025-06-01','Islam','TETAP','3','2025-06-04 20:04:47','2025-06-04 20:04:47');
/*!40000 ALTER TABLE `gurus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kelas` (
  `id_kelas` char(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kode_kelas` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `pengampu_kelas` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kelas`),
  UNIQUE KEY `kelas_kode_kelas_unique` (`kode_kelas`),
  UNIQUE KEY `kelas_pengampu_kelas_unique` (`pengampu_kelas`),
  CONSTRAINT `kelas_pengampu_kelas_foreign` FOREIGN KEY (`pengampu_kelas`) REFERENCES `gurus` (`id_guru`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--

LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES ('2f42fbfa-ec11-4644-bcbe-478ebcb2b0e3','1','MI-1',NULL,'de17a5ce-bee2-4cb3-82a1-2783bedc98fd','2025-06-04 20:05:13','2025-06-04 20:05:13');
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_03_19_024632_create_logins_table',1),(6,'2025_03_19_054523_create_sessions_table',2),(7,'2025_03_19_062503_create_siswas_table',3),(8,'2025_03_20_012538_update_users_table',4),(9,'2025_03_20_030008_add_status_to_siswas_table',5),(10,'2025_03_20_041015_update_status_enum_in_siswas_table',6),(11,'2025_03_20_041804_update_status_enum_remove_tidak_aktif',7),(14,'2025_03_20_042535_update_enum_status_on_siswas_table',8),(15,'2025_03_20_045420_update_status_column_on_siswas_table',8),(16,'2025_03_20_050815_update_status_column_on_siswas_table',9),(17,'2025_03_20_050831_update_status_column_on_siswas_table',9),(19,'2025_03_21_065117_add_bypass_column_to_users_table',10),(20,'2025_03_21_065835_modify_users_table',11),(21,'2025_03_21_070208_modify_users_table',11),(22,'2025_03_21_072817_create_gurus_table',12),(23,'2025_03_22_013414_add_users_id_to_siswas_table',13),(24,'2025_03_22_023010_add_jenis_kelamin__to_siswas_table',14),(25,'2025_03_22_031320_add_columns_to_gurus_table',15),(26,'2025_03_22_034723_update_users_id_foreign_key_on_siswas_table',16),(27,'2025_03_24_021702_create_kelas_table',17),(28,'2025_03_24_021732_add_id_kelas_to_siswas_table',17),(29,'2025_03_24_024239_add_pengampu_kelas_to_kelas_table',18),(30,'2025_03_24_025117_add_unique_pengampu_to_kelas',19),(31,'2025_03_24_043749_modify_kelas_column_on_siswas_table',20),(32,'2025_03_24_073105_create_biaya_table',21),(33,'2025_03_24_075433_update_status_column_in_biaya_table',22),(34,'2025_03_24_080203_update_biaya_table',23),(35,'2025_03_26_114515_create_tagihan_table',24),(36,'2025_03_27_120939_add_kelas_to_tagihan_table',25),(37,'2025_03_28_084300_add_tanggal_bayar_to_tagihan_table',26),(38,'2025_03_28_191729_remove_tanggal_bayar_from_tagihan_table',27),(39,'2025_03_28_195130_create_pembayaran_table',28),(40,'2025_03_28_195349_add_tanggal_bayar_to_pembayaran_table',29),(41,'2025_03_28_202122_add_bulan_to_pembayaran_table',30),(42,'2025_03_28_202716_add_id_users_to_pembayaran_table',31),(43,'2025_03_29_191754_add_kode_to_tagihan_and_pembayaran',32),(44,'2025_04_02_115928_change_category_column_type_in_siswas_table',33),(45,'2025_04_04_114034_remove_id_guru_from_kelas_table',34),(46,'2025_04_06_030403_create_settings_table',35),(48,'2025_04_07_120813_create_profil_sekolah_table',36),(49,'2025_04_07_133256_rename_id_to_id_settings_on_settings_table',36),(50,'2025_04_10_071425_add_tanggal_tagihan_to_tagihan_table',37),(51,'2025_04_13_205201_rename_warna_sidebar_to_tema_in_settings_table',38),(52,'2025_04_19_224346_add_ip_and_user_agent_to_users_table',39),(53,'2025_05_03_024356_add_gambar_column_to_users_table',40),(54,'2025_05_21_111458_add_columns_to_profil_sekolah_table',41),(55,'2025_05_27_104653_add_captcha_enabled_to_settings_table',42),(56,'2025_06_04_110713_add_tahun_ajar_to_tagihan_table',43),(57,'2025_06_04_110825_add_tahun_ajar_to_pembayaran_table',43),(58,'2025_06_04_111235_rename_tahun_ajar_column_in_tagihan_table',44),(59,'2025_06_04_111345_rename_tahun_ajar_column_in_pembayaran_table',44),(60,'2025_06_05_045418_change_jumlah_column_type_on_tagihan_and_biaya_tables',45),(61,'2025_06_05_051004_alter_decimal_precision_on_pembayaran_table',46);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pembayaran`
--

DROP TABLE IF EXISTS `pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pembayaran` (
  `id_pembayaran` char(36) NOT NULL,
  `id_users` char(36) DEFAULT NULL,
  `id_tagihan` char(36) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `id_siswa` char(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nis` varchar(255) NOT NULL,
  `kelas` varchar(255) NOT NULL,
  `tahun_pelajaran` varchar(255) DEFAULT NULL,
  `nama_pembayaran` varchar(255) NOT NULL,
  `jenis` enum('SPP','NON-SPP') NOT NULL,
  `bulan` varchar(255) DEFAULT NULL,
  `jumlah_tagihan` decimal(15,2) NOT NULL,
  `dibayar` decimal(15,2) NOT NULL,
  `piutang` decimal(15,2) NOT NULL,
  `status` enum('LUNAS','BELUM LUNAS') NOT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pembayaran`),
  KEY `pembayaran_id_tagihan_foreign` (`id_tagihan`),
  KEY `pembayaran_id_siswa_foreign` (`id_siswa`),
  KEY `pembayaran_id_users_foreign` (`id_users`),
  CONSTRAINT `pembayaran_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswas` (`id_siswa`) ON DELETE CASCADE,
  CONSTRAINT `pembayaran_id_users_foreign` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pembayaran`
--

LOCK TABLES `pembayaran` WRITE;
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
INSERT INTO `pembayaran` VALUES ('2c0cdbf1-88e7-40c3-9c3a-965a61f69d57','d78b44bb-58d7-4668-94b5-77f8a3fca965','5ac9ac7b-366a-4f9d-8bc8-cf2c4253f536','SPP-1','d1923174-39a1-42dd-8e67-35d0ba751904','MUHAMAT IRFAN RIFAI','148320721022','1','2025/2026','SPP','SPP','Januari',7500.00,7500.00,0.00,'LUNAS','2025-06-05','2025-06-04 20:53:12','2025-06-04 20:53:12'),('f89fac71-404b-4b54-8003-2ef300bcb6d0','d78b44bb-58d7-4668-94b5-77f8a3fca965','5ac9ac7b-366a-4f9d-8bc8-cf2c4253f536','SPP-1','d1923174-39a1-42dd-8e67-35d0ba751904','MUHAMAT IRFAN RIFAI','148320721022','1','2025/2026','SPP','SPP','Januari',15000.00,7500.00,7500.00,'BELUM LUNAS','2025-06-05','2025-06-04 20:07:13','2025-06-04 20:07:13');
/*!40000 ALTER TABLE `pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profil_sekolah`
--

DROP TABLE IF EXISTS `profil_sekolah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profil_sekolah` (
  `id_profil` char(36) NOT NULL,
  `naungan` varchar(255) NOT NULL,
  `nama_sekolah` varchar(255) NOT NULL,
  `kepala_sekolah` varchar(255) NOT NULL,
  `nip` varchar(255) NOT NULL,
  `npsn` varchar(255) DEFAULT NULL,
  `nsm` varchar(255) NOT NULL,
  `akreditasi` varchar(255) NOT NULL,
  `sk` varchar(255) NOT NULL,
  `alamat_sekolah` text NOT NULL,
  `kode_pos` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `tahun_pelajaran` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `logo_naungan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_profil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profil_sekolah`
--

LOCK TABLES `profil_sekolah` WRITE;
/*!40000 ALTER TABLE `profil_sekolah` DISABLE KEYS */;
INSERT INTO `profil_sekolah` VALUES ('e27a6e4c-39fd-403e-b5d0-fb7bbcdea006','PONDOK PESANTREN ROUDLOTUL KHUFFADZ','MI ROUDLOTUL KHUFFADZ','Muhammad Irfan','12345','60724564','111292010010','B','SK BAP-S/M : 045/BAP-SM/LL/XII/2013','Jl. Wortel Lorong Kakatua Kel. Malasom Distrik Aimas Kab.Sorong Papua Barat Daya','98418','irfanfann005@gmail.com','https://drive.google.com','0811486625','2025/2026','logo9.png','logo_naungan.png','2025-04-07 04:39:46','2025-06-04 02:04:46');
/*!40000 ALTER TABLE `profil_sekolah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('5aYUaMV8H7ZJGcS9enXxhkjjknh8esVh9HijAvme',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYzlpSXFQMUgwdTRCZU00UkF6aUZ5UFFYQ3dwVG0waWl1MW1KVjBCaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1748820753);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id_settings` char(36) NOT NULL,
  `nama_aplikasi` varchar(255) NOT NULL DEFAULT 'INFAQKU',
  `ikon_sidebar` varchar(255) NOT NULL DEFAULT 'fa-dollar-sign',
  `tema` varchar(255) NOT NULL DEFAULT 'bg-gradient-primary',
  `footer` varchar(255) NOT NULL DEFAULT 'Copyright © Pembayaran SPP',
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `captcha_enabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_settings`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('1a5d1d6b-13b5-11f0-807c-0a002700000b','SB ADMIN 2','fas fa-wallet','primary','Copyright © Pembayaran SPP 2025','assets/img/logo-login/logo.png','2025-04-06 04:01:22','2025-05-27 02:14:20',1);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `siswas`
--

DROP TABLE IF EXISTS `siswas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `siswas` (
  `id_siswa` char(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `nis` varchar(255) NOT NULL,
  `id_kelas` char(36) DEFAULT NULL,
  `users_id` char(36) DEFAULT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `kelas` varchar(255) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `status` enum('AKTIF','LULUS','PINDAHAN','KELUAR') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_siswa`),
  UNIQUE KEY `siswas_nis_unique` (`nis`),
  KEY `siswas_users_id_foreign` (`users_id`),
  KEY `siswas_id_kelas_foreign` (`id_kelas`),
  CONSTRAINT `siswas_id_kelas_foreign` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `siswas_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`id_users`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `siswas`
--

LOCK TABLES `siswas` WRITE;
/*!40000 ALTER TABLE `siswas` DISABLE KEYS */;
INSERT INTO `siswas` VALUES ('d1923174-39a1-42dd-8e67-35d0ba751904','MUHAMAT IRFAN RIFAI','Laki-laki','148320721022','2f42fbfa-ec11-4644-bcbe-478ebcb2b0e3',NULL,'Sorong','2025-06-01','1','Bawah','AKTIF','2025-06-04 20:06:04','2025-06-04 20:06:04');
/*!40000 ALTER TABLE `siswas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tagihan`
--

DROP TABLE IF EXISTS `tagihan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tagihan` (
  `id_tagihan` char(36) NOT NULL,
  `id_siswa` char(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nis` varchar(255) NOT NULL,
  `kelas` varchar(255) DEFAULT NULL,
  `tahun_pelajaran` varchar(255) DEFAULT NULL,
  `id_biaya` char(36) NOT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `nama_pembayaran` varchar(255) NOT NULL,
  `jenis` enum('SPP','NON-SPP') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `bulan` varchar(255) DEFAULT NULL,
  `status` enum('BELUM DIBAYAR','SUDAH DIBAYAR') NOT NULL DEFAULT 'BELUM DIBAYAR',
  `tanggal_tagihan` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_tagihan`),
  KEY `tagihan_id_siswa_foreign` (`id_siswa`),
  KEY `tagihan_id_biaya_foreign` (`id_biaya`),
  CONSTRAINT `tagihan_id_biaya_foreign` FOREIGN KEY (`id_biaya`) REFERENCES `biaya` (`id_biaya`) ON DELETE CASCADE,
  CONSTRAINT `tagihan_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswas` (`id_siswa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tagihan`
--

LOCK TABLES `tagihan` WRITE;
/*!40000 ALTER TABLE `tagihan` DISABLE KEYS */;
INSERT INTO `tagihan` VALUES ('5ac9ac7b-366a-4f9d-8bc8-cf2c4253f536','d1923174-39a1-42dd-8e67-35d0ba751904','MUHAMAT IRFAN RIFAI','148320721022','1','2025/2026','1ad76f2e-c0c6-4294-bbde-ea431bae15b6','SPP-1','SPP','SPP',15000.00,'Januari','SUDAH DIBAYAR','2025-06-05','2025-06-04 20:06:31','2025-06-04 20:53:12');
/*!40000 ALTER TABLE `tagihan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `bypass` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_users` char(36) NOT NULL,
  `username` varchar(255) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `role_id` enum('1','2','3','4','5') NOT NULL,
  `login_times` timestamp NULL DEFAULT NULL,
  `last_ip` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `last_seen` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_users`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('Administrator','$2y$12$j6s8axxqEFFbew.79Ii2RumexXk57TkVMlwtUj5scbuXcfi65OeT6','admin','2025-03-19 16:37:40','2025-06-04 20:52:46','d78b44bb-58d7-4668-94b5-77f8a3fca965','admin',NULL,'1','2025-06-04 17:36:52','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','2025-06-04 20:52:46');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-05 14:53:30
