-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 01, 2025 at 06:08 PM
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
  `quantity` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00'
) ;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `item_id`, `quantity`, `created_at`, `updated_at`, `price`) VALUES
(25, 6, 2, 1, '2025-06-13 08:39:31', '2025-06-13 08:39:31', 15000.00),
(28, 14, 2, 1, '2025-06-19 13:23:51', '2025-06-19 13:23:51', 15000.00),
(30, 22, 3, 1, '2025-06-21 02:01:29', '2025-06-21 02:01:29', 12000.00),
(31, 23, 1, 1, '2025-06-26 14:04:40', '2025-06-26 14:04:40', 15000.00);

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `name`, `phone`, `vehicle_number`, `restaurant_id`, `created_at`, `updated_at`) VALUES
(2, 'Raga', '08123456789', 'E 1234 QW', 2, '2025-07-01 03:37:00', '2025-07-01 03:37:00'),
(3, 'Adit', '08123456789', 'E 1234 QW', 2, '2025-07-01 03:39:26', '2025-07-01 03:39:26');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_item.png',
  `rate` decimal(3,1) NOT NULL DEFAULT '0.0',
  `rating` int NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) UNSIGNED NOT NULL,
  `item_category_id` bigint UNSIGNED DEFAULT NULL,
  `restaurant_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `image`, `rate`, `rating`, `type`, `price`, `item_category_id`, `restaurant_id`, `created_at`, `updated_at`) VALUES
(1, 'Smoky mushrooms', '1747167139.jpg', 0.0, 0, 'sayuran', 15000.00, 2, 2, '2025-05-13 13:12:19', '2025-05-13 13:12:19'),
(2, 'Soup Enak', '1747168579.jpg', 4.0, 12, 'Kuah', 15000.00, 2, 2, '2025-05-13 13:36:20', '2025-05-13 13:36:20'),
(3, 'Soup Enak punya sarul', '1748246629.jpeg', 0.0, 0, 'Kuah', 12000.00, 2, 3, '2025-05-26 01:03:49', '2025-05-26 01:03:49'),
(4, 'Pizza Margherita', '1748246629.jpeg', 4.5, 150, 'Italian Food', 75000.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(5, 'Burger Combo', '1748246629.jpeg', 4.2, 100, 'Fast Food', 50000.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(6, 'Coca Cola (Small)', '1748246629.jpeg', 4.0, 80, 'Soft Drink', 10000.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(7, 'Laptop X100', '1748246629.jpeg', 4.8, 200, 'Gadget', 12000000.00, 3, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(8, 'Test Item 1', '1748246629.jpeg', 4.8, 24, 'Misc', 47759.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(9, 'Test Item 2', '1748246629.jpeg', 3.9, 57, 'Misc', 33317.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(10, 'Test Item 3', '1748246629.jpeg', 4.7, 17, 'Misc', 13228.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(11, 'Test Item 4', '1748246629.jpeg', 5.0, 42, 'Misc', 38699.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(12, 'Test Item 5', '1748246629.jpeg', 4.5, 93, 'Misc', 25710.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(13, 'Test Item 6', '1748246629.jpeg', 3.6, 99, 'Misc', 30757.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(14, 'Test Item 7', '1748246629.jpeg', 3.2, 12, 'Misc', 5633.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(15, 'Test Item 8', '1748246629.jpeg', 3.5, 60, 'Misc', 34539.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(16, 'Test Item 9', '1748246629.jpeg', 4.4, 14, 'Misc', 32938.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(17, 'Test Item 10', '1748246629.jpeg', 3.1, 60, 'Misc', 12708.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(18, 'Test Item 11', '1748246629.jpeg', 3.0, 58, 'Misc', 38393.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(19, 'Test Item 12', '1748246629.jpeg', 3.3, 31, 'Misc', 10683.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(20, 'Test Item 13', '1748246629.jpeg', 3.5, 68, 'Misc', 22246.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(21, 'Test Item 14', '1748246629.jpeg', 4.7, 95, 'Misc', 37973.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(22, 'Test Item 15', '1748246629.jpeg', 4.4, 68, 'Misc', 8028.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(23, 'Test Item 16', '1748246629.jpeg', 3.2, 20, 'Misc', 33169.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(24, 'Test Item 17', '1748246629.jpeg', 3.7, 62, 'Misc', 15351.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(25, 'Test Item 18', '1748246629.jpeg', 4.4, 18, 'Misc', 32389.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(26, 'Test Item 19', '1748246629.jpeg', 3.0, 48, 'Misc', 45003.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(27, 'Test Item 20', '1748246629.jpeg', 3.6, 39, 'Misc', 9919.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(28, 'Test Item 21', '1748246629.jpeg', 3.3, 55, 'Misc', 41586.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(29, 'Test Item 22', '1748246629.jpeg', 4.3, 14, 'Misc', 42706.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(30, 'Test Item 23', '1748246629.jpeg', 4.3, 25, 'Misc', 47292.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(31, 'Test Item 24', '1748246629.jpeg', 4.5, 23, 'Misc', 30187.00, 4, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(32, 'Test Item 25', '1748246629.jpeg', 4.1, 48, 'Misc', 30455.00, 5, 2, '2025-06-21 12:57:15', '2025-06-21 12:57:15');

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
(2, 'Resto', '1747590369.jpg', '2025-05-18 10:46:09', '2025-05-18 10:46:09'),
(3, 'Electronics', '1746897692.jpg', '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(4, 'Food', '1746962237.jpg', '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(5, 'Drinks', '1746966048.jpg', '2025-06-21 12:57:15', '2025-06-21 12:57:15'),
(6, 'Snacks', '1746966149.jpg', '2025-06-21 12:57:15', '2025-06-21 12:57:15');

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
(15, '2025_05_04_141142_create_notifications_table', 1),
(16, '2025_05_12_211713_update_notifications_table', 1),
(17, '2025_05_12_211805_update_orders_status', 1),
(18, '2025_05_12_211901_add_driver_status_to_users', 1),
(19, '2025_05_12_211938_drop_products_table', 1),
(20, '2025_05_12_212125_update_rating_columns', 1),
(21, '2025_05_12_212157_add_constraints', 1),
(22, '2025_05_20_135737_update_database_schema', 2),
(23, '2025_05_22_174836_add_price_to_carts_table', 3),
(24, '2025_05_22_181615_update_price_column_to_carts_table', 4),
(25, '2025_06_06_195626_update_orders_table_for_checkout_flow', 5),
(26, '2025_06_14_170312_update_orders_for_checkout_flow', 6),
(27, '2025_06_19_133218_add_location_fields_to_users_table', 7),
(28, '2025_06_19_140559_remove_formatted_address_from_users_table', 8),
(29, '2025_06_23_074921_add_address_and_location_to_orders_table', 9),
(30, '2025_06_27_151902_update_order_status_enum_column', 10),
(31, '2025_06_27_152422_clean_old_order_status_values', 11),
(32, '2025_06_29_170124_update_status_length_on_orders_table', 12),
(35, '2025_06_30_124313_create_drivers_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `type` enum('order_placed','order_accepted','order_rejected','order_assigned','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
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
  `restaurant_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED DEFAULT NULL,
  `driver_confirmed_at` timestamp NULL DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `status` enum('menunggu_konfirmasi','diproses','diantar','diterima','berhasil','dibatalkan') COLLATE utf8mb4_unicode_ci DEFAULT 'menunggu_konfirmasi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_timeout_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `restaurant_id`, `driver_id`, `driver_confirmed_at`, `total_price`, `delivery_fee`, `payment_method`, `address`, `latitude`, `longitude`, `status`, `created_at`, `updated_at`, `order_timeout_at`) VALUES
(1, 4, 3, NULL, NULL, 41000.00, 5000.00, 'COD', NULL, NULL, NULL, 'dibatalkan', '2025-06-14 10:36:30', '2025-06-14 11:46:47', '2025-06-14 10:41:30'),
(2, 4, 3, NULL, NULL, 17000.00, 5000.00, 'COD', NULL, NULL, NULL, 'dibatalkan', '2025-06-14 12:02:19', '2025-06-14 12:08:13', '2025-06-14 12:07:19'),
(3, 4, 2, NULL, NULL, 65000.00, 5000.00, 'Cash on delivery', NULL, NULL, NULL, 'menunggu_konfirmasi', '2025-06-23 00:56:15', '2025-06-23 00:56:15', '2025-06-23 01:11:15'),
(4, 4, 2, NULL, NULL, 65000.00, 5000.00, 'Cash on delivery', 'Jl. Raya Cirebon-Indramayu', -6.9, 108.3, 'diproses', '2025-06-23 00:57:35', '2025-06-28 14:52:44', '2025-06-23 01:12:35'),
(5, 23, 2, NULL, NULL, 65000.00, 5000.00, 'Cash on delivery', 'Jl. Raya Cirebon-Indramayu', -6.9, 108.3, 'diantar', '2025-06-26 14:07:56', '2025-06-28 15:09:34', '2025-06-26 14:22:56'),
(6, 4, 2, NULL, NULL, 80000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'menunggu_konfirmasi', '2025-06-29 10:07:42', '2025-06-29 10:07:42', '2025-06-29 10:22:42'),
(7, 4, 2, NULL, NULL, 55000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'menunggu_konfirmasi', '2025-06-29 10:14:55', '2025-06-29 10:14:55', '2025-06-29 10:29:55'),
(8, 4, 2, NULL, NULL, 55000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'menunggu_konfirmasi', '2025-06-29 10:20:47', '2025-06-29 10:20:47', '2025-06-29 10:35:47'),
(9, 4, 2, NULL, NULL, 80000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'menunggu_konfirmasi', '2025-06-29 10:25:47', '2025-06-29 10:25:47', '2025-06-29 10:40:47'),
(10, 4, 2, NULL, NULL, 80000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'menunggu_konfirmasi', '2025-06-29 10:29:37', '2025-06-29 10:29:37', '2025-06-29 10:44:37'),
(11, 4, 2, NULL, NULL, 80000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'berhasil', '2025-06-29 10:40:16', '2025-06-29 12:14:04', '2025-06-29 10:55:16'),
(12, 4, 2, NULL, NULL, 80000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'menunggu_konfirmasi', '2025-06-29 10:47:33', '2025-06-29 10:47:33', '2025-06-29 11:02:33'),
(13, 4, 2, NULL, NULL, 80000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'menunggu_konfirmasi', '2025-06-29 10:51:05', '2025-06-29 10:51:05', '2025-06-29 11:06:05'),
(14, 4, 2, NULL, NULL, 80000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'menunggu_konfirmasi', '2025-06-29 10:51:40', '2025-06-29 10:51:40', '2025-06-29 11:06:40'),
(15, 4, 2, NULL, NULL, 155000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'diproses', '2025-06-29 11:24:37', '2025-06-30 05:06:52', '2025-06-29 11:39:37'),
(16, 4, 2, NULL, NULL, 230000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'menunggu_konfirmasi', '2025-06-29 11:29:38', '2025-06-29 11:29:38', '2025-06-29 11:44:38'),
(17, 4, 2, NULL, NULL, 80000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'berhasil', '2025-06-29 11:31:06', '2025-06-29 12:16:24', '2025-06-29 11:46:06'),
(18, 4, 2, NULL, NULL, 55000.00, 5000.00, 'Cash on delivery', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', -6.4642294794704, 108.44227179018, 'berhasil', '2025-06-29 11:46:09', '2025-06-29 12:14:59', '2025-06-29 12:01:09');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `price` decimal(10,2) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 3, 12000.00, '2025-06-14 10:36:30', '2025-06-14 10:36:30'),
(2, 2, 3, 1, 12000.00, '2025-06-14 12:02:19', '2025-06-14 12:02:19'),
(3, 3, 10, 2, 13228.00, '2025-06-23 00:56:15', '2025-06-23 00:56:15'),
(4, 3, 14, 1, 5633.00, '2025-06-23 00:56:15', '2025-06-23 00:56:15'),
(5, 4, 10, 2, 13228.00, '2025-06-23 00:57:35', '2025-06-23 00:57:35'),
(6, 4, 14, 1, 5633.00, '2025-06-23 00:57:35', '2025-06-23 00:57:35'),
(7, 5, 10, 2, 13228.00, '2025-06-26 14:07:56', '2025-06-26 14:07:56'),
(8, 5, 14, 1, 5633.00, '2025-06-26 14:07:56', '2025-06-26 14:07:56'),
(9, 6, 4, 1, 75000.00, '2025-06-29 10:07:42', '2025-06-29 10:07:42'),
(10, 7, 5, 1, 50000.00, '2025-06-29 10:14:55', '2025-06-29 10:14:55'),
(11, 8, 5, 1, 50000.00, '2025-06-29 10:20:47', '2025-06-29 10:20:47'),
(12, 9, 4, 1, 75000.00, '2025-06-29 10:25:47', '2025-06-29 10:25:47'),
(13, 10, 4, 1, 75000.00, '2025-06-29 10:29:37', '2025-06-29 10:29:37'),
(14, 11, 4, 1, 75000.00, '2025-06-29 10:40:16', '2025-06-29 10:40:16'),
(15, 12, 4, 1, 75000.00, '2025-06-29 10:47:33', '2025-06-29 10:47:33'),
(16, 13, 4, 1, 75000.00, '2025-06-29 10:51:05', '2025-06-29 10:51:05'),
(17, 14, 4, 1, 75000.00, '2025-06-29 10:51:40', '2025-06-29 10:51:40'),
(18, 15, 4, 2, 75000.00, '2025-06-29 11:24:37', '2025-06-29 11:24:37'),
(19, 16, 4, 3, 75000.00, '2025-06-29 11:29:38', '2025-06-29 11:29:38'),
(20, 17, 4, 1, 75000.00, '2025-06-29 11:31:06', '2025-06-29 11:31:06'),
(21, 18, 5, 1, 50000.00, '2025-06-29 11:46:09', '2025-06-29 11:46:09');

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
(1, 'App\\Models\\User', 2, 'api_token', 'c66f443863ffe19c73123ddac485f3d8b48e4335138510ebf708bab773ec0c83', '[\"*\"]', NULL, NULL, '2025-05-12 15:04:07', '2025-05-12 15:04:07'),
(2, 'App\\Models\\User', 1, 'auth_token', '333caec8083c421035c011d1c8ca0831714fb20c690f59fe5dce29e50b215038', '[\"*\"]', '2025-05-13 11:56:43', NULL, '2025-05-12 15:04:47', '2025-05-13 11:56:43'),
(3, 'App\\Models\\User', 3, 'auth_token', '62d154ca5ef5ca8818ea63c28532ec65de543547a1cd343a48a2d86f13757158', '[\"*\"]', '2025-05-13 12:48:46', NULL, '2025-05-13 12:15:28', '2025-05-13 12:48:46'),
(4, 'App\\Models\\User', 1, 'auth_token', 'd8e9cfa66bc39e5cc841f57853b53149924bdb8ec557b404492a57f6250ad6a4', '[\"*\"]', '2025-05-13 12:57:44', NULL, '2025-05-13 12:56:07', '2025-05-13 12:57:44'),
(5, 'App\\Models\\User', 3, 'auth_token', '75698d8f0e97d57961b7a47eb384141229f8a3267d31e362449f7e625c904a04', '[\"*\"]', '2025-05-15 09:36:08', NULL, '2025-05-13 13:07:28', '2025-05-15 09:36:08'),
(6, 'App\\Models\\User', 4, 'api_token', '3cec06f35bd020ad1fb9ba70090c1f647d44f2eaa4391965480aa680c727b121', '[\"*\"]', '2025-05-18 10:36:30', NULL, '2025-05-13 13:38:37', '2025-05-18 10:36:30'),
(7, 'App\\Models\\User', 2, 'auth_token', '487364b107902ecd5371f3677821eddbc9f725b355c537f51424abf3ae773873', '[\"*\"]', '2025-05-18 10:42:49', NULL, '2025-05-15 09:36:16', '2025-05-18 10:42:49'),
(8, 'App\\Models\\User', 1, 'auth_token', '592ad8562aaeb41139af4b16a8770f4df1b3928e3a4aae5301e0361c86faa530', '[\"*\"]', '2025-05-18 10:46:09', NULL, '2025-05-18 10:44:52', '2025-05-18 10:46:09'),
(9, 'App\\Models\\User', 4, 'auth_token', '36425783f654bf7038c729e61741e6f9405d2bff46ea3db2db3974644afee81e', '[\"*\"]', '2025-05-18 14:17:54', NULL, '2025-05-18 11:04:45', '2025-05-18 14:17:54'),
(10, 'App\\Models\\User', 2, 'auth_token', '99df39eb0c40fb44066fd3ddfa1f5125e4675fa1980f5a2c2e4352d3dddae07d', '[\"*\"]', '2025-05-18 14:17:41', NULL, '2025-05-18 11:07:35', '2025-05-18 14:17:41'),
(11, 'App\\Models\\User', 2, 'auth_token', '59b21b5c46b1adf166034775f5a345c4aaea491a1a6a88a23625e7d52e894e66', '[\"*\"]', '2025-05-19 00:59:43', NULL, '2025-05-19 00:55:05', '2025-05-19 00:59:43'),
(12, 'App\\Models\\User', 4, 'auth_token', '35779930bfd9578825c490e0b831a414642ee4a9d6b2fd759f096fd9a83c20c5', '[\"*\"]', '2025-05-20 11:15:30', NULL, '2025-05-20 07:08:49', '2025-05-20 11:15:30'),
(13, 'App\\Models\\User', 2, 'auth_token', 'e026ad6c6e99b9a0705af94c0ac6588f404250028d7a1246a69debdac6d887a2', '[\"*\"]', '2025-05-22 11:25:11', NULL, '2025-05-20 07:16:54', '2025-05-22 11:25:11'),
(14, 'App\\Models\\User', 4, 'auth_token', '59bf5855ba9ffefac45a15348ee326a64552b324863123983f3d0e58402c51d0', '[\"*\"]', '2025-05-20 16:00:52', NULL, '2025-05-20 11:16:33', '2025-05-20 16:00:52'),
(15, 'App\\Models\\User', 4, 'auth_token', '5e512adedaf51d037e75f5d0eda841a4ec13e1d402df95e13655c448bec7733e', '[\"*\"]', '2025-06-02 10:25:57', NULL, '2025-05-20 16:01:25', '2025-06-02 10:25:57'),
(16, 'App\\Models\\User', 1, 'auth_token', '2527367849cd0bee051d4f3710648cb7339bf1c4f202ac429eebc727ec45d455', '[\"*\"]', '2025-05-25 23:08:45', NULL, '2025-05-25 23:08:24', '2025-05-25 23:08:45'),
(17, 'App\\Models\\User', 2, 'auth_token', '6a9a54ad4da8d2524029dd88e01d53e528f7ceea5775131e35898ea347d7e3d0', '[\"*\"]', '2025-05-26 00:51:52', NULL, '2025-05-25 23:14:25', '2025-05-26 00:51:52'),
(18, 'App\\Models\\User', 1, 'auth_token', '29ee60e4929a5ef9c2ad1d4a4db011d7250e89c9a096e109dfaa2703efb2461b', '[\"*\"]', '2025-05-26 00:52:23', NULL, '2025-05-26 00:52:01', '2025-05-26 00:52:23'),
(19, 'App\\Models\\User', 5, 'auth_token', '1403d75c44cc6f56b2e38d70f85daf3075bfd3588bfe485c6ebdf5735c16fe60', '[\"*\"]', '2025-05-26 01:03:14', NULL, '2025-05-26 00:52:40', '2025-05-26 01:03:14'),
(20, 'App\\Models\\User', 2, 'auth_token', '9320d2b9778d19c8b5e1ba628889444fb1121bdebb52fee42a1624640596f687', '[\"*\"]', '2025-05-26 01:03:23', NULL, '2025-05-26 01:03:20', '2025-05-26 01:03:23'),
(21, 'App\\Models\\User', 5, 'auth_token', '33e048d2e0df4fbe015ff90aef26f7164ddc98ffa03e8cb117e31e4129a06241', '[\"*\"]', '2025-05-26 01:03:49', NULL, '2025-05-26 01:03:40', '2025-05-26 01:03:49'),
(22, 'App\\Models\\User', 2, 'auth_token', 'efcad55598fb3ee4999694fb2bc7aba26c8a99a73abcc4732a49a2766b49d381', '[\"*\"]', '2025-05-26 01:30:11', NULL, '2025-05-26 01:05:45', '2025-05-26 01:30:11'),
(23, 'App\\Models\\User', 2, 'auth_token', 'a28ec853452f2e9ed80b07e8972884d5ded81cae92bc814c5675851cdcda11ce', '[\"*\"]', '2025-05-28 09:00:51', NULL, '2025-05-28 07:37:01', '2025-05-28 09:00:51'),
(24, 'App\\Models\\User', 4, 'auth_token', '808da6e4781a3d377d496fc7094e1581e3a57ba589e75e85aeb18c8c48792170', '[\"*\"]', '2025-06-04 04:34:18', NULL, '2025-05-28 09:01:38', '2025-06-04 04:34:18'),
(25, 'App\\Models\\User', 4, 'auth_token', 'b24ba6f705ecb46aeffe3ff411b895aa65b23ca08eb681a51e24e5d195d6c724', '[\"*\"]', '2025-06-04 11:22:48', NULL, '2025-06-04 03:24:08', '2025-06-04 11:22:48'),
(26, 'App\\Models\\User', 5, 'auth_token', '889fffab992c15794a6ee51598ff94b8fd58be296ea8ba290be6b128b7f52152', '[\"*\"]', NULL, NULL, '2025-06-04 04:36:56', '2025-06-04 04:36:56'),
(27, 'App\\Models\\User', 3, 'auth_token', 'f3598551a36813fce84c7e929f63d597fe5270486e19670795925b681e9ee58f', '[\"*\"]', '2025-06-04 04:59:45', NULL, '2025-06-04 04:37:08', '2025-06-04 04:59:45'),
(28, 'App\\Models\\User', 4, 'auth_token', '7f7ac57aaf632c4aa3c1859ac2228ee0e87c841d37a0faa089472074bac9997a', '[\"*\"]', '2025-06-04 04:59:57', NULL, '2025-06-04 04:59:51', '2025-06-04 04:59:57'),
(29, 'App\\Models\\User', 4, 'auth_token', 'bb1ccf1d57e3d796b1210d4af3fdcf88974f81b8086e8cddb890ded4fa3636e5', '[\"*\"]', '2025-06-04 11:39:17', NULL, '2025-06-04 11:39:16', '2025-06-04 11:39:17'),
(30, 'App\\Models\\User', 1, 'auth_token', 'feaa5faa5c8d0f329c02428d481187bb4c122e470e5d17f22452975d6cf07b66', '[\"*\"]', NULL, NULL, '2025-06-04 11:39:36', '2025-06-04 11:39:36'),
(31, 'App\\Models\\User', 4, 'auth_token', '3d4ef115c5fed8d608cf8dc28ab021911a66ca34c5e282f0499822ddfaed42b7', '[\"*\"]', '2025-06-06 12:42:02', NULL, '2025-06-04 11:40:20', '2025-06-06 12:42:02'),
(32, 'App\\Models\\User', 4, 'auth_token', '46645ae53e2ab56be6a544d1eec437103d1622d9217e5cc2bf7bcaec030ef1e2', '[\"*\"]', '2025-06-06 11:59:04', NULL, '2025-06-06 11:42:12', '2025-06-06 11:59:04'),
(33, 'App\\Models\\User', 4, 'auth_token', 'b9cc980fdc1bfec6fae9cb2a30c6fe41548413849e5de971d7fa5159b59751d9', '[\"*\"]', '2025-06-06 12:02:16', NULL, '2025-06-06 11:51:10', '2025-06-06 12:02:16'),
(34, 'App\\Models\\User', 4, 'auth_token', 'ed15a0c63852663e1fac5f7b144c68147c4f3331290677ca153a70d84b35e22b', '[\"*\"]', '2025-06-06 12:18:39', NULL, '2025-06-06 12:00:35', '2025-06-06 12:18:39'),
(35, 'App\\Models\\User', 4, 'auth_token', '316da4315e7787a3695c95da77c697d4aa36795820a6c40ae16f440caf3d0ec1', '[\"*\"]', '2025-06-06 12:20:35', NULL, '2025-06-06 12:19:44', '2025-06-06 12:20:35'),
(36, 'App\\Models\\User', 4, 'auth_token', 'fd7d320c71396329456ecc904acced7fdcb6d91a0fa35681e70cdbd102db860b', '[\"*\"]', '2025-06-06 12:26:43', NULL, '2025-06-06 12:25:20', '2025-06-06 12:26:43'),
(37, 'App\\Models\\User', 4, 'auth_token', 'ba8e6321c203e59f4f2ece30c6ad640e9fc394de9266765626c2ca2bd682452e', '[\"*\"]', '2025-06-13 08:10:13', NULL, '2025-06-13 03:42:38', '2025-06-13 08:10:13'),
(38, 'App\\Models\\User', 4, 'auth_token', 'acf9b2179995eefccd131ca3ed3b1dd759a89f95303d0cea151c04b39219850b', '[\"*\"]', '2025-06-17 11:12:29', NULL, '2025-06-13 06:29:09', '2025-06-17 11:12:29'),
(39, 'App\\Models\\User', 6, 'api_token', 'd527b90c66f61498ada4ec897ee8b696343b65b64ad0bf60530ccf4696f37e8c', '[\"*\"]', '2025-06-13 08:23:33', NULL, '2025-06-13 08:11:21', '2025-06-13 08:23:33'),
(40, 'App\\Models\\User', 4, 'auth_token', '92e9ce62ff8040b4e9f991a91d5c0b8804d8056400b9b9e27752803cd9f80c1c', '[\"*\"]', '2025-06-13 08:24:21', NULL, '2025-06-13 08:24:10', '2025-06-13 08:24:21'),
(41, 'App\\Models\\User', 6, 'auth_token', '3b6cdf4d66a865a15cb8baf217f1acc3351dfb283388ba820e81e15f6907e401', '[\"*\"]', '2025-06-13 08:39:35', NULL, '2025-06-13 08:24:45', '2025-06-13 08:39:35'),
(42, 'App\\Models\\User', 3, 'auth_token', '9a8455fc1e49c346d9112ca1db1219b56cf509e16e97443d1f1ca48ce11cb434', '[\"*\"]', '2025-06-17 09:18:48', NULL, '2025-06-17 09:10:43', '2025-06-17 09:18:48'),
(43, 'App\\Models\\User', 3, 'auth_token', 'fabd4463a5227cb1039aca575977b48977423d347079bedb16158c8144b331fd', '[\"*\"]', '2025-06-17 09:38:30', NULL, '2025-06-17 09:19:11', '2025-06-17 09:38:30'),
(44, 'App\\Models\\User', 4, 'auth_token', '6d4e3d8fe1c659f13c4e0fa765a92cec658f64f36b2a0b10238134d7f0f5b557', '[\"*\"]', '2025-06-17 11:12:41', NULL, '2025-06-17 11:09:01', '2025-06-17 11:12:41'),
(45, 'App\\Models\\User', 3, 'auth_token', '624a0a31d23e634826ae0909c584ffd04324814dbc8ded2146983dfaeb752821', '[\"*\"]', '2025-06-17 11:20:32', NULL, '2025-06-17 11:14:47', '2025-06-17 11:20:32'),
(46, 'App\\Models\\User', 3, 'auth_token', 'd1ef258db6b92ec8ed21986be32d8c79774ee7929a146be0c31bc5876dce6201', '[\"*\"]', '2025-06-17 12:04:11', NULL, '2025-06-17 11:20:40', '2025-06-17 12:04:11'),
(47, 'App\\Models\\User', 3, 'auth_token', '0af0a1b5b16f87e16fd821a685dc0b6cf6b52a198f81f809505186e912e232ec', '[\"*\"]', '2025-06-17 20:42:02', NULL, '2025-06-17 11:41:54', '2025-06-17 20:42:02'),
(48, 'App\\Models\\User', 4, 'auth_token', '3bf9e5e268ec173fa5b93d7d695e73f75e086826ebccf2c28c6211a3868c3b34', '[\"*\"]', '2025-06-17 20:45:01', NULL, '2025-06-17 20:44:23', '2025-06-17 20:45:01'),
(49, 'App\\Models\\User', 4, 'auth_token', '9fb76a2904aba30bc5ce56e789136979219a412622a41909a139fa7060b6def1', '[\"*\"]', NULL, NULL, '2025-06-18 02:10:42', '2025-06-18 02:10:42'),
(50, 'App\\Models\\User', 4, 'auth_token', '177caef2ad7b58bc6a653cf458e12f3d78491bed41943f6b17e4dd4e00d0793a', '[\"*\"]', '2025-06-18 02:12:53', NULL, '2025-06-18 02:12:15', '2025-06-18 02:12:53'),
(51, 'App\\Models\\User', 3, 'auth_token', 'd937b4e35d410c0ded77f2166a96fdf5d889c5d8b356d2f1c4783bb0e090edf2', '[\"*\"]', NULL, NULL, '2025-06-18 02:14:28', '2025-06-18 02:14:28'),
(52, 'App\\Models\\User', 7, 'api_token', '801d75f71e7ee2ef2020e4ed9468dbad1b4ac621c16dff2c75264fbc971b7ac6', '[\"*\"]', '2025-06-19 12:10:24', NULL, '2025-06-19 11:42:46', '2025-06-19 12:10:24'),
(53, 'App\\Models\\User', 8, 'api_token', 'b4b9ad8172d695ea98937c9e2b0966bb9308b44d28bae4c88b21b173a8c49a56', '[\"*\"]', NULL, NULL, '2025-06-19 12:24:52', '2025-06-19 12:24:52'),
(54, 'App\\Models\\User', 9, 'api_token', '18af248b47f6a43d89c41c20debce7778ae32efb8d6a0a94bb7658c37f025f24', '[\"*\"]', NULL, NULL, '2025-06-19 12:27:28', '2025-06-19 12:27:28'),
(55, 'App\\Models\\User', 10, 'api_token', 'cca00b427077a185db14fd12ce6fc770be2aa9b57c44b63667fc086bd320e5b9', '[\"*\"]', NULL, NULL, '2025-06-19 12:28:41', '2025-06-19 12:28:41'),
(56, 'App\\Models\\User', 14, 'api_token', 'f3c62aae8a880f11c585f2c0ccce140a03aa2bcc7647b3a36d8582a20faa2b98', '[\"*\"]', '2025-06-19 13:23:53', NULL, '2025-06-19 13:23:27', '2025-06-19 13:23:53'),
(57, 'App\\Models\\User', 15, 'api_token', 'e740b20ef01f3610e8a288884328e2d58c1a64289e392bd40530be03bc613dee', '[\"*\"]', NULL, NULL, '2025-06-19 13:24:19', '2025-06-19 13:24:19'),
(58, 'App\\Models\\User', 16, 'api_token', '4eff3dbc7413ff55b01d9014d88fc5e94ca2840ec77472b963c955ca2c153d4d', '[\"*\"]', NULL, NULL, '2025-06-19 13:41:27', '2025-06-19 13:41:27'),
(59, 'App\\Models\\User', 17, 'api_token', '3681c3171910e62c4558ee0145240a913e074fe091ad57cb35428408543b7dcd', '[\"*\"]', NULL, NULL, '2025-06-19 13:42:22', '2025-06-19 13:42:22'),
(60, 'App\\Models\\User', 18, 'api_token', 'aebb1386edf06ba36277836b67c3ad418cf5d337ea831cdbbeede37cbb96e847', '[\"*\"]', NULL, NULL, '2025-06-19 13:47:41', '2025-06-19 13:47:41'),
(61, 'App\\Models\\User', 19, 'api_token', '940596a608a183ecca0265fb05d063cc5c66827f48717bbef0d9be9d050440d7', '[\"*\"]', NULL, NULL, '2025-06-19 13:50:53', '2025-06-19 13:50:53'),
(62, 'App\\Models\\User', 20, 'api_token', '02cca894a7100a742bb4466cf58db0750adacefcdc304aabca1796243e595205', '[\"*\"]', NULL, NULL, '2025-06-19 13:51:39', '2025-06-19 13:51:39'),
(63, 'App\\Models\\User', 21, 'api_token', 'b8b29ddeac48e57d61c7f67b682f8298aae1023efdba773b7b357584a0a23582', '[\"*\"]', '2025-06-19 13:55:14', NULL, '2025-06-19 13:55:14', '2025-06-19 13:55:14'),
(64, 'App\\Models\\User', 22, 'api_token', '0fc23dbb9a42dfe1af4066724f87d3cb184c67810fe723a185e0800a4def21f4', '[\"*\"]', '2025-06-21 05:30:21', NULL, '2025-06-21 01:06:32', '2025-06-21 05:30:21'),
(65, 'App\\Models\\User', 2, 'auth_token', '6cdce459549bf6a403231a4913bfea31b4a047ddef0d2e914f1ae5af5dfb4791', '[\"*\"]', '2025-06-21 05:36:47', NULL, '2025-06-21 05:05:17', '2025-06-21 05:36:47'),
(66, 'App\\Models\\User', 5, 'auth_token', '65b1ca09ddf6b6a7ba16838075fe7687dc7477612897dc3829f46ed447dae531', '[\"*\"]', NULL, NULL, '2025-06-21 05:31:51', '2025-06-21 05:31:51'),
(67, 'App\\Models\\User', 4, 'auth_token', '01a290196d47f89432b37a0dd12fa33ad43b7b0581c0a14d64b79ee381f8bcf4', '[\"*\"]', '2025-06-21 05:42:07', NULL, '2025-06-21 05:32:05', '2025-06-21 05:42:07'),
(68, 'App\\Models\\User', 4, 'auth_token', '44393d00bd1374163a5293761bff04f30f7829b9ff969927197f4cb56e04cb87', '[\"*\"]', '2025-06-21 13:26:09', NULL, '2025-06-21 05:40:21', '2025-06-21 13:26:09'),
(69, 'App\\Models\\User', 4, 'auth_token', 'b7bf2e5f07d05f3a15d433df8f3d282d7bb35645b40dbf4a6d5830815b4ac7f4', '[\"*\"]', '2025-06-21 06:07:34', NULL, '2025-06-21 05:42:22', '2025-06-21 06:07:34'),
(70, 'App\\Models\\User', 4, 'auth_token', '7013f42ff9c55fffe2f1c9f77f6e0e6a0b1ca483efbf41f93a994f66d550fba5', '[\"*\"]', NULL, NULL, '2025-06-21 14:01:04', '2025-06-21 14:01:04'),
(71, 'App\\Models\\User', 4, 'auth_token', 'ca7f7e1f34deb997b9b8bfa50deac00efc2c3980da7a9e254ebec1d70ff9962a', '[\"*\"]', '2025-06-23 01:32:26', NULL, '2025-06-21 14:03:26', '2025-06-23 01:32:26'),
(72, 'App\\Models\\User', 4, 'auth_token', 'c5b1abf34ee166937b328abf1bd6031d5cc4f51521bbe344cdaab239049ce6b1', '[\"*\"]', '2025-06-23 00:57:35', NULL, '2025-06-23 00:55:35', '2025-06-23 00:57:35'),
(73, 'App\\Models\\User', 3, 'auth_token', '22da5785d7fbd7bb2d1481a1e2e3bf3bceb7387731ed7575ea1c945f1fc9fc65', '[\"*\"]', '2025-06-23 02:08:49', NULL, '2025-06-23 01:33:20', '2025-06-23 02:08:49'),
(74, 'App\\Models\\User', 3, 'auth_token', 'a12899a0d7d1773115eca8dafe62317990dabbf1f3d5b9b77a0a87247624e8f3', '[\"*\"]', '2025-06-23 02:32:01', NULL, '2025-06-23 02:14:07', '2025-06-23 02:32:01'),
(75, 'App\\Models\\User', 3, 'auth_token', '389c87b6036245b0b5c06f291c6d728654827f1fc86bc489daea90c85bbbd5a0', '[\"*\"]', NULL, NULL, '2025-06-23 02:33:15', '2025-06-23 02:33:15'),
(76, 'App\\Models\\User', 3, 'auth_token', '95fe0ec62726fd4e0d3b715efdec1c83d8e414c1a2ac2d246594014d57b03a99', '[\"*\"]', '2025-06-23 02:52:06', NULL, '2025-06-23 02:51:15', '2025-06-23 02:52:06'),
(77, 'App\\Models\\User', 3, 'auth_token', 'a4cdd96793518dbc0d2add2714f880459774e5080768d859fc3bea4bfa605232', '[\"*\"]', NULL, NULL, '2025-06-23 02:53:43', '2025-06-23 02:53:43'),
(78, 'App\\Models\\User', 3, 'auth_token', 'cc2edc51ab3bc82405c79e98a529fc0d0be782504994f9f9c8d69e28064ec7cb', '[\"*\"]', NULL, NULL, '2025-06-25 09:49:25', '2025-06-25 09:49:25'),
(79, 'App\\Models\\User', 3, 'auth_token', 'f2faa54aea02834481412318cebdcbe57b60567921ce1d62fdb8b29c39763fa4', '[\"*\"]', '2025-06-26 07:27:53', NULL, '2025-06-25 10:08:10', '2025-06-26 07:27:53'),
(80, 'App\\Models\\User', 3, 'auth_token', '51bf2005a660d75e20cfc9176cbd9d56ec183981719de3498e450807901b1cb7', '[\"*\"]', '2025-06-26 06:25:24', NULL, '2025-06-26 05:51:54', '2025-06-26 06:25:24'),
(81, 'App\\Models\\User', 3, 'auth_token', 'a93827bb405d85baf29955ff93df9b80a6fe4c14437437ccd522a9b0695d8606', '[\"*\"]', NULL, NULL, '2025-06-26 06:02:28', '2025-06-26 06:02:28'),
(82, 'App\\Models\\User', 4, 'auth_token', 'b6a1b596c6c82000f175495cb0010f46f8c31149616532ece2f2e984bb6150eb', '[\"*\"]', '2025-06-26 10:35:07', NULL, '2025-06-26 06:03:33', '2025-06-26 10:35:07'),
(83, 'App\\Models\\User', 3, 'auth_token', '3fa62862e79201a27dfceefef62d9a2276cf8ed4fb2808090e2aa3d79b84a80f', '[\"*\"]', NULL, NULL, '2025-06-26 06:56:59', '2025-06-26 06:56:59'),
(84, 'App\\Models\\User', 3, 'auth_token', 'c35163eb345ca788ed0715ee14e15271f4b942c77a62701058e4dfef8eb241f5', '[\"*\"]', '2025-06-26 07:52:03', NULL, '2025-06-26 07:46:04', '2025-06-26 07:52:03'),
(85, 'App\\Models\\User', 3, 'auth_token', 'd795a9fcfcdd3ddcc357032d9805ac0c2449f3ce84480e8678783698e07526cb', '[\"*\"]', '2025-06-26 07:54:00', NULL, '2025-06-26 07:52:22', '2025-06-26 07:54:00'),
(86, 'App\\Models\\User', 3, 'auth_token', 'b46509081afb65a731c18c82945037a35046b5c1c8bc96f559786bd6d8c2415b', '[\"*\"]', NULL, NULL, '2025-06-26 08:22:38', '2025-06-26 08:22:38'),
(87, 'App\\Models\\User', 3, 'auth_token', '688437bac813ebca9629881c561a90177f862141485beeaeddeb4ec381d84b4f', '[\"*\"]', NULL, NULL, '2025-06-26 08:23:10', '2025-06-26 08:23:10'),
(88, 'App\\Models\\User', 3, 'auth_token', '674e9addf0aaf3f8147d3684a4e6646abbdf784ddb31a97cfead5ee418733805', '[\"*\"]', NULL, NULL, '2025-06-26 08:48:34', '2025-06-26 08:48:34'),
(89, 'App\\Models\\User', 3, 'auth_token', 'b1bc56022a341f4ba8fb9d0ac2221baa23fec5ec40a22d8d5692ed91337dd892', '[\"*\"]', NULL, NULL, '2025-06-26 08:58:05', '2025-06-26 08:58:05'),
(90, 'App\\Models\\User', 3, 'auth_token', '3d28e9086e8aeaa36406a64df218976d22961da3dc4371a5223c36159f77233b', '[\"*\"]', NULL, NULL, '2025-06-26 09:16:39', '2025-06-26 09:16:39'),
(91, 'App\\Models\\User', 3, 'auth_token', 'd12ad133d0789f35a7fc850b47e5bd66c65952549171493463c2e46d094f179e', '[\"*\"]', '2025-06-26 09:41:09', NULL, '2025-06-26 09:39:38', '2025-06-26 09:41:09'),
(92, 'App\\Models\\User', 4, 'auth_token', '9563cc879c67ff0870c2c43ccca573c6836da97c5e2efd6d592ba3eb2bd3b794', '[\"*\"]', '2025-06-26 10:00:03', NULL, '2025-06-26 09:59:09', '2025-06-26 10:00:03'),
(93, 'App\\Models\\User', 3, 'auth_token', 'ca171ccc4fec4fd29701c5391cfe41050bde1769e6138792b30badb88470f6f5', '[\"*\"]', '2025-06-26 10:02:27', NULL, '2025-06-26 10:00:50', '2025-06-26 10:02:27'),
(94, 'App\\Models\\User', 3, 'auth_token', '4c7016fa1c0348da6389792d86cbd7b64af372e53e06e62911aa4e04447b78f1', '[\"*\"]', NULL, NULL, '2025-06-26 10:18:35', '2025-06-26 10:18:35'),
(95, 'App\\Models\\User', 23, 'api_token', 'e2b44b68ef3227685872b95a4d3555adf4e45666e14126f28f28a241eb35cd45', '[\"*\"]', '2025-06-28 14:19:13', NULL, '2025-06-26 10:37:58', '2025-06-28 14:19:13'),
(96, 'App\\Models\\User', 3, 'auth_token', 'c5eeb4a3ca29b25feaa91aff8601043ba60a3887a070498a4bcb2b040b4b2315', '[\"*\"]', '2025-06-26 11:33:59', NULL, '2025-06-26 10:44:47', '2025-06-26 11:33:59'),
(97, 'App\\Models\\User', 3, 'auth_token', '6ea2b6189606ab497b65f321155ee70554897607a03f4226b41dad92cb1b63fc', '[\"*\"]', '2025-06-26 11:55:52', NULL, '2025-06-26 11:09:11', '2025-06-26 11:55:52'),
(98, 'App\\Models\\User', 4, 'auth_token', '7efe8c7930f9ad65e695632327b5df9cf06f2ec7da02820e3715fcaed95b243a', '[\"*\"]', NULL, NULL, '2025-06-26 11:35:20', '2025-06-26 11:35:20'),
(99, 'App\\Models\\User', 4, 'auth_token', '407e9cb29986009f63f771f7241300a0c70f5d3f3454b7c8b3a67184be7f62ef', '[\"*\"]', '2025-06-26 11:37:25', NULL, '2025-06-26 11:35:32', '2025-06-26 11:37:25'),
(100, 'App\\Models\\User', 4, 'auth_token', '9eb4f66dbf826e33aca83b6b3c35b739d8e57fdfddb7a38f5a43bb3050045cd3', '[\"*\"]', NULL, NULL, '2025-06-26 11:37:44', '2025-06-26 11:37:44'),
(101, 'App\\Models\\User', 4, 'auth_token', 'a001a8bba23c9f15d25f7ba489e6f0bb9f3099347d660edf8b411b14beb2680d', '[\"*\"]', '2025-06-26 11:43:37', NULL, '2025-06-26 11:37:59', '2025-06-26 11:43:37'),
(102, 'App\\Models\\User', 4, 'auth_token', '89d639b5e3158e02d633ee6d71187b20d9ae885f1796f7b3b9d4c0c839d86fbb', '[\"*\"]', '2025-06-26 11:44:36', NULL, '2025-06-26 11:44:36', '2025-06-26 11:44:36'),
(103, 'App\\Models\\User', 3, 'auth_token', '5e0329e54c70046b799e144e4ba7b3a32915a8befba2b3afbe41fa5fbaf00ddd', '[\"*\"]', '2025-06-29 12:16:32', NULL, '2025-06-26 11:49:36', '2025-06-29 12:16:32'),
(104, 'App\\Models\\User', 23, 'auth_token', '479dd1035474407ba7aaa0c582274fb20b4ef201e0f30ca2aa55d76030dca15f', '[\"*\"]', '2025-06-26 14:07:56', NULL, '2025-06-26 14:06:04', '2025-06-26 14:07:56'),
(105, 'App\\Models\\User', 3, 'auth_token', 'f564336018c0e830564fae2f390dc35212c8301afdc583b5164e4f55ad7db1af', '[\"*\"]', '2025-06-28 12:28:11', NULL, '2025-06-26 14:08:14', '2025-06-28 12:28:11'),
(106, 'App\\Models\\User', 3, 'auth_token', '7f3288134227d39b9eba7455f9274e65311aab784cd229ffa2acf333ed813838', '[\"*\"]', '2025-06-28 12:28:36', NULL, '2025-06-28 12:28:28', '2025-06-28 12:28:36'),
(107, 'App\\Models\\User', 5, 'auth_token', '417bc3a1fdaf253476807c9dbc93d4861bc30c7741da3ff07646152214fb9054', '[\"*\"]', '2025-06-28 12:29:12', NULL, '2025-06-28 12:29:07', '2025-06-28 12:29:12'),
(108, 'App\\Models\\User', 23, 'auth_token', '96cc43bfac36e993ddb28e483a7a9788ac31b7cfcb3817eff3fd581bd27f85ac', '[\"*\"]', NULL, NULL, '2025-06-28 12:30:54', '2025-06-28 12:30:54'),
(109, 'App\\Models\\User', 3, 'auth_token', 'e1b12c0062c9e020eb74348836689465621151c1967229a6aac0346b9a27b381', '[\"*\"]', '2025-06-29 07:17:42', NULL, '2025-06-28 12:53:35', '2025-06-29 07:17:42'),
(110, 'App\\Models\\User', 3, 'auth_token', '55f19f95e31fa7d0630faac58dc3e3623027f35656c930a074eda9b1b557a755', '[\"*\"]', '2025-06-28 15:09:34', NULL, '2025-06-28 14:20:21', '2025-06-28 15:09:34'),
(111, 'App\\Models\\User', 5, 'auth_token', 'c62dfbea70ffe3a208c5645a4e81cfbe82f8df25842fc7e5a91eec33cbb309dc', '[\"*\"]', '2025-06-28 15:21:03', NULL, '2025-06-28 15:15:08', '2025-06-28 15:21:03'),
(112, 'App\\Models\\User', 4, 'auth_token', '3dc429c2e947b3c64708a6e82382c42aaba6179578b860b9e1eccf977d66967b', '[\"*\"]', '2025-06-29 06:50:30', NULL, '2025-06-28 15:21:19', '2025-06-29 06:50:30'),
(113, 'App\\Models\\User', 4, 'auth_token', '506107df497a5ccabd95f7be13cfa5d3730924081004d6e5cb366d87a3d7ca41', '[\"*\"]', '2025-06-29 09:36:27', NULL, '2025-06-29 06:53:57', '2025-06-29 09:36:27'),
(114, 'App\\Models\\User', 23, 'auth_token', 'd4eb462faa3efc890877092b9a0bcbb88522ff7bb2ae74dafc16d262694254c7', '[\"*\"]', '2025-06-29 07:35:45', NULL, '2025-06-29 07:17:49', '2025-06-29 07:35:45'),
(115, 'App\\Models\\User', 4, 'auth_token', '00e77de693c807d4cd8d83f85317021e55f70dff29ff8d7c1fa3187578a2fab6', '[\"*\"]', '2025-06-29 10:14:30', NULL, '2025-06-29 07:36:12', '2025-06-29 10:14:30'),
(116, 'App\\Models\\User', 23, 'auth_token', '940f19937b0017f4ab4a80c34a12ad376e6ca19f2fe77c4b2be5f3a91e1f751c', '[\"*\"]', '2025-06-29 09:42:10', NULL, '2025-06-29 09:41:43', '2025-06-29 09:42:10'),
(117, 'App\\Models\\User', 4, 'auth_token', 'a3217caabc0c2afe1d0743ed3b0d679b6e5b42f472331d3acd606d8d22da8b80', '[\"*\"]', '2025-06-29 09:46:18', NULL, '2025-06-29 09:46:02', '2025-06-29 09:46:18'),
(118, 'App\\Models\\User', 4, 'auth_token', 'd1279523013fa7ee6d8f33796154f9f7fdbda1d5a9dd45de45acf86352f85163', '[\"*\"]', '2025-06-29 09:48:17', NULL, '2025-06-29 09:48:12', '2025-06-29 09:48:17'),
(119, 'App\\Models\\User', 4, 'auth_token', 'b37a0b139eec917103a23ad7f5bf93c5ef6736bcee03287595506d5a22b5ce80', '[\"*\"]', '2025-06-30 05:35:45', NULL, '2025-06-29 09:50:37', '2025-06-30 05:35:45'),
(120, 'App\\Models\\User', 3, 'auth_token', '2ba158f39fc686fc0ae4fc318802a71c71de3838279e384626d2f78836039288', '[\"*\"]', '2025-06-30 05:06:52', NULL, '2025-06-30 04:57:05', '2025-06-30 05:06:52'),
(121, 'App\\Models\\User', 3, 'auth_token', 'aacb49cb0a2dc49c31730a1c4d60c52aec1d4b74c35cb78eec25a78f7666a6dd', '[\"*\"]', '2025-06-30 06:21:58', NULL, '2025-06-30 05:39:57', '2025-06-30 06:21:58'),
(122, 'App\\Models\\User', 3, 'auth_token', 'b0adac1ba40df4e8f588cc0a57449451f6153898058d7daa1d2e3154929cd0b1', '[\"*\"]', '2025-06-30 06:29:59', NULL, '2025-06-30 06:22:09', '2025-06-30 06:29:59'),
(123, 'App\\Models\\User', 3, 'auth_token', 'dabae0d21fab23537532ea440b26786dae586b5052b914bd35147105a9be1428', '[\"*\"]', '2025-06-30 07:19:26', NULL, '2025-06-30 06:30:56', '2025-06-30 07:19:26'),
(124, 'App\\Models\\User', 5, 'auth_token', '1a96ff04fcb4d2c7c39e50611187d03de58246f41322958f8131d0489ae3c5be', '[\"*\"]', '2025-06-30 09:07:44', NULL, '2025-06-30 07:19:36', '2025-06-30 09:07:44'),
(125, 'App\\Models\\User', 5, 'auth_token', 'b09dcf5eb7b173ff2bb23aa9c3e5791b07eda49c579977a20cf4b6bae8e2203d', '[\"*\"]', NULL, NULL, '2025-07-01 03:34:23', '2025-07-01 03:34:23'),
(126, 'App\\Models\\User', 3, 'auth_token', '8b1b00737d5d1c2b5fdba4966b3cf75905d7438055aa0729d067e00776da00fe', '[\"*\"]', '2025-07-01 03:37:07', NULL, '2025-07-01 03:34:29', '2025-07-01 03:37:07'),
(127, 'App\\Models\\User', 5, 'auth_token', 'abd46579a3d0415304ea0c26065b3b25418bc5d8cfd57d7cbeb9b5f7e06e7f25', '[\"*\"]', '2025-07-01 03:37:26', NULL, '2025-07-01 03:37:17', '2025-07-01 03:37:26'),
(128, 'App\\Models\\User', 3, 'auth_token', '9ce6e7012dbf563f654a0c58a4d2bd935662504d7e5a6978c664d8da0a2a8b46', '[\"*\"]', '2025-07-01 03:44:24', NULL, '2025-07-01 03:37:33', '2025-07-01 03:44:24');

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_restaurant.png',
  `rate` decimal(3,1) NOT NULL DEFAULT '0.0',
  `rating` int NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `food_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT '5000.00',
  `is_most_popular` tinyint(1) NOT NULL DEFAULT '0',
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `image`, `rate`, `rating`, `type`, `food_type`, `location`, `delivery_fee`, `is_most_popular`, `owner_id`, `created_at`, `updated_at`) VALUES
(2, 'Resto April Jaya', '1747165145.jpg', 2.5, 0, 'Restoran Keluarga', 'Masakan Rumah', 'lohbener', 5000.00, 0, 3, '2025-05-13 12:39:06', '2025-05-13 12:39:06'),
(3, 'Resto Sarul Gih', '1748246133.jpg', 0.0, 0, 'Restoran Keluarga', 'Masakan Rumah', 'lohbener', 5000.00, 0, 5, '2025-05-26 00:55:33', '2025-05-26 00:55:33');

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
  `is_active_driver` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address_latitude` double DEFAULT NULL,
  `address_longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `address`, `photo`, `phone`, `email`, `password`, `role`, `is_active_driver`, `created_at`, `updated_at`, `address_latitude`, `address_longitude`) VALUES
(1, 'Admin User', 'Admin Address', NULL, '111111111', 'admin@gmail.com', '$2y$12$aRT4tRzAI2VwYhhVJdjUSebDCbfbRV3AqCpVl6i1K8LCcfit6XEAm', 'admin', 0, '2025-05-12 15:03:56', '2025-05-12 15:03:56', NULL, NULL),
(2, 'adit updated', 'Jl. Baru No. 123', NULL, '085312345678', 'aditupdated@example.com', '$2y$12$T3KzY66N5uMwmouGl41iuuWRSDRhQ6MJN5yv8WSe1RAHz9o/Ds8rK', 'customer', 0, '2025-05-12 15:04:07', '2025-06-21 05:24:28', -6.327, 108.323),
(3, 'April', 'indramayu Kota', NULL, '081234567890', 'april@gmail.com', '$2y$12$AZz2PTq9bARBCAPGLuDwr.xs7zHW4FCdZfT5v9FSnQS57bHN3l9MW', 'restaurant_owner', 0, '2025-05-12 15:07:51', '2025-05-12 15:07:51', NULL, NULL),
(4, 'wiranto', 'Gg. 7 Utara No.49, Karangampel, Kecamatan Karangampel', 'user_4.jpg', '0895340891989', 'wiranto@gmail.com', '$2y$12$W7U5RW2BxpiqTSe5LNg40ufRChQCjeVtlxr9ILBXDpRr2f5m1EkEm', 'customer', 0, '2025-05-13 13:38:37', '2025-06-21 14:04:59', -6.4642294794704, 108.44227179018),
(5, 'Sarul Resto', 'indramayu Kota', NULL, '0812929222', 'Sarul@gmail.com', '$2y$12$K7/5462qimEwQu5Qrx6ScOYnraEFmIYAL4.W22hTdUUQ9HoiNnlm6', 'restaurant_owner', 0, '2025-05-26 00:52:24', '2025-05-26 00:52:24', NULL, NULL),
(6, 'parhan', 'teluk agung', NULL, '085461265481', 'parhan@gmail.com', '$2y$12$D2OAq7eH3xTYMfwn/fUFpeNG37DRzzlpaHuzvB/WJ7V9Yc82qzS1G', 'customer', 0, '2025-06-13 08:11:21', '2025-06-13 08:11:21', NULL, NULL),
(7, 'riyan', 'H7WJ+XVH, Lohbener, Kecamatan Lohbener', NULL, '08546162532', 'riyan@gmail.com', '$2y$12$M.T.VLYCQ9UildiU4AQA6uQuu/yJfCh.pj.zIxeIB/vxN2ymsnGVq', 'customer', 0, '2025-06-19 11:42:46', '2025-06-19 11:42:46', NULL, NULL),
(8, 'adit', 'indramayu', NULL, '085398389383', 'adit22@gmail.com', '$2y$12$h7TyDeGffMWOA0OoLRVdKeeXfvWD4goykIZXdI2bamVK0sGjOV.wi', 'customer', 0, '2025-06-19 12:24:52', '2025-06-19 12:24:52', NULL, NULL),
(9, 'adit', 'indramayu', NULL, '085398389383', 'adit223@gmail.com', '$2y$12$tdwJbrSPjcUMo0EylmLrG.mLJwcznHJCmt.3P90Ndgox7pL5hPE3C', 'customer', 0, '2025-06-19 12:27:28', '2025-06-19 12:27:28', NULL, NULL),
(10, 'adit', 'indramayu', NULL, '085398389383', 'adit2232@gmail.com', '$2y$12$tlWynmvog6NntftEauJl0uzRCN70wjhoAC0Cf4y.W2YG4zOENfOxS', 'customer', 0, '2025-06-19 12:28:41', '2025-06-19 12:28:41', NULL, NULL),
(11, 'adit', 'indramayu', NULL, '085398389383', 'adit22323@gmail.com', '$2y$12$J/d80TEjmCd4mlbHBOuDhOXLi67bzbFcmzX5RpIggntxPTo16bqC6', 'customer', 0, '2025-06-19 12:28:58', '2025-06-19 12:28:58', NULL, NULL),
(12, 'adit', 'indramayu', NULL, '085398389383', 'adit223231@gmail.com', '$2y$12$5vxhusIwpE4L.YvKU4SVtO8JHeGKef6LCpsOEbZ.XiibTxjj.4WtC', 'customer', 0, '2025-06-19 12:34:37', '2025-06-19 12:34:37', NULL, NULL),
(13, 'adit', 'indramayu', NULL, '085398389383', 'adit2232313@gmail.com', '$2y$12$JoMD3xLH2j482hvwEOhEx.pAgswXBYY8ork50wMDTvhZc.bucPU22', 'customer', 0, '2025-06-19 12:37:02', '2025-06-19 12:37:02', NULL, NULL),
(14, 'riyan', 'Jl. Raya Lohbener Lama Blok Celo No.31, Lohbener, Kecamatan Lohbener', NULL, '0854311654', 'riyannn@gmail.com', '$2y$12$E1sIfcV.arkdDoZ7Udu8MuTuygjNMqAhKxB6FHRD4mWTBbdKRZYQW', 'customer', 0, '2025-06-19 13:23:27', '2025-06-19 13:23:27', NULL, NULL),
(15, 'adit', 'indramayu', NULL, '085398389383', 'adit2232312333@gmail.com', '$2y$12$Kst2xeYVi5oS5h/8ikn2fuAlutjmDP0pg17gQvB0LeU5btLDTZP9a', 'customer', 0, '2025-06-19 13:24:19', '2025-06-19 13:24:19', NULL, NULL),
(16, 'adit', 'indramayu', NULL, '085398389383', 'adit22323123333@gmail.com', '$2y$12$uXvcs0HmdPHC0.YlpXS1i.xlQDVmpEjvemcCbmWBd8l6ge7OS004e', 'customer', 0, '2025-06-19 13:41:27', '2025-06-19 13:41:27', NULL, NULL),
(17, 'adit', 'indramayu', NULL, '085398389383', 'adit223231233323@gmail.com', '$2y$12$vbKfQ0QURic.Q7nAnJwP3eeGSWdFOpAX.SNbMmD0IsUx6ZR31Fx2O', 'customer', 0, '2025-06-19 13:42:22', '2025-06-19 13:42:22', NULL, NULL),
(18, 'adit', 'indramayu', NULL, '085398389383', 'adit2232312933323@gmail.com', '$2y$12$J1otIccuWPq//v3.4ujh3Of6aXU9VLZhKLiC7nwgiyUqgiiRVVp02', 'customer', 0, '2025-06-19 13:47:41', '2025-06-19 13:47:41', -6.3, 108.323),
(19, 'adit', 'indramayu', NULL, '085398389383', 'adit22323123332223@gmail.com', '$2y$12$HxlMbBs6DzDtVGfxrtmO0OTCi3tbF6TbnK2hfyLD64dRrLbJwjhAW', 'customer', 0, '2025-06-19 13:50:53', '2025-06-19 13:50:53', NULL, NULL),
(20, 'adit', 'indramayu', NULL, '085398389383', 'adit223223332223@gmail.com', '$2y$12$7HWw4wgF.de2S8rBnDRSTeJFHAfgqAbtOR79uNU2oqyKZfkdJLswi', 'customer', 0, '2025-06-19 13:51:39', '2025-06-19 13:51:39', -6.3, 108.323),
(21, 'tesss', 'H7WJ+XVH, Lohbener, Kecamatan Lohbener', NULL, '05498642826', 'twssss@gmail.com', '$2y$12$AcZ6DMjb/f95qfWDA2ugX.fiErcefEvFQeTSnV2D0ottncHPh0U.K', 'customer', 0, '2025-06-19 13:55:14', '2025-06-19 13:55:14', -6.4024746, 108.2822715),
(22, 'amel', 'H7WJ+XVH, Lohbener, Kecamatan Lohbener', 'user_22.jpg', '08549761542', 'amel@gmail.com', '$2y$12$lStxomfmre7dxptETxNCruw02.3V2/XVf/BleNPvtB1PmKRYFfOA.', 'customer', 0, '2025-06-21 01:06:32', '2025-06-21 05:28:51', -6.402482, 108.2822567),
(23, 'rista', 'M83H+2GX, Kepandean, Kecamatan Indramayu', NULL, '08975734600', 'rista@gmail.com', '$2y$12$0.8MuA0Po7tHNVYdPvbmiuNFxxhgdO/c5o4WcGBW8DD1gvHJEBDIW', 'customer', 0, '2025-06-26 10:37:58', '2025-06-26 10:37:58', -6.3465831989407, 108.32778482963),
(24, 'Rudi Supir', 'Jalan Mangga No.12', NULL, '08953413932', 'rudi.driver@example.com', '$2y$12$yqwNOVNnqkN41LWp0mR2seFBU/O5w4wmpW7Hu9e4/r4bzdG6BJl4e', 'driver', 0, '2025-06-30 07:05:26', '2025-06-30 07:05:26', NULL, NULL),
(25, 'Rudi Supir2', '-', NULL, '', 'rudi.driver2@example.com', '$2y$12$IQjlo8lEZ6PtPyyCKpe38u8o8PeUax/viAXne9iBoYUDPd4Uyk/0q', 'driver', 0, '2025-06-30 08:35:01', '2025-06-30 08:35:01', NULL, NULL),
(26, 'Rudi Supir2', '-', NULL, '', 'rudi.driver3@example.com', '$2y$12$0GOTWX83gjO5tsYtTwBxguI8rj3WzWG2v8xF2dN/UCBBUuXHKha82', 'driver', 0, '2025-06-30 08:36:17', '2025-06-30 08:36:17', NULL, NULL),
(27, 'Rudi Supir2', '-', NULL, '', 'rudi.driver4@example.com', '$2y$12$ReMcDaaaPr.3sHorwigjf.o5W0a7/MJzfQ.OLd.Q1eC2qxLdELivu', 'driver', 0, '2025-06-30 08:37:01', '2025-06-30 08:37:01', NULL, NULL);

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
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drivers_restaurant_id_foreign` (`restaurant_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`),
  ADD KEY `notifications_order_id_foreign` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_driver_id_foreign` (`driver_id`),
  ADD KEY `orders_restaurant_id_foreign` (`restaurant_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurants_owner_id_foreign` (`owner_id`);

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
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_item_category_id_foreign` FOREIGN KEY (`item_category_id`) REFERENCES `item_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `items_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_restaurant_id_foreign` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `restaurants_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
