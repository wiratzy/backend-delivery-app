-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 12, 2025 at 06:29 PM
-- Server version: 8.0.30
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `deliv_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `address_line` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_item.png',
  `rate` decimal(3,1) NOT NULL DEFAULT '0.0',
  `rating` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `item_category_id` bigint UNSIGNED DEFAULT NULL,
  `restaurant_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `image`, `rate`, `rating`, `type`, `location`, `price`, `item_category_id`, `restaurant_id`, `created_at`, `updated_at`) VALUES
(1, 'Nasi Goreng', '1746818338.jpg', 0.0, '0', 'Makanan', 'indonesia', 10000.00, 1, 2, '2025-05-09 12:19:00', '2025-05-09 12:19:00'),
(2, 'Coffe', '1746818571.jpg', 0.0, '0', 'Minuman', 'Jawa', 12000.00, 2, 2, '2025-05-09 12:22:51', '2025-05-09 12:22:51');

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`id`, `name`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Street Food', '1746962237.jpg', '2025-05-10 10:17:17', '2025-05-11 04:17:17'),
(2, 'Soft Drinks', '1746897692.jpg', '2025-05-10 10:21:32', '2025-05-11 04:28:18'),
(3, 'Healthy Food', '1746966048.jpg', '2025-05-11 05:20:49', '2025-05-11 05:21:21'),
(4, 'Junk Food', '1746966149.jpg', '2025-05-11 05:22:29', '2025-05-11 05:22:29');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_04_19_155655_create_products_table', 1),
(6, '2025_04_19_155705_create_addresses_table', 1),
(7, '2025_05_01_215931_add_photo_to_users_table', 1),
(8, '2025_05_03_160100_create_item_categories_table', 1),
(9, '2025_05_03_160105_create_restaurant_categories_table', 1),
(10, '2025_05_03_160113_create_restaurants_table', 1),
(11, '2025_05_03_160120_create_items_table', 1),
(12, '2025_05_04_140916_create_carts_table', 1),
(13, '2025_05_04_140950_create_orders_table', 1),
(14, '2025_05_04_141051_create_order_items_table', 1),
(15, '2025_05_04_141142_create_notifications_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','shipped','delivered') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 2, 'api_token', '599f348906b686909649e826a016546d373b940946b86374355babeda533220f', '[\"*\"]', NULL, NULL, '2025-05-08 09:05:56', '2025-05-08 09:05:56'),
(2, 'App\\Models\\User', 2, 'auth_token', '03207e5003feec19d6d44ec045b666b3c54fb418d9740886451aba89cbd62b05', '[\"*\"]', '2025-05-09 13:03:21', NULL, '2025-05-08 09:06:19', '2025-05-09 13:03:21'),
(3, 'App\\Models\\User', 1, 'auth_token', 'edbfecb557de5db643166b6ac18b1332742f064ae3bd634a84bbaebdbb8af614', '[\"*\"]', '2025-05-08 09:50:07', NULL, '2025-05-08 09:49:56', '2025-05-08 09:50:07'),
(4, 'App\\Models\\User', 3, 'auth_token', 'cc78810b4e8b237f36cdbc617fcdcd4f45b69e7517e4263b23054b4a169f530d', '[\"*\"]', '2025-05-08 09:51:43', NULL, '2025-05-08 09:50:16', '2025-05-08 09:51:43'),
(5, 'App\\Models\\User', 1, 'auth_token', 'd6f58400157344aa6d31eaf30a0e2fa30704720a486bb8da7b0d1c375593b55f', '[\"*\"]', '2025-05-08 12:33:26', NULL, '2025-05-08 09:52:44', '2025-05-08 12:33:26'),
(6, 'App\\Models\\User', 2, 'auth_token', 'fb9ce1c852781d294f9066bc248662616183a253bfe2adf3136e18d3fe9af3e2', '[\"*\"]', '2025-05-08 12:45:27', NULL, '2025-05-08 12:45:20', '2025-05-08 12:45:27'),
(7, 'App\\Models\\User', 3, 'auth_token', '42b39a8c087474051a1a6a56fb6c62ef11c8b54a7b6b45612bf17c24f242d6a1', '[\"*\"]', '2025-05-08 12:48:40', NULL, '2025-05-08 12:45:37', '2025-05-08 12:48:40'),
(8, 'App\\Models\\User', 1, 'auth_token', 'bda0ee4fa261ab4d84c0aac3ed227f541b22e9e9208b6de826471efddb4d2c38', '[\"*\"]', '2025-05-08 12:50:45', NULL, '2025-05-08 12:49:55', '2025-05-08 12:50:45'),
(9, 'App\\Models\\User', 3, 'auth_token', '60ac5ab4d7a8449d044e09b084b1fbd115bed024267ca49573a5a45e00b278ea', '[\"*\"]', '2025-05-08 12:55:30', NULL, '2025-05-08 12:50:52', '2025-05-08 12:55:30'),
(10, 'App\\Models\\User', 1, 'auth_token', '156cfc5670834f473b50b48632158d09419bb180d634cbfe1144809136da6716', '[\"*\"]', '2025-05-08 12:57:30', NULL, '2025-05-08 12:56:57', '2025-05-08 12:57:30'),
(11, 'App\\Models\\User', 3, 'auth_token', '5e86336b6a6447e1033cdd1ee042d7f20d76795f70683dfb7157152f02a6cede', '[\"*\"]', '2025-05-08 12:57:55', NULL, '2025-05-08 12:57:45', '2025-05-08 12:57:55'),
(12, 'App\\Models\\User', 3, 'auth_token', 'a0f219ca2fc5ca2b2b25bbb929aadf07b6d95e5e91314258618338f523e0ec34', '[\"*\"]', '2025-05-08 12:58:13', NULL, '2025-05-08 12:58:02', '2025-05-08 12:58:13'),
(13, 'App\\Models\\User', 1, 'auth_token', '43325604d5c019826710afcd719467da5576d12727c53a1d31e6c4fd329c15b6', '[\"*\"]', '2025-05-08 13:00:50', NULL, '2025-05-08 12:59:19', '2025-05-08 13:00:50'),
(14, 'App\\Models\\User', 3, 'auth_token', '09eb872e9e27f191848f47bbb6ddfc71709087e69c79721bc85cb074a40f8129', '[\"*\"]', '2025-05-08 14:34:21', NULL, '2025-05-08 13:01:03', '2025-05-08 14:34:21'),
(15, 'App\\Models\\User', 4, 'api_token', '8b8ee900084a7c791f39126cac0cad2f15d094ad129edc7aa171e711a832db7b', '[\"*\"]', NULL, NULL, '2025-05-09 08:01:08', '2025-05-09 08:01:08'),
(16, 'App\\Models\\User', 4, 'auth_token', '0c20e8918d08ff5d7cf441a3ff60498da2b0f4287acb872beb42fd74f4b6e256', '[\"*\"]', '2025-05-09 08:01:36', NULL, '2025-05-09 08:01:30', '2025-05-09 08:01:36'),
(17, 'App\\Models\\User', 2, 'auth_token', '76d361840effe5f46dd2e20fc59110de0857018b262a51e190a18a73d52ac137', '[\"*\"]', '2025-05-09 08:10:50', NULL, '2025-05-09 08:01:59', '2025-05-09 08:10:50'),
(18, 'App\\Models\\User', 1, 'auth_token', '0d4faf12e73c7e42deb03fc4a4408c8fec2276d04fbeb13a1c83ec917e3da1e6', '[\"*\"]', '2025-05-09 12:22:51', NULL, '2025-05-09 08:11:33', '2025-05-09 12:22:51'),
(19, 'App\\Models\\User', 4, 'auth_token', '469b37410506680787657ea3a8fd74d8762a1d7606ed4545ce60766a260e5596', '[\"*\"]', '2025-05-09 12:31:42', NULL, '2025-05-09 08:29:41', '2025-05-09 12:31:42'),
(20, 'App\\Models\\User', 2, 'auth_token', 'b14ee562a10670e4be8066df660a74fe4085a0f6d7b40e22bc492af0423fb533', '[\"*\"]', '2025-05-09 12:47:07', NULL, '2025-05-09 12:23:44', '2025-05-09 12:47:07'),
(21, 'App\\Models\\User', 4, 'auth_token', '8c8e788184423efab0fb5582d25111e42a56e75c79f5339603dd12165d7b8adb', '[\"*\"]', '2025-05-11 05:32:00', NULL, '2025-05-09 13:04:46', '2025-05-11 05:32:00'),
(22, 'App\\Models\\User', 1, 'auth_token', 'fdeeb9155418049868663991f4ebfcab0a8133cb36b6ce4d77f25cc1872f6c2f', '[\"*\"]', '2025-05-10 10:53:18', NULL, '2025-05-10 09:57:12', '2025-05-10 10:53:18'),
(23, 'App\\Models\\User', 4, 'auth_token', '9b65e52d4b990fed387758c721fd3064f8c0ccb6ccf29768aeafa850906b1536', '[\"*\"]', '2025-05-11 04:31:18', NULL, '2025-05-10 10:41:31', '2025-05-11 04:31:18'),
(24, 'App\\Models\\User', 2, 'auth_token', '799a0efd58375f98c305c8d6197634d5b9b90c14382150d0e898ca547cdf7327', '[\"*\"]', '2025-05-11 04:07:54', NULL, '2025-05-10 10:53:23', '2025-05-11 04:07:54'),
(25, 'App\\Models\\User', 1, 'auth_token', 'd9afae91e5bd3fb536b8c070d90692fb28c638dc35b4e99eac906ee865445f8e', '[\"*\"]', '2025-05-11 05:22:29', NULL, '2025-05-11 04:08:05', '2025-05-11 05:22:29'),
(26, 'App\\Models\\User', 4, 'auth_token', '6c2731ceeea28e7a16f130ffece80b60e8b16eebfb60114cfbbc223ce484a3c8', '[\"*\"]', '2025-05-11 08:09:41', NULL, '2025-05-11 08:09:40', '2025-05-11 08:09:41'),
(27, 'App\\Models\\User', 2, 'auth_token', '38a413ee3dca44ca891540a690a577f266c88ecf9faf7e5d8fa703dd1059878e', '[\"*\"]', '2025-05-11 10:44:47', NULL, '2025-05-11 08:10:38', '2025-05-11 10:44:47'),
(28, 'App\\Models\\User', 1, 'auth_token', '6afa98895ae9482bb518ce4079383b4d55de80f03aa6303e56b4fd72eb2e9cd0', '[\"*\"]', '2025-05-11 10:57:40', NULL, '2025-05-11 10:44:53', '2025-05-11 10:57:40'),
(29, 'App\\Models\\User', 1, 'auth_token', 'b10890ae73de00097bdb48c282eb35508e374c7790e67cb61a7527b90f3f475f', '[\"*\"]', NULL, NULL, '2025-05-11 12:04:10', '2025-05-11 12:04:10'),
(30, 'App\\Models\\User', 3, 'auth_token', '3ad8a00d3c74495cc1a21dd2be3733347d1fc3ec1e4ef52d321314141652bff0', '[\"*\"]', '2025-05-11 12:12:35', NULL, '2025-05-11 12:07:57', '2025-05-11 12:12:35'),
(31, 'App\\Models\\User', 2, 'auth_token', '283f3cab2efa78fa9bac847a78a66442d355f9388c1bbf3d4f9a02e9a8c1e06b', '[\"*\"]', '2025-05-11 13:36:50', NULL, '2025-05-11 13:33:20', '2025-05-11 13:36:50'),
(32, 'App\\Models\\User', 4, 'auth_token', 'c5d4bdb9cf8bb035ac39ad8443f56e40ca38ff9b1555278008f3f94f74bdc23e', '[\"*\"]', '2025-05-11 15:17:58', NULL, '2025-05-11 13:44:16', '2025-05-11 15:17:58'),
(33, 'App\\Models\\User', 1, 'auth_token', '105cf94c69f97a2601039e3e4da35e23087f9fa7f3aec34ed37ee388d413a1d0', '[\"*\"]', '2025-05-11 15:13:09', NULL, '2025-05-11 14:16:31', '2025-05-11 15:13:09'),
(34, 'App\\Models\\User', 4, 'auth_token', 'ca7c2a5396c9a64390304be1632cfa8f24016202b42a39141cce1fc495c1e351', '[\"*\"]', '2025-05-11 15:30:32', NULL, '2025-05-11 15:30:30', '2025-05-11 15:30:32'),
(35, 'App\\Models\\User', 1, 'auth_token', 'b237feefa921bd59db734d9e85a7d7303ebe8585af517936e230276156136de0', '[\"*\"]', NULL, NULL, '2025-05-11 15:31:07', '2025-05-11 15:31:07'),
(36, 'App\\Models\\User', 1, 'auth_token', '7827834f961c54770c1355d564e4084b2d5994c170667794198bc672ed714439', '[\"*\"]', NULL, NULL, '2025-05-11 15:31:31', '2025-05-11 15:31:31'),
(37, 'App\\Models\\User', 3, 'auth_token', '0e79f440c2ce16d7ba7749e7ec2a66d3abea654091ea45c3536a1210d6c79c04', '[\"*\"]', NULL, NULL, '2025-05-11 15:31:52', '2025-05-11 15:31:52'),
(38, 'App\\Models\\User', 4, 'auth_token', '98f8588763ebe2cee3860bc5f32862f4558dd4dddc736f5ae9e2b9d36722fd1a', '[\"*\"]', '2025-05-11 15:32:14', NULL, '2025-05-11 15:32:12', '2025-05-11 15:32:14'),
(39, 'App\\Models\\User', 4, 'auth_token', '675ec6b3e6d472fae7b5d9e7285d101d885b90198d9e91d53097a32449c6dffc', '[\"*\"]', '2025-05-11 15:47:48', NULL, '2025-05-11 15:32:24', '2025-05-11 15:47:48'),
(40, 'App\\Models\\User', 5, 'api_token', 'e8625b1d2c9aac72017db86ca736cb6630f4503a0d31a9c83fb38fc24d0b659e', '[\"*\"]', NULL, NULL, '2025-05-11 15:48:39', '2025-05-11 15:48:39'),
(41, 'App\\Models\\User', 5, 'auth_token', '5079d9282d939b41fb020c774c7cfd9d1c7344f38da4645e01fcf9b7704a113b', '[\"*\"]', '2025-05-11 15:49:34', NULL, '2025-05-11 15:48:54', '2025-05-11 15:49:34'),
(42, 'App\\Models\\User', 5, 'auth_token', 'dca0dbee0fee31cb3de740bfb2b36f9ec96ab9cf2babac6770437e769732664a', '[\"*\"]', '2025-05-11 15:50:54', NULL, '2025-05-11 15:50:49', '2025-05-11 15:50:54'),
(43, 'App\\Models\\User', 5, 'auth_token', '95dd7a47382707ada5b46e62b694267e582907e345eb26f7f33bc9bdafb083d9', '[\"*\"]', '2025-05-11 15:51:14', NULL, '2025-05-11 15:51:12', '2025-05-11 15:51:14'),
(44, 'App\\Models\\User', 4, 'auth_token', '4155a20db35c97b5a109e86565707bef26b95da987979bd7bb40efa49b3fb8c1', '[\"*\"]', '2025-05-11 15:53:48', NULL, '2025-05-11 15:51:25', '2025-05-11 15:53:48'),
(45, 'App\\Models\\User', 1, 'auth_token', '19a79c19eede7fc072822fead3b83d2321494a84a18505671613517565e358fb', '[\"*\"]', '2025-05-11 15:54:34', NULL, '2025-05-11 15:54:34', '2025-05-11 15:54:34'),
(46, 'App\\Models\\User', 4, 'auth_token', '20196c76ad487d422188290695493c1fc07e53f74baac73e337fd576c170e993', '[\"*\"]', '2025-05-12 10:18:11', NULL, '2025-05-12 10:17:01', '2025-05-12 10:18:11'),
(47, 'App\\Models\\User', 1, 'auth_token', 'de0a11f2916b3c8078d3be8d63ea1afeb16311d1a1f8f3a0bdb69c8309085558', '[\"*\"]', '2025-05-12 11:00:06', NULL, '2025-05-12 10:19:54', '2025-05-12 11:00:06'),
(48, 'App\\Models\\User', 1, 'auth_token', 'c0bc18636b18b608af9b204137d6edfcab1628ee472eeb8a02ce969ca50bd72d', '[\"*\"]', '2025-05-12 11:25:59', NULL, '2025-05-12 11:00:31', '2025-05-12 11:25:59'),
(49, 'App\\Models\\User', 1, 'auth_token', '81759cc7a020113a79e769c791e568017572b6b91822a413c8bd1a427753138e', '[\"*\"]', '2025-05-12 11:08:45', NULL, '2025-05-12 11:04:14', '2025-05-12 11:08:45');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_restaurant.png',
  `rate` decimal(3,1) NOT NULL DEFAULT '0.0',
  `rating` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `food_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_most_popular` tinyint(1) NOT NULL DEFAULT '0',
  `restaurant_category_id` bigint UNSIGNED DEFAULT NULL,
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `image`, `rate`, `rating`, `type`, `food_type`, `location`, `is_most_popular`, `restaurant_category_id`, `owner_id`, `created_at`, `updated_at`) VALUES
(2, 'Restaurant nya april', 'default_restaurant.png', 0.0, '0', 'Cafe', 'Drinks', 'Celeng', 0, 1, 1, '2025-05-08 12:46:45', '2025-05-08 12:46:45'),
(3, 'Restaurant nya april', 'default_restaurant.png', 0.0, '0', 'Warung Nasi Padang', 'Foods', 'karpel', 0, 1, 1, '2025-05-08 12:50:58', '2025-05-08 12:50:58'),
(4, 'Restaurant nya Acong', 'default_restaurant.png', 0.0, '0', 'Warung Nasi Mang Acong', 'Foods', 'Celeng', 0, 1, 1, '2025-05-08 12:54:24', '2025-05-08 12:54:24'),
(6, 'Cafe April Update', '1746990687.png', 0.0, '0', 'Cafe', 'Coffee', 'Margadadi', 0, 1, 3, '2025-05-08 13:59:38', '2025-05-11 12:11:27');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_categories`
--

CREATE TABLE `restaurant_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurant_categories`
--

INSERT INTO `restaurant_categories` (`id`, `name`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Makanan', '/storage/categories/restaurants/zWi2r9DrrLup9xtxtBOcMPdBaPzjndr94gNZgDyx.jpg', '2025-05-08 12:33:26', '2025-05-08 12:33:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('customer','admin','restaurant_owner','driver') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `address`, `photo`, `phone`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'Admin Address', NULL, '111111111', 'admin@example.com', '$2y$12$G/E.G2F4S5TxI1GCVPeRxe7G3Nf8AkDgMD.1VMBUBvXYwz.hJfMZS', 'admin', '2025-05-08 09:02:09', '2025-05-08 09:02:09'),
(2, 'adit', 'indramayu', 'user_2.jpg', '085398389383', 'adit@gmail.com', '$2y$12$SHbPrYCu2eFhV58g1mazw.AfNpp4IzfcHR/X9qa0XCABrcPvO9yRC', 'customer', '2025-05-08 09:05:56', '2025-05-08 09:23:36'),
(3, 'April', 'indramayu Kota', NULL, '081234567890', 'april@gmail.com', '$2y$12$xHQP6NcrI3grOuwQXg9mGeS5sKZnlbkkVLoSuJuXOhUBfHa9e.PMK', 'restaurant_owner', '2025-05-08 09:50:08', '2025-05-08 09:50:08'),
(4, 'wiranto', 'karangampel', 'user_4.jpg', '0895340891989', 'wiranto@gmail.com', '$2y$12$2iQYuneQZSuaP5VGuWRFE.JtupoFGON8NjzG7/FIg8M9x.xe7AF9q', 'customer', '2025-05-09 08:01:07', '2025-05-11 13:46:10'),
(5, 'wisnu', 'karangampel', NULL, '08549461111', 'wisnu@gmail.com', '$2y$12$awW0vImPkNjrioY2pvifP.5XFxCgUU29lOff3GTpUXV2e/PksG3qm', 'customer', '2025-05-11 15:48:39', '2025-05-11 15:48:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_item_id_foreign` (`item_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_item_category_id_foreign` (`item_category_id`),
  ADD KEY `items_restaurant_id_foreign` (`restaurant_id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_driver_id_foreign` (`driver_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurants_restaurant_category_id_foreign` (`restaurant_category_id`),
  ADD KEY `restaurants_owner_id_foreign` (`owner_id`);

--
-- Indexes for table `restaurant_categories`
--
ALTER TABLE `restaurant_categories`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `restaurant_categories`
--
ALTER TABLE `restaurant_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_item_category_id_foreign` FOREIGN KEY (`item_category_id`) REFERENCES `item_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `items_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `restaurants_restaurant_category_id_foreign` FOREIGN KEY (`restaurant_category_id`) REFERENCES `restaurant_categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
