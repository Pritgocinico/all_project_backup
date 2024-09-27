-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 01, 2024 at 02:02 AM
-- Server version: 10.6.17-MariaDB-cll-lve-log
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kznoyp51_mp_group`
--

-- --------------------------------------------------------

--
-- Table structure for table `accesses`
--

CREATE TABLE `accesses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `menu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) DEFAULT 2 COMMENT '0=disabled,1=view Only, 2=all',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accesses`
--

INSERT INTO `accesses` (`id`, `role_id`, `menu_id`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 2, '2024-06-29 18:05:06', '2024-06-29 19:55:28', NULL),
(2, 2, 1, 1, '2024-06-29 18:05:06', '2024-06-29 19:55:20', NULL),
(3, 1, 2, 2, '2024-06-29 18:05:06', '2024-06-29 19:55:28', NULL),
(4, 2, 2, 1, '2024-06-29 18:05:06', '2024-06-29 19:55:20', NULL),
(5, 1, 3, 2, '2024-06-29 18:05:06', '2024-06-29 19:55:28', NULL),
(6, 2, 3, 1, '2024-06-29 18:05:06', '2024-06-29 19:55:20', NULL),
(7, 1, 4, 2, '2024-06-29 18:05:06', '2024-06-29 19:55:28', NULL),
(8, 2, 4, 1, '2024-06-29 18:05:06', '2024-06-29 19:55:20', NULL),
(9, 1, 5, 2, '2024-06-29 18:05:06', '2024-06-29 19:55:28', NULL),
(10, 2, 5, 1, '2024-06-29 18:05:06', '2024-06-29 19:55:20', NULL),
(11, 1, 6, 2, '2024-06-29 18:05:06', '2024-06-29 19:55:28', NULL),
(12, 2, 6, 1, '2024-06-29 18:05:06', '2024-06-29 19:55:20', NULL);

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
('admin@admin.co|106.205.249.152', 'i:1;', 1719681130),
('admin@admin.co|106.205.249.152:timer', 'i:1719681130;', 1719681130);

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
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `state_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `country_code` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iso2` varchar(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `phone_code` varchar(5) NOT NULL,
  `iso3` varchar(3) NOT NULL,
  `region` varchar(255) NOT NULL,
  `subregion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `precision` tinyint(4) NOT NULL DEFAULT 2,
  `symbol` varchar(255) NOT NULL,
  `symbol_native` varchar(255) NOT NULL,
  `symbol_first` tinyint(4) NOT NULL DEFAULT 1,
  `decimal_mark` varchar(1) NOT NULL DEFAULT '.',
  `thousands_separator` varchar(1) NOT NULL DEFAULT ','
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=active and 0= inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'MP World Travel', 1, '2024-06-29 19:53:14', '2024-06-29 19:53:14', NULL),
(2, 'MP Financial Services', 1, '2024-06-29 19:53:25', '2024-06-29 19:53:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT '1' COMMENT '1= active and  0 = in active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`, `department_id`) VALUES
(1, 'Sales Manager', NULL, '1', '2024-06-29 19:53:42', '2024-06-29 19:53:42', NULL, 1),
(2, 'Sales Executive', NULL, '1', '2024-06-29 19:53:53', '2024-06-29 19:53:53', NULL, 1),
(3, 'Calling Department', NULL, '1', '2024-06-29 19:54:04', '2024-06-29 19:54:04', NULL, 1),
(4, 'Sales Manager', NULL, '1', '2024-06-29 19:54:14', '2024-06-29 19:54:14', NULL, 2),
(5, 'Human Resource', NULL, '1', '2024-06-29 19:54:25', '2024-06-29 19:54:37', NULL, 1),
(6, 'Sales Executive', NULL, '1', '2024-06-29 19:54:50', '2024-06-29 19:54:50', NULL, 2),
(7, 'Calling Department', NULL, '1', '2024-06-29 19:55:00', '2024-06-29 19:55:00', NULL, 2);

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
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` char(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_native` varchar(255) NOT NULL,
  `dir` char(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `module`, `description`, `created_at`, `updated_at`, `deleted_at`, `user_id`) VALUES
(1, 'Login', 'Super Admin Logged In', '2024-06-29 18:07:13', '2024-06-29 18:07:13', NULL, 1),
(2, 'Login', 'Super Admin Logged In', '2024-06-29 18:11:05', '2024-06-29 18:11:05', NULL, 1),
(3, 'Login', 'Super Admin Logged In', '2024-06-29 18:23:27', '2024-06-29 18:23:27', NULL, 1),
(4, 'Login', 'Super Admin Logged In', '2024-06-29 19:37:25', '2024-06-29 19:37:25', NULL, 1),
(5, 'Department', 'Super Admin created a department named \'MP World Travel\'', '2024-06-29 19:53:14', '2024-06-29 19:53:14', NULL, 1),
(6, 'Department', 'Super Admin created a department named \'MP Financial Services\'', '2024-06-29 19:53:25', '2024-06-29 19:53:25', NULL, 1),
(7, 'Designation', 'Super Admin created a Designation named \'Sales Manager\'', '2024-06-29 19:53:42', '2024-06-29 19:53:42', NULL, 1),
(8, 'Designation', 'Super Admin created a Designation named \'Sales Executive\'', '2024-06-29 19:53:53', '2024-06-29 19:53:53', NULL, 1),
(9, 'Designation', 'Super Admin created a Designation named \'Calling Department\'', '2024-06-29 19:54:04', '2024-06-29 19:54:04', NULL, 1),
(10, 'Designation', 'Super Admin created a Designation named \'Sales Manager\'', '2024-06-29 19:54:14', '2024-06-29 19:54:14', NULL, 1),
(11, 'Designation', 'Super Admin created a Designation named \'Human Resoruce\'', '2024-06-29 19:54:25', '2024-06-29 19:54:25', NULL, 1),
(12, 'Designation', 'Super Admin updated a Designation named \'Human Resource\'', '2024-06-29 19:54:37', '2024-06-29 19:54:37', NULL, 1),
(13, 'Designation', 'Super Admin created a Designation named \'Sales Executive\'', '2024-06-29 19:54:50', '2024-06-29 19:54:50', NULL, 1),
(14, 'Designation', 'Super Admin created a Designation named \'Calling Department\'', '2024-06-29 19:55:00', '2024-06-29 19:55:00', NULL, 1),
(15, 'Role', 'Super Admin updated a role\'s detail named \'Employee\'', '2024-06-29 19:55:20', '2024-06-29 19:55:20', NULL, 1),
(16, 'Role', 'Super Admin updated a role\'s detail named \'Admin\'', '2024-06-29 19:55:28', '2024-06-29 19:55:28', NULL, 1),
(17, 'Setting', 'Super Admin Updated Successfully.', '2024-06-29 20:17:14', '2024-06-29 20:17:14', NULL, 1),
(18, 'Login', 'Super Admin Logged In', '2024-06-29 20:32:46', '2024-06-29 20:32:46', NULL, 1),
(19, 'Login', 'Super Admin Logged In', '2024-06-29 21:11:44', '2024-06-29 21:11:44', NULL, 1),
(20, 'Login', 'Super Admin Logged In', '2024-07-01 08:46:12', '2024-07-01 08:46:12', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=active and 0= inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'dashboard', 1, '2024-06-29 18:05:06', '2024-06-29 18:05:06', NULL),
(2, 'department', 1, '2024-06-29 18:05:06', '2024-06-29 18:05:06', NULL),
(3, 'designation', 1, '2024-06-29 18:05:06', '2024-06-29 18:05:06', NULL),
(4, 'role', 1, '2024-06-29 18:05:06', '2024-06-29 18:05:06', NULL),
(5, 'user', 1, '2024-06-29 18:05:06', '2024-06-29 18:05:06', NULL),
(6, 'log', 1, '2024-06-29 18:05:06', '2024-06-29 18:05:06', NULL);

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
(4, '2020_07_07_055656_create_countries_table', 1),
(5, '2020_07_07_055725_create_cities_table', 1),
(6, '2020_07_07_055746_create_timezones_table', 1),
(7, '2021_10_19_071730_create_states_table', 1),
(8, '2021_10_23_082414_create_currencies_table', 1),
(9, '2022_01_22_034939_create_languages_table', 1),
(10, '2024_06_26_125756_create_settings_table', 1),
(11, '2024_06_27_054446_create_menus_table', 1),
(12, '2024_06_27_055426_create_roles_table', 1),
(13, '2024_06_27_061052_create_accesses_table', 1),
(14, '2024_06_27_103928_create_logs_table', 1),
(15, '2024_06_27_123900_add_field_in_users_table', 1),
(16, '2024_06_28_050935_add_field_in_users_table', 1),
(17, '2024_06_28_101039_create_departments_table', 1),
(18, '2024_06_28_135031_add_field_in_logs_table', 1),
(19, '2024_06_29_113304_create_designations_table', 1),
(20, '2024_06_29_121853_add_field_in_designations_table', 1);

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
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=active and 0= inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', 1, '2024-06-29 18:05:05', '2024-06-29 19:55:28', NULL),
(2, 'Employee', 1, '2024-06-29 18:05:05', '2024-06-29 19:55:20', NULL);

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
('0irTHib6KnlJPfJcF3jMNWw3KYkq1tODs0Sp8On0', NULL, '54.146.255.201', 'Slackbot-LinkExpanding 1.0 (+https://api.slack.com/robots)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUHF3MTAwYUpTNHY0b2FCeWxFN2J3cXVDZ3VtTVJadzZ2aTZLUXJyYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHBzOi8vdHJ1c3RlZHN0YWdpbmcuY29tL21wZ3JvdXAtY3JtIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1719672822),
('Cr2DRevdtbAvSSI4rAYMuhmrBn03Fj1riAgD8qBD', 1, '103.106.21.4', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoidkMxY0owSkRlUnVYekJvWlpHd2FzOEh6RmhFMWJBdWs5YURiR25jbyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjYzOiJodHRwczovL3RydXN0ZWRzdGFnaW5nLmNvbS9tcGdyb3VwLWNybS9wdWJsaWMvZGFzaGJvYXJkLXNldHRpbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTcxOTY3MDI2NTt9fQ==', 1719678185),
('cV36FFxzGFcenYIIXzRLQC5ZTFBLPVxzTmkBDb3N', 1, '103.106.21.4', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSkxDa3FqcGZ2cGR6YmhWWkNJWnhUQUtMMjFGTzBPZnJ2aVB0T0ZIeCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTY6Imh0dHBzOi8vdHJ1c3RlZHN0YWdpbmcuY29tL21wZ3JvdXAtY3JtL2Rhc2hib2FyZC1zZXR0aW5nIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3MTk2Nzg3NjY7fX0=', 1719678806),
('CvuEuN3AzUvVymtHoJPx02AwVKrMFksADNdlAyyE', 1, '103.106.21.4', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNkdITWE1Ulk2bTFhSWJtWHRBT3NmZmxEV1A1SUFVWUhuUzA3ZlB4YSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTY6Imh0dHBzOi8vdHJ1c3RlZHN0YWdpbmcuY29tL21wZ3JvdXAtY3JtL2Rhc2hib2FyZC1zZXR0aW5nIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3MTk2NzEwMDc7fX0=', 1719677774),
('HQLD8l1lSYGtHOO2UisCHizY8rWoG2hk2qthaGkg', 1, '103.106.21.4', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoicjQ2a0pWbDRPWEwwZURwamhONmVVMVFuTUpUQnRUYURTMDlmdjB0WiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTY6Imh0dHBzOi8vdHJ1c3RlZHN0YWdpbmcuY29tL21wZ3JvdXAtY3JtL2Rhc2hib2FyZC1zZXR0aW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3MTk4MDkxNzI7fX0=', 1719810692),
('pMbLT7n3mSo79fR9HHiQJnaypEZK7ZG6A2W15P5Y', NULL, '103.106.21.4', 'WhatsApp/2.2424.6 W', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidGJINVFUOHozaUVIeHJzZWlJb2FLV3VLUHdPQ1hvOUJIMGRvaVpRciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHBzOi8vdHJ1c3RlZHN0YWdpbmcuY29tL21wZ3JvdXAtY3JtIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1719677769),
('tyOv9f3D8fsWtk3D9PYH6x6R1FfFWBTEMx4SRJUT', 1, '106.205.249.152', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTVFBOE80MWx5TjhzbUJvQVh2NVdmVnQ1NmNQNk5CbGFCUlE5eHdmVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vdHJ1c3RlZHN0YWdpbmcuY29tL21wZ3JvdXAtY3JtL3JvbGUvY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3MTk2ODExMDQ7fX0=', 1719681192);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `fa_icon` varchar(255) DEFAULT NULL,
  `site_name` text DEFAULT NULL,
  `site_url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `logo`, `fa_icon`, `site_name`, `site_url`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'logo/Dlp1T9XBXCGtcB79Q0f92ybOv5X4lqcrU219iJLn.png', 'fa_icon/kTqBUtPHEkNA3nzHQVY63R2NmLzgRWPdlmWSqDLz.png', 'MP Group CRM', NULL, '2024-06-29 20:17:14', '2024-06-29 20:18:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `country_code` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

CREATE TABLE `timezones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `country_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_number` bigint(20) UNSIGNED DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `original_password` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `address` longtext DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `profile_image`, `email_verified_at`, `phone_number`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `original_password`, `state`, `city`, `country`, `address`, `zip_code`, `deleted_at`) VALUES
(1, 'Super Admin', 'admin@admin.com', 'profile/ZMReWqYf0K77JnHZPPOZz8IgMJDzE5iBbSuqrua6.png', NULL, 1111111111, '$2y$12$gOPKFJL1sG1yazRGq2697umVGcnrWs.naf4bNGjtjYdj98RIqD0ue', NULL, NULL, '2024-06-29 20:23:27', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accesses`
--
ALTER TABLE `accesses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accesses_role_id_foreign` (`role_id`),
  ADD KEY `accesses_menu_id_foreign` (`menu_id`);

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
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `designations_department_id_foreign` (`department_id`);

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
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accesses`
--
ALTER TABLE `accesses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timezones`
--
ALTER TABLE `timezones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accesses`
--
ALTER TABLE `accesses`
  ADD CONSTRAINT `accesses_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accesses_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `designations`
--
ALTER TABLE `designations`
  ADD CONSTRAINT `designations_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
