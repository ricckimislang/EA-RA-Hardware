-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 21, 2025 at 12:09 PM
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
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance_records`
--

INSERT INTO `attendance_records` (`id`, `employee_id`, `time_in`, `time_out`, `total_hours`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(76, 1, '2025-04-21 19:35:43', NULL, NULL, 'late', NULL, '2025-04-21 11:35:43', '2025-04-21 11:35:43');

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Stanley', 'Quality hand tools and storage solutions', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(2, 'DeWalt', 'Professional-grade power tools and equipment', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(3, 'Makita', 'Innovative power tools and outdoor equipment', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(4, 'Bosch', 'High-performance tools and accessories', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(5, '3M', 'Industrial and consumer products', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(6, 'Sanrio', NULL, '2025-04-17 21:16:17', '2025-04-17 21:16:17');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Tools', 'Hand tools and power tools for construction and repairs', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(2, 'Hardware', 'General hardware items including fasteners and fittings', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(3, 'Electrical', 'Electrical supplies, wiring, and components', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(4, 'Plumbing', 'Plumbing supplies, pipes, and fixtures', '2025-04-17 20:51:27', '2025-04-17 20:51:27'),
(5, 'Paint', 'Paint, primers, and painting supplies', '2025-04-17 20:51:27', '2025-04-17 20:51:27');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `full_name`, `position_id`, `employment_type`, `salary_rate_type`, `date_hired`, `overtime_rate`, `contact_number`, `email_address`, `created_at`, `updated_at`) VALUES
(1, 'Riccki Mislang', 2, 'full-time', 'monthly', '2025-04-21', 100.00, '09631245565', 'codingriccki123@gmail.com', '2025-04-21 10:52:49', '2025-04-21 10:58:06');

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_government_ids`
--

INSERT INTO `employee_government_ids` (`id`, `employee_id`, `sss_number`, `sss_file_path`, `pagibig_number`, `pagibig_file_path`, `philhealth_number`, `philhealth_file_path`, `tin_number`, `tin_file_path`, `created_at`, `updated_at`) VALUES
(10, 9, '34-1234567-9', NULL, '1234-5678-9012 ', NULL, '12-345678901-2', NULL, '123-456-789-000', NULL, '2025-04-21 10:26:31', '2025-04-21 10:26:31'),
(11, 1, '34-1234567-9', NULL, '1234-5678-9012 ', NULL, '12-345678901-2', NULL, '123-456-789-000  ', NULL, '2025-04-21 10:51:46', '2025-04-21 10:51:46'),
(12, 1, '34-1234567-9', NULL, '1234-5678-9012 ', NULL, '12-345678901-2', NULL, '123-456-789-000  ', NULL, '2025-04-21 10:52:49', '2025-04-21 10:58:06');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_qr_codes`
--

INSERT INTO `employee_qr_codes` (`id`, `employee_id`, `qr_code_hash`, `is_active`, `created_at`, `updated_at`) VALUES
(5, 1, '9200ca8411029178bbaebb146', 1, '2025-04-21 11:30:29', '2025-04-21 11:31:56');

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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expense_transactions`
--

INSERT INTO `expense_transactions` (`transaction_id`, `category_id`, `expense_name`, `amount`, `transaction_date`, `receipt_path`, `notes`, `created_at`) VALUES
(25, 4, 'Fare', 120.00, '2025-04-21', 'assets/images/receipts/receipt_68062545ae711.png', 'To Tupi', '2025-04-21 11:00:10');

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pay_periods`
--

INSERT INTO `pay_periods` (`id`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(5, '2025-04-01', '2025-04-15', 'open', '2025-04-21 11:56:07');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `title`, `base_salary`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Store Manager', 25000.00, 'Oversees store operations and staff', '2025-04-21 07:16:32', '2025-04-21 07:16:32'),
(2, 'Cashier', 15000.00, 'Handles customer transactions and receipts', '2025-04-21 07:16:32', '2025-04-21 07:16:32'),
(3, 'Inventory Clerk', 14000.00, 'Manages inventory records and stock levels', '2025-04-21 07:16:32', '2025-04-21 07:16:32'),
(4, 'Sales Associate', 13000.00, 'Assists customers and promotes products', '2025-04-21 07:16:32', '2025-04-21 07:16:32'),
(5, 'Delivery Staff', 12000.00, 'Handles product deliveries and logistics', '2025-04-21 07:16:32', '2025-04-21 07:16:32');

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `sku`, `barcode`, `name`, `description`, `category_id`, `brand_id`, `unit`, `cost_price`, `selling_price`, `stock_level`, `reorder_point`, `created_at`, `updated_at`) VALUES
(11, 'HMR-001', '1234567890', 'Claw Hammers', '16oz steel claw hammer with rubber grip', 1, 1, 'piece', 12.00, 24.00, 45, 5, '2025-04-21 11:16:26', '2025-04-21 11:52:07');

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_sales`
--

INSERT INTO `product_sales` (`sale_id`, `transaction_id`, `cashier_name`, `product_id`, `quantity_sold`, `discount_applied`, `sale_price`, `sale_timestamp`) VALUES
(6, '00001', 'Riccki Mislang', 11, 5, 0.00, 120.00, '2025-04-21 19:52:07');

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stock_transactions`
--

INSERT INTO `stock_transactions` (`transaction_id`, `product_id`, `transaction_type`, `quantity`, `unit_price`, `total_amount`, `reference_no`, `notes`, `transaction_date`, `created_at`) VALUES
(4, 11, 'initial', 50, 0.00, 0.00, 'HMR-001', 'Initial inventory', '2025-04-21 11:16:26', '2025-04-21 11:16:26'),
(5, 11, 'stock_in', 5, 0.00, 0.00, NULL, 'ordered extra', '2025-04-21 11:17:25', '2025-04-21 11:17:25'),
(6, 11, 'stock_out', 5, 0.00, 0.00, NULL, 'for personal use', '2025-04-21 11:17:36', '2025-04-21 11:17:36');

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier_contacts`
--

INSERT INTO `supplier_contacts` (`contact_id`, `name`, `contact_person`, `phone`, `email`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Hardware Wholesale Co.', 'John Smith', '555-0101', 'john@hwwholesale.com', '123 Supply St, Industry City', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(2, 'Tools Direct', 'Mary Johnson', '555-0102', 'mary@toolsdirect.com', '456 Warehouse Ave, Commerce Town', '2025-04-18 04:51:27', '2025-04-18 04:51:27'),
(3, 'Building Supply Inc.', 'Robert Brown', '555-0103', 'robert@bsupply.com', '789 Industrial Rd, Trade City', '2025-04-18 04:51:27', '2025-04-18 04:51:27');

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
