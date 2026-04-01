-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2026 at 02:29 PM
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
-- Database: `sangam_tours`
--

-- --------------------------------------------------------

--
-- Table structure for table `accommodations`
--

CREATE TABLE `accommodations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `accommodation_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `hotel_type` varchar(255) NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'WiFi', 'bx bx-wifi', '2026-03-24 02:03:51', '2026-03-24 02:03:51'),
(2, 'Air Conditioned (AC)', 'bx bx-wind', '2026-03-24 02:03:51', '2026-03-24 02:03:51'),
(3, 'Complimentary Meal', 'bx bx-restaurant', '2026-03-24 02:03:51', '2026-03-24 02:03:51'),
(4, 'Water Bottle', 'bx bx-water', '2026-03-24 02:03:51', '2026-03-24 02:03:51'),
(5, 'Charging Point', 'bx bx-battery-charging', '2026-03-24 02:03:51', '2026-03-24 02:03:51'),
(6, 'Blankets & Pillow', 'bx bx-bed', '2026-03-24 02:03:51', '2026-03-24 02:03:51'),
(7, 'Recliner Seats', 'bx bx-chair', '2026-03-24 02:03:51', '2026-03-24 02:03:51'),
(8, 'GPS Tracking', 'bx bx-map-pin', '2026-03-24 02:03:51', '2026-03-24 02:03:51'),
(9, 'Entertainment (TV)', 'bx bx-tv', '2026-03-24 02:03:51', '2026-03-24 02:03:51'),
(10, 'Emergency Exit', 'bx bx-exit', '2026-03-24 02:03:51', '2026-03-24 02:03:51');

-- --------------------------------------------------------

--
-- Table structure for table `amenity_vehicle`
--

CREATE TABLE `amenity_vehicle` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `amenity_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `amenity_vehicle`
--

INSERT INTO `amenity_vehicle` (`id`, `vehicle_id`, `amenity_id`) VALUES
(1, 1, 8),
(2, 1, 10),
(3, 2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_no` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `service_type` enum('Flight','Bus','Train','Tour Package') NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED DEFAULT NULL,
  `route_id` bigint(20) UNSIGNED DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `travel_date` date NOT NULL,
  `pax_count` int(11) NOT NULL DEFAULT 1,
  `total_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(255) NOT NULL DEFAULT 'Pending',
  `booking_status` varchar(255) NOT NULL DEFAULT 'Upcoming',
  `internal_note` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_no`, `customer_id`, `service_type`, `vehicle_id`, `route_id`, `package_id`, `travel_date`, `pax_count`, `total_amount`, `paid_amount`, `due_amount`, `payment_status`, `booking_status`, `internal_note`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'ST-2026-0001', 1, 'Bus', NULL, NULL, NULL, '2026-03-24', 1, 56.21, 56.21, 0.00, 'Paid', 'Upcoming', NULL, '2026-03-24 04:18:35', '2026-03-24 02:17:59', '2026-03-24 04:18:35'),
(4, 'ST-2026-0002', 1, 'Bus', 2, NULL, NULL, '2026-03-24', 1, 3.22, 3.22, 0.00, 'Paid', 'Upcoming', NULL, '2026-03-24 05:53:28', '2026-03-24 04:56:07', '2026-03-24 05:53:28'),
(5, 'ST-2026-0005', 1, 'Bus', 1, NULL, NULL, '2026-03-26', 1, 108.15, 108.15, 0.00, 'Paid', 'Upcoming', NULL, '2026-03-24 05:53:31', '2026-03-24 05:18:34', '2026-03-24 05:53:31'),
(6, 'ST-2026-0006', 1, 'Bus', 2, NULL, NULL, '2026-03-25', 1, 25.30, 25.30, 0.00, 'Paid', 'Upcoming', NULL, '2026-03-24 05:53:47', '2026-03-24 05:37:39', '2026-03-24 05:53:47'),
(7, 'ST-2026-0007', 1, 'Bus', 2, 1, NULL, '2026-03-24', 1, 23.29, 23.29, 0.00, 'Paid', 'Upcoming', NULL, '2026-03-24 05:53:51', '2026-03-24 05:52:28', '2026-03-24 05:53:51'),
(8, 'ST-2026-0008', 1, 'Bus', 1, 1, NULL, '2026-03-24', 1, 34.94, 34.85, 0.09, 'Partial', 'Upcoming', NULL, NULL, '2026-03-24 06:14:20', '2026-03-24 06:14:20');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `common_images`
--

CREATE TABLE `common_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL,
  `image_type` varchar(255) NOT NULL,
  `imageable_id` bigint(20) UNSIGNED NOT NULL,
  `imageable_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `pan_number` varchar(255) DEFAULT NULL,
  `aadhar_number` varchar(255) DEFAULT NULL,
  `customer_image` varchar(255) DEFAULT NULL,
  `pan_front` varchar(255) DEFAULT NULL,
  `pan_back` varchar(255) DEFAULT NULL,
  `aadhar_front` varchar(255) DEFAULT NULL,
  `aadhar_back` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `pan_number`, `aadhar_number`, `customer_image`, `pan_front`, `pan_back`, `aadhar_front`, `aadhar_back`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'ved prakash pandey', 'vedprakash.infoera@gmail.com', '73015 63233', 'Sanagam Viahar', 'abcd123456', '823792541258', 'customer_image_1774336358.jpg', 'pan_front_1774336358.jpg', 'pan_back_1774336358.jpg', 'aadhar_front_1774336358.webp', 'aadhar_back_1774336359.jpg', 1, NULL, '2026-03-24 01:42:39', '2026-03-25 01:16:13');

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

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"e7442a44-d494-477f-80eb-0e263a98360f\",\"displayName\":\"App\\\\Events\\\\AiMessageEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:25:\\\"App\\\\Events\\\\AiMessageEvent\\\":2:{s:7:\\\"message\\\";s:84:\\\"Maafi chahunga, abhi server mein thodi dikkat hai. Kripya thodi der baad try karein.\\\";s:9:\\\"sessionId\\\";s:40:\\\"hU6mX4FpNKHdkNq2wyvHhoDG27XW69QNk5pmSB0f\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1774530415,\"delay\":null}', 0, NULL, 1774530415, 1774530415),
(2, 'default', '{\"uuid\":\"7012708a-ea28-415c-8e7d-044bc2fc77fb\",\"displayName\":\"App\\\\Events\\\\AiMessageEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:25:\\\"App\\\\Events\\\\AiMessageEvent\\\":2:{s:7:\\\"message\\\";s:84:\\\"Maafi chahunga, abhi server mein thodi dikkat hai. Kripya thodi der baad try karein.\\\";s:9:\\\"sessionId\\\";s:40:\\\"hU6mX4FpNKHdkNq2wyvHhoDG27XW69QNk5pmSB0f\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1774530552,\"delay\":null}', 0, NULL, 1774530552, 1774530552),
(3, 'default', '{\"uuid\":\"0d70dbbe-2069-4d10-8c1c-16cd6fb189db\",\"displayName\":\"App\\\\Events\\\\AiMessageEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:25:\\\"App\\\\Events\\\\AiMessageEvent\\\":2:{s:7:\\\"message\\\";s:84:\\\"Maafi chahunga, abhi server mein thodi dikkat hai. Kripya thodi der baad try karein.\\\";s:9:\\\"sessionId\\\";s:40:\\\"hU6mX4FpNKHdkNq2wyvHhoDG27XW69QNk5pmSB0f\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1774530674,\"delay\":null}', 0, NULL, 1774530674, 1774530674),
(4, 'default', '{\"uuid\":\"fab815e7-7d31-45e6-bc4a-9cc5e85d940b\",\"displayName\":\"App\\\\Events\\\\AiMessageEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":17:{s:5:\\\"event\\\";O:25:\\\"App\\\\Events\\\\AiMessageEvent\\\":2:{s:7:\\\"message\\\";s:84:\\\"Maafi chahunga, abhi server mein thodi dikkat hai. Kripya thodi der baad try karein.\\\";s:9:\\\"sessionId\\\";s:40:\\\"hU6mX4FpNKHdkNq2wyvHhoDG27XW69QNk5pmSB0f\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:23:\\\"deleteWhenMissingModels\\\";b:1;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1774531047,\"delay\":null}', 0, NULL, 1774531047, 1774531047);

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
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `state_name` varchar(255) NOT NULL,
  `city_location` varchar(255) NOT NULL,
  `country_id` varchar(255) DEFAULT NULL,
  `state_id` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `country_name`, `state_name`, `city_location`, `country_id`, `state_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'India', 'Bihar', 'Patna', NULL, NULL, 1, '2026-03-24 01:43:58', '2026-03-24 01:43:58'),
(2, 'India', 'Bihar', 'Hajipur', NULL, NULL, 1, '2026-03-24 01:44:08', '2026-03-24 01:44:08'),
(3, 'India', 'Bihar', 'Chhapra', NULL, NULL, 1, '2026-03-24 01:44:20', '2026-03-24 01:44:20'),
(4, 'India', 'Bihar', 'Sonpur', NULL, NULL, 1, '2026-03-24 01:44:30', '2026-03-24 01:44:30');

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
(4, '2026_03_20_112941_create_locations_table', 1),
(5, '2026_03_20_113029_create_tour_packages_table', 1),
(6, '2026_03_20_113101_create_package_stays_table', 1),
(7, '2026_03_20_113118_create_common_images_table', 1),
(8, '2026_03_21_073518_add_soft_deletes_to_tour_packages_table', 1),
(9, '2026_03_21_082005_create_accommodations_table', 1),
(10, '2026_03_21_083716_add_accommodation_id_to_accommodations_table', 1),
(11, '2026_03_21_095839_create_customers_table', 1),
(12, '2026_03_21_114244_create_vendors_table', 1),
(13, '2026_03_21_114253_create_amenities_table', 1),
(14, '2026_03_21_114302_create_vehicles_table', 1),
(15, '2026_03_21_114312_create_routes_table', 1),
(16, '2026_03_21_123330_add_fields_to_routes_table', 1),
(17, '2026_03_21_123550_add_status_to_routes_table', 1),
(18, '2026_03_21_124555_create_bookings_table', 1),
(19, '2026_03_21_130939_create_offers_table', 1),
(20, '2026_03_21_132756_add_restrictions_to_offers_table', 1),
(21, '2026_03_21_134319_add_soft_deletes_to_offers_table', 1),
(22, '2026_03_23_060848_create_schedules_table', 1),
(23, '2026_03_23_064019_create_schedule_stoppages_table', 1),
(24, '2026_03_23_071008_modify_route_id_in_schedules_table', 1),
(25, '2026_03_23_071207_cleanup_schedules_table_for_stoppages', 1),
(26, '2026_03_23_080230_create_seat_categories_table', 1),
(27, '2026_03_23_080305_rename_base_fare_in_vehicles', 1),
(28, '2026_03_23_082220_add_distance_to_routes_table', 1),
(29, '2026_03_24_053740_add_role_to_users_table', 1),
(30, '2026_03_24_081249_create_passengers_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `offer_name` varchar(255) NOT NULL,
  `offer_code` varchar(255) NOT NULL,
  `apply_to` varchar(255) NOT NULL DEFAULT 'All',
  `content_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_type` enum('Fixed','Percentage') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_booking_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `valid_until` date NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_stays`
--

CREATE TABLE `package_stays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tour_package_id` bigint(20) UNSIGNED NOT NULL,
  `days` int(11) NOT NULL,
  `nights` int(11) NOT NULL,
  `place_description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `passengers`
--

CREATE TABLE `passengers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('vedprakash.infoera@gmail.com', '$2y$12$yO.7RnXy3ubpKeHQLMGUUOt0kP97a4omHHRU5SHziPbwV2FwBw06O', '2026-03-25 01:22:12');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_city_id` bigint(20) UNSIGNED NOT NULL,
  `to_city_id` bigint(20) UNSIGNED NOT NULL,
  `distance_km` decimal(8,2) NOT NULL DEFAULT 0.00,
  `distance` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `from_city_id`, `to_city_id`, `distance_km`, `distance`, `duration`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 23.29, '21', '00h40m', 1, NULL, '2026-03-24 02:01:10', '2026-03-24 05:19:35'),
(2, 1, 4, 25.30, '25', '00h50m', 1, NULL, '2026-03-24 02:01:41', '2026-03-24 02:07:38'),
(3, 1, 3, 72.10, '75', '02h 00m', 1, NULL, '2026-03-24 02:02:20', '2026-03-24 02:07:29'),
(4, 2, 3, 59.22, NULL, NULL, 1, NULL, '2026-03-24 02:09:24', '2026-03-24 02:09:24'),
(5, 2, 4, 3.22, NULL, NULL, 1, NULL, '2026-03-24 02:10:50', '2026-03-24 02:10:50'),
(6, 4, 3, 56.21, NULL, NULL, 1, NULL, '2026-03-24 02:11:50', '2026-03-24 02:11:50');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `route_id` bigint(20) UNSIGNED DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `specific_date` date DEFAULT NULL,
  `days_of_week` varchar(255) DEFAULT NULL,
  `fare_override` decimal(10,2) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `vehicle_id`, `route_id`, `departure_time`, `arrival_time`, `specific_date`, `days_of_week`, `fare_override`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, 'Mon,Tue,Wed,Thu,Fri,Sat,Sun', NULL, 1, '2026-03-24 02:08:56', '2026-03-24 02:08:56'),
(2, 2, NULL, NULL, NULL, NULL, 'Mon,Tue,Wed,Thu,Fri,Sat,Sun', NULL, 1, '2026-03-24 02:14:42', '2026-03-24 02:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_stoppages`
--

CREATE TABLE `schedule_stoppages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule_id` bigint(20) UNSIGNED NOT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `arrival_time` time DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `stop_order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedule_stoppages`
--

INSERT INTO `schedule_stoppages` (`id`, `schedule_id`, `location_id`, `arrival_time`, `departure_time`, `stop_order`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '00:00:00', '04:00:00', 0, '2026-03-24 02:08:56', '2026-03-24 02:08:56'),
(2, 1, 2, '04:50:00', '04:55:00', 1, '2026-03-24 02:08:56', '2026-03-24 02:08:56'),
(3, 1, 3, '06:10:00', '00:00:00', 2, '2026-03-24 02:08:56', '2026-03-24 02:08:56'),
(4, 2, 1, '00:00:00', '05:00:00', 0, '2026-03-24 02:14:42', '2026-03-24 02:14:42'),
(5, 2, 2, '05:45:00', '05:50:00', 1, '2026-03-24 02:14:42', '2026-03-24 02:14:42'),
(6, 2, 4, '06:00:00', '06:05:00', 2, '2026-03-24 02:14:42', '2026-03-24 02:14:42'),
(7, 2, 3, '06:30:00', '00:00:00', 3, '2026-03-24 02:14:42', '2026-03-24 02:14:42');

-- --------------------------------------------------------

--
-- Table structure for table `seat_categories`
--

CREATE TABLE `seat_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seat_categories`
--

INSERT INTO `seat_categories` (`id`, `vendor_id`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Seater(Non-AC)', '2026-03-24 02:07:05', '2026-03-24 02:07:05');

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
('hU6mX4FpNKHdkNq2wyvHhoDG27XW69QNk5pmSB0f', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0kxQmNZMGJ2cHN5Y1RRaDE0Z3FpWm00WXZOVVVOd29tQzhhQ1F5WCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9fQ==', 1774531740),
('LnqyAPDb4jpcJrhzb3nHIXv1JmHtwFMHipVQ0Not', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDhtSmVZVGNkR2NDUlFna3Q0Q2FZTGdJeUxoM0NhUnhHOHFKOXowSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MjI6ImFkbWluLnBhc3N3b3JkLnJlcXVlc3QiO319', 1774421549);

-- --------------------------------------------------------

--
-- Table structure for table `tour_packages`
--

CREATE TABLE `tour_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `location_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'vedprakash.infoera@gmail.com', NULL, '$2y$12$qJkpwaKOf0Hl9zRxZdSgo.jhkvK38od2vemLVBhzG11wZfN7.QQf.', 'admin', 'CC8acwFsWsvZeZx6WQoFdYjmZ5GG1KmY8SXHLMWr3loBg744NODyyotm4usJ', '2026-03-24 00:23:42', '2026-03-24 01:23:13');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('Flight','Bus','Train') NOT NULL,
  `vehicle_number` varchar(255) NOT NULL,
  `total_seats` int(11) NOT NULL,
  `charges_per_km` decimal(10,2) NOT NULL,
  `seat_type` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `vendor_id`, `type`, `vehicle_number`, `total_seats`, `charges_per_km`, `seat_type`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bus', 'BR06AP7636', 60, 1.50, 'Seater(Non-AC)', 1, NULL, '2026-03-24 02:07:05', '2026-03-24 02:07:05'),
(2, 1, 'Bus', 'BR06AP7635', 80, 1.00, 'Seater(Non-AC)', 1, NULL, '2026-03-24 02:12:30', '2026-03-24 02:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Airline','Bus Operator','Train Dept','Hotel Partner') NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_api` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `type`, `phone`, `email`, `is_api`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Jai Hanuman', 'Bus Operator', '73015 63232', NULL, 0, 1, NULL, '2026-03-24 01:43:10', '2026-03-24 01:43:10'),
(2, 'Local Tour Travels', 'Bus Operator', '9089098789', 'local.g@gmail.com', 1, 1, NULL, '2026-03-24 06:07:21', '2026-03-24 06:07:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodations`
--
ALTER TABLE `accommodations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accommodations_location_id_foreign` (`location_id`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `amenity_vehicle`
--
ALTER TABLE `amenity_vehicle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `amenity_vehicle_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `amenity_vehicle_amenity_id_foreign` (`amenity_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_no_unique` (`booking_no`),
  ADD KEY `bookings_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `common_images`
--
ALTER TABLE `common_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`),
  ADD UNIQUE KEY `customers_phone_unique` (`phone`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `offers_offer_code_unique` (`offer_code`);

--
-- Indexes for table `package_stays`
--
ALTER TABLE `package_stays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_stays_tour_package_id_foreign` (`tour_package_id`);

--
-- Indexes for table `passengers`
--
ALTER TABLE `passengers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `passengers_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `routes_from_city_id_foreign` (`from_city_id`),
  ADD KEY `routes_to_city_id_foreign` (`to_city_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `schedules_route_id_foreign` (`route_id`);

--
-- Indexes for table `schedule_stoppages`
--
ALTER TABLE `schedule_stoppages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_stoppages_schedule_id_foreign` (`schedule_id`),
  ADD KEY `schedule_stoppages_location_id_foreign` (`location_id`);

--
-- Indexes for table `seat_categories`
--
ALTER TABLE `seat_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seat_categories_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tour_packages`
--
ALTER TABLE `tour_packages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tour_packages_package_id_unique` (`package_id`),
  ADD KEY `tour_packages_location_id_foreign` (`location_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicles_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_phone_unique` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodations`
--
ALTER TABLE `accommodations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `amenity_vehicle`
--
ALTER TABLE `amenity_vehicle`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `common_images`
--
ALTER TABLE `common_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_stays`
--
ALTER TABLE `package_stays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `passengers`
--
ALTER TABLE `passengers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `schedule_stoppages`
--
ALTER TABLE `schedule_stoppages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `seat_categories`
--
ALTER TABLE `seat_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tour_packages`
--
ALTER TABLE `tour_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accommodations`
--
ALTER TABLE `accommodations`
  ADD CONSTRAINT `accommodations_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `amenity_vehicle`
--
ALTER TABLE `amenity_vehicle`
  ADD CONSTRAINT `amenity_vehicle_amenity_id_foreign` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `amenity_vehicle_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `package_stays`
--
ALTER TABLE `package_stays`
  ADD CONSTRAINT `package_stays_tour_package_id_foreign` FOREIGN KEY (`tour_package_id`) REFERENCES `tour_packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `passengers`
--
ALTER TABLE `passengers`
  ADD CONSTRAINT `passengers_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_from_city_id_foreign` FOREIGN KEY (`from_city_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `routes_to_city_id_foreign` FOREIGN KEY (`to_city_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`),
  ADD CONSTRAINT `schedules_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

--
-- Constraints for table `schedule_stoppages`
--
ALTER TABLE `schedule_stoppages`
  ADD CONSTRAINT `schedule_stoppages_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `schedule_stoppages_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seat_categories`
--
ALTER TABLE `seat_categories`
  ADD CONSTRAINT `seat_categories_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_packages`
--
ALTER TABLE `tour_packages`
  ADD CONSTRAINT `tour_packages_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`);

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
