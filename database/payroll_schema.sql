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


DROP TABLE IF EXISTS `pay_periods`;
CREATE TABLE IF NOT EXISTS `pay_periods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('open','processed') DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `attendance_records`;
CREATE TABLE IF NOT EXISTS `attendance_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `time_in` datetime NOT NULL,
  `time_out` datetime DEFAULT NULL,
  `total_hours` int DEFAULT NULL,
  `minutes` decimal(5,2) DEFAULT NULL DEFAULT 0,
  `status` enum('present','late','half-day','absent') DEFAULT 'present',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


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
(1, 'work_start_time', '07:00:00', 'Regular work start time', '2025-04-18 17:08:43', '2025-04-18 17:08:43'),
(2, 'work_end_time', '17:00:00', 'Regular work end time', '2025-04-18 17:08:43', '2025-04-18 17:08:43'),
(3, 'late_threshold_minutes', '15', 'Minutes after work start time to mark as late', '2025-04-18 17:08:43', '2025-04-18 17:08:43'),
(4, 'half_day_hours', '4', 'Minimum hours to be counted as half-day', '2025-04-18 17:08:43', '2025-04-18 17:08:43');
