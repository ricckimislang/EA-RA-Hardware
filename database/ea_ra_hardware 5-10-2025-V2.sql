-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 10, 2025 at 03:44 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ea_ra_hardware`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

DROP TABLE IF EXISTS `attendance_records`;
CREATE TABLE IF NOT EXISTS `attendance_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `time_in` datetime NOT NULL,
  `time_out` datetime DEFAULT NULL,
  `total_hours` int DEFAULT NULL,
  `minutes` int DEFAULT '0',
  `status` enum('present','late','half-day','absent') DEFAULT 'present',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=153 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance_records`
--

INSERT INTO `attendance_records` (`id`, `employee_id`, `time_in`, `time_out`, `total_hours`, `minutes`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-04-01 08:00:00', '2025-04-01 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(2, 1, '2025-04-02 08:15:00', '2025-04-02 17:00:00', 9, 0, 'late', 'Late arrival', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(3, 1, '2025-04-03 08:00:00', '2025-04-03 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(4, 1, '2025-04-04 00:00:00', NULL, 0, 0, 'absent', 'No show', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(5, 1, '2025-04-05 08:00:00', '2025-04-05 12:00:00', 4, 0, 'half-day', 'Left early - personal reasons', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(6, 1, '2025-04-06 08:00:00', '2025-04-06 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(7, 1, '2025-04-07 08:30:00', '2025-04-07 17:00:00', 9, 0, 'late', 'Traffic delay', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(8, 1, '2025-04-08 08:00:00', '2025-04-08 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(9, 1, '2025-04-09 08:00:00', '2025-04-09 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(10, 1, '2025-04-10 00:00:00', NULL, 0, 0, 'absent', 'Sick leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(11, 1, '2025-04-11 08:00:00', '2025-04-11 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(12, 1, '2025-04-12 08:20:00', '2025-04-12 17:00:00', 9, 0, 'late', 'Car trouble', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(13, 1, '2025-04-13 08:00:00', '2025-04-13 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(14, 1, '2025-04-14 08:00:00', '2025-04-14 12:30:00', 5, 0, 'half-day', 'Doctor appointment', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(15, 1, '2025-04-15 08:00:00', '2025-04-15 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(16, 1, '2025-04-16 08:00:00', '2025-04-16 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(17, 1, '2025-04-17 00:00:00', NULL, 0, 0, 'absent', 'Family emergency', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(18, 1, '2025-04-18 08:00:00', '2025-04-18 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(19, 1, '2025-04-19 08:10:00', '2025-04-19 17:00:00', 9, 0, 'late', 'Minor delay', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(20, 1, '2025-04-20 08:00:00', '2025-04-20 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(21, 1, '2025-04-21 08:00:00', '2025-04-21 12:00:00', 4, 0, 'half-day', 'Personal matters', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(22, 1, '2025-04-22 08:00:00', '2025-04-22 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(23, 1, '2025-04-23 08:25:00', '2025-04-23 17:00:00', 9, 0, 'late', 'Heavy traffic', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(24, 1, '2025-04-24 08:00:00', '2025-04-24 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(25, 1, '2025-04-25 00:00:00', NULL, 0, 0, 'absent', 'Vacation leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(26, 1, '2025-04-26 08:00:00', '2025-04-26 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(27, 1, '2025-04-27 08:00:00', '2025-04-27 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(28, 1, '2025-04-28 08:00:00', '2025-04-28 12:15:00', 4, 0, 'half-day', 'Family event', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(29, 1, '2025-04-29 08:00:00', '2025-04-29 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(30, 1, '2025-04-30 08:18:00', '2025-04-30 17:00:00', 9, 0, 'late', 'Public transport delay', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(31, 2, '2025-04-01 08:00:00', '2025-04-01 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(32, 2, '2025-04-02 00:00:00', NULL, 0, 0, 'absent', 'Sick leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(33, 2, '2025-04-03 08:00:00', '2025-04-03 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(34, 2, '2025-04-04 08:22:00', '2025-04-04 17:00:00', 9, 0, 'late', 'Traffic jam', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(35, 2, '2025-04-05 08:00:00', '2025-04-05 12:30:00', 5, 0, 'half-day', 'Personal appointment', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(36, 2, '2025-04-06 08:00:00', '2025-04-06 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(37, 2, '2025-04-07 08:00:00', '2025-04-07 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(38, 2, '2025-04-08 08:17:00', '2025-04-08 17:00:00', 9, 0, 'late', 'Car problems', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(39, 2, '2025-04-09 08:00:00', '2025-04-09 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(40, 2, '2025-04-10 08:00:00', '2025-04-10 12:00:00', 4, 0, 'half-day', 'Medical checkup', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(41, 2, '2025-04-11 00:00:00', NULL, 0, 0, 'absent', 'Personal leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(42, 2, '2025-04-12 08:00:00', '2025-04-12 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(43, 2, '2025-04-13 08:25:00', '2025-04-13 17:00:00', 9, 0, 'late', 'Overslept', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(44, 2, '2025-04-14 08:00:00', '2025-04-14 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(45, 2, '2025-04-15 08:00:00', '2025-04-15 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(46, 2, '2025-04-16 08:00:00', '2025-04-16 12:15:00', 4, 0, 'half-day', 'Family matter', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(47, 2, '2025-04-17 08:00:00', '2025-04-17 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(48, 2, '2025-04-18 08:15:00', '2025-04-18 17:00:00', 9, 0, 'late', 'Public transport delay', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(49, 2, '2025-04-19 08:00:00', '2025-04-19 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(50, 2, '2025-04-20 00:00:00', NULL, 0, 0, 'absent', 'Emergency leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(51, 2, '2025-04-21 08:00:00', '2025-04-21 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(52, 2, '2025-04-22 08:00:00', '2025-04-22 12:30:00', 5, 0, 'half-day', 'Personal reasons', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(53, 2, '2025-04-23 08:20:00', '2025-04-23 17:00:00', 9, 0, 'late', 'Traffic congestion', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(54, 2, '2025-04-24 08:00:00', '2025-04-24 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(55, 2, '2025-04-25 08:00:00', '2025-04-25 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(56, 2, '2025-04-26 00:00:00', NULL, 0, 0, 'absent', 'Vacation', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(57, 2, '2025-04-27 08:00:00', '2025-04-27 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(58, 2, '2025-04-28 08:18:00', '2025-04-28 17:00:00', 9, 0, 'late', 'Road closure', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(59, 2, '2025-04-29 08:00:00', '2025-04-29 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(60, 2, '2025-04-30 08:00:00', '2025-04-30 12:00:00', 4, 0, 'half-day', 'Doctor visit', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(61, 3, '2025-04-01 08:15:00', '2025-04-01 17:00:00', 9, 0, 'late', 'Bus delay', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(62, 3, '2025-04-02 08:00:00', '2025-04-02 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(63, 3, '2025-04-03 08:00:00', '2025-04-03 12:00:00', 4, 0, 'half-day', 'Personal errand', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(64, 3, '2025-04-04 08:00:00', '2025-04-04 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(65, 3, '2025-04-05 00:00:00', NULL, 0, 0, 'absent', 'Sick leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(66, 3, '2025-04-06 08:00:00', '2025-04-06 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(67, 3, '2025-04-07 08:20:00', '2025-04-07 17:00:00', 9, 0, 'late', 'Train delay', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(68, 3, '2025-04-08 08:00:00', '2025-04-08 12:15:00', 4, 0, 'half-day', 'Family emergency', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(69, 3, '2025-04-09 08:00:00', '2025-04-09 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(70, 3, '2025-04-10 08:00:00', '2025-04-10 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(71, 3, '2025-04-11 08:25:00', '2025-04-11 17:00:00', 9, 0, 'late', 'Heavy traffic', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(72, 3, '2025-04-12 00:00:00', NULL, 0, 0, 'absent', 'Personal leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(73, 3, '2025-04-13 08:00:00', '2025-04-13 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(74, 3, '2025-04-14 08:00:00', '2025-04-14 12:30:00', 5, 0, 'half-day', 'Dental appointment', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(75, 3, '2025-04-15 08:17:00', '2025-04-15 17:00:00', 9, 0, 'late', 'Car trouble', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(76, 3, '2025-04-16 08:00:00', '2025-04-16 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(77, 3, '2025-04-17 08:00:00', '2025-04-17 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(78, 3, '2025-04-18 00:00:00', NULL, 0, 0, 'absent', 'Emergency leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(79, 3, '2025-04-19 08:00:00', '2025-04-19 12:00:00', 4, 0, 'half-day', 'Personal matters', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(80, 3, '2025-04-20 08:22:00', '2025-04-20 17:00:00', 9, 0, 'late', 'Traffic jam', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(81, 3, '2025-04-21 08:00:00', '2025-04-21 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(82, 3, '2025-04-22 08:00:00', '2025-04-22 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(83, 3, '2025-04-23 00:00:00', NULL, 0, 0, 'absent', 'Sick leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(84, 3, '2025-04-24 08:15:00', '2025-04-24 17:00:00', 9, 0, 'late', 'Public transport issues', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(85, 3, '2025-04-25 08:00:00', '2025-04-25 12:15:00', 4, 0, 'half-day', 'Doctor appointment', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(86, 3, '2025-04-26 08:00:00', '2025-04-26 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(87, 3, '2025-04-27 08:18:00', '2025-04-27 17:00:00', 9, 0, 'late', 'Road construction', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(88, 3, '2025-04-28 08:00:00', '2025-04-28 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(89, 3, '2025-04-29 00:00:00', NULL, 0, 0, 'absent', 'Vacation', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(90, 3, '2025-04-30 08:00:00', '2025-04-30 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(91, 4, '2025-04-01 00:00:00', NULL, 0, 0, 'absent', 'Sick leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(92, 4, '2025-04-02 08:20:00', '2025-04-02 17:00:00', 9, 0, 'late', 'Traffic delay', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(93, 4, '2025-04-03 08:00:00', '2025-04-03 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(94, 4, '2025-04-04 08:00:00', '2025-04-04 12:30:00', 5, 0, 'half-day', 'Personal appointment', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(95, 4, '2025-04-05 08:00:00', '2025-04-05 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(96, 4, '2025-04-06 08:25:00', '2025-04-06 17:00:00', 9, 0, 'late', 'Car problems', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(97, 4, '2025-04-07 08:00:00', '2025-04-07 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(98, 4, '2025-04-08 00:00:00', NULL, 0, 0, 'absent', 'Family emergency', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(99, 4, '2025-04-09 08:00:00', '2025-04-09 12:00:00', 4, 0, 'half-day', 'Medical checkup', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(100, 4, '2025-04-10 08:15:00', '2025-04-10 17:00:00', 9, 0, 'late', 'Train delay', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(101, 4, '2025-04-11 08:00:00', '2025-04-11 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(102, 4, '2025-04-12 08:00:00', '2025-04-12 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(103, 4, '2025-04-13 08:22:00', '2025-04-13 17:00:00', 9, 0, 'late', 'Traffic congestion', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(104, 4, '2025-04-14 00:00:00', NULL, 0, 0, 'absent', 'Personal leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(105, 4, '2025-04-15 08:00:00', '2025-04-15 12:15:00', 4, 0, 'half-day', 'Family matter', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(106, 4, '2025-04-16 08:00:00', '2025-04-16 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(107, 4, '2025-04-17 08:17:00', '2025-04-17 17:00:00', 9, 0, 'late', 'Bus delay', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(108, 4, '2025-04-18 08:00:00', '2025-04-18 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(109, 4, '2025-04-19 08:00:00', '2025-04-19 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(110, 4, '2025-04-20 00:00:00', NULL, 0, 0, 'absent', 'Sick leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(111, 4, '2025-04-21 08:00:00', '2025-04-21 12:30:00', 5, 0, 'half-day', 'Doctor visit', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(112, 4, '2025-04-22 08:25:00', '2025-04-22 17:00:00', 9, 0, 'late', 'Heavy traffic', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(113, 4, '2025-04-23 08:00:00', '2025-04-23 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(114, 4, '2025-04-24 08:00:00', '2025-04-24 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(115, 4, '2025-04-25 08:18:00', '2025-04-25 17:00:00', 9, 0, 'late', 'Road closure', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(116, 4, '2025-04-26 00:00:00', NULL, 0, 0, 'absent', 'Vacation', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(117, 4, '2025-04-27 08:00:00', '2025-04-27 12:00:00', 4, 0, 'half-day', 'Personal errand', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(118, 4, '2025-04-28 08:00:00', '2025-04-28 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(119, 4, '2025-04-29 08:20:00', '2025-04-29 17:00:00', 9, 0, 'late', 'Public transport issues', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(120, 4, '2025-04-30 08:00:00', '2025-04-30 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(121, 5, '2025-04-01 08:00:00', '2025-04-01 12:15:00', 4, 0, 'half-day', 'Doctor appointment', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(122, 5, '2025-04-02 08:00:00', '2025-04-02 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(123, 5, '2025-04-03 08:25:00', '2025-04-03 17:00:00', 9, 0, 'late', 'Traffic jam', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(124, 5, '2025-04-04 00:00:00', NULL, 0, 0, 'absent', 'Sick leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(125, 5, '2025-04-05 08:00:00', '2025-04-05 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(126, 5, '2025-04-06 08:15:00', '2025-04-06 17:00:00', 9, 0, 'late', 'Car trouble', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(127, 5, '2025-04-07 08:00:00', '2025-04-07 12:30:00', 5, 0, 'half-day', 'Personal matters', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(128, 5, '2025-04-08 08:00:00', '2025-04-08 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(129, 5, '2025-04-09 08:22:00', '2025-04-09 17:00:00', 9, 0, 'late', 'Train delay', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(130, 5, '2025-04-10 08:00:00', '2025-04-10 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(131, 5, '2025-04-11 00:00:00', NULL, 0, 0, 'absent', 'Family emergency', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(132, 5, '2025-04-12 08:00:00', '2025-04-12 12:00:00', 4, 0, 'half-day', 'Medical checkup', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(133, 5, '2025-04-13 08:17:00', '2025-04-13 17:00:00', 9, 0, 'late', 'Bus delay', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(134, 5, '2025-04-14 08:00:00', '2025-04-14 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(135, 5, '2025-04-15 08:00:00', '2025-04-15 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(136, 5, '2025-04-16 00:00:00', NULL, 0, 0, 'absent', 'Personal leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(137, 5, '2025-04-17 08:20:00', '2025-04-17 17:00:00', 9, 0, 'late', 'Heavy traffic', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(138, 5, '2025-04-18 08:00:00', '2025-04-18 12:15:00', 4, 0, 'half-day', 'Family matter', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(139, 5, '2025-04-19 08:00:00', '2025-04-19 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(140, 5, '2025-04-20 08:25:00', '2025-04-20 17:00:00', 9, 0, 'late', 'Road construction', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(141, 5, '2025-04-21 08:00:00', '2025-04-21 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(142, 5, '2025-04-22 00:00:00', NULL, 0, 0, 'absent', 'Sick leave', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(143, 5, '2025-04-23 08:00:00', '2025-04-23 12:30:00', 5, 0, 'half-day', 'Dental appointment', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(144, 5, '2025-04-24 08:18:00', '2025-04-24 17:00:00', 9, 0, 'late', 'Traffic congestion', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(145, 5, '2025-04-25 08:00:00', '2025-04-25 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(146, 5, '2025-04-26 08:00:00', '2025-04-26 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(147, 5, '2025-04-27 08:15:00', '2025-04-27 17:00:00', 9, 0, 'late', 'Public transport issues', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(148, 5, '2025-04-28 00:00:00', NULL, 0, 0, 'absent', 'Vacation', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(149, 5, '2025-04-29 08:00:00', '2025-04-29 12:00:00', 4, 0, 'half-day', 'Personal errand', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(150, 5, '2025-04-30 08:00:00', '2025-04-30 17:00:00', 9, 0, 'present', 'Normal shift', '2025-04-28 14:19:32', '2025-04-28 14:19:32'),
(152, 20, '2025-05-10 07:00:25', '2025-05-10 18:18:48', 10, 18, 'present', NULL, '2025-05-09 23:00:25', '2025-05-10 10:18:48');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_settings`
--

DROP TABLE IF EXISTS `attendance_settings`;
CREATE TABLE IF NOT EXISTS `attendance_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance_settings`
--

INSERT INTO `attendance_settings` (`id`, `setting_name`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'work_start_time', '07:00:00', 'Regular work start time', '2025-04-18 17:08:43', '2025-05-10 10:21:01'),
(2, 'work_end_time', '17:00:00', 'Regular work end time', '2025-04-18 17:08:43', '2025-04-18 17:08:43'),
(3, 'late_threshold_minutes', '15', 'Minutes after work start time to mark as late', '2025-04-18 17:08:43', '2025-04-18 17:08:43'),
(4, 'half_day_hours', '4', 'Minimum hours to be counted as half-day', '2025-04-18 17:08:43', '2025-04-18 17:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `brand_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Bosch', 'High-quality power tools and accessories', '2025-04-28 14:08:44', '2025-04-28 14:08:44'),
(2, 'Stanley', 'Professional hand tools and storage solutions', '2025-04-28 14:08:44', '2025-04-28 14:08:44'),
(3, 'Makita', 'Power tools and accessories', '2025-04-28 14:08:44', '2025-04-28 14:08:44'),
(4, 'Dewalt', 'Professional power tools', '2025-04-28 14:08:44', '2025-04-28 14:08:44'),
(5, '3M', 'Safety and construction products', '2025-04-28 14:08:44', '2025-04-28 14:08:44'),
(6, 'Dulux', 'Premium paint products', '2025-04-28 14:08:44', '2025-04-28 14:08:44'),
(7, 'Cemex', 'Building materials', '2025-04-28 14:08:44', '2025-04-28 14:08:44'),
(8, 'KYK', 'Paint and coatings', '2025-04-28 14:08:44', '2025-04-28 14:08:44'),
(9, 'Hilti', 'Professional construction tools', '2025-04-28 14:08:44', '2025-04-28 14:08:44'),
(10, 'Rust-Oleum', 'Protective paints and coatings', '2025-04-28 14:08:44', '2025-04-28 14:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `cash_advances`
--

DROP TABLE IF EXISTS `cash_advances`;
CREATE TABLE IF NOT EXISTS `cash_advances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `request_date` date NOT NULL,
  `approval_date` date DEFAULT NULL,
  `approved_by` int DEFAULT NULL,
  `status` enum('pending','approved','rejected','paid') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `notes` text,
  `payroll_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  KEY `approved_by` (`approved_by`),
  KEY `payroll_id` (`payroll_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cash_advances`
--

INSERT INTO `cash_advances` (`id`, `employee_id`, `amount`, `request_date`, `approval_date`, `approved_by`, `status`, `payment_method`, `notes`, `payroll_id`, `created_at`, `updated_at`) VALUES
(1, 4, 4500.00, '2025-05-10', '2025-05-10', 1, 'approved', 'cash', 'hospital bills\n\n[approved on 2025-05-10 15:13:18] ', NULL, '2025-05-10 15:13:14', '2025-05-10 15:13:18'),
(2, 20, 1500.00, '2025-05-10', '2025-05-10', 1, 'approved', 'cash', '123\n\n[approved on 2025-05-10 15:13:40] ', NULL, '2025-05-10 15:13:38', '2025-05-10 15:13:40');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Tools', 'Hand tools and power tools for construction and repairs', '2025-04-17 12:51:27', '2025-04-17 12:51:27'),
(2, 'Hardware', 'General hardware items including fasteners and fittings', '2025-04-17 12:51:27', '2025-04-17 12:51:27'),
(3, 'Electrical', 'Electrical supplies, wiring, and components', '2025-04-17 12:51:27', '2025-04-17 12:51:27'),
(4, 'Plumbing', 'Plumbing supplies, pipes, and fixtures', '2025-04-17 12:51:27', '2025-04-17 12:51:27'),
(5, 'Paint', 'Paint, primers, and painting supplies', '2025-04-17 12:51:27', '2025-04-17 12:51:27'),
(6, 'Safety Equipment', 'Protective gear and safety devices', '2025-04-21 04:14:27', '2025-04-21 04:14:27'),
(7, 'Fasteners', 'Screws, nails, bolts, and other fastening hardware', '2025-04-21 04:14:27', '2025-04-21 04:14:27'),
(8, 'Garden Tools', 'Equipment for landscaping and gardening', '2025-04-21 04:14:27', '2025-04-21 04:14:27'),
(9, 'Power Tools', 'Electric and battery-powered tools', '2025-04-21 04:14:27', '2025-04-21 04:14:27'),
(10, 'Hand Tools', 'Manual tools for various applications', '2025-04-21 04:14:27', '2025-04-21 04:14:27'),
(11, 'Building Materials', 'Construction materials and supplies', '2025-04-21 04:14:27', '2025-04-21 04:14:27'),
(12, 'Automotive', 'Tools and supplies for vehicle maintenance', '2025-04-21 04:14:27', '2025-04-21 04:14:27'),
(13, 'Adhesives', 'Glues, tapes, and bonding agents', '2025-04-21 04:14:27', '2025-04-21 04:14:27'),
(14, 'Storage Solutions', 'Tool boxes, cabinets, and organizers', '2025-04-21 04:14:27', '2025-04-21 04:14:27'),
(15, 'Lighting', 'Indoor and outdoor lighting fixtures', '2025-04-21 04:14:27', '2025-04-21 04:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `position_id` int NOT NULL,
  `employment_type` enum('full-time','part-time') NOT NULL,
  `salary_rate_type` enum('daily','monthly','hourly') NOT NULL,
  `date_hired` date NOT NULL,
  `overtime_rate` decimal(10,2) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `position_id` (`position_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `full_name`, `position_id`, `employment_type`, `salary_rate_type`, `date_hired`, `overtime_rate`, `contact_number`, `email_address`, `created_at`, `updated_at`) VALUES
(1, 'Juan Dela Cruz', 1, 'full-time', 'monthly', '2023-01-15', 150.00, '09171234567', 'juan.delacruz@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(2, 'Maria Santos', 2, 'full-time', 'monthly', '2023-02-20', 140.00, '09171234568', 'maria.santos@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(3, 'Pedro Reyes', 3, 'full-time', 'monthly', '2023-03-10', 100.00, '09171234569', 'pedro.reyes@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(4, 'Ana Martinez', 3, 'full-time', 'monthly', '2023-04-05', 100.00, '09171234570', 'ana.martinez@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(5, 'Luis Garcia', 4, 'full-time', 'monthly', '2023-05-12', 110.00, '09171234571', 'luis.garcia@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(6, 'Carlos Lopez', 5, 'full-time', 'monthly', '2023-06-15', 90.00, '09171234572', 'carlos.lopez@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(7, 'Elena Torres', 8, 'full-time', 'monthly', '2023-07-20', 120.00, '09171234573', 'elena.torres@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(8, 'Miguel Ramos', 9, 'full-time', 'monthly', '2023-08-10', 80.00, '09171234574', 'miguel.ramos@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(9, 'Rosa Mendoza', 9, 'full-time', 'monthly', '2023-09-05', 80.00, '09171234575', 'rosa.mendoza@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(10, 'Jose Cruz', 9, 'full-time', 'monthly', '2023-10-12', 80.00, '09171234576', 'jose.cruz@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(11, 'Sofia Rivera', 6, 'full-time', 'monthly', '2023-11-15', 90.00, '09171234577', 'sofia.rivera@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(12, 'Antonio Gomez', 7, 'full-time', 'monthly', '2023-12-20', 110.00, '09171234578', 'antonio.gomez@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(13, 'Carmen Diaz', 6, 'full-time', 'monthly', '2024-01-10', 90.00, '09171234579', 'carmen.diaz@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(14, 'Ricardo Castro', 11, 'full-time', 'monthly', '2024-02-05', 95.00, '09171234580', 'ricardo.castro@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(15, 'Isabel Ortega', 11, 'full-time', 'monthly', '2024-03-12', 95.00, '09171234581', 'isabel.ortega@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(16, 'Fernando Ruiz', 10, 'full-time', 'monthly', '2024-04-15', 100.00, '09171234582', 'fernando.ruiz@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(17, 'Patricia Silva', 12, 'full-time', 'monthly', '2024-05-20', 90.00, '09171234583', 'patricia.silva@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(18, 'Roberto Chavez', 13, 'full-time', 'monthly', '2024-06-10', 85.00, '09171234584', 'roberto.chavez@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(19, 'Teresa Vargas', 14, 'full-time', 'monthly', '2024-07-05', 80.00, '09171234585', 'teresa.vargas@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(20, 'Alberto Herrera', 15, 'full-time', 'monthly', '2024-08-12', 120.00, '09171234586', 'alberto.herrera@example.com', '2025-04-28 14:04:23', '2025-04-28 14:04:23'),
(21, 'riccki mislang', 3, 'full-time', 'monthly', '2025-05-02', 150.00, '+63 4556987212', 'riccki@gmail.com', '2025-05-02 07:14:47', '2025-05-02 07:14:47'),
(22, 'cas', 3, 'full-time', 'monthly', '2025-05-04', 150.00, '123', '123@22.com', '2025-05-04 13:47:56', '2025-05-04 13:47:56'),
(23, '123123123', 3, 'full-time', 'monthly', '2025-05-04', 150.00, '123', '123123@mm.com', '2025-05-04 13:48:21', '2025-05-04 13:48:21');

-- --------------------------------------------------------

--
-- Table structure for table `employee_government_ids`
--

DROP TABLE IF EXISTS `employee_government_ids`;
CREATE TABLE IF NOT EXISTS `employee_government_ids` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `sss_number` varchar(20) DEFAULT NULL,
  `sss_file_path` varchar(255) DEFAULT NULL,
  `pagibig_number` varchar(20) DEFAULT NULL,
  `pagibig_file_path` varchar(255) DEFAULT NULL,
  `philhealth_number` varchar(20) DEFAULT NULL,
  `philhealth_file_path` varchar(255) DEFAULT NULL,
  `tin_number` varchar(20) DEFAULT NULL,
  `tin_file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_government_ids`
--

INSERT INTO `employee_government_ids` (`id`, `employee_id`, `sss_number`, `sss_file_path`, `pagibig_number`, `pagibig_file_path`, `philhealth_number`, `philhealth_file_path`, `tin_number`, `tin_file_path`, `created_at`, `updated_at`) VALUES
(1, 1, '34-1234567-9', NULL, '1234-5678-9012', NULL, '12-345678901-2', NULL, '123-456-789-000', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(2, 2, '34-2345678-0', NULL, '1234-5678-9013', NULL, '12-345678902-3', NULL, '123-456-789-001', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(3, 3, '34-3456789-1', NULL, '1234-5678-9014', NULL, '12-345678903-4', NULL, '123-456-789-002', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(4, 4, '34-4567890-2', NULL, '1234-5678-9015', NULL, '12-345678904-5', NULL, '123-456-789-003', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(5, 5, '34-5678901-3', NULL, '1234-5678-9016', NULL, '12-345678905-6', NULL, '123-456-789-004', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(6, 6, '34-6789012-4', NULL, '1234-5678-9017', NULL, '12-345678906-7', NULL, '123-456-789-005', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(7, 7, '34-7890123-5', NULL, '1234-5678-9018', NULL, '12-345678907-8', NULL, '123-456-789-006', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(8, 8, '34-8901234-6', NULL, '1234-5678-9019', NULL, '12-345678908-9', NULL, '123-456-789-007', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(9, 9, '34-9012345-7', NULL, '1234-5678-9020', NULL, '12-345678909-0', NULL, '123-456-789-008', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(10, 10, '34-0123456-8', NULL, '1234-5678-9021', NULL, '12-345678910-1', NULL, '123-456-789-009', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(11, 11, '34-1234567-0', NULL, '1234-5678-9022', NULL, '12-345678911-2', NULL, '123-456-789-010', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(12, 12, '34-2345678-1', NULL, '1234-5678-9023', NULL, '12-345678912-3', NULL, '123-456-789-011', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(13, 13, '34-3456789-2', NULL, '1234-5678-9024', NULL, '12-345678913-4', NULL, '123-456-789-012', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(14, 14, '34-4567890-3', NULL, '1234-5678-9025', NULL, '12-345678914-5', NULL, '123-456-789-013', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(15, 15, '34-5678901-4', NULL, '1234-5678-9026', NULL, '12-345678915-6', NULL, '123-456-789-014', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(16, 16, '34-6789012-5', NULL, '1234-5678-9027', NULL, '12-345678916-7', NULL, '123-456-789-015', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(17, 17, '34-7890123-6', NULL, '1234-5678-9028', NULL, '12-345678917-8', NULL, '123-456-789-016', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(18, 18, '34-8901234-7', NULL, '1234-5678-9029', NULL, '12-345678918-9', NULL, '123-456-789-017', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(19, 19, '34-9012345-8', NULL, '1234-5678-9030', NULL, '12-345678919-0', NULL, '123-456-789-018', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(20, 20, '34-0123456-9', NULL, '1234-5678-9031', NULL, '12-345678920-1', NULL, '123-456-789-019', NULL, '2025-04-28 14:06:14', '2025-04-28 14:06:14'),
(21, 21, '1234-56789', NULL, '2234-56789', NULL, '3234-56789', NULL, '4234-56789', NULL, '2025-05-02 07:14:47', '2025-05-02 07:14:47'),
(22, 22, '123', NULL, '123', NULL, '123', NULL, '123', NULL, '2025-05-04 13:47:56', '2025-05-04 13:47:56'),
(23, 23, '3123', NULL, '123123', NULL, '1312321312', NULL, '32132', NULL, '2025-05-04 13:48:21', '2025-05-04 13:48:21');

-- --------------------------------------------------------

--
-- Table structure for table `employee_qr_codes`
--

DROP TABLE IF EXISTS `employee_qr_codes`;
CREATE TABLE IF NOT EXISTS `employee_qr_codes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `qr_code_hash` varchar(25) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_code_hash` (`qr_code_hash`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_qr_codes`
--

INSERT INTO `employee_qr_codes` (`id`, `employee_id`, `qr_code_hash`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, '9200ca8411029178bbaebb001', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(2, 2, '9200ca8411029178bbaebb002', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(3, 3, '9200ca8411029178bbaebb003', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(4, 4, '9200ca8411029178bbaebb004', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(5, 5, '9200ca8411029178bbaebb005', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(6, 6, '9200ca8411029178bbaebb006', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(7, 7, '9200ca8411029178bbaebb007', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(8, 8, '9200ca8411029178bbaebb008', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(9, 9, '9200ca8411029178bbaebb009', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(10, 10, '9200ca8411029178bbaebb010', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(11, 11, '9200ca8411029178bbaebb011', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(12, 12, '9200ca8411029178bbaebb012', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(13, 13, '9200ca8411029178bbaebb013', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(14, 14, '9200ca8411029178bbaebb014', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(15, 15, '9200ca8411029178bbaebb015', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(16, 16, '9200ca8411029178bbaebb016', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(17, 17, '9200ca8411029178bbaebb017', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(18, 18, '9200ca8411029178bbaebb018', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(19, 19, '9200ca8411029178bbaebb019', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59'),
(20, 20, '9200ca8411029178bbaebb020', 1, '2025-04-28 14:06:59', '2025-04-28 14:06:59');

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

DROP TABLE IF EXISTS `expense_categories`;
CREATE TABLE IF NOT EXISTS `expense_categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`category_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Office Supplies', 'Expenses for office stationery and supplies', '2025-04-17 22:29:56', '2025-04-17 22:29:56'),
(2, 'Utilities', 'Electricity, water, and internet bills', '2025-04-17 22:29:56', '2025-04-17 22:29:56'),
(3, 'Maintenance', 'Building and equipment maintenance costs', '2025-04-17 22:29:56', '2025-04-17 22:29:56'),
(4, 'Travel', 'Employee travel and transportation expenses', '2025-04-17 22:29:56', '2025-04-17 22:29:56'),
(5, 'Miscellaneous', 'Other uncategorized expenses', '2025-04-17 22:29:56', '2025-04-17 22:29:56'),
(6, 'Marketing & Advertising', 'Costs for promotions, online ads, flyers, and campaigns', '2025-04-17 22:29:56', '2025-04-17 22:29:56'),
(7, 'Employee Salaries', 'Regular wages, benefits, and bonuses paid to staff', '2025-04-17 22:29:56', '2025-04-17 22:29:56'),
(8, 'Professional Services', 'Payments for accounting, legal, and consulting services', '2025-04-17 22:29:56', '2025-04-17 22:29:56'),
(9, 'Software & Subscriptions', 'Licensing fees for tools, software, and cloud platforms', '2025-04-17 22:29:56', '2025-04-17 22:29:56'),
(10, 'Training & Development', 'Workshops, courses, and certifications for employees', '2025-04-17 22:29:56', '2025-04-17 22:29:56'),
(11, 'Insurance', 'Expenses for business insurance, health, property, etc.', '2025-04-17 22:29:56', '2025-04-17 22:29:56'),
(12, 'Taxes & Government Fees', 'Annual taxes, business permits, and other regulatory costs', '2025-04-17 22:29:56', '2025-04-17 22:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `expense_transactions`
--

DROP TABLE IF EXISTS `expense_transactions`;
CREATE TABLE IF NOT EXISTS `expense_transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `expense_name` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expense_transactions`
--

INSERT INTO `expense_transactions` (`transaction_id`, `category_id`, `expense_name`, `amount`, `transaction_date`, `receipt_path`, `notes`, `created_at`) VALUES
(1, 1, 'Printer ink cartridges', 350.00, '2025-01-01', NULL, NULL, '2025-04-28 15:35:51'),
(2, 1, 'Office paper supplies', 250.00, '2025-01-01', NULL, NULL, '2025-04-28 15:35:51'),
(3, 1, 'Pens and markers', 200.00, '2025-01-01', NULL, NULL, '2025-04-28 15:35:51'),
(4, 2, 'Internet bill', 980.00, '2025-01-01', NULL, NULL, '2025-04-28 15:35:51'),
(5, 3, 'AC maintenance', 700.00, '2025-01-01', NULL, NULL, '2025-04-28 15:35:51'),
(6, 4, 'Employee taxi fare', 500.00, '2025-01-01', NULL, NULL, '2025-04-28 15:35:51'),
(7, 5, 'Cleaning supplies', 400.00, '2025-01-01', NULL, NULL, '2025-04-28 15:35:51'),
(8, 6, 'Social media ads', 600.00, '2025-01-01', NULL, NULL, '2025-04-28 15:35:51'),
(9, 8, 'Legal document fees', 800.00, '2025-01-01', NULL, NULL, '2025-04-28 15:35:51'),
(10, 9, 'Cloud storage subscription', 450.00, '2025-01-01', NULL, NULL, '2025-04-28 15:35:51'),
(11, 1, 'Printer ink cartridges', 360.00, '2025-01-02', NULL, NULL, '2025-04-28 15:35:51'),
(12, 1, 'Office paper supplies', 260.00, '2025-01-02', NULL, NULL, '2025-04-28 15:35:51'),
(13, 1, 'Pens and markers', 210.00, '2025-01-02', NULL, NULL, '2025-04-28 15:35:51'),
(14, 2, 'Internet bill', 985.00, '2025-01-02', NULL, NULL, '2025-04-28 15:35:51'),
(15, 3, 'AC maintenance', 720.00, '2025-01-02', NULL, NULL, '2025-04-28 15:35:51'),
(16, 4, 'Employee taxi fare', 520.00, '2025-01-02', NULL, NULL, '2025-04-28 15:35:51'),
(17, 5, 'Cleaning supplies', 420.00, '2025-01-02', NULL, NULL, '2025-04-28 15:35:51'),
(18, 6, 'Social media ads', 620.00, '2025-01-02', NULL, NULL, '2025-04-28 15:35:51'),
(19, 8, 'Legal document fees', 820.00, '2025-01-02', NULL, NULL, '2025-04-28 15:35:51'),
(20, 9, 'Cloud storage subscription', 460.00, '2025-01-02', NULL, NULL, '2025-04-28 15:35:51'),
(21, 1, 'Printer ink cartridges', 370.00, '2025-01-03', NULL, NULL, '2025-04-28 15:35:51'),
(22, 1, 'Office paper supplies', 270.00, '2025-01-03', NULL, NULL, '2025-04-28 15:35:51'),
(23, 1, 'Pens and markers', 220.00, '2025-01-03', NULL, NULL, '2025-04-28 15:35:51'),
(24, 2, 'Internet bill', 990.00, '2025-01-03', NULL, NULL, '2025-04-28 15:35:51'),
(25, 3, 'AC maintenance', 740.00, '2025-01-03', NULL, NULL, '2025-04-28 15:35:51'),
(26, 4, 'Employee taxi fare', 540.00, '2025-01-03', NULL, NULL, '2025-04-28 15:35:51'),
(27, 5, 'Cleaning supplies', 440.00, '2025-01-03', NULL, NULL, '2025-04-28 15:35:51'),
(28, 6, 'Social media ads', 640.00, '2025-01-03', NULL, NULL, '2025-04-28 15:35:51'),
(29, 8, 'Legal document fees', 840.00, '2025-01-03', NULL, NULL, '2025-04-28 15:35:51'),
(30, 9, 'Cloud storage subscription', 470.00, '2025-01-03', NULL, NULL, '2025-04-28 15:35:51'),
(31, 1, 'Printer ink cartridges', 380.00, '2025-01-06', NULL, NULL, '2025-04-28 15:35:51'),
(32, 1, 'Office paper supplies', 280.00, '2025-01-06', NULL, NULL, '2025-04-28 15:35:51'),
(33, 1, 'Pens and markers', 230.00, '2025-01-06', NULL, NULL, '2025-04-28 15:35:51'),
(34, 2, 'Internet bill', 995.00, '2025-01-06', NULL, NULL, '2025-04-28 15:35:51'),
(35, 3, 'AC maintenance', 760.00, '2025-01-06', NULL, NULL, '2025-04-28 15:35:51'),
(36, 4, 'Employee taxi fare', 560.00, '2025-01-06', NULL, NULL, '2025-04-28 15:35:51'),
(37, 5, 'Cleaning supplies', 460.00, '2025-01-06', NULL, NULL, '2025-04-28 15:35:51'),
(38, 6, 'Social media ads', 660.00, '2025-01-06', NULL, NULL, '2025-04-28 15:35:51'),
(39, 8, 'Legal document fees', 860.00, '2025-01-06', NULL, NULL, '2025-04-28 15:35:51'),
(40, 9, 'Cloud storage subscription', 480.00, '2025-01-06', NULL, NULL, '2025-04-28 15:35:51'),
(41, 1, 'Printer ink cartridges', 390.00, '2025-01-07', NULL, NULL, '2025-04-28 15:35:51'),
(42, 1, 'Office paper supplies', 290.00, '2025-01-07', NULL, NULL, '2025-04-28 15:35:51'),
(43, 1, 'Pens and markers', 240.00, '2025-01-07', NULL, NULL, '2025-04-28 15:35:51'),
(44, 2, 'Internet bill', 998.00, '2025-01-07', NULL, NULL, '2025-04-28 15:35:51'),
(45, 3, 'AC maintenance', 780.00, '2025-01-07', NULL, NULL, '2025-04-28 15:35:51'),
(46, 4, 'Employee taxi fare', 580.00, '2025-01-07', NULL, NULL, '2025-04-28 15:35:51'),
(47, 5, 'Cleaning supplies', 480.00, '2025-01-07', NULL, NULL, '2025-04-28 15:35:51'),
(48, 6, 'Social media ads', 680.00, '2025-01-07', NULL, NULL, '2025-04-28 15:35:51'),
(49, 8, 'Legal document fees', 880.00, '2025-01-07', NULL, NULL, '2025-04-28 15:35:51'),
(50, 9, 'Cloud storage subscription', 490.00, '2025-01-07', NULL, NULL, '2025-04-28 15:35:51'),
(51, 1, 'Printer ink cartridges', 400.00, '2025-01-08', NULL, NULL, '2025-04-28 15:35:51'),
(52, 1, 'Office paper supplies', 300.00, '2025-01-08', NULL, NULL, '2025-04-28 15:35:51'),
(53, 1, 'Pens and markers', 250.00, '2025-01-08', NULL, NULL, '2025-04-28 15:35:51'),
(54, 2, 'Internet bill', 999.00, '2025-01-08', NULL, NULL, '2025-04-28 15:35:51'),
(55, 3, 'AC maintenance', 800.00, '2025-01-08', NULL, NULL, '2025-04-28 15:35:51'),
(56, 4, 'Employee taxi fare', 600.00, '2025-01-08', NULL, NULL, '2025-04-28 15:35:51'),
(57, 5, 'Cleaning supplies', 500.00, '2025-01-08', NULL, NULL, '2025-04-28 15:35:51'),
(58, 6, 'Social media ads', 700.00, '2025-01-08', NULL, NULL, '2025-04-28 15:35:51'),
(59, 8, 'Legal document fees', 900.00, '2025-01-08', NULL, NULL, '2025-04-28 15:35:51'),
(60, 9, 'Cloud storage subscription', 500.00, '2025-01-08', NULL, NULL, '2025-04-28 15:35:51'),
(61, 1, 'Printer ink cartridges', 410.00, '2025-01-09', NULL, NULL, '2025-04-28 15:35:51'),
(62, 1, 'Office paper supplies', 310.00, '2025-01-09', NULL, NULL, '2025-04-28 15:35:51'),
(63, 1, 'Pens and markers', 260.00, '2025-01-09', NULL, NULL, '2025-04-28 15:35:51'),
(64, 2, 'Internet bill', 999.00, '2025-01-09', NULL, NULL, '2025-04-28 15:35:51'),
(65, 3, 'AC maintenance', 820.00, '2025-01-09', NULL, NULL, '2025-04-28 15:35:51'),
(66, 4, 'Employee taxi fare', 620.00, '2025-01-09', NULL, NULL, '2025-04-28 15:35:51'),
(67, 5, 'Cleaning supplies', 520.00, '2025-01-09', NULL, NULL, '2025-04-28 15:35:51'),
(68, 6, 'Social media ads', 720.00, '2025-01-09', NULL, NULL, '2025-04-28 15:35:51'),
(69, 8, 'Legal document fees', 920.00, '2025-01-09', NULL, NULL, '2025-04-28 15:35:51'),
(70, 9, 'Cloud storage subscription', 510.00, '2025-01-09', NULL, NULL, '2025-04-28 15:35:51'),
(71, 1, 'Printer ink cartridges', 420.00, '2025-01-10', NULL, NULL, '2025-04-28 15:35:51'),
(72, 1, 'Office paper supplies', 320.00, '2025-01-10', NULL, NULL, '2025-04-28 15:35:51'),
(73, 1, 'Pens and markers', 270.00, '2025-01-10', NULL, NULL, '2025-04-28 15:35:51'),
(74, 2, 'Internet bill', 999.00, '2025-01-10', NULL, NULL, '2025-04-28 15:35:51'),
(75, 3, 'AC maintenance', 840.00, '2025-01-10', NULL, NULL, '2025-04-28 15:35:51'),
(76, 4, 'Employee taxi fare', 640.00, '2025-01-10', NULL, NULL, '2025-04-28 15:35:51'),
(77, 5, 'Cleaning supplies', 540.00, '2025-01-10', NULL, NULL, '2025-04-28 15:35:51'),
(78, 6, 'Social media ads', 740.00, '2025-01-10', NULL, NULL, '2025-04-28 15:35:51'),
(79, 8, 'Legal document fees', 940.00, '2025-01-10', NULL, NULL, '2025-04-28 15:35:51'),
(80, 9, 'Cloud storage subscription', 520.00, '2025-01-10', NULL, NULL, '2025-04-28 15:35:51'),
(81, 1, 'Printer ink cartridges', 430.00, '2025-01-13', NULL, NULL, '2025-04-28 15:35:51'),
(82, 1, 'Office paper supplies', 330.00, '2025-01-13', NULL, NULL, '2025-04-28 15:35:51'),
(83, 1, 'Pens and markers', 280.00, '2025-01-13', NULL, NULL, '2025-04-28 15:35:51'),
(84, 2, 'Internet bill', 999.00, '2025-01-13', NULL, NULL, '2025-04-28 15:35:51'),
(85, 3, 'AC maintenance', 860.00, '2025-01-13', NULL, NULL, '2025-04-28 15:35:51'),
(86, 4, 'Employee taxi fare', 660.00, '2025-01-13', NULL, NULL, '2025-04-28 15:35:51'),
(87, 5, 'Cleaning supplies', 560.00, '2025-01-13', NULL, NULL, '2025-04-28 15:35:51'),
(88, 6, 'Social media ads', 760.00, '2025-01-13', NULL, NULL, '2025-04-28 15:35:51'),
(89, 8, 'Legal document fees', 960.00, '2025-01-13', NULL, NULL, '2025-04-28 15:35:51'),
(90, 9, 'Cloud storage subscription', 530.00, '2025-01-13', NULL, NULL, '2025-04-28 15:35:51'),
(91, 1, 'Printer ink cartridges', 440.00, '2025-01-14', NULL, NULL, '2025-04-28 15:35:51'),
(92, 1, 'Office paper supplies', 340.00, '2025-01-14', NULL, NULL, '2025-04-28 15:35:51'),
(93, 1, 'Pens and markers', 290.00, '2025-01-14', NULL, NULL, '2025-04-28 15:35:51'),
(94, 2, 'Internet bill', 999.00, '2025-01-14', NULL, NULL, '2025-04-28 15:35:51'),
(95, 3, 'AC maintenance', 880.00, '2025-01-14', NULL, NULL, '2025-04-28 15:35:51'),
(96, 4, 'Employee taxi fare', 680.00, '2025-01-14', NULL, NULL, '2025-04-28 15:35:51'),
(97, 5, 'Cleaning supplies', 580.00, '2025-01-14', NULL, NULL, '2025-04-28 15:35:51'),
(98, 6, 'Social media ads', 780.00, '2025-01-14', NULL, NULL, '2025-04-28 15:35:51'),
(99, 8, 'Legal document fees', 980.00, '2025-01-14', NULL, NULL, '2025-04-28 15:35:51'),
(100, 9, 'Cloud storage subscription', 540.00, '2025-01-14', NULL, NULL, '2025-04-28 15:35:51'),
(101, 1, 'Printer ink cartridges', 500.00, '2025-02-03', NULL, NULL, '2025-04-28 15:35:51'),
(102, 1, 'Office paper supplies', 400.00, '2025-02-03', NULL, NULL, '2025-04-28 15:35:51'),
(103, 1, 'Pens and markers', 350.00, '2025-02-03', NULL, NULL, '2025-04-28 15:35:51'),
(104, 2, 'Internet bill', 999.00, '2025-02-03', NULL, NULL, '2025-04-28 15:35:51'),
(105, 3, 'AC maintenance', 999.00, '2025-02-03', NULL, NULL, '2025-04-28 15:35:51'),
(106, 4, 'Employee taxi fare', 800.00, '2025-02-03', NULL, NULL, '2025-04-28 15:35:51'),
(107, 5, 'Cleaning supplies', 700.00, '2025-02-03', NULL, NULL, '2025-04-28 15:35:51'),
(108, 6, 'Social media ads', 900.00, '2025-02-03', NULL, NULL, '2025-04-28 15:35:51'),
(109, 8, 'Legal document fees', 999.00, '2025-02-03', NULL, NULL, '2025-04-28 15:35:51'),
(110, 9, 'Cloud storage subscription', 600.00, '2025-02-03', NULL, NULL, '2025-04-28 15:35:51'),
(111, 1, 'Printer ink cartridges', 510.00, '2025-02-04', NULL, NULL, '2025-04-28 15:35:51'),
(112, 1, 'Office paper supplies', 410.00, '2025-02-04', NULL, NULL, '2025-04-28 15:35:51'),
(113, 1, 'Pens and markers', 360.00, '2025-02-04', NULL, NULL, '2025-04-28 15:35:51'),
(114, 2, 'Internet bill', 999.00, '2025-02-04', NULL, NULL, '2025-04-28 15:35:51'),
(115, 3, 'AC maintenance', 999.00, '2025-02-04', NULL, NULL, '2025-04-28 15:35:51'),
(116, 4, 'Employee taxi fare', 820.00, '2025-02-04', NULL, NULL, '2025-04-28 15:35:51'),
(117, 5, 'Cleaning supplies', 720.00, '2025-02-04', NULL, NULL, '2025-04-28 15:35:51'),
(118, 6, 'Social media ads', 920.00, '2025-02-04', NULL, NULL, '2025-04-28 15:35:51'),
(119, 8, 'Legal document fees', 999.00, '2025-02-04', NULL, NULL, '2025-04-28 15:35:51'),
(120, 9, 'Cloud storage subscription', 610.00, '2025-02-04', NULL, NULL, '2025-04-28 15:35:51'),
(121, 1, 'Printer ink cartridges', 600.00, '2025-03-03', NULL, NULL, '2025-04-28 15:35:51'),
(122, 1, 'Office paper supplies', 500.00, '2025-03-03', NULL, NULL, '2025-04-28 15:35:51'),
(123, 1, 'Pens and markers', 450.00, '2025-03-03', NULL, NULL, '2025-04-28 15:35:51'),
(124, 2, 'Internet bill', 999.00, '2025-03-03', NULL, NULL, '2025-04-28 15:35:51'),
(125, 3, 'AC maintenance', 999.00, '2025-03-03', NULL, NULL, '2025-04-28 15:35:51'),
(126, 4, 'Employee taxi fare', 999.00, '2025-03-03', NULL, NULL, '2025-04-28 15:35:51'),
(127, 5, 'Cleaning supplies', 900.00, '2025-03-03', NULL, NULL, '2025-04-28 15:35:51'),
(128, 6, 'Social media ads', 999.00, '2025-03-03', NULL, NULL, '2025-04-28 15:35:51'),
(129, 8, 'Legal document fees', 999.00, '2025-03-03', NULL, NULL, '2025-04-28 15:35:51'),
(130, 9, 'Cloud storage subscription', 700.00, '2025-03-03', NULL, NULL, '2025-04-28 15:35:51'),
(131, 1, 'Printer ink cartridges', 610.00, '2025-03-04', NULL, NULL, '2025-04-28 15:35:51'),
(132, 1, 'Office paper supplies', 510.00, '2025-03-04', NULL, NULL, '2025-04-28 15:35:51'),
(133, 1, 'Pens and markers', 460.00, '2025-03-04', NULL, NULL, '2025-04-28 15:35:51'),
(134, 2, 'Internet bill', 999.00, '2025-03-04', NULL, NULL, '2025-04-28 15:35:51'),
(135, 3, 'AC maintenance', 999.00, '2025-03-04', NULL, NULL, '2025-04-28 15:35:51'),
(136, 4, 'Employee taxi fare', 999.00, '2025-03-04', NULL, NULL, '2025-04-28 15:35:51'),
(137, 5, 'Cleaning supplies', 920.00, '2025-03-04', NULL, NULL, '2025-04-28 15:35:51'),
(138, 6, 'Social media ads', 999.00, '2025-03-04', NULL, NULL, '2025-04-28 15:35:51'),
(139, 8, 'Legal document fees', 999.00, '2025-03-04', NULL, NULL, '2025-04-28 15:35:51'),
(140, 9, 'Cloud storage subscription', 710.00, '2025-03-04', NULL, NULL, '2025-04-28 15:35:51'),
(141, 1, 'Printer ink cartridges', 800.00, '2025-03-31', NULL, NULL, '2025-04-28 15:35:51'),
(142, 1, 'Office paper supplies', 700.00, '2025-03-31', NULL, NULL, '2025-04-28 15:35:51'),
(143, 1, 'Pens and markers', 650.00, '2025-03-31', NULL, NULL, '2025-04-28 15:35:51'),
(144, 2, 'Internet bill', 999.00, '2025-03-31', NULL, NULL, '2025-04-28 15:35:51'),
(145, 3, 'AC maintenance', 999.00, '2025-03-31', NULL, NULL, '2025-04-28 15:35:51'),
(146, 4, 'Employee taxi fare', 999.00, '2025-03-31', NULL, NULL, '2025-04-28 15:35:51'),
(147, 5, 'Cleaning supplies', 999.00, '2025-03-31', NULL, NULL, '2025-04-28 15:35:51'),
(148, 6, 'Social media ads', 999.00, '2025-03-31', NULL, NULL, '2025-04-28 15:35:51'),
(149, 8, 'Legal document fees', 999.00, '2025-03-31', NULL, NULL, '2025-04-28 15:35:51'),
(150, 9, 'Cloud storage subscription', 900.00, '2025-03-31', NULL, NULL, '2025-04-28 15:35:51');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

DROP TABLE IF EXISTS `payroll`;
CREATE TABLE IF NOT EXISTS `payroll` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pay_period_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `total_hours` int NOT NULL,
  `regular_hours` decimal(10,2) DEFAULT NULL,
  `overtime_hours` decimal(10,2) DEFAULT NULL,
  `gross_pay` decimal(10,2) NOT NULL,
  `deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_pay` decimal(10,2) NOT NULL,
  `deduction_breakdown` text COMMENT 'JSON encoded breakdown of deductions',
  `payment_status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `pay_period_id` (`pay_period_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `pay_period_id`, `employee_id`, `total_hours`, `regular_hours`, `overtime_hours`, `gross_pay`, `deductions`, `net_pay`, `deduction_breakdown`, `payment_status`) VALUES
(6, 1, 20, 10, NULL, NULL, 1149.09, 1788.18, -639.09, NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `pay_periods`
--

DROP TABLE IF EXISTS `pay_periods`;
CREATE TABLE IF NOT EXISTS `pay_periods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('open','processed') DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pay_periods`
--

INSERT INTO `pay_periods` (`id`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, '2025-05-01', '2025-05-15', 'open', '2025-05-10 14:39:36');

-- --------------------------------------------------------

--
-- Table structure for table `pay_settings`
--

DROP TABLE IF EXISTS `pay_settings`;
CREATE TABLE IF NOT EXISTS `pay_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_name` varchar(50) NOT NULL,
  `setting_value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pay_settings`
--

INSERT INTO `pay_settings` (`id`, `setting_name`, `setting_value`) VALUES
(1, 'sss_rate', '5'),
(2, 'philhealth_rate', '2.5'),
(3, 'pagibig_rate', '100'),
(4, 'tin_fixed', '200'),
(5, 'standard_hours', '8'),
(6, 'overtime_multiplier', '1.5'),
(7, 'max_cash_advance_percent', '30');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
CREATE TABLE IF NOT EXISTS `positions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `base_salary` decimal(10,2) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `title`, `base_salary`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Store Manager', 25000.00, 'Oversees store operations and staff', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(2, 'Assistant Manager', 22000.00, 'Assists store manager with daily operations', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(3, 'Cashier', 15000.00, 'Handles customer transactions and receipts', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(4, 'Senior Cashier', 17000.00, 'Experienced cashier with additional responsibilities', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(5, 'Inventory Clerk', 14000.00, 'Manages inventory records and stock levels', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(6, 'Sales Associate', 13000.00, 'Assists customers and promotes products', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(7, 'Senior Sales Associate', 17000.00, 'Experienced sales staff with product expertise', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(8, 'Warehouse Supervisor', 28000.00, 'Manages warehouse operations and inventory', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(9, 'Warehouse Staff', 12000.00, 'Handles product storage and movement', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(10, 'Maintenance Technician', 16000.00, 'Handles store repairs and equipment maintenance', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(11, 'Customer Service Representative', 14500.00, 'Handles customer inquiries and returns', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(12, 'Security Guard', 13000.00, 'Ensures store security and safety', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(13, 'Delivery Driver', 12000.00, 'Handles product deliveries and logistics', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(14, 'Janitor', 12000.00, 'Maintains store cleanliness', '2025-04-28 14:04:05', '2025-04-28 14:04:05'),
(15, 'IT Support', 20000.00, 'Handles technical issues and system maintenance', '2025-04-28 14:04:05', '2025-04-28 14:04:05');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) NOT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `category_id` int DEFAULT NULL,
  `brand_id` int DEFAULT NULL,
  `unit` varchar(20) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `stock_level` int DEFAULT '0',
  `reorder_point` int DEFAULT '10',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `sku` (`sku`),
  UNIQUE KEY `barcode` (`barcode`),
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `sku`, `barcode`, `name`, `description`, `category_id`, `brand_id`, `unit`, `cost_price`, `selling_price`, `stock_level`, `reorder_point`, `created_at`, `updated_at`) VALUES
(1, 'PT001', '123456789001', 'Bosch 18V Drill', 'Cordless drill with 2 batteries', 1, 1, 'piece', 2500.00, 3500.00, 15, 5, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(2, 'PT002', '123456789002', 'Makita Circular Saw', '7-1/4 inch circular saw', 1, 3, 'piece', 3200.00, 4200.00, 10, 3, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(3, 'PT003', '123456789003', 'Dewalt Impact Driver', '20V MAX impact driver', 1, 4, 'piece', 2800.00, 3800.00, 12, 4, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(4, 'HT001', '123456789004', 'Stanley Hammer', '16oz claw hammer', 2, 2, 'piece', 350.00, 550.00, 25, 8, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(5, 'HT002', '123456789005', 'Stanley Screwdriver Set', '6-piece screwdriver set', 2, 2, 'set', 450.00, 650.00, 20, 6, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(6, 'HT003', '123456789006', 'Stanley Wrench Set', '10-piece combination wrench set', 2, 2, 'set', 850.00, 1200.00, 15, 5, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(7, 'FS001', '123456789007', 'Common Nails 2\"', 'Box of 100 2-inch common nails', 3, 2, 'box', 120.00, 180.00, 50, 15, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(8, 'FS002', '123456789008', 'Wood Screws #8', 'Box of 100 #8 wood screws', 3, 2, 'box', 150.00, 220.00, 40, 12, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(9, 'FS003', '123456789009', 'Drywall Screws', 'Box of 100 drywall screws', 3, 2, 'box', 130.00, 190.00, 45, 14, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(10, 'PS001', '123456789010', 'Dulux Premium Paint', '1 gallon premium interior paint', 4, 6, 'gallon', 850.00, 1200.00, 30, 10, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(11, 'PS002', '123456789011', 'KYK Exterior Paint', '1 gallon exterior paint', 4, 8, 'gallon', 750.00, 1100.00, 25, 8, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(12, 'PS003', '123456789012', 'Paint Brush Set', '3-piece paint brush set', 4, 6, 'set', 250.00, 350.00, 40, 12, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(13, 'PL001', '123456789013', 'PVC Pipe 1/2\"', '10ft PVC pipe 1/2 inch', 5, 7, 'piece', 120.00, 180.00, 100, 30, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(14, 'PL002', '123456789014', 'PVC Fittings Kit', 'Assorted PVC fittings', 5, 7, 'kit', 350.00, 500.00, 25, 8, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(15, 'PL003', '123456789015', 'Pipe Wrench', '14-inch pipe wrench', 5, 2, 'piece', 450.00, 650.00, 15, 5, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(16, 'EL001', '123456789016', 'Electrical Wire 14/2', '100ft 14/2 electrical wire', 6, 7, 'roll', 850.00, 1200.00, 20, 6, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(17, 'EL002', '123456789017', 'Switch Box', 'Standard electrical switch box', 6, 7, 'piece', 45.00, 65.00, 50, 15, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(18, 'EL003', '123456789018', 'Circuit Breaker', '20A circuit breaker', 6, 7, 'piece', 150.00, 220.00, 30, 10, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(19, 'HW001', '123456789019', 'Door Hinge Set', 'Set of 3 door hinges', 7, 2, 'set', 120.00, 180.00, 40, 12, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(20, 'HW002', '123456789020', 'Door Knob Set', 'Standard door knob set', 7, 2, 'set', 250.00, 350.00, 30, 10, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(21, 'HW003', '123456789021', 'Drawer Slides', 'Pair of drawer slides', 7, 2, 'pair', 180.00, 250.00, 35, 11, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(22, 'SE001', '123456789022', 'Safety Glasses', 'Clear safety glasses', 8, 5, 'pair', 120.00, 180.00, 50, 15, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(23, 'SE002', '123456789023', 'Work Gloves', 'Pair of work gloves', 8, 5, 'pair', 150.00, 220.00, 40, 12, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(24, 'SE003', '123456789024', 'Hard Hat', 'Standard hard hat', 8, 5, 'piece', 250.00, 350.00, 25, 8, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(25, 'GT001', '123456789025', 'Garden Shovel', 'Standard garden shovel', 9, 2, 'piece', 350.00, 500.00, 20, 6, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(26, 'GT002', '123456789026', 'Pruning Shears', 'Professional pruning shears', 9, 2, 'piece', 450.00, 650.00, 15, 5, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(27, 'GT003', '123456789027', 'Garden Hose', '50ft garden hose', 9, 2, 'piece', 550.00, 750.00, 10, 3, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(28, 'BM001', '123456789028', 'Cement Bag', '40kg cement bag', 10, 7, 'bag', 250.00, 350.00, 100, 30, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(29, 'BM002', '123456789029', 'Sand Bag', '50kg sand bag', 10, 7, 'bag', 150.00, 220.00, 80, 25, '2025-04-28 14:10:14', '2025-04-28 14:10:14'),
(30, 'BM003', '123456789030', 'Gravel Bag', '50kg gravel bag', 10, 7, 'bag', 180.00, 250.00, 69, 20, '2025-04-28 14:10:14', '2025-04-28 15:57:22');

-- --------------------------------------------------------

--
-- Table structure for table `product_sales`
--

DROP TABLE IF EXISTS `product_sales`;
CREATE TABLE IF NOT EXISTS `product_sales` (
  `sale_id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(10) NOT NULL,
  `cashier_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `product_id` int NOT NULL,
  `quantity_sold` int NOT NULL,
  `discount_applied` decimal(5,2) NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `sale_timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sale_id`),
  KEY `product_id` (`product_id`),
  KEY `idx_transaction_id` (`transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_sales`
--

INSERT INTO `product_sales` (`sale_id`, `transaction_id`, `cashier_name`, `product_id`, `quantity_sold`, `discount_applied`, `sale_price`, `sale_timestamp`) VALUES
(1, '00001', 'Pedro Reyes', 1, 1, 0.00, 3500.00, '2025-01-01 09:15:00'),
(2, '00002', 'Pedro Reyes', 4, 2, 50.00, 1050.00, '2025-01-01 09:45:00'),
(3, '00003', 'Ana Martinez', 7, 3, 0.00, 540.00, '2025-01-01 10:30:00'),
(4, '00004', 'Ana Martinez', 10, 1, 100.00, 1100.00, '2025-01-01 11:15:00'),
(5, '00005', 'Luis Garcia', 13, 5, 0.00, 900.00, '2025-01-01 12:00:00'),
(6, '00006', 'Luis Garcia', 16, 1, 0.00, 1200.00, '2025-01-01 13:30:00'),
(7, '00007', 'Pedro Reyes', 19, 2, 20.00, 340.00, '2025-01-01 14:15:00'),
(8, '00008', 'Ana Martinez', 22, 1, 0.00, 180.00, '2025-01-01 15:00:00'),
(9, '00009', 'Luis Garcia', 25, 1, 0.00, 500.00, '2025-01-01 16:30:00'),
(10, '000010', 'Pedro Reyes', 28, 2, 0.00, 700.00, '2025-01-01 17:45:00'),
(11, '00011', 'Ana Martinez', 2, 1, 0.00, 4200.00, '2025-01-02 09:00:00'),
(12, '00012', 'Luis Garcia', 5, 2, 0.00, 1300.00, '2025-01-02 09:30:00'),
(13, '00013', 'Pedro Reyes', 8, 4, 40.00, 840.00, '2025-01-02 10:15:00'),
(14, '00014', 'Ana Martinez', 11, 1, 0.00, 1100.00, '2025-01-02 11:00:00'),
(15, '00015', 'Luis Garcia', 14, 2, 100.00, 900.00, '2025-01-02 12:30:00'),
(16, '00016', 'Pedro Reyes', 17, 3, 0.00, 195.00, '2025-01-02 13:45:00'),
(17, '00017', 'Ana Martinez', 20, 1, 0.00, 350.00, '2025-01-02 14:30:00'),
(18, '00018', 'Luis Garcia', 23, 2, 20.00, 420.00, '2025-01-02 15:15:00'),
(19, '00019', 'Pedro Reyes', 26, 1, 0.00, 650.00, '2025-01-02 16:00:00'),
(20, '00020', 'Ana Martinez', 29, 3, 0.00, 660.00, '2025-01-02 17:30:00'),
(21, '00021', 'Luis Garcia', 3, 1, 0.00, 3800.00, '2025-01-03 09:15:00'),
(22, '00022', 'Pedro Reyes', 6, 1, 0.00, 1200.00, '2025-01-03 10:00:00'),
(23, '00023', 'Ana Martinez', 9, 2, 20.00, 360.00, '2025-01-03 10:45:00'),
(24, '00024', 'Luis Garcia', 12, 3, 0.00, 1050.00, '2025-01-03 11:30:00'),
(25, '00025', 'Pedro Reyes', 15, 1, 0.00, 650.00, '2025-01-03 12:15:00'),
(26, '00026', 'Ana Martinez', 18, 2, 0.00, 440.00, '2025-01-03 13:00:00'),
(27, '00027', 'Luis Garcia', 21, 1, 0.00, 250.00, '2025-01-03 14:30:00'),
(28, '00028', 'Pedro Reyes', 24, 1, 0.00, 350.00, '2025-01-03 15:15:00'),
(29, '00029', 'Ana Martinez', 27, 2, 50.00, 1450.00, '2025-01-03 16:45:00'),
(30, '00030', 'Luis Garcia', 30, 4, 0.00, 1000.00, '2025-01-03 17:30:00'),
(31, '00031', 'Pedro Reyes', 1, 2, 200.00, 6800.00, '2025-01-06 09:00:00'),
(32, '00032', 'Ana Martinez', 4, 1, 0.00, 550.00, '2025-01-06 09:45:00'),
(33, '00033', 'Luis Garcia', 7, 5, 50.00, 850.00, '2025-01-06 10:30:00'),
(34, '00034', 'Pedro Reyes', 10, 2, 100.00, 2300.00, '2025-01-06 11:15:00'),
(35, '00035', 'Ana Martinez', 13, 3, 0.00, 540.00, '2025-01-06 12:00:00'),
(36, '00036', 'Luis Garcia', 16, 1, 0.00, 1200.00, '2025-01-06 13:30:00'),
(37, '00037', 'Pedro Reyes', 19, 2, 0.00, 360.00, '2025-01-06 14:15:00'),
(38, '00038', 'Ana Martinez', 22, 4, 40.00, 680.00, '2025-01-06 15:00:00'),
(39, '00039', 'Luis Garcia', 25, 1, 0.00, 500.00, '2025-01-06 16:30:00'),
(40, '00040', 'Pedro Reyes', 28, 3, 50.00, 1000.00, '2025-01-06 17:45:00'),
(41, '00041', 'Ana Martinez', 2, 1, 0.00, 4200.00, '2025-01-07 09:15:00'),
(42, '00042', 'Luis Garcia', 5, 3, 100.00, 1850.00, '2025-01-07 10:00:00'),
(43, '00043', 'Pedro Reyes', 8, 2, 0.00, 440.00, '2025-01-07 10:45:00'),
(44, '00044', 'Ana Martinez', 11, 1, 0.00, 1100.00, '2025-01-07 11:30:00'),
(45, '00045', 'Luis Garcia', 14, 4, 200.00, 1800.00, '2025-01-07 12:15:00'),
(46, '00046', 'Pedro Reyes', 17, 2, 0.00, 130.00, '2025-01-07 13:45:00'),
(47, '00047', 'Ana Martinez', 20, 1, 0.00, 350.00, '2025-01-07 14:30:00'),
(48, '00048', 'Luis Garcia', 23, 3, 30.00, 630.00, '2025-01-07 15:15:00'),
(49, '00049', 'Pedro Reyes', 26, 2, 50.00, 1250.00, '2025-01-07 16:00:00'),
(50, '00050', 'Ana Martinez', 29, 5, 100.00, 1000.00, '2025-01-07 17:30:00'),
(51, '00051', 'Luis Garcia', 3, 2, 100.00, 7500.00, '2025-01-08 09:00:00'),
(52, '00052', 'Pedro Reyes', 6, 1, 0.00, 1200.00, '2025-01-08 09:45:00'),
(53, '00053', 'Ana Martinez', 9, 3, 30.00, 540.00, '2025-01-08 10:30:00'),
(54, '00054', 'Luis Garcia', 12, 2, 0.00, 700.00, '2025-01-08 11:15:00'),
(55, '00055', 'Pedro Reyes', 15, 1, 0.00, 650.00, '2025-01-08 12:45:00'),
(56, '00056', 'Ana Martinez', 18, 4, 40.00, 840.00, '2025-01-08 13:30:00'),
(57, '00057', 'Luis Garcia', 21, 2, 20.00, 480.00, '2025-01-08 14:15:00'),
(58, '00058', 'Pedro Reyes', 24, 1, 0.00, 350.00, '2025-01-08 15:45:00'),
(59, '00059', 'Ana Martinez', 27, 3, 75.00, 2175.00, '2025-01-08 16:30:00'),
(60, '00060', 'Luis Garcia', 30, 2, 0.00, 500.00, '2025-01-08 17:15:00'),
(61, '00061', 'Pedro Reyes', 1, 1, 0.00, 3500.00, '2025-01-09 09:15:00'),
(62, '00062', 'Ana Martinez', 4, 3, 75.00, 1575.00, '2025-01-09 10:00:00'),
(63, '00063', 'Luis Garcia', 7, 2, 0.00, 360.00, '2025-01-09 10:45:00'),
(64, '00064', 'Pedro Reyes', 10, 1, 0.00, 1200.00, '2025-01-09 11:30:00'),
(65, '00065', 'Ana Martinez', 13, 4, 40.00, 680.00, '2025-01-09 12:15:00'),
(66, '00066', 'Luis Garcia', 16, 2, 100.00, 2300.00, '2025-01-09 13:45:00'),
(67, '00067', 'Pedro Reyes', 19, 1, 0.00, 180.00, '2025-01-09 14:30:00'),
(68, '00068', 'Ana Martinez', 22, 3, 30.00, 510.00, '2025-01-09 15:15:00'),
(69, '00069', 'Luis Garcia', 25, 2, 50.00, 950.00, '2025-01-09 16:45:00'),
(70, '00070', 'Pedro Reyes', 28, 1, 0.00, 350.00, '2025-01-09 17:30:00'),
(71, '00071', 'Ana Martinez', 2, 1, 0.00, 4200.00, '2025-01-10 09:00:00'),
(72, '00072', 'Luis Garcia', 5, 2, 50.00, 1250.00, '2025-01-10 09:45:00'),
(73, '00073', 'Pedro Reyes', 8, 3, 30.00, 630.00, '2025-01-10 10:30:00'),
(74, '00074', 'Ana Martinez', 11, 1, 0.00, 1100.00, '2025-01-10 11:15:00'),
(75, '00075', 'Luis Garcia', 14, 2, 100.00, 900.00, '2025-01-10 12:45:00'),
(76, '00076', 'Pedro Reyes', 17, 4, 40.00, 220.00, '2025-01-10 13:30:00'),
(77, '00077', 'Ana Martinez', 20, 1, 0.00, 350.00, '2025-01-10 14:15:00'),
(78, '00078', 'Luis Garcia', 23, 2, 20.00, 420.00, '2025-01-10 15:45:00'),
(79, '00079', 'Pedro Reyes', 26, 3, 75.00, 1875.00, '2025-01-10 16:30:00'),
(80, '00080', 'Ana Martinez', 29, 2, 0.00, 440.00, '2025-01-10 17:15:00'),
(81, '00081', 'Luis Garcia', 3, 1, 0.00, 3800.00, '2025-01-13 09:15:00'),
(82, '00082', 'Pedro Reyes', 6, 2, 50.00, 2350.00, '2025-01-13 10:00:00'),
(83, '00083', 'Ana Martinez', 9, 3, 30.00, 540.00, '2025-01-13 10:45:00'),
(84, '00084', 'Luis Garcia', 12, 1, 0.00, 350.00, '2025-01-13 11:30:00'),
(85, '00085', 'Pedro Reyes', 15, 2, 100.00, 1200.00, '2025-01-13 12:15:00'),
(86, '00086', 'Ana Martinez', 18, 4, 40.00, 840.00, '2025-01-13 13:45:00'),
(87, '00087', 'Luis Garcia', 21, 1, 0.00, 250.00, '2025-01-13 14:30:00'),
(88, '00088', 'Pedro Reyes', 24, 3, 75.00, 975.00, '2025-01-13 15:15:00'),
(89, '00089', 'Ana Martinez', 27, 2, 50.00, 1450.00, '2025-01-13 16:45:00'),
(90, '00090', 'Luis Garcia', 30, 1, 0.00, 250.00, '2025-01-13 17:30:00'),
(91, '00091', 'Pedro Reyes', 1, 1, 0.00, 3500.00, '2025-01-14 09:00:00'),
(92, '00092', 'Ana Martinez', 4, 2, 50.00, 1050.00, '2025-01-14 09:45:00'),
(93, '00093', 'Luis Garcia', 7, 3, 30.00, 510.00, '2025-01-14 10:30:00'),
(94, '00094', 'Pedro Reyes', 10, 1, 0.00, 1200.00, '2025-01-14 11:15:00'),
(95, '00095', 'Ana Martinez', 13, 2, 20.00, 340.00, '2025-01-14 12:45:00'),
(96, '00096', 'Luis Garcia', 16, 1, 0.00, 1200.00, '2025-01-14 13:30:00'),
(97, '00097', 'Pedro Reyes', 19, 4, 80.00, 640.00, '2025-01-14 14:15:00'),
(98, '00098', 'Ana Martinez', 22, 2, 20.00, 340.00, '2025-01-14 15:45:00'),
(99, '00099', 'Luis Garcia', 25, 1, 0.00, 500.00, '2025-01-14 16:30:00'),
(100, '00100', 'Pedro Reyes', 28, 3, 75.00, 975.00, '2025-01-14 17:15:00'),
(101, '00101', 'Ana Martinez', 2, 1, 0.00, 4200.00, '2025-01-15 09:15:00'),
(102, '00102', 'Luis Garcia', 5, 2, 50.00, 1250.00, '2025-01-15 10:00:00'),
(103, '00103', 'Pedro Reyes', 8, 3, 30.00, 630.00, '2025-01-15 10:45:00'),
(104, '00104', 'Ana Martinez', 11, 1, 0.00, 1100.00, '2025-01-15 11:30:00'),
(105, '00105', 'Luis Garcia', 14, 2, 100.00, 900.00, '2025-01-15 12:15:00'),
(106, '00106', 'Pedro Reyes', 17, 4, 40.00, 220.00, '2025-01-15 13:45:00'),
(107, '00107', 'Ana Martinez', 20, 1, 0.00, 350.00, '2025-01-15 14:30:00'),
(108, '00108', 'Luis Garcia', 23, 2, 20.00, 420.00, '2025-01-15 15:15:00'),
(109, '00109', 'Pedro Reyes', 26, 3, 75.00, 1875.00, '2025-01-15 16:45:00'),
(110, '00110', 'Ana Martinez', 29, 2, 0.00, 440.00, '2025-01-15 17:30:00'),
(111, '00111', 'Luis Garcia', 3, 1, 0.00, 3800.00, '2025-01-16 09:00:00'),
(112, '00112', 'Pedro Reyes', 6, 2, 50.00, 2350.00, '2025-01-16 09:45:00'),
(113, '00113', 'Ana Martinez', 9, 3, 30.00, 540.00, '2025-01-16 10:30:00'),
(114, '00114', 'Luis Garcia', 12, 1, 0.00, 350.00, '2025-01-16 11:15:00'),
(115, '00115', 'Pedro Reyes', 15, 2, 100.00, 1200.00, '2025-01-16 12:45:00'),
(116, '00116', 'Ana Martinez', 18, 4, 40.00, 840.00, '2025-01-16 13:30:00'),
(117, '00117', 'Luis Garcia', 21, 1, 0.00, 250.00, '2025-01-16 14:15:00'),
(118, '00118', 'Pedro Reyes', 24, 3, 75.00, 975.00, '2025-01-16 15:45:00'),
(119, '00119', 'Ana Martinez', 27, 2, 50.00, 1450.00, '2025-01-16 16:30:00'),
(120, '00120', 'Luis Garcia', 30, 1, 0.00, 250.00, '2025-01-16 17:15:00'),
(121, '00121', 'Pedro Reyes', 1, 2, 100.00, 6900.00, '2025-01-17 09:15:00'),
(122, '00122', 'Ana Martinez', 4, 1, 0.00, 550.00, '2025-01-17 10:00:00'),
(123, '00123', 'Luis Garcia', 7, 3, 30.00, 510.00, '2025-01-17 10:45:00'),
(124, '00124', 'Pedro Reyes', 10, 2, 100.00, 2300.00, '2025-01-17 11:30:00'),
(125, '00125', 'Ana Martinez', 13, 1, 0.00, 180.00, '2025-01-17 12:15:00'),
(126, '00126', 'Luis Garcia', 16, 2, 100.00, 2300.00, '2025-01-17 13:45:00'),
(127, '00127', 'Pedro Reyes', 19, 4, 80.00, 640.00, '2025-01-17 14:30:00'),
(128, '00128', 'Ana Martinez', 22, 1, 0.00, 180.00, '2025-01-17 15:15:00'),
(129, '00129', 'Luis Garcia', 25, 2, 50.00, 950.00, '2025-01-17 16:45:00'),
(130, '00130', 'Pedro Reyes', 28, 3, 75.00, 975.00, '2025-01-17 17:30:00'),
(131, '00131', 'Pedro Reyes', 30, 1, 0.00, 250.00, '2025-04-28 23:57:22');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transactions`
--

DROP TABLE IF EXISTS `stock_transactions`;
CREATE TABLE IF NOT EXISTS `stock_transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `transaction_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `reference_no` varchar(50) DEFAULT NULL,
  `notes` text,
  `transaction_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stock_transactions`
--

INSERT INTO `stock_transactions` (`transaction_id`, `product_id`, `transaction_type`, `quantity`, `unit_price`, `total_amount`, `reference_no`, `notes`, `transaction_date`, `created_at`) VALUES
(1, 1, 'initial', 15, 2500.00, 37500.00, 'INIT001', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(2, 2, 'initial', 10, 3200.00, 32000.00, 'INIT002', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(3, 3, 'initial', 12, 2800.00, 33600.00, 'INIT003', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(4, 4, 'initial', 25, 350.00, 8750.00, 'INIT004', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(5, 5, 'initial', 20, 450.00, 9000.00, 'INIT005', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(6, 6, 'initial', 15, 850.00, 12750.00, 'INIT006', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(7, 7, 'initial', 50, 120.00, 6000.00, 'INIT007', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(8, 8, 'initial', 40, 150.00, 6000.00, 'INIT008', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(9, 9, 'initial', 45, 130.00, 5850.00, 'INIT009', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(10, 10, 'initial', 30, 850.00, 25500.00, 'INIT010', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(11, 11, 'initial', 25, 750.00, 18750.00, 'INIT011', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(12, 12, 'initial', 40, 250.00, 10000.00, 'INIT012', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(13, 13, 'initial', 100, 120.00, 12000.00, 'INIT013', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(14, 14, 'initial', 25, 350.00, 8750.00, 'INIT014', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(15, 15, 'initial', 15, 450.00, 6750.00, 'INIT015', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(16, 16, 'initial', 20, 850.00, 17000.00, 'INIT016', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(17, 17, 'initial', 50, 45.00, 2250.00, 'INIT017', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(18, 18, 'initial', 30, 150.00, 4500.00, 'INIT018', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(19, 19, 'initial', 40, 120.00, 4800.00, 'INIT019', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(20, 20, 'initial', 30, 250.00, 7500.00, 'INIT020', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(21, 21, 'initial', 35, 180.00, 6300.00, 'INIT021', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(22, 22, 'initial', 50, 120.00, 6000.00, 'INIT022', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(23, 23, 'initial', 40, 150.00, 6000.00, 'INIT023', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(24, 24, 'initial', 25, 250.00, 6250.00, 'INIT024', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(25, 25, 'initial', 20, 350.00, 7000.00, 'INIT025', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(26, 26, 'initial', 15, 450.00, 6750.00, 'INIT026', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(27, 27, 'initial', 10, 550.00, 5500.00, 'INIT027', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(28, 28, 'initial', 100, 250.00, 25000.00, 'INIT028', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(29, 29, 'initial', 80, 150.00, 12000.00, 'INIT029', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(30, 30, 'initial', 70, 180.00, 12600.00, 'INIT030', 'Initial stock entry', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(31, 1, 'restock', 5, 2500.00, 12500.00, 'REST001', 'Restock order', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(32, 2, 'restock', 3, 3200.00, 9600.00, 'REST002', 'Restock order', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(33, 3, 'restock', 4, 2800.00, 11200.00, 'REST003', 'Restock order', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(34, 4, 'restock', 8, 350.00, 2800.00, 'REST004', 'Restock order', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(35, 5, 'restock', 6, 450.00, 2700.00, 'REST005', 'Restock order', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(36, 6, 'restock', 5, 850.00, 4250.00, 'REST006', 'Restock order', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(37, 7, 'restock', 15, 120.00, 1800.00, 'REST007', 'Restock order', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(38, 8, 'restock', 12, 150.00, 1800.00, 'REST008', 'Restock order', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(39, 9, 'restock', 14, 130.00, 1820.00, 'REST009', 'Restock order', '2025-04-28 14:10:49', '2025-04-28 14:10:49'),
(40, 10, 'restock', 10, 850.00, 8500.00, 'REST010', 'Restock order', '2025-04-28 14:10:49', '2025-04-28 14:10:49');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_contacts`
--

DROP TABLE IF EXISTS `supplier_contacts`;
CREATE TABLE IF NOT EXISTS `supplier_contacts` (
  `contact_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`contact_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `fullname` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `email` varchar(50) NOT NULL,
  `contact_no` text,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `usertype` enum('1','2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'superadmin=1, admin=2, cashier=3,\r\nstaff = 4',
  `OTP` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `username`, `fullname`, `email`, `contact_no`, `password`, `usertype`) VALUES
(1, NULL, 'superadmin', NULL, '', NULL, '17c4520f6cfd1ab53d8745e84681eb49', '1'),
(2, 2, 'admin', 'Maria Santos', 'maria.santos@example.com', '09171234568', '21232f297a57a5a743894a0e4a801fc3', '2'),
(3, 3, 'pedro', NULL, '', NULL, '6ac2470ed8ccf204fd5ff89b32a355cf', '3'),
(4, 4, 'ana', NULL, '', NULL, '6ac2470ed8ccf204fd5ff89b32a355cf', '3'),
(5, 5, 'luis', NULL, '', NULL, '6ac2470ed8ccf204fd5ff89b32a355cf', '3'),
(6, 21, 'riccki', 'riccki mislang', 'riccki@gmail.com', '+63 4556987212', '6ac2470ed8ccf204fd5ff89b32a355cf', '4'),
(7, 22, 'cas', 'cas', '123@22.com', '123', '6ac2470ed8ccf204fd5ff89b32a355cf', '3'),
(8, 23, '123123123', '123123123', '123123@mm.com', '123', '6ac2470ed8ccf204fd5ff89b32a355cf', '4');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
