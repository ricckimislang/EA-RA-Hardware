-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 27, 2025 at 05:44 AM
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
  `status` enum('present','late','half-day','absent') DEFAULT 'present',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance_records`
--

INSERT INTO `attendance_records` (`id`, `employee_id`, `time_in`, `time_out`, `total_hours`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(76, 1, '2025-04-21 19:35:43', NULL, NULL, 'late', NULL, '2025-04-21 11:35:43', '2025-04-21 11:35:43'),
(77, 2, '2025-04-19 08:02:11', '2025-04-19 17:05:23', 9, 'present', 'Normal shift', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(78, 3, '2025-04-19 08:15:43', '2025-04-19 17:10:18', 9, 'late', 'Traffic delay', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(79, 4, '2025-04-19 07:55:32', '2025-04-19 16:58:41', 9, 'present', 'Normal shift', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(80, 5, '2025-04-19 13:02:15', '2025-04-19 17:03:45', 4, 'half-day', 'Part-time schedule', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(81, 6, '2025-04-19 08:07:22', '2025-04-19 17:15:33', 9, 'present', 'Stayed late for inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(82, 2, '2025-04-20 07:58:43', '2025-04-20 17:03:12', 9, 'present', 'Normal shift', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(83, 3, '2025-04-20 08:25:19', '2025-04-20 17:08:54', 9, 'late', 'Public transport delay', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(84, 4, '2025-04-20 08:03:47', '2025-04-20 17:01:32', 9, 'present', 'Normal shift', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(85, 6, '2025-04-20 08:01:05', '2025-04-20 17:05:21', 9, 'present', 'Normal shift', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(86, 7, '2025-04-20 13:00:33', '2025-04-20 17:02:46', 4, 'half-day', 'Part-time schedule', '2025-04-21 12:14:27', '2025-04-21 12:14:27');

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
(1, 'work_start_time', '08:00:00', 'Regular work start time', '2025-04-19 01:08:43', '2025-04-19 01:08:43'),
(2, 'work_end_time', '17:00:00', 'Regular work end time', '2025-04-19 01:08:43', '2025-04-19 01:08:43'),
(3, 'late_threshold_minutes', '15', 'Minutes after work start time to mark as late', '2025-04-19 01:08:43', '2025-04-19 01:08:43'),
(4, 'half_day_hours', '4', 'Minimum hours to be counted as half-day', '2025-04-19 01:08:43', '2025-04-19 01:08:43');

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
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Stanley', 'Quality hand tools and storage solutions', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(2, 'DeWalt', 'Professional-grade power tools and equipment', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(3, 'Makita', 'Innovative power tools and outdoor equipment', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(4, 'Bosch', 'High-performance tools and accessories', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(5, '3M', 'Industrial and consumer products', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(6, 'Sanrio', NULL, '2025-04-17 21:16:17', '2025-04-17 21:16:17'),
(7, 'Milwaukee', 'Premium power tools for professionals', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(8, 'Craftsman', 'American-made quality tools', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(9, 'Ridgid', 'Heavy-duty professional tools', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(10, 'Ryobi', 'DIY and homeowner tools and equipment', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(11, 'Hitachi', 'Japanese precision tools and equipment', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(12, 'Black & Decker', 'Consumer-friendly tools for home use', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(13, 'Husky', 'Hand tools and storage solutions', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(14, 'Kobalt', 'Professional-grade tools at affordable prices', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(15, 'Dremel', 'Rotary tools and accessories', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(16, 'Irwin', 'Quality hand tools for construction', '2025-04-21 12:14:27', '2025-04-21 12:14:27');

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
(1, 'Tools', 'Hand tools and power tools for construction and repairs', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(2, 'Hardware', 'General hardware items including fasteners and fittings', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(3, 'Electrical', 'Electrical supplies, wiring, and components', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(4, 'Plumbing', 'Plumbing supplies, pipes, and fixtures', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(5, 'Paint', 'Paint, primers, and painting supplies', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(6, 'Safety Equipment', 'Protective gear and safety devices', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(7, 'Fasteners', 'Screws, nails, bolts, and other fastening hardware', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(8, 'Garden Tools', 'Equipment for landscaping and gardening', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(9, 'Power Tools', 'Electric and battery-powered tools', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(10, 'Hand Tools', 'Manual tools for various applications', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(11, 'Building Materials', 'Construction materials and supplies', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(12, 'Automotive', 'Tools and supplies for vehicle maintenance', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(13, 'Adhesives', 'Glues, tapes, and bonding agents', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(14, 'Storage Solutions', 'Tool boxes, cabinets, and organizers', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(15, 'Lighting', 'Indoor and outdoor lighting fixtures', '2025-04-21 12:14:27', '2025-04-21 12:14:27');

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `full_name`, `position_id`, `employment_type`, `salary_rate_type`, `date_hired`, `overtime_rate`, `contact_number`, `email_address`, `created_at`, `updated_at`) VALUES
(1, 'Riccki Mislang', 2, 'full-time', 'monthly', '2025-04-21', 100.00, '09631245565', 'codingriccki123@gmail.com', '2025-04-21 10:52:49', '2025-04-21 10:58:06'),
(2, 'Maria Santos', 1, 'full-time', 'monthly', '2023-05-15', 120.00, '09761234567', 'maria.santos@example.com', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(3, 'John Davis', 2, 'full-time', 'monthly', '2024-01-10', 110.00, '09891234567', 'john.davis@example.com', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(4, 'Anna Kim', 3, 'full-time', 'monthly', '2023-08-22', 95.00, '09561234567', 'anna.kim@example.com', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(5, 'Carlos Reyes', 4, 'full-time', 'monthly', '2024-02-05', 90.00, '09451234567', 'carlos.reyes@example.com', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(6, 'Sarah Johnson', 5, 'part-time', 'hourly', '2024-03-12', 80.00, '09231234567', 'sarah.johnson@example.com', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(7, 'Mark Wu', 3, 'full-time', 'monthly', '2023-11-18', 95.00, '09321234567', 'mark.wu@example.com', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(8, 'Leila Patel', 5, 'part-time', 'hourly', '2024-01-25', 80.00, '09781234567', 'leila.patel@example.com', '2025-04-21 12:14:27', '2025-04-21 12:14:27');

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
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_government_ids`
--

INSERT INTO `employee_government_ids` (`id`, `employee_id`, `sss_number`, `sss_file_path`, `pagibig_number`, `pagibig_file_path`, `philhealth_number`, `philhealth_file_path`, `tin_number`, `tin_file_path`, `created_at`, `updated_at`) VALUES
(10, 9, '34-1234567-9', NULL, '1234-5678-9012 ', NULL, '12-345678901-2', NULL, '123-456-789-000', NULL, '2025-04-21 10:26:31', '2025-04-21 10:26:31'),
(11, 1, '34-1234567-9', NULL, '1234-5678-9012 ', NULL, '12-345678901-2', NULL, '123-456-789-000  ', NULL, '2025-04-21 10:51:46', '2025-04-21 10:51:46'),
(12, 1, '34-1234567-9', NULL, '1234-5678-9012 ', NULL, '12-345678901-2', NULL, '123-456-789-000  ', NULL, '2025-04-21 10:52:49', '2025-04-21 10:58:06'),
(13, 2, '34-2345678-0', NULL, '1234-5678-9013', NULL, '12-345678902-3', NULL, '123-456-789-001', NULL, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(14, 3, '34-3456789-1', NULL, '1234-5678-9014', NULL, '12-345678903-4', NULL, '123-456-789-002', NULL, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(15, 4, '34-4567890-2', NULL, '1234-5678-9015', NULL, '12-345678904-5', NULL, '123-456-789-003', NULL, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(16, 5, '34-5678901-3', NULL, '1234-5678-9016', NULL, '12-345678905-6', NULL, '123-456-789-004', NULL, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(17, 6, '34-6789012-4', NULL, '1234-5678-9017', NULL, '12-345678906-7', NULL, '123-456-789-005', NULL, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(18, 7, '34-7890123-5', NULL, '1234-5678-9018', NULL, '12-345678907-8', NULL, '123-456-789-006', NULL, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(19, 8, '34-8901234-6', NULL, '1234-5678-9019', NULL, '12-345678908-9', NULL, '123-456-789-007', NULL, '2025-04-21 12:14:27', '2025-04-21 12:14:27');

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_qr_codes`
--

INSERT INTO `employee_qr_codes` (`id`, `employee_id`, `qr_code_hash`, `is_active`, `created_at`, `updated_at`) VALUES
(5, 1, '9200ca8411029178bbaebb146', 1, '2025-04-21 11:30:29', '2025-04-21 11:31:56'),
(6, 2, '9200ca8411029178bbaebb123', 1, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(7, 3, '9200ca8411029178bbaebb124', 1, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(8, 4, '9200ca8411029178bbaebb125', 1, '2025-04-21 12:14:27', '2025-04-26 13:37:09'),
(9, 5, '9200ca8411029178bbaebb126', 1, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(10, 6, '9200ca8411029178bbaebb127', 1, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(11, 7, '9200ca8411029178bbaebb128', 1, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(12, 8, '9200ca8411029178bbaebb129', 1, '2025-04-21 12:14:27', '2025-04-21 12:14:27');

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
(1, 'Office Supplies', 'Expenses for office stationery and supplies', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(2, 'Utilities', 'Electricity, water, and internet bills', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(3, 'Maintenance', 'Building and equipment maintenance costs', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(4, 'Travel', 'Employee travel and transportation expenses', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(5, 'Miscellaneous', 'Other uncategorized expenses', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(6, 'Marketing & Advertising', 'Costs for promotions, online ads, flyers, and campaigns', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(7, 'Employee Salaries', 'Regular wages, benefits, and bonuses paid to staff', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(8, 'Professional Services', 'Payments for accounting, legal, and consulting services', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(9, 'Software & Subscriptions', 'Licensing fees for tools, software, and cloud platforms', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(10, 'Training & Development', 'Workshops, courses, and certifications for employees', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(11, 'Insurance', 'Expenses for business insurance, health, property, etc.', '2025-04-18 06:29:56', '2025-04-18 06:29:56'),
(12, 'Taxes & Government Fees', 'Annual taxes, business permits, and other regulatory costs', '2025-04-18 06:29:56', '2025-04-18 06:29:56');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expense_transactions`
--

INSERT INTO `expense_transactions` (`transaction_id`, `category_id`, `expense_name`, `amount`, `transaction_date`, `receipt_path`, `notes`, `created_at`) VALUES
(1, 4, 'fare', 220.00, '2025-04-21', 'assets/images/receipts/receipt_680dbe08b3b37.png', 'Travel to marvel', '2025-04-21 13:35:06'),
(2, 1, 'printer and papers', 6000.00, '2025-04-21', NULL, 'printer for documents', '2025-04-21 13:35:28');

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
  `gross_pay` decimal(10,2) NOT NULL,
  `deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_pay` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `pay_period_id` (`pay_period_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `pay_period_id`, `employee_id`, `total_hours`, `gross_pay`, `deductions`, `net_pay`, `payment_status`) VALUES
(1, 2, 1, 0, 0.00, 200.00, -200.00, 'paid'),
(2, 2, 2, 18, 2556.82, 442.90, 2113.92, 'paid'),
(3, 2, 3, 18, 1534.09, 345.74, 1188.35, 'paid'),
(4, 2, 4, 18, 1431.82, 336.02, 1095.80, 'paid'),
(5, 2, 5, 4, 295.45, 228.07, 67.39, 'paid'),
(6, 2, 6, 18, 1227.27, 316.59, 910.68, 'paid'),
(7, 2, 7, 4, 318.18, 230.23, 87.95, 'paid');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pay_periods`
--

INSERT INTO `pay_periods` (`id`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, '2025-04-01', '2025-04-15', 'open', '2025-04-21 12:58:33'),
(2, '2025-04-16', '2025-04-30', 'processed', '2025-04-21 12:58:35');

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pay_settings`
--

INSERT INTO `pay_settings` (`id`, `setting_name`, `setting_value`) VALUES
(1, 'standard_hours', '8'),
(2, 'overtime_multiplier', '1.25'),
(3, 'sss_rate', '5'),
(4, 'philhealth_rate', '2.5'),
(5, 'pagibig_rate', '2'),
(6, 'tin_fixed', '200');

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `title`, `base_salary`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Store Manager', 25000.00, 'Oversees store operations and staff', '2025-04-21 07:16:32', '2025-04-21 07:16:32'),
(2, 'Cashier', 15000.00, 'Handles customer transactions and receipts', '2025-04-21 07:16:32', '2025-04-21 07:16:32'),
(3, 'Inventory Clerk', 14000.00, 'Manages inventory records and stock levels', '2025-04-21 07:16:32', '2025-04-21 07:16:32'),
(4, 'Sales Associate', 13000.00, 'Assists customers and promotes products', '2025-04-21 07:16:32', '2025-04-21 07:16:32'),
(5, 'Delivery Staff', 12000.00, 'Handles product deliveries and logistics', '2025-04-21 07:16:32', '2025-04-21 07:16:32'),
(6, 'Warehouse Supervisor', 28000.00, 'Manages warehouse operations and inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(7, 'Assistant Manager', 22000.00, 'Assists store manager with daily operations', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(8, 'Senior Sales Associate', 17000.00, 'Experienced sales staff with product expertise', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(9, 'Maintenance Technician', 16000.00, 'Handles store repairs and equipment maintenance', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(10, 'Customer Service Representative', 14500.00, 'Handles customer inquiries and returns', '2025-04-21 12:14:27', '2025-04-21 12:14:27');

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
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `sku`, `barcode`, `name`, `description`, `category_id`, `brand_id`, `unit`, `cost_price`, `selling_price`, `stock_level`, `reorder_point`, `created_at`, `updated_at`) VALUES
(11, 'HMR-001', '1234567890', 'Claw Hammers', '16oz steel claw hammer with rubber grip', 1, 1, 'piece', 12.00, 24.00, 39, 5, '2025-04-21 11:16:26', '2025-04-21 12:20:07'),
(12, 'DRILL-001', '9876543210', 'Power Drill 18V', 'Cordless drill with variable speed', 4, 2, 'piece', 45.50, 79.99, 52, 8, '2025-04-21 12:14:27', '2025-04-21 13:31:31'),
(13, 'HSAW-002', '9876543211', 'Hacksaw', 'Metal cutting hacksaw with comfort grip', 5, 7, 'piece', 8.75, 16.99, 45, 10, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(14, 'SCRW-003', '9876543212', 'Screwdriver Set', '10-piece precision screwdriver set', 5, 6, 'set', 12.25, 24.50, 24, 5, '2025-04-21 12:14:27', '2025-04-21 12:20:07'),
(15, 'HMLT-004', '9876543213', 'Sledgehammer', '8lb sledgehammer with fiberglass handle', 5, 7, 'piece', 18.50, 34.99, 14, 3, '2025-04-21 12:14:27', '2025-04-21 12:20:07'),
(16, 'NAIL-005', '9876543214', 'Finishing Nails', '16ga 2-inch finishing nails, box of 1000', 2, 1, 'box', 5.25, 9.99, 49, 10, '2025-04-21 12:14:27', '2025-04-21 12:20:07'),
(17, 'BOLT-006', '9876543215', 'Hex Bolts', '3/8\" x 2\" zinc-plated hex bolts, pack of 50', 2, 3, 'pack', 7.50, 14.50, 39, 8, '2025-04-21 12:14:27', '2025-04-21 13:35:52'),
(18, 'TAPE-007', '9876543216', 'Measuring Tape', '25ft retractable measuring tape', 5, 2, 'piece', 8.25, 15.99, 33, 7, '2025-04-21 12:14:27', '2025-04-21 13:35:52'),
(19, 'GLUE-008', '9876543217', 'Wood Glue', '16oz waterproof wood adhesive', 8, 5, 'bottle', 4.50, 8.99, 47, 12, '2025-04-21 12:14:27', '2025-04-21 12:20:07'),
(20, 'LGHT-009', '9876543218', 'LED Floodlight', '15W outdoor LED floodlight', 10, 6, 'piece', 22.75, 39.99, 19, 5, '2025-04-21 12:14:27', '2025-04-21 12:20:07'),
(21, 'SAFE-010', '9876543219', 'Safety Glasses', 'Clear anti-fog safety glasses', 1, 3, 'piece', 3.25, 6.99, 60, 15, '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(22, 'WRCH-011', '9876543220', 'Adjustable Wrench', '10-inch chrome adjustable wrench', 5, 8, 'piece', 7.75, 15.50, 22, 8, '2025-04-21 12:14:27', '2025-04-21 13:35:52'),
(23, 'PLRS-012', '9876543221', 'Needle Nose Pliers', '8-inch needle nose pliers with wire cutter', 5, 1, 'piece', 9.25, 18.99, 16, 6, '2025-04-21 12:14:27', '2025-04-21 13:35:52'),
(24, 'SPRG-013', '9876543222', 'Garden Sprinkler', 'Oscillating lawn sprinkler with brass nozzles', 3, 4, 'piece', 13.50, 26.99, 11, 4, '2025-04-21 12:14:27', '2025-04-21 13:35:52'),
(25, 'HNOS-014', '9876543223', 'Garden Hose', '50ft kink-resistant garden hose', 3, 4, 'piece', 15.25, 29.99, 11, 5, '2025-04-21 12:14:27', '2025-04-21 13:35:52'),
(26, 'RAKE-015', '9876543224', 'Leaf Rake', 'Poly leaf rake with 48-inch handle', 3, 7, 'piece', 9.50, 18.99, 22, 5, '2025-04-21 12:14:27', '2025-04-21 12:14:27');

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_sales`
--

INSERT INTO `product_sales` (`sale_id`, `transaction_id`, `cashier_name`, `product_id`, `quantity_sold`, `discount_applied`, `sale_price`, `sale_timestamp`) VALUES
(1, '00001', 'Riccki Mislang', 12, 2, 0.00, 159.98, '2025-04-21 21:31:31'),
(2, '00002', 'Riccki Mislang', 25, 9, 0.00, 784.24, '2025-04-21 21:35:52'),
(3, '00002', 'Riccki Mislang', 24, 7, 0.00, 784.24, '2025-04-21 21:35:52'),
(4, '00002', 'Riccki Mislang', 23, 9, 0.00, 784.24, '2025-04-21 21:35:52'),
(5, '00002', 'Riccki Mislang', 22, 8, 0.00, 784.24, '2025-04-21 21:35:52'),
(6, '00002', 'Riccki Mislang', 18, 1, 0.00, 784.24, '2025-04-21 21:35:52'),
(7, '00002', 'Riccki Mislang', 17, 1, 0.00, 784.24, '2025-04-21 21:35:52');

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
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stock_transactions`
--

INSERT INTO `stock_transactions` (`transaction_id`, `product_id`, `transaction_type`, `quantity`, `unit_price`, `total_amount`, `reference_no`, `notes`, `transaction_date`, `created_at`) VALUES
(4, 11, 'initial', 50, 0.00, 0.00, 'HMR-001', 'Initial inventory', '2025-04-21 11:16:26', '2025-04-21 11:16:26'),
(5, 11, 'stock_in', 5, 0.00, 0.00, NULL, 'ordered extra', '2025-04-21 11:17:25', '2025-04-21 11:17:25'),
(6, 11, 'stock_out', 5, 0.00, 0.00, NULL, 'for personal use', '2025-04-21 11:17:36', '2025-04-21 11:17:36'),
(7, 12, 'initial', 25, 9.25, 231.25, 'PLRS-012', 'Initial inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(8, 13, 'initial', 18, 13.50, 243.00, 'SPRG-013', 'Initial inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(9, 14, 'initial', 20, 15.25, 305.00, 'HNOS-014', 'Initial inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(10, 15, 'initial', 22, 9.50, 209.00, 'RAKE-015', 'Initial inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(11, 16, 'initial', 30, 45.50, 1365.00, 'DRILL-001', 'Initial inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(12, 17, 'initial', 45, 8.75, 393.75, 'HSAW-002', 'Initial inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(13, 18, 'initial', 25, 12.25, 306.25, 'SCRW-003', 'Initial inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(14, 19, 'initial', 15, 18.50, 277.50, 'HMLT-004', 'Initial inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(15, 20, 'initial', 50, 5.25, 262.50, 'NAIL-005', 'Initial inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(16, 21, 'initial', 40, 7.50, 300.00, 'BOLT-006', 'Initial inventory', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(17, 12, 'stock_in', 10, 9.00, 90.00, 'PO-2025-042', 'Regular restock', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(18, 13, 'stock_out', 3, 13.50, 40.50, 'SO-2025-011', 'Customer order', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(19, 14, 'stock_in', 5, 15.00, 75.00, 'PO-2025-043', 'Seasonal restock', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(20, 15, 'stock_out', 2, 9.50, 19.00, 'SO-2025-012', 'Store display', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(21, 16, 'stock_in', 8, 44.50, 356.00, 'PO-2025-044', 'Supplier promotion', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(22, 17, 'stock_out', 5, 8.75, 43.75, 'SO-2025-013', 'Customer order', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(23, 18, 'stock_in', 15, 12.00, 180.00, 'PO-2025-045', 'Regular restock', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(24, 19, 'stock_out', 1, 18.50, 18.50, 'SO-2025-014', 'Staff demo', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(25, 20, 'stock_in', 25, 5.00, 125.00, 'PO-2025-046', 'Bulk purchase', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(26, 21, 'stock_out', 8, 7.50, 60.00, 'SO-2025-015', 'Contractor order', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(27, 12, 'stock_in', 50, 0.00, 0.00, NULL, 're stock', '2025-04-21 13:31:02', '2025-04-21 13:31:02');

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier_contacts`
--

INSERT INTO `supplier_contacts` (`contact_id`, `name`, `contact_person`, `phone`, `email`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Hardware Wholesale Co.', 'John Smith', '555-0101', 'john@hwwholesale.com', '123 Supply St, Industry City', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(2, 'Tools Direct', 'Mary Johnson', '555-0102', 'mary@toolsdirect.com', '456 Warehouse Ave, Commerce Town', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(3, 'Building Supply Inc.', 'Robert Brown', '555-0103', 'robert@bsupply.com', '789 Industrial Rd, Trade City', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(4, 'Metro Hardware Supply', 'David Wilson', '555-0104', 'david@metrohardware.com', '101 Distribution Blvd, Trade Center', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(5, 'Tool Kingdom', 'Sarah Adams', '555-0105', 'sarah@toolkingdom.com', '202 Factory Lane, Manufacturing Park', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(6, 'Construction Essentials', 'Michael Chen', '555-0106', 'michael@constructessentials.com', '303 Builder St, Commerce District', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(7, 'Garden World', 'Emily Rodriguez', '555-0107', 'emily@gardenworld.com', '404 Landscape Ave, Green Valley', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(8, 'Electrical Masters', 'James Thompson', '555-0108', 'james@electricalmasters.com', '505 Power Road, Tech City', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(9, 'Plumbing Pros', 'Lisa Garcia', '555-0109', 'lisa@plumbingpros.com', '606 Pipeline Blvd, Service Town', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(10, 'Fastener Hub', 'Kevin Lee', '555-0110', 'kevin@fastenerhub.com', '707 Connection St, Assembly Park', '2025-04-21 12:14:27', '2025-04-21 12:14:27'),
(11, 'Paint & Finish Co.', 'Amanda Martinez', '555-0111', 'amanda@paintfinish.com', '808 Color Lane, Design District', '2025-04-21 12:14:27', '2025-04-21 12:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `usertype` enum('1','2','3') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'superadmin=1, admin=2, cashier=3',
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `username`, `password`, `usertype`) VALUES
(1, NULL, 'superadmin', '17c4520f6cfd1ab53d8745e84681eb49', '1'),
(2, NULL, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2'),
(3, 1, 'cashier', '6ac2470ed8ccf204fd5ff89b32a355cf', '3'),
(6, 1, 'Riccki Mislang', '6ac2470ed8ccf204fd5ff89b32a355cf', '3');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
