-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 03, 2024 at 02:50 AM
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
-- Database: `svjpym88_peticash`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `category`, `created_at`, `updated_at`) VALUES
(1, 1, 'Test Category', '2024-05-29 11:13:17', '2024-05-29 11:13:30'),
(2, 1, 'PETROL EXPANSES', '2024-06-08 19:07:30', '2024-06-08 19:08:52'),
(3, 1, 'FACTORY EXPANSES', '2024-06-08 19:07:45', '2024-06-08 19:08:26'),
(4, 1, 'OFFICE EXPANSES', '2024-06-08 19:07:58', '2024-06-08 19:07:58'),
(5, 1, 'TRANSPORT EXPENSES', '2024-06-08 19:21:02', '2024-06-08 19:21:02'),
(6, 1, 'LALIT KAKA', '2024-06-08 19:29:06', '2024-06-08 19:29:06'),
(8, 1, 'TEJASHBHAI', '2024-06-08 19:29:15', '2024-06-08 19:29:15'),
(9, 1, 'LALABHAI', '2024-06-08 19:29:25', '2024-06-08 19:29:25'),
(10, 1, 'SALARY EXPANSES', '2024-06-08 19:35:00', '2024-06-08 19:35:00'),
(11, 1, 'BACHAT MANDAL', '2024-06-08 20:58:27', '2024-06-08 20:58:27'),
(12, 1, 'SALMANBHAI GLASS', '2024-06-08 21:06:02', '2024-06-08 21:06:02'),
(13, 1, 'KISHANBHAI', '2024-06-08 21:14:10', '2024-06-08 21:14:10'),
(14, 1, 'JOB WORK', '2024-06-11 16:02:16', '2024-06-11 16:02:16');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `party_name` varchar(255) DEFAULT NULL,
  `category` varchar(11) DEFAULT NULL,
  `expense_title` varchar(255) NOT NULL,
  `expense_amount` decimal(15,2) NOT NULL,
  `expense_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `party_name`, `category`, `expense_title`, `expense_amount`, `expense_date`, `created_at`, `updated_at`) VALUES
(2, 1, '1', '1', 'Durgaram bhai 15/5/24 sudhino hisab', 72000.00, '2024-05-29 19:52:18', '2024-05-27 21:49:48', '2024-05-29 14:22:38'),
(3, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-11-20 20:11:57', '2024-06-08 19:12:33', '2024-06-08 19:12:33'),
(4, 1, '1', '4', 'JASHVANTBHAI BONUS', 100.00, '2023-11-22 20:13:04', '2024-06-08 19:13:55', '2024-06-08 19:13:55'),
(5, 1, '1', '4', 'KAVENDRA KAKA ACTIVA SERVICES', 600.00, '2023-11-21 20:13:57', '2024-06-08 19:15:55', '2024-06-08 19:15:55'),
(6, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-11-22 20:16:04', '2024-06-08 19:16:42', '2024-06-08 19:16:42'),
(7, 1, '1', '4', 'BUTTER MILK', 50.00, '2023-11-22 20:16:46', '2024-06-08 19:18:36', '2024-06-08 19:18:36'),
(8, 1, '1', '5', 'POOJA SALES TRANSPOTATION', 200.00, '2023-11-23 20:18:40', '2024-06-08 19:19:27', '2024-06-08 19:21:22'),
(9, 1, '1', '3', 'SILICON', 800.00, '2023-11-23 20:21:58', '2024-06-08 19:22:47', '2024-06-08 19:22:47'),
(10, 1, '1', '3', 'GRUB SCREW', 115.00, '2023-11-23 20:22:49', '2024-06-08 19:24:07', '2024-06-08 19:24:07'),
(11, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-11-24 20:24:12', '2024-06-08 19:24:44', '2024-06-08 19:24:44'),
(12, 1, '1', '4', 'UDAYBHAI GYM AREA PRINTING', 1800.00, '2023-11-24 20:24:55', '2024-06-08 19:26:30', '2024-06-08 19:26:42'),
(13, 1, '1', '4', 'UDAYBHAI GYM AREA PRINTING', 1800.00, '2023-11-24 20:24:55', '2024-06-08 19:26:31', '2024-06-08 19:26:52'),
(14, 1, '1', '3', '12 INCH GAM PATTA/SILICON CLEAR/GOLDEN SPREY 1', 2090.00, '2023-11-25 20:27:50', '2024-06-08 19:28:43', '2024-06-08 19:28:43'),
(15, 1, '1', '6', 'LALITKAKA UPAD', 10000.00, '2023-11-25 20:29:48', '2024-06-08 19:30:26', '2024-06-08 19:30:26'),
(16, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-11-27 20:30:30', '2024-06-08 19:30:58', '2024-06-08 19:30:58'),
(17, 1, '1', '2', 'LALABHAI', 785.00, '2023-11-27 20:31:08', '2024-06-08 19:31:30', '2024-06-08 19:31:30'),
(18, 1, '1', '3', 'THINAR 5 LITER', 480.00, '2023-11-28 20:31:40', '2024-06-08 19:32:27', '2024-06-08 19:32:27'),
(19, 1, '1', '10', 'KAVENDRA KAKA', 12000.00, '2023-11-28 20:35:05', '2024-06-08 19:35:30', '2024-06-08 19:35:30'),
(20, 1, '1', '3', '8 FT LAKDA ATULBHAI FARMA MATE', 500.00, '2023-11-29 20:35:38', '2024-06-08 19:37:01', '2024-06-08 19:37:01'),
(21, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-11-29 20:37:07', '2024-06-08 19:37:26', '2024-06-08 19:37:26'),
(22, 1, '1', '4', 'JAMNAGAR CURIER', 50.00, '2023-11-29 20:37:30', '2024-06-08 19:38:07', '2024-06-08 19:38:07'),
(23, 1, '1', '4', 'KAPOORBHAI SON MARRIAGE CHALO', 1000.00, '2023-11-28 20:38:09', '2024-06-08 19:38:56', '2024-06-08 19:38:56'),
(24, 1, '1', '3', 'PAINT REMOVER', 180.00, '2023-11-29 20:39:05', '2024-06-08 19:39:26', '2024-06-08 19:39:26'),
(25, 1, '1', '3', 'KEROSIN 5 LITER', 500.00, '2023-11-29 20:39:40', '2024-06-08 19:40:19', '2024-06-08 19:40:19'),
(26, 1, '1', '3', 'COMPRESSION OIL 5 LITER', 600.00, '2023-11-29 20:40:32', '2024-06-08 19:41:00', '2024-06-08 19:41:00'),
(27, 1, '1', '3', '7 X 13 SCREW', 420.00, '2023-11-30 20:41:02', '2024-06-08 19:41:30', '2024-06-08 19:41:30'),
(28, 1, '1', '5', 'RAJKOT PARSAL  AVYU', 200.00, '2023-11-30 20:41:41', '2024-06-08 19:42:17', '2024-06-08 19:42:49'),
(29, 1, '1', '4', 'MANDIR MATE GHEE', 200.00, '2023-12-01 15:23:20', '2024-06-08 19:43:53', '2024-06-08 19:43:53'),
(30, 1, '1', '5', 'RAJKOT COURIER KARYU', 250.00, '2023-12-01 14:02:55', '2024-06-08 19:45:29', '2024-06-08 19:45:29'),
(31, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-01 15:45:31', '2024-06-08 19:45:56', '2024-06-08 19:45:56'),
(32, 1, '1', '10', 'JASHVANTBHAI SALARY', 1000.00, '2023-12-04 15:45:59', '2024-06-08 19:46:36', '2024-06-08 19:46:36'),
(33, 1, '1', '10', 'MANGHUBEN SALARY', 1100.00, '2023-02-04 17:22:38', '2024-06-08 19:50:19', '2024-06-08 19:50:19'),
(34, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-04 15:50:39', '2024-06-08 19:51:35', '2024-06-08 19:51:35'),
(35, 1, '1', '5', 'MAGNUS MAAL', 250.00, '2023-12-04 05:00:37', '2024-06-08 19:53:57', '2024-06-08 19:53:57'),
(36, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2024-12-05 05:00:51', '2024-06-08 19:56:36', '2024-06-08 19:56:36'),
(37, 1, '1', '4', 'WATER BOTTEL', 150.00, '2023-12-05 05:00:03', '2024-06-08 19:57:42', '2024-06-08 19:57:42'),
(38, 1, '1', '5', 'RAJKOT PARSAL  AVYU', 450.00, '2023-12-05 05:57:58', '2024-06-08 19:58:48', '2024-06-08 19:58:48'),
(39, 1, '1', '5', 'RAJKOT COURIER KARYU', 450.00, '2023-12-06 20:58:53', '2024-06-08 19:59:41', '2024-06-08 19:59:41'),
(40, 1, '1', '4', 'OFFICE MOBILE BETRRY', 900.00, '2023-12-06 15:15:45', '2024-06-08 20:00:34', '2024-06-08 20:00:34'),
(41, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-06 15:08:29', '2024-06-08 20:15:58', '2024-06-08 20:15:58'),
(42, 1, '1', '5', 'POOJA SALES TRANSPOTATION', 350.00, '2023-12-06 15:16:01', '2024-06-08 20:16:50', '2024-06-08 20:16:50'),
(43, 1, '1', '3', 'SCREW LAVYA', 580.00, '2023-12-06 15:16:52', '2024-06-08 20:17:26', '2024-06-08 20:17:26'),
(44, 1, '1', '3', '3M TAP LAVY', 5710.00, '2023-12-06 21:17:28', '2024-06-08 20:18:01', '2024-06-08 20:18:01'),
(45, 1, '1', '4', 'OFFICE MOBILE CABLE', 200.00, '2023-12-06 15:18:04', '2024-06-08 20:19:06', '2024-06-08 20:19:06'),
(46, 1, '1', '3', 'SCREW LAVYA', 300.00, '2023-12-07 15:19:09', '2024-06-08 20:19:38', '2024-06-08 20:19:38'),
(47, 1, '1', '3', 'MINRAL WATER BOTTLE MOTI', 900.00, '2023-12-08 15:20:22', '2024-06-08 20:21:52', '2024-06-08 20:21:52'),
(48, 1, '1', '3', 'CASTER LAVYA MADHAV ORVE MATHI', 60.00, '2023-12-08 15:23:56', '2024-06-08 20:25:27', '2024-06-08 20:25:27'),
(49, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-08 15:25:36', '2024-06-08 20:25:59', '2024-06-08 20:25:59'),
(50, 1, '1', '2', 'LALABHAI', 600.00, '2023-12-08 15:50:05', '2024-06-08 20:26:31', '2024-06-08 20:26:31'),
(51, 1, '1', '3', 'FEVI STICK', 80.00, '2023-12-09 15:42:47', '2024-06-08 20:43:12', '2024-06-08 20:43:12'),
(52, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-11 15:43:13', '2024-06-08 20:48:42', '2024-06-08 20:48:42'),
(53, 1, '1', '5', 'H G MUTHA MAAL TRASPOTATION', 250.00, '2023-12-11 15:48:44', '2024-06-08 20:50:57', '2024-06-08 20:51:45'),
(54, 1, '1', '4', 'BUTTER MILK', 50.00, '2023-12-11 15:51:02', '2024-06-08 20:51:36', '2024-06-08 20:51:36'),
(55, 1, '1', '3', 'RAUTER BERRING PANU', 340.00, '2023-12-11 15:25:08', '2024-06-08 20:52:43', '2024-06-08 20:52:43'),
(56, 1, '1', '2', 'LALABHAI', 100.00, '2023-12-11 15:52:53', '2024-06-08 20:53:20', '2024-06-08 20:53:20'),
(57, 1, '1', '3', 'BOOSH MACHINE REPARING', 1056.00, '2023-12-11 16:53:23', '2024-06-08 20:53:59', '2024-06-08 20:53:59'),
(58, 1, '1', '5', 'MUMBAI COURIER', 70.00, '2023-12-11 16:54:15', '2024-06-08 20:55:24', '2024-06-08 20:55:35'),
(59, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-13 16:55:39', '2024-06-08 20:56:41', '2024-06-08 20:56:41'),
(60, 1, '1', '11', 'VIJAYBHAI BACHAT MANDAL', 4000.00, '2023-12-13 16:05:47', '2024-06-08 21:01:19', '2024-06-08 21:01:19'),
(61, 1, '1', '12', 'GLASS BILL PETE APYA', 50000.00, '2023-12-13 16:06:07', '2024-06-08 21:07:01', '2024-06-08 21:07:01'),
(62, 1, '1', '3', 'SILICON', 80.00, '2023-12-13 16:07:38', '2024-06-08 21:08:01', '2024-06-08 21:08:01'),
(63, 1, '1', '3', 'DURO CELL LAVYA', 200.00, '2023-12-14 15:08:08', '2024-06-08 21:08:59', '2024-06-08 21:08:59'),
(64, 1, '1', '3', 'ROLL PLUG LAVYA', 250.00, '2023-12-14 15:09:24', '2024-06-08 21:09:52', '2024-06-08 21:09:52'),
(65, 1, '1', '3', '2 PIVOT ROSE GOLD KARAVYA', 80.00, '2023-12-15 15:10:54', '2024-06-08 21:11:13', '2024-06-08 21:11:13'),
(66, 1, '1', '4', 'BUTTER MILK', 50.00, '2023-12-15 17:12:31', '2024-06-08 21:12:54', '2024-06-08 21:12:54'),
(67, 1, '1', '13', 'GUJRAT GOODS MATHI MAAL CHODAVYO ENA', 400.00, '2023-12-15 06:14:16', '2024-06-08 21:15:08', '2024-06-08 21:15:08'),
(68, 1, '1', '3', 'BLACK SPREY 2 NOS LAVYA', 360.00, '2023-12-15 17:15:30', '2024-06-08 21:16:17', '2024-06-08 21:16:17'),
(69, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-15 17:16:25', '2024-06-08 21:16:50', '2024-06-08 21:16:50'),
(70, 1, '1', '6', 'LALITKAKA UPAD', 25000.00, '2023-12-15 17:17:05', '2024-06-08 21:17:27', '2024-06-08 21:17:27'),
(71, 1, '1', '4', 'VRUDHA ASHRAM', 8000.00, '2023-12-15 18:17:36', '2024-06-08 21:17:58', '2024-06-08 21:17:58'),
(72, 1, '1', '7', 'TEJASHBHAI UPAD', 3000.00, '2023-12-16 18:18:01', '2024-06-08 21:18:31', '2024-06-08 21:18:31'),
(73, 1, '1', '5', 'JAYESHBHAI RIKSHAW', 1100.00, '2023-12-16 17:18:34', '2024-06-08 21:20:25', '2024-06-08 21:20:25'),
(74, 1, '1', '3', 'BUFFING DISH LAVYA', 200.00, '2023-12-16 17:20:26', '2024-06-08 21:21:04', '2024-06-08 21:21:04'),
(75, 1, '1', '5', 'RAJKOT COURIER KARYU', 200.00, '2023-12-16 17:22:29', '2024-06-08 21:23:03', '2024-06-08 21:23:03'),
(76, 1, '1', '2', 'ASHOKBHAI', 100.00, '2023-12-16 15:26:04', '2024-06-09 03:26:56', '2024-06-09 03:26:56'),
(77, 1, '1', '5', 'POOJA SALES MATHI MAAL AVYO', 700.00, '2023-12-16 15:27:02', '2024-06-09 03:28:55', '2024-06-09 03:28:55'),
(78, 1, '1', '3', '5X8 NA SCREW LAVYA', 475.00, '2023-12-18 15:29:00', '2024-06-09 03:29:53', '2024-06-09 03:29:53'),
(79, 1, '1', '4', 'CHA LAVYA', 25.00, '2023-12-18 16:29:58', '2024-06-09 03:30:44', '2024-06-09 03:30:44'),
(80, 1, '1', '5', 'POOJA SALES MATHI MAAL LAVYA', 250.00, '2023-12-18 17:30:47', '2024-06-09 03:32:16', '2024-06-09 03:32:16'),
(81, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-18 15:32:19', '2024-06-09 03:32:50', '2024-06-09 03:32:50'),
(82, 1, '1', '3', 'WD 40 1 NOS. FEVIKWIK 2 LAVYA', 290.00, '2023-12-19 15:33:12', '2024-06-09 03:34:40', '2024-06-09 03:34:40'),
(83, 1, '1', '4', 'ROTLI LAVYA', 30.00, '2023-12-19 15:34:42', '2024-06-09 03:35:51', '2024-06-09 03:35:51'),
(84, 1, '1', '5', 'RAJKOT PARCAL AVYU', 300.00, '2023-12-20 04:35:53', '2024-06-09 03:36:36', '2024-06-09 03:36:36'),
(85, 1, '1', '13', 'KISHANBHAI UPAD', 30000.00, '2023-12-20 18:36:40', '2024-06-09 03:37:23', '2024-06-09 03:37:23'),
(86, 1, '1', '3', 'GAM PATTA LAVYA', 1470.00, '2023-02-21 15:39:58', '2024-06-09 03:40:32', '2024-06-09 03:40:32'),
(87, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-20 16:40:41', '2024-06-09 03:41:31', '2024-06-09 03:41:31'),
(88, 1, '1', '3', 'STRECH ROLL LAVYA', 1500.00, '2023-12-20 17:41:47', '2024-06-09 03:42:18', '2024-06-09 03:42:18'),
(89, 1, '1', '4', 'SWITCH BOARD LAVYA', 475.00, '2023-12-22 04:42:23', '2024-06-09 03:43:41', '2024-06-09 03:44:58'),
(90, 1, '1', '4', 'CHA NI PYALI LAVYA', 300.00, '2023-12-21 17:43:44', '2024-06-09 03:44:35', '2024-06-09 03:44:35'),
(91, 1, '1', '3', 'CUTTER MACHINE 2 NOS LAVYA', 50.00, '2023-12-21 17:45:02', '2024-06-09 03:45:51', '2024-06-09 03:45:51'),
(92, 1, '1', '2', 'LALABHAI', 310.00, '2023-12-21 15:45:58', '2024-06-09 03:47:26', '2024-06-09 03:47:26'),
(93, 1, '1', '3', 'SILICON LAVYA', 720.00, '2023-12-22 16:47:42', '2024-06-09 03:48:13', '2024-06-09 03:48:13'),
(94, 1, '1', '3', 'PIVOT LAVYA', 180.00, '2023-12-23 15:48:16', '2024-06-09 03:48:55', '2024-06-09 03:48:55'),
(95, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-23 16:49:01', '2024-06-09 03:50:53', '2024-06-09 03:50:53'),
(96, 1, '1', '3', 'CUTTER BLED NANI ANE MOTI LAVYA', 70.00, '2023-12-23 16:50:57', '2024-06-09 03:51:40', '2024-06-09 03:51:40'),
(97, 1, '1', '5', 'MAGNUS OVERSEAS', 500.00, '2023-12-23 16:52:00', '2024-06-09 03:52:43', '2024-06-09 03:52:43'),
(98, 1, '1', '3', 'SCREW ANE THINAR LAVYA', 640.00, '2023-12-25 18:52:47', '2024-06-09 03:53:53', '2024-06-09 03:53:53'),
(99, 1, '1', '3', 'CUTTER MACHINE BLED', 500.00, '2023-12-25 06:53:57', '2024-06-09 03:54:30', '2024-06-09 03:54:30'),
(100, 1, '1', '4', 'RAJKOT CURIER KARYU', 50.00, '2023-12-26 16:54:34', '2024-06-09 03:55:18', '2024-06-09 03:55:18'),
(101, 1, '1', '5', 'BARODA COURIER KARYU', 100.00, '2023-12-26 17:55:23', '2024-06-09 03:57:59', '2024-06-09 03:57:59'),
(102, 1, '1', '4', 'MASHI BA NE APYA', 500.00, '2023-12-26 06:58:02', '2024-06-09 03:58:31', '2024-06-09 03:58:31'),
(103, 1, '1', '2', 'LALABHAI', 100.00, '2023-12-26 06:58:36', '2024-06-09 03:59:03', '2024-06-09 03:59:03'),
(105, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-27 16:22:08', '2024-06-11 15:22:40', '2024-06-11 15:22:40'),
(106, 1, '1', '4', 'WATER BOTTLE SMALL', 150.00, '2023-12-27 16:23:20', '2024-06-11 15:23:54', '2024-06-11 15:23:54'),
(107, 1, '1', '4', 'MATAJI MATE GHEE', 200.00, '2023-12-27 16:23:56', '2024-06-11 15:24:34', '2024-06-11 15:24:34'),
(108, 1, '1', '3', 'GOLDEN SPREY LAVYA', 230.00, '2023-12-27 16:24:36', '2024-06-11 15:25:08', '2024-06-11 15:25:08'),
(109, 1, '1', '10', 'KAVENDRA KAKA', 12000.00, '2023-12-28 16:26:37', '2024-06-11 15:27:11', '2024-06-11 15:27:11'),
(110, 1, '1', '3', 'THINER 5 LITER', 500.00, '2023-12-28 16:27:14', '2024-06-11 15:27:52', '2024-06-11 15:27:52'),
(111, 1, '1', '5', 'PORTAL AVYU', 80.00, '2023-12-28 16:27:54', '2024-06-11 15:29:03', '2024-06-11 15:29:03'),
(112, 1, '1', '5', 'VMS MATHI MAAL AVYO', 200.00, '2023-12-28 16:29:06', '2024-06-11 15:29:45', '2024-06-11 15:29:45'),
(113, 1, '1', '3', 'DAN PETE APYA', 100.00, '2023-12-28 05:29:48', '2024-06-11 15:30:28', '2024-06-11 15:30:28'),
(114, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-29 16:30:42', '2024-06-11 15:31:17', '2024-06-11 15:31:17'),
(115, 1, '1', '4', 'PALAK PAKODA LAVYA', 150.00, '2023-12-29 16:31:22', '2024-06-11 15:32:12', '2024-06-11 15:32:12'),
(116, 1, '1', '4', 'VICKS LAVYA', 24.00, '2023-12-29 16:32:14', '2024-06-11 15:33:16', '2024-06-11 15:33:16'),
(117, 1, '1', '4', 'TEJASBHAI CAR REPARING', 21000.00, '2023-12-29 16:35:05', '2024-06-11 15:36:33', '2024-06-11 15:36:33'),
(118, 1, '1', '4', 'GREEN TEA LAVYA', 581.00, '2023-12-29 16:36:35', '2024-06-11 15:37:08', '2024-06-11 15:37:20'),
(119, 1, '1', '3', 'PIVOT AND PATRA COATING', 740.00, '2023-12-29 16:37:54', '2024-06-11 15:38:30', '2024-06-11 15:38:30'),
(120, 1, '1', '5', 'POOJA SALES MAAL AVYO', 200.00, '2023-12-30 16:38:40', '2024-06-11 15:39:10', '2024-06-11 15:39:10'),
(121, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2023-12-30 16:39:12', '2024-06-11 15:39:54', '2024-06-11 15:39:54'),
(122, 1, '1', '2', 'LALABHAI', 300.00, '2023-12-30 16:40:31', '2024-06-11 15:41:31', '2024-06-11 15:41:31'),
(123, 1, '1', '3', 'SCREW LAVYA', 400.00, '2024-01-01 16:44:57', '2024-06-11 15:45:37', '2024-06-11 15:45:53'),
(124, 1, '1', '4', 'GREREN PEN LAVYA', 25.00, '2024-01-01 16:51:36', '2024-06-11 15:51:59', '2024-06-11 15:51:59'),
(125, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2024-01-01 16:52:05', '2024-06-11 15:52:28', '2024-06-11 15:52:28'),
(126, 1, '1', '5', 'GUJRAT GOODS MATHI MAAL CHOVADYO', 660.00, '2024-01-01 16:53:58', '2024-06-11 15:54:36', '2024-06-11 15:54:36'),
(127, 1, '1', '14', 'AJAYBHAI NOVEMBER AND DECEMBER BILL', 200000.00, '2024-01-01 16:55:03', '2024-06-11 15:58:11', '2024-06-11 16:02:27'),
(128, 1, '1', '5', 'JAGHDISHBHAI TENDOM NA AVYA', 57.00, '2024-01-02 16:58:19', '2024-06-11 15:59:17', '2024-06-11 15:59:17'),
(129, 1, '1', '14', 'HETALBHAI NE JAYESHBHAI MODI SITE PETE APYA', 800.00, '2024-01-02 17:03:35', '2024-06-11 16:05:56', '2024-06-11 16:05:56'),
(130, 1, '1', '14', 'HETALBHAI NE DIWALI PELA BAKI HISAB PETE', 2000.00, '2024-01-02 17:06:27', '2024-06-11 16:07:07', '2024-06-11 16:07:07'),
(131, 1, '1', '14', 'HETALBHAI NE SHILAPNBHAI NE TYA KAMKARYU ENA PETE', 2000.00, '2024-01-02 17:08:29', '2024-06-11 16:09:00', '2024-06-11 16:09:00'),
(132, 1, '1', '10', 'MANGHUBEN', 1100.00, '2024-01-02 17:09:28', '2024-06-11 16:09:48', '2024-06-11 16:09:48'),
(133, 1, '1', '5', 'MAGNUS MATHI MAAL AVYO', 270.00, '2024-01-02 17:09:50', '2024-06-11 16:10:33', '2024-06-11 16:10:33'),
(134, 1, '1', '5', 'VMS MATHI MAAL AVYO', 200.00, '2024-01-02 17:10:35', '2024-06-11 16:10:56', '2024-06-11 16:10:56'),
(135, 1, '1', '4', 'COLD COFFE LAVYA', 457.00, '2024-01-02 17:11:30', '2024-06-11 16:11:55', '2024-06-11 16:11:55'),
(136, 1, '1', '10', 'JASHVANTBHAI', 1000.00, '2024-01-31 17:11:57', '2024-06-11 16:12:19', '2024-06-11 16:12:19'),
(137, 1, '1', '3', 'WHITE SPREY 2 NOS.', 360.00, '2024-01-03 17:12:25', '2024-06-11 16:12:44', '2024-06-11 16:12:44'),
(138, 1, '1', '5', 'POOJA SALES MAAL AVYO', 350.00, '2024-01-03 17:18:03', '2024-06-11 16:18:24', '2024-06-11 16:18:24'),
(139, 1, '1', '5', 'RAJKOT MAAL AVYO', 170.00, '2024-01-03 17:18:27', '2024-06-11 16:19:07', '2024-06-11 16:19:07'),
(140, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2024-01-03 17:19:18', '2024-06-11 16:19:42', '2024-06-11 16:19:42'),
(141, 1, '1', '10', 'LALABHAI', 20000.00, '2024-01-03 17:20:31', '2024-06-11 16:21:01', '2024-06-11 16:21:01'),
(142, 1, '1', '10', 'BINAL', 14000.00, '2024-01-03 17:21:21', '2024-06-11 16:21:43', '2024-06-11 16:21:43'),
(143, 1, '1', '10', 'ANISHA', 10000.00, '2024-01-03 17:21:52', '2024-06-11 16:22:13', '2024-06-11 16:22:13'),
(144, 1, '1', '8', 'TEJASHBHAI UPAD', 5200.00, '2024-01-03 17:22:17', '2024-06-11 16:22:57', '2024-06-11 16:33:08'),
(145, 1, '1', '6', 'LALITBHAI UPAD', 8900.00, '2024-01-03 17:33:30', '2024-06-11 16:34:01', '2024-06-11 16:34:01'),
(146, 1, '1', '3', 'MEGNET AND MAGER TAP LAVY', 195.00, '2024-01-04 17:35:08', '2024-06-11 16:35:39', '2024-06-11 16:35:39'),
(147, 1, '1', '3', 'SCREW LAVYA', 220.00, '2024-01-04 17:35:44', '2024-06-11 16:36:04', '2024-06-11 16:36:04'),
(148, 1, '1', '6', 'LALITBHAI UPAD', 2000.00, '2024-01-05 17:36:13', '2024-06-11 16:36:30', '2024-06-11 16:36:30'),
(149, 1, '1', '3', '4 PIVOT COLOUR KARAVYO', 160.00, '2024-01-08 17:37:26', '2024-06-11 16:37:59', '2024-06-11 16:37:59'),
(150, 1, '1', '5', 'RAJKOT MAAL AVYO', 100.00, '2024-01-08 17:38:16', '2024-06-11 16:38:55', '2024-06-11 16:38:55'),
(151, 1, '1', '3', 'BLASTING KARAVYU', 70.00, '2024-01-08 17:39:29', '2024-06-11 16:39:56', '2024-06-11 16:40:16'),
(152, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2024-01-05 17:40:19', '2024-06-11 16:40:53', '2024-06-11 16:40:53'),
(153, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2024-01-07 17:41:38', '2024-06-11 16:41:56', '2024-06-11 16:41:56'),
(154, 1, '1', '5', 'RAJKOT MAAL AVYO', 100.00, '2024-01-09 17:42:00', '2024-06-11 16:42:17', '2024-06-11 16:42:17'),
(155, 1, '1', '3', 'GAM PATTA LAVYA', 1980.00, '2024-01-09 17:42:28', '2024-06-11 16:42:56', '2024-06-11 16:42:56'),
(156, 1, '1', '4', 'CHODAFADI LAVYA', 100.00, '2024-01-10 18:29:35', '2024-06-11 17:30:04', '2024-06-11 17:30:04'),
(157, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2024-01-10 18:30:21', '2024-06-11 17:30:39', '2024-06-11 17:30:39'),
(158, 1, '1', '5', 'JAYESHBHAI', 2200.00, '2024-01-10 18:30:41', '2024-06-11 17:31:03', '2024-06-11 17:31:03'),
(159, 1, '1', '2', 'LALIT KAKA', 1000.00, '2024-01-10 18:31:04', '2024-06-11 17:31:49', '2024-06-11 17:31:49'),
(160, 1, '1', '3', 'ACID LAVYA', 150.00, '2024-01-12 18:43:32', '2024-06-11 17:43:57', '2024-06-11 17:43:57'),
(161, 1, '1', '2', 'KAVENDRA KAKA', 350.00, '2024-01-12 18:43:59', '2024-06-11 17:44:14', '2024-06-11 17:44:14'),
(162, 1, '1', '4', 'BRODA ANE AMBAWADI PARSAL KARYU', 60.00, '2024-01-12 18:44:21', '2024-06-11 17:45:01', '2024-06-11 17:45:01'),
(163, 1, '1', '6', 'LALITBHAI UPAD', 1000.00, '2024-01-12 18:45:02', '2024-06-11 17:45:56', '2024-06-11 17:45:56'),
(164, 1, '1', '3', 'PVC SOLUTION LAVYA', 130.00, '2024-01-13 18:45:58', '2024-06-11 17:46:26', '2024-06-11 17:46:26'),
(165, 1, '1', '4', 'PLASTIC SPOON & PADIYA AND GLASS', 80.00, '2024-01-13 18:46:28', '2024-06-11 17:47:37', '2024-06-11 17:47:37'),
(166, 1, '1', '5', 'POOJA SALES MAAL AVYO E SAHAJANAND MA GAYO', 400.00, '2024-01-13 18:47:52', '2024-06-11 17:49:17', '2024-06-11 17:49:17'),
(167, 1, '1', '4', 'KAVENDRA KAKA ACTIVA MA TYUB NAKHVI', 250.00, '2024-01-13 18:49:42', '2024-06-11 17:50:49', '2024-06-11 17:50:49'),
(168, 1, '1', '5', 'KISHANBHAI MAAL LAVYA TENI BILTI NA', 280.00, '2024-01-13 18:50:55', '2024-06-11 17:51:24', '2024-06-11 17:51:24'),
(169, 1, '1', '2', 'LALABHAI', 808.00, '2024-01-13 18:51:40', '2024-06-11 17:52:27', '2024-06-11 17:52:27'),
(170, 1, '1', '3', 'SENTING VAYAR/HAND GLOS AND JARO', 650.00, '2024-01-13 18:52:31', '2024-06-11 17:54:17', '2024-06-11 17:54:17'),
(171, 1, '1', '2', 'KAVENDRA KAKA', 300.00, '2024-01-16 18:54:21', '2024-06-11 17:54:41', '2024-06-11 17:54:41'),
(172, 1, '1', '3', '2 SHUTTER KARAVYA', 400.00, '2024-01-16 18:54:47', '2024-06-11 17:55:23', '2024-06-11 17:55:23'),
(173, 1, '1', '3', 'JARO LAVYA', 279.00, '2024-01-16 18:55:30', '2024-06-11 17:56:03', '2024-06-11 17:56:03'),
(174, 1, '1', '4', 'WATER BOTTLE SMALL', 150.00, '2024-01-16 18:56:05', '2024-06-11 17:56:24', '2024-06-11 17:56:24'),
(175, 1, '1', '3', 'PAINT REMOVER', 300.00, '2024-01-17 18:56:28', '2024-06-11 17:56:48', '2024-06-11 17:56:48');

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
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `party_name` varchar(255) DEFAULT NULL,
  `category` varchar(11) DEFAULT NULL,
  `income_title` varchar(255) NOT NULL,
  `income_amount` decimal(15,2) NOT NULL,
  `income_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`id`, `user_id`, `party_name`, `category`, `income_title`, `income_amount`, `income_date`, `created_at`, `updated_at`) VALUES
(3, 1, NULL, NULL, 'Jayni farm advance', 200000.00, '2024-05-27 20:20:23', '2024-05-27 21:48:56', '2024-05-27 21:48:56'),
(5, 1, '1', '1', 'test', 10000.00, '2024-05-29 20:20:23', '2024-05-29 20:20:42', '2024-05-29 20:20:42'),
(6, 1, '1', 'Category', 'PARAM SHAH', 250000.00, '2023-12-13 16:05:02', '2024-06-08 21:05:33', '2024-06-08 21:05:33'),
(7, 1, '1', 'Category', 'ANANATARA ALPINE ANANTARA EBORD', 200000.00, '2023-12-19 17:37:47', '2024-06-09 03:39:42', '2024-06-09 03:39:42'),
(8, 1, '1', 'Category', 'VRIXESHBHAI PANCHOLI', 10000.00, '2023-12-29 16:33:35', '2024-06-11 15:34:39', '2024-06-11 15:34:39'),
(9, 1, '1', 'Category', 'DILIPBHAI MEGNUS ENTRY FERVI', 209000.00, '2024-01-01 16:42:52', '2024-06-11 15:43:32', '2024-06-11 15:43:32'),
(10, 1, '1', 'Category', 'RAJENDRABHAI NA AVYA', 2800.00, '2024-01-10 18:42:45', '2024-06-11 17:43:18', '2024-06-11 17:43:18');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_09_30_224707_create_incomes_table', 1),
(5, '2019_09_30_224745_create_expenses_table', 1),
(6, '2019_10_01_163012_create_notes_table', 1),
(7, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(8, '2024_05_29_062253_create_categories_table', 2),
(9, '2024_05_29_062425_create_parties_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `note_title` varchar(255) NOT NULL,
  `note_amount` decimal(15,2) NOT NULL,
  `note_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

CREATE TABLE `parties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `party_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parties`
--

INSERT INTO `parties` (`id`, `user_id`, `party_name`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 1, 'test Party', '7777777777', 'Ahmedabad', '2024-05-29 11:12:55', '2024-05-29 11:12:55');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@admin.com', '2024-05-24 18:11:02', '$2y$10$5t9GrChxn/qhyOxhgUIKau9cH2dP9XHtIWUqI/vrcOpeiKfukGI3i', 'XBCaxxzYZ9eCI6xZIUXjXyBNnCpyjuUNB819tVLFoXcv7cc6cSZ2ckkYGOU5', '2024-05-24 18:11:02', '2024-05-24 18:11:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parties`
--
ALTER TABLE `parties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parties`
--
ALTER TABLE `parties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
