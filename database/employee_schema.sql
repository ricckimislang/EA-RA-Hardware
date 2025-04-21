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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `full_name`, `position_id`, `employment_type`, `salary_rate_type`, `date_hired`, `overtime_rate`, `contact_number`, `email_address`, `created_at`, `updated_at`) VALUES
(1, 'Juan Dela Cruz', 1, 'full-time', 'hourly', '2022-05-10', 150.00, '09171234567', 'juan.manager@email.com', '2025-04-21 07:16:32', '2025-04-21 08:34:01'),
(2, 'Ana Santos', 2, 'full-time', 'hourly', '2023-01-20', 100.00, '09181234567', 'ana.santos@email.com', '2025-04-21 07:16:32', '2025-04-21 08:34:09'),
(3, 'Marco Reyes', 2, 'part-time', 'hourly', '2023-06-01', 80.00, '09221234567', 'marco.reyes@email.com', '2025-04-21 07:16:32', '2025-04-21 08:34:07'),
(4, 'Ella Lopez', 3, 'full-time', 'hourly', '2021-11-15', 90.00, '09331234567', 'ella.lopez@email.com', '2025-04-21 07:16:32', '2025-04-21 08:34:11'),
(5, 'John Mendoza', 3, 'part-time', 'hourly', '2024-02-10', 70.00, '09441234567', 'john.mendoza@email.com', '2025-04-21 07:16:32', '2025-04-21 08:34:05');


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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_government_ids`
--

INSERT INTO `employee_government_ids` (`id`, `employee_id`, `sss_number`, `sss_file_path`, `pagibig_number`, `pagibig_file_path`, `philhealth_number`, `philhealth_file_path`, `tin_number`, `tin_file_path`, `created_at`, `updated_at`) VALUES
(1, 1, '34-1234567-8', NULL, '1234-5678-9101', NULL, '12-345678901', NULL, '123-456-789', NULL, '2025-04-19 04:34:52', '2025-04-19 04:34:52'),
(2, 2, '34-2234567-8', NULL, '2234-5678-9101', NULL, '22-345678901', NULL, '223-456-789', NULL, '2025-04-19 04:34:52', '2025-04-19 04:34:52'),
(4, 4, '34-4234567-8', NULL, '4234-5678-9101', NULL, '42-345678901', NULL, '423-456-789', NULL, '2025-04-19 04:34:52', '2025-04-19 04:34:52'),
(5, 5, '34-5234567-8', NULL, '5234-5678-9101', NULL, '52-345678901', NULL, '523-456-789', NULL, '2025-04-19 04:34:52', '2025-04-19 04:34:52');

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_qr_codes`
--

INSERT INTO `employee_qr_codes` (`id`, `employee_id`, `qr_code_hash`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, '051ddfc6326318ec6f5c9464f', 1, '2025-04-19 09:08:43', '2025-04-19 09:16:28'),
(2, 2, '77b942b3de1f79ed99121a736', 1, '2025-04-19 09:08:43', '2025-04-19 09:12:10'),
(3, 4, '75eff173fbdc7ed21fac6c0de', 1, '2025-04-19 09:08:43', '2025-04-19 09:08:43'),
(4, 5, 'af807ef2b4e38ed76a5297cfb', 1, '2025-04-19 09:08:43', '2025-04-19 09:08:43');

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `username`, `password`, `usertype`) VALUES
(1, NULL, 'superadmin', '17c4520f6cfd1ab53d8745e84681eb49', '1'),
(2, NULL, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2'),
(3, 1, 'cashier', '6ac2470ed8ccf204fd5ff89b32a355cf', '3');