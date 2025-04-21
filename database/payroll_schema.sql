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

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `pay_period_id`, `employee_id`, `total_hours`, `gross_pay`, `deductions`, `net_pay`, `payment_status`) VALUES
(1, 1, 1, 95, 13494.32, 1481.96, 12012.36, 'pending'),
(2, 1, 2, 86, 7329.55, 896.31, 6433.24, 'paid'),
(3, 1, 3, 86, 7329.55, 896.31, 6433.24, 'pending'),
(4, 1, 4, 86, 6840.91, 849.89, 5991.02, 'pending'),
(5, 1, 5, 86, 6840.91, 849.89, 5991.02, 'pending'),
(6, 2, 1, 36, 5113.64, 685.80, 4427.84, 'pending'),
(7, 2, 2, 36, 3068.18, 491.48, 2576.70, 'pending'),
(8, 2, 3, 36, 3068.18, 491.48, 2576.70, 'pending'),
(9, 2, 4, 36, 2863.64, 472.05, 2391.59, 'pending'),
(10, 2, 5, 36, 2863.64, 472.05, 2391.59, 'pending');

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pay_periods`
--

INSERT INTO `pay_periods` (`id`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, '2025-04-01', '2025-04-15', 'processed', '2025-04-21 08:49:42'),
(2, '2025-04-16', '2025-04-30', 'processed', '2025-04-21 08:55:12'),
(3, '2025-05-01', '2025-05-15', 'open', '2025-04-21 08:55:26'),
(4, '2025-05-16', '2025-05-31', 'open', '2025-04-21 08:58:20');

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
